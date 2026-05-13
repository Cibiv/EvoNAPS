#!/bin/bash

# Folder containing the fasta files
DIR="${1:-.}"

# Output config file
OUTFILE="config/config_test.yaml"

# Build Python list from fasta file paths
echo -n "ali_ids: [" >> "$OUTFILE"

first=true
for file in "$DIR"*.fasta; do
    # Skip if no files match
    [ -e "$file" ] || continue

    # Keep relative path
    relpath="$file"

    if [ "$first" = true ]; then
        first=false
    else
        echo -n ", " >> "$OUTFILE"
    fi

    echo -n "'$relpath'" >> "$OUTFILE"
done

echo "]" >> "$OUTFILE"

echo "Wrote config to $OUTFILE"