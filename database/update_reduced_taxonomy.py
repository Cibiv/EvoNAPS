#!/usr/bin/env python3
"""Populate reduced taxonomy columns in the EvoNAPS taxonomy table."""

from __future__ import annotations

import argparse
import csv
import json
import pathlib
import sys
import time
from typing import Any

LOCAL_INFILE_DISABLED_ERROR = 3948
LOCK_WAIT_TIMEOUT_ERROR = 1205
UPDATE_COLUMNS = ["TAX_ID", "RED_TAX_ID", "RED_TAX_NAME", "RED_RANK"]

DEFAULT_CREDENTIALS = pathlib.Path(__file__).resolve().parents[1] / "config" / "EvoNAPS_credentials.cnf"
DEFAULT_TAXONOMY_TABLE = pathlib.Path(__file__).resolve().parents[1] / "config" / "taxonomy_table.json"
DEFAULT_UPDATES_FILE = pathlib.Path(__file__).resolve().parent / "reduced_taxonomy_updates.tsv"


def log(message: str) -> None:
    """Print progress immediately, also when called through conda run."""

    print(message, flush=True)


def connect_to_database(db_config: dict[str, Any]) -> Any:
    """Open a MySQL connection after checking that mysql.connector is installed."""

    try:
        import mysql.connector as mysql
    except ModuleNotFoundError:
        sys.exit(
            "ERROR: Missing Python package mysql-connector-python. "
            "Run this script inside the EvoNAPS/Snakemake environment or install mysql-connector-python."
        )

    return mysql.connect(**db_config)


def read_db_credentials(file_name: pathlib.Path) -> dict[str, Any]:
    """Read a simple key=value MySQL credentials file."""

    if not file_name.exists():
        sys.exit(f"ERROR: Could not find db credentials file: {file_name}!")

    credentials = {}
    with file_name.open("r", encoding="utf-8") as handle:
        for line in handle:
            line = line.strip()
            if not line or line.startswith("#"):
                continue
            if "=" not in line:
                sys.exit(f"ERROR: Invalid credential line in {file_name}: {line}")
            key, value = line.split("=", 1)
            credentials[key.strip()] = value.strip()

    for key in ["host", "user", "password", "database"]:
        if key not in credentials:
            sys.exit(f"ERROR: Credential {key} is missing from credential file {file_name}")

    return {
        "host": credentials["host"],
        "user": credentials["user"],
        "password": credentials["password"],
        "database": credentials["database"],
        "allow_local_infile": True,
    }


def read_rank_table(file_name: pathlib.Path) -> dict[str, int]:
    """Read allowed reduced ranks from config/taxonomy_table.json."""

    if not file_name.exists():
        sys.exit(f"ERROR: Could not find taxonomy table file: {file_name}!")

    with file_name.open("r", encoding="utf-8") as handle:
        taxonomy_table = json.load(handle)

    if not isinstance(taxonomy_table, list) or not taxonomy_table or not isinstance(taxonomy_table[0], dict):
        sys.exit(f"ERROR: Expected first JSON entry in {file_name} to be the rank mapping.")

    return taxonomy_table[0]


def normalize_rank(rank: str | None) -> str:
    """Normalize taxonomy rank labels for matching config keys to DB values."""

    return "" if rank is None else rank.strip().lower().replace("_", " ")


def allowed_rank_keys(rank_table: dict[str, int]) -> set[str]:
    """Return normalized rank names that should be treated as reduced ranks."""

    return {normalize_rank(rank) for rank in rank_table}


def fetch_taxonomy(conn: Any, sample_size: int | None = None, chunk_size: int = 100000) -> dict[int, dict[str, Any]]:
    """Download the taxonomy table from EvoNAPS."""

    query = "SELECT TAX_ID, PARENT_TAX_ID, TAX_NAME, TAX_RANK FROM taxonomy"
    if sample_size is not None:
        query += f" ORDER BY TAX_ID LIMIT {sample_size}"

    cursor = conn.cursor()
    cursor.execute(query)
    taxonomy = {}

    while True:
        rows = cursor.fetchmany(chunk_size)
        if not rows:
            break
        for tax_id, parent_tax_id, tax_name, tax_rank in rows:
            tax_id = int(tax_id)
            taxonomy[tax_id] = {
                "parent_tax_id": int(parent_tax_id) if parent_tax_id is not None else None,
                "tax_name": tax_name,
                "tax_rank": tax_rank,
            }
        log(f"Downloaded {len(taxonomy)} taxonomy rows...")

    cursor.close()
    return taxonomy


