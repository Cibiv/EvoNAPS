rule import_to_db:
    input:
        prefix="{ali_id}",
        ali_file="{ali_id}_ali_parameters.tsv",
        seq_file="{ali_id}_seq_parameters.tsv",
        model_file="{ali_id}_model_parameters.tsv",
        branch_file="{ali_id}_branch_parameters.tsv",
        tree_file="{ali_id}_tree_parameters.tsv",
        pythia_file="{ali_id}.pythia.csv"
    output:
        "{ali_id}_summary.txt"
    params:
        credentials=config.get("credentials"),
        import_commands=config.get("import_commands"),
        tables=config.get("tables"),
        extra=lambda wc: (
            f"-i {config['info']}"
            if config.get("info")
            else ""
        )
    conda:
        "../envs/evonaps_env.yaml"
    shell: """
            python workflow/scripts/import_to_db.py \
                -p {input.prefix} \
                -db {params.credentials} \
                -t {params.tables} \
                -c {params.import_commands} \
                {params.extra} \
                -py {input.pythia_file}
        
        """