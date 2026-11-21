<div class="row-fluid">
    <div id="footer" class="span12">
        <a class="pecolor" href="https://www.supremasti.com.br" target="_blank"><?= date('Y') ?> &copy; Supremasti</a> - Versão: <?= $this->config->item('app_version') ?>
    </div>
</div>
<!--end-Footer-part-->
<script src="<?= base_url() ?>assets/js/bootstrap.min.js"></script>
<script src="<?= base_url() ?>assets/js/matrix.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        // Toggle sidebar: escuta clique direto no .mode para garantir funcionamento em todas as páginas
        $('#sidebar .mode').on('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            $('#sidebar').toggleClass('open');
        });

        var dataTableEnabled = '<?= $configuration['control_datatable'] ?>';
        if(dataTableEnabled == '1') {
            $('#tabela').dataTable( {
                "ordering": false,
                "info": false,
                "language": {
                    "url": "<?= base_url() ?>assets/js/dataTable_pt-br.json",
                },
                "oLanguage": {
                    "sSearch": "Pesquisa rápida na tabela abaixo:"
                }
            } );
        }
    });
</script>
</body>
</html>