def fetch_columns(conn: Any) -> set[str]:
    """Return column names in the taxonomy table."""

    cursor = conn.cursor()
    cursor.execute("SHOW COLUMNS FROM taxonomy")
    columns = {row[0] for row in cursor.fetchall()}
    cursor.close()
    return columns


def add_missing_columns(conn: Any, columns: set[str]) -> set[str]:
    """Add reduced taxonomy columns that are useful but missing from older schemas."""

    desired_columns = {
        "RED_TAX_ID": "INT DEFAULT NULL",
        "RED_TAX_NAME": "VARCHAR(250) DEFAULT NULL",
        "RED_RANK": "VARCHAR(50) DEFAULT NULL",
    }

    cursor = conn.cursor()
    for column, definition in desired_columns.items():
        if column not in columns:
            cursor.execute(f"ALTER TABLE taxonomy ADD COLUMN {column} {definition}")
            columns.add(column)
    conn.commit()
    cursor.close()
    return columns


def resolve_reduced_taxon(
    tax_id: int,
    taxonomy: dict[int, dict[str, Any]],
    allowed_ranks: set[str],
) -> tuple[int, str | None, str | None]:
    """
    Return the nearest self-or-parent taxon whose rank is in allowed_ranks.

    Missing parents and cycles fall back to root.
    """

    current_tax_id = tax_id
    seen = set()
    root = taxonomy.get(1, {})
    root_name = root.get("tax_name", "root")
    root_rank = root.get("tax_rank", "no rank")
    root_result = (1, root_name, root_rank)

    while current_tax_id is not None:
        if current_tax_id in seen:
            return root_result
        seen.add(current_tax_id)

        row = taxonomy.get(current_tax_id)
        if row is None:
            return root_result

        rank = row["tax_rank"]
        if normalize_rank(rank) in allowed_ranks:
            return current_tax_id, row["tax_name"], rank

        parent_tax_id = row["parent_tax_id"]
        if parent_tax_id == current_tax_id:
            return root_result
        current_tax_id = parent_tax_id

    return root_result


def build_updates(
    taxonomy: dict[int, dict[str, Any]],
    allowed_ranks: set[str],
    progress_interval: int = 100000,
) -> list[dict[str, Any]]:
    """Calculate reduced taxonomy values for all rows."""

    cache = {}
    updates = []
    root = taxonomy.get(1, {})
    root_result = (1, root.get("tax_name", "root"), root.get("tax_rank", "no rank"))

    for index, tax_id in enumerate(sorted(taxonomy), start=1):
        current_tax_id = tax_id
        path = []
        seen = set()
        result = root_result

        while current_tax_id is not None:
            if current_tax_id in cache:
                result = cache[current_tax_id]
                break
            if current_tax_id in seen:
                result = root_result
                break

            seen.add(current_tax_id)
            path.append(current_tax_id)
            row = taxonomy.get(current_tax_id)
            if row is None:
                result = root_result
                break

            rank = row["tax_rank"]
            if normalize_rank(rank) in allowed_ranks:
                result = (current_tax_id, row["tax_name"], rank)
                break

            parent_tax_id = row["parent_tax_id"]
            if parent_tax_id == current_tax_id:
                result = root_result
                break
            current_tax_id = parent_tax_id

        for visited_tax_id in path:
            cache[visited_tax_id] = result

        red_tax_id, red_tax_name, red_rank = result
        updates.append(
            {
                "TAX_ID": tax_id,
                "RED_TAX_ID": red_tax_id,
                "RED_TAX_NAME": red_tax_name,
                "RED_RANK": red_rank,
            }
        )

        if index % progress_interval == 0:
            log(f"Calculated {index}/{len(taxonomy)} reduced taxonomy rows...")

    return updates


def write_updates_file(updates: list[dict[str, Any]], columns: list[str], file_name: pathlib.Path) -> pathlib.Path:
    """Write reduced taxonomy updates to a reusable TSV file for fast loading."""

    tmp_file = pathlib.Path(f"{file_name}.tmp")
    with tmp_file.open("w", encoding="utf-8", newline="") as handle:
        writer = csv.writer(handle, delimiter="\t", lineterminator="\n")
        for row in updates:
            writer.writerow(["\\N" if row[column] is None else row[column] for column in columns])
    tmp_file.replace(file_name)
    return file_name


