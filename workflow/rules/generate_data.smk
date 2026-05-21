rule test_models_and_tree_search:
    """
    The TestModelsAndTreeSearch rule starts the model selection and tree search using the alignment file as input.
    """
    output:
        iqtree_file = "{ali_id}.iqtree",
        treefile = "{ali_id}.treefile",
        checkpoint_file = "{ali_id}.ckp.gz",
        log_file = "{ali_id}.log",
        mldist="{ali_id}.mldist",
        model_file="{ali_id}.model.gz"
    input:
        seq = "{ali_id}"
    params:
        seq_type = config["seq_type"]
    conda:
        "../envs/evonaps_env.yaml"
    shell: """
        workflow/bin/iqtree2mod \
            -s {input.seq} \
            --seqtype {params.seq_type} \
            -m MFP -mrate E,I,G,I+G,R \
            > {input.seq}.iqlog

        if test -f {input.seq}.uniqueseq.phy; then

            seed_num=$(grep "^Random seed number: " {input.seq}.iqtree | awk '{{print $4}}')

            workflow/bin/iqtree2mod \
                -s {input.seq} \
                --seqtype {params.seq_type} \
                -m MFP -mrate E,I,G,I+G,R \
                --keep-ident \
                --seed $seed_num \
                --prefix {input.seq}.keep_ident \
                > {input.seq}.keep_ident.iqlog
                
        fi;
        """

rule parse_parameters:
    """
    Extract alignment and tree parameters from IQ-TREE output.
    """
    input:
        seq="{ali_id}",
        treefile="{ali_id}.treefile",
        iqtree_file="{ali_id}.iqtree",
        checkpoint_file = "{ali_id}.ckp.gz",
        log_file = "{ali_id}.log",
        mldist = "{ali_id}.mldist",
        model_file = "{ali_id}.model.gz"
    output:
        "{ali_id}_ali_parameters.tsv",
        "{ali_id}_seq_parameters.tsv",
        "{ali_id}_model_parameters.tsv",
        "{ali_id}_branch_parameters.tsv",
        "{ali_id}_tree_parameters.tsv"
    params:
        config_dir=config.get("config_dir", "config/"),
        extra=lambda wc: (
            f"-t {config['tax']}" if config.get("tax") else ""
        )
    conda:
        "../envs/evonaps_env.yaml"
    shell: """
        python workflow/scripts/parse_parameters.py \
            -p {input.seq} \
            -o {input.seq} \
            {params.extra} \
            -c {params.config_dir} -q
        """

rule calculate_pythia_score:
    output:
        "{ali_id}.pythia.csv"
    input:
        seq = "{ali_id}"
    conda: "../envs/pypythia.yml"
    shell: """
        pythia -m {input.seq} -r workflow/bin/raxml-ng --prefix {input.seq} --forceDuplicates > {input.seq}.pythia.ter.log 2>&1
        """
