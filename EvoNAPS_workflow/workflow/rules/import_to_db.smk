rule import_to_db:
    input: 
        prefix = "{ali_id}",
        ali_file = "{ali_id}_ali_parameters.tsv",
        seq_file = "{ali_id}_seq_parameters.tsv",
        model_file = "{ali_id}_model_parameters.tsv",
        branch_file = "{ali_id}_branch_parameters.tsv",
        tree_file = "{ali_id}_tree_parameters.tsv",
        pythia_file = "{ali_id}.pythia",
        credentials = "config/EvoNAPS_credentials.txt",
        import_commands = "config/EvoNAPS_import_statements.sql"
    output: "{ali_id}_importlog.txt"
    conda: "../envs/evonaps_env.yaml"
    shell: """
        if [ -f "{input.prefix}.info" ]; then 
            info_file="{input.prefix}.info"
        elif [ -f config/import.info ]; then
            info_file="config/import.info"
        else
            info_file="{input.prefix}.info"
        fi;

        pythia_score=$(echo {input.pythia_file})
        echo "PYTHIA_SCORE="$pythia_score >> {input.prefix}.info
        
        python workflow/scripts/import_to_db.py \
        -p {input.prefix} -db {input.credentials} \
        -c {input.import_commands} -i {input.prefix}.info
        """