def iter_updates_file(file_name: pathlib.Path, columns: list[str]) -> Any:
    """Yield rows from a cached reduced-taxonomy TSV file."""

    with file_name.open("r", encoding="utf-8", newline="") as handle:
        reader = csv.reader(handle, delimiter="\t")
        for row in reader:
            if len(row) != len(columns):
                sys.exit(f"ERROR: Cached update file has an invalid row: {row}")
            values = [None if value == "\\N" else value for value in row]
            update = dict(zip(columns, values))
            update["TAX_ID"] = int(update["TAX_ID"])
            update["RED_TAX_ID"] = int(update["RED_TAX_ID"])
            yield update


def summarize_updates_file(file_name: pathlib.Path) -> None:
    """Print a compact summary of a reusable updates TSV file."""

    total = 0
    changed_to_parent = 0
    reduced_to_root = 0

    for row in iter_updates_file(file_name, UPDATE_COLUMNS):
        total += 1
        if row["RED_TAX_ID"] != row["TAX_ID"]:
            changed_to_parent += 1
        if row["RED_TAX_ID"] == 1:
            reduced_to_root += 1

    log(f"Calculated reduced taxonomy values for {total} rows.")
    log(f"Rows reduced to a parent taxon: {changed_to_parent}")
    log(f"Rows reduced to root: {reduced_to_root}")


def load_updates_file(cursor: Any, conn: Any, updates_file: pathlib.Path) -> bool:
    """Load updates with LOAD DATA LOCAL INFILE if MySQL allows it."""

    try:
        cursor.execute(
            """
            LOAD DATA LOCAL INFILE %s
            INTO TABLE tmp_reduced_taxonomy
            FIELDS TERMINATED BY '\t' OPTIONALLY ENCLOSED BY '"' ESCAPED BY '\\\\'
            LINES TERMINATED BY '\n'
            (TAX_ID, RED_TAX_ID, RED_TAX_NAME, RED_RANK)
            """,
            (str(updates_file),),
        )
        conn.commit()
        return True
    except Exception as err:
        if getattr(err, "errno", None) != LOCAL_INFILE_DISABLED_ERROR:
            raise

        log("MySQL has local_infile disabled. Trying to enable it for this server...")
        try:
            cursor.execute("SET GLOBAL local_infile = 1")
            conn.commit()
            cursor.execute(
                """
                LOAD DATA LOCAL INFILE %s
                INTO TABLE tmp_reduced_taxonomy
                FIELDS TERMINATED BY '\t' OPTIONALLY ENCLOSED BY '"' ESCAPED BY '\\\\'
                LINES TERMINATED BY '\n'
                (TAX_ID, RED_TAX_ID, RED_TAX_NAME, RED_RANK)
                """,
                (str(updates_file),),
            )
            conn.commit()
            return True
        except Exception as enable_err:
            log(f"Could not use LOAD DATA LOCAL INFILE: {enable_err}")
            return False


def insert_updates_file_in_batches(
    cursor: Any,
    conn: Any,
    updates_file: pathlib.Path,
    columns: list[str],
    batch_size: int = 50000,
) -> None:
    """Fallback loader for servers where LOAD DATA LOCAL INFILE is disabled."""

    placeholders = ", ".join(["%s"] * len(columns))
    query = f"INSERT INTO tmp_reduced_taxonomy ({', '.join(columns)}) VALUES ({placeholders})"

    log(f"Falling back to batched INSERTs with batch size {batch_size}...")
    batch = []
    total = 0
    for row in iter_updates_file(updates_file, columns):
        batch.append(tuple(row[column] for column in columns))
        if len(batch) == batch_size:
            cursor.executemany(query, batch)
            conn.commit()
            total += len(batch)
            log(f"Loaded {total} rows into temporary table")
            batch = []

    if batch:
        cursor.executemany(query, batch)
        conn.commit()
        total += len(batch)
        log(f"Loaded {total} rows into temporary table")


def run_update_window(
    cursor: Any,
    conn: Any,
    set_clause: str,
    window_start: int,
    window_end: int,
    retries: int = 3,
) -> int:
    """Run one taxonomy update window, retrying lock wait timeouts."""

    query = f"""
        UPDATE taxonomy AS t
        JOIN tmp_reduced_taxonomy AS u ON t.TAX_ID = u.TAX_ID
        SET {set_clause}
        WHERE t.TAX_ID BETWEEN %s AND %s
    """

    for attempt in range(1, retries + 1):
        try:
            cursor.execute(query, (window_start, window_end))
            conn.commit()
            return max(cursor.rowcount, 0)
        except Exception as err:
            conn.rollback()
            if getattr(err, "errno", None) != LOCK_WAIT_TIMEOUT_ERROR or attempt == retries:
                raise
            wait_seconds = attempt * 5
            log(
                f"Lock wait timeout for TAX_ID {window_start}-{window_end}; "
                f"retrying in {wait_seconds} seconds..."
            )
            time.sleep(wait_seconds)

    return 0


def update_window_with_splitting(
    cursor: Any,
    conn: Any,
    set_clause: str,
    window_start: int,
    window_end: int,
    min_window: int,
) -> int:
    """Update a TAX_ID window, splitting it if lock contention persists."""

    try:
        return run_update_window(cursor, conn, set_clause, window_start, window_end)
    except Exception as err:
        if getattr(err, "errno", None) != LOCK_WAIT_TIMEOUT_ERROR:
            raise
        if window_end <= window_start or (window_end - window_start + 1) <= min_window:
            raise

        midpoint = (window_start + window_end) // 2
        log(
            f"Splitting locked TAX_ID window {window_start}-{window_end} "
            f"into {window_start}-{midpoint} and {midpoint + 1}-{window_end}"
        )
        left_count = update_window_with_splitting(
            cursor, conn, set_clause, window_start, midpoint, min_window
        )
        right_count = update_window_with_splitting(
            cursor, conn, set_clause, midpoint + 1, window_end, min_window
        )
        return left_count + right_count


def update_database(
    conn: Any,
    columns: set[str],
    updates_file: pathlib.Path,
    update_window: int,
    min_update_window: int,
    lock_wait_timeout: int,
) -> None:
    """Update reduced taxonomy columns through a temporary table."""

    update_columns = [
        column
        for column in ["RED_TAX_ID", "RED_TAX_NAME", "RED_RANK"]
        if column in columns
    ]
    if not update_columns:
        sys.exit("ERROR: No reduced taxonomy columns found in taxonomy table.")

    cursor = conn.cursor()
    cursor.execute("SET SESSION innodb_lock_wait_timeout = %s", (lock_wait_timeout,))
    cursor.execute("DROP TEMPORARY TABLE IF EXISTS tmp_reduced_taxonomy")
    cursor.execute(
        """
        CREATE TEMPORARY TABLE tmp_reduced_taxonomy (
            TAX_ID INT NOT NULL PRIMARY KEY,
            RED_TAX_ID INT DEFAULT NULL,
            RED_TAX_NAME VARCHAR(250) DEFAULT NULL,
            RED_RANK VARCHAR(50) DEFAULT NULL
        ) ENGINE=InnoDB
        """
    )

    log(f"Loading temporary table from {updates_file}...")
    loaded = load_updates_file(cursor, conn, updates_file)

    if not loaded:
        insert_updates_file_in_batches(cursor, conn, updates_file, UPDATE_COLUMNS)

    cursor.execute("SELECT MIN(TAX_ID), MAX(TAX_ID) FROM tmp_reduced_taxonomy")
    min_tax_id, max_tax_id = cursor.fetchone()
    if min_tax_id is None or max_tax_id is None:
        sys.exit("ERROR: Temporary update table is empty.")

    log(f"Updating taxonomy from temporary table in TAX_ID windows of {update_window}...")
    set_clause = ", ".join(f"t.{column}=u.{column}" for column in update_columns)
    total_updated = 0
    window_start = int(min_tax_id)
    max_tax_id = int(max_tax_id)

    while window_start <= max_tax_id:
        window_end = min(window_start + update_window - 1, max_tax_id)
        updated = update_window_with_splitting(
            cursor, conn, set_clause, window_start, window_end, min_update_window
        )
        total_updated += updated
        log(f"Updated TAX_ID {window_start}-{window_end}; affected rows so far: {total_updated}")
        window_start = window_end + 1

    log(f"Updated reduced taxonomy columns for {total_updated} affected rows")

    cursor.close()


def summarize(updates: list[dict[str, Any]]) -> None:
    """Print a compact update summary."""

    reduced_to_root = [row for row in updates if row["RED_TAX_ID"] == 1]
    changed_to_parent = [row for row in updates if row["RED_TAX_ID"] != row["TAX_ID"]]

    log(f"Calculated reduced taxonomy values for {len(updates)} rows.")
    log(f"Rows reduced to a parent taxon: {len(changed_to_parent)}")
    log(f"Rows reduced to root: {len(reduced_to_root)}")


def parse_args() -> argparse.Namespace:
    parser = argparse.ArgumentParser(
        description="Download EvoNAPS taxonomy and populate reduced taxonomy columns."
    )
    parser.add_argument(
        "-db",
        "--db-credentials",
        type=pathlib.Path,
        default=DEFAULT_CREDENTIALS,
        help=f"Database credentials file. Default: {DEFAULT_CREDENTIALS}",
    )
    parser.add_argument(
        "-tbl",
        "--table",
        type=pathlib.Path,
        default=DEFAULT_TAXONOMY_TABLE,
        help=f"Taxonomy rank JSON file. Default: {DEFAULT_TAXONOMY_TABLE}",
    )
    parser.add_argument(
        "--dry-run",
        action="store_true",
        help="Calculate and summarize reduced taxonomy values without updating the database.",
    )
    parser.add_argument(
        "--sample-size",
        type=int,
        default=None,
        help="Only fetch this many taxonomy rows for a quick dry-run/debug check.",
    )
    parser.add_argument(
        "--add-missing-columns",
        action="store_true",
        help="Add missing RED_TAX_ID, RED_TAX_NAME, and RED_RANK columns before updating.",
    )
    parser.add_argument(
        "--updates-file",
        type=pathlib.Path,
        default=DEFAULT_UPDATES_FILE,
        help=f"Reusable TSV with calculated reduced taxonomy values. Default: {DEFAULT_UPDATES_FILE}",
    )
    parser.add_argument(
        "--force-recompute",
        action="store_true",
        help="Recalculate the updates TSV even if --updates-file already exists.",
    )
    parser.add_argument(
        "--prepare-only",
        action="store_true",
        help="Create or reuse the updates TSV and stop before loading/updating the database.",
    )
    parser.add_argument(
        "--update-window",
        type=int,
        default=50000,
        help="TAX_ID range size for each committed UPDATE window.",
    )
    parser.add_argument(
        "--min-update-window",
        type=int,
        default=1000,
        help="Smallest TAX_ID range to use when splitting locked UPDATE windows.",
    )
    parser.add_argument(
        "--lock-wait-timeout",
        type=int,
        default=120,
        help="MySQL session innodb_lock_wait_timeout value in seconds.",
    )
    return parser.parse_args()


def main() -> None:
    args = parse_args()
    if args.update_window < 1:
        sys.exit("ERROR: --update-window must be at least 1.")
    if args.min_update_window < 1:
        sys.exit("ERROR: --min-update-window must be at least 1.")
    if args.lock_wait_timeout < 1:
        sys.exit("ERROR: --lock-wait-timeout must be at least 1.")
    if args.prepare_only and args.sample_size is not None:
        sys.exit("ERROR: --prepare-only cannot be combined with --sample-size.")

    start_time = time.monotonic()
    allowed_ranks = allowed_rank_keys(read_rank_table(args.table))
    db_config = read_db_credentials(args.db_credentials)

    conn = connect_to_database(db_config)
    try:
        log("Checking taxonomy columns...")
        columns = fetch_columns(conn)
        if args.add_missing_columns and not args.dry_run:
            columns = add_missing_columns(conn, columns)

        if args.sample_size is not None and not args.dry_run:
            sys.exit("ERROR: --sample-size can only be used together with --dry-run.")
        if args.sample_size is not None and args.sample_size < 1:
            sys.exit("ERROR: --sample-size must be at least 1.")

        if args.sample_size is not None:
            log("Downloading taxonomy table sample...")
            taxonomy = fetch_taxonomy(conn, sample_size=args.sample_size)
            log(f"Downloaded {len(taxonomy)} taxonomy rows.")

            log("Calculating reduced taxonomy values for sample...")
            updates = build_updates(taxonomy, allowed_ranks)
            summarize(updates)
        elif args.updates_file.exists() and not args.force_recompute:
            log(f"Reusing existing updates file: {args.updates_file}")
            summarize_updates_file(args.updates_file)
        else:
            log("Downloading taxonomy table...")
            taxonomy = fetch_taxonomy(conn, sample_size=args.sample_size)
            log(f"Downloaded {len(taxonomy)} taxonomy rows.")

            log("Calculating reduced taxonomy values...")
            updates = build_updates(taxonomy, allowed_ranks)
            log(f"Writing updates file: {args.updates_file}")
            write_updates_file(updates, UPDATE_COLUMNS, args.updates_file)
            summarize(updates)

        if args.dry_run:
            if args.sample_size is not None:
                log("Sample dry run only; database was not changed.")
            else:
                log("Dry run only; database was not changed.")
            return

        if args.prepare_only:
            log(f"Prepared updates file only: {args.updates_file}")
            return

        update_database(
            conn,
            columns,
            args.updates_file,
            args.update_window,
            args.min_update_window,
            args.lock_wait_timeout,
        )
        elapsed = time.monotonic() - start_time
        log(f"Finished in {elapsed:.1f} seconds.")
    finally:
        conn.close()


if __name__ == "__main__":
    main()
