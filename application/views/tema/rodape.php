<div class="row-fluid">
    <div id="footer" class="span12">
        <a class="pecolor" href="https://www.supremasti.com.br" target="_blank"><?= date('Y') ?> &copy; Supremasti</a> - Versão: <?= $this->config->item('app_version') ?>
    </div>
</div>
<!--end-Footer-part-->
<script src="<?= base_url() ?>assets/js/bootstrap.min.js"></script>
<script src="<?= base_url() ?>assets/js/matrix.js"></script>
</body>
<script type="text/javascript">
    // Toggle do menu lateral (seta > / <)
    $(document).ready(function() {
        var $sidebar = $('#sidebar');
        var STORAGE_KEY = 'sidebar_open';

        // Restaura estado salvo
        if (localStorage.getItem(STORAGE_KEY) === '1') {
            $sidebar.addClass('open');
        }

        // Clique na seta
        $sidebar.find('.mode').on('click', function(e) {
            e.preventDefault();
            $sidebar.toggleClass('open');
            localStorage.setItem(STORAGE_KEY, $sidebar.hasClass('open') ? '1' : '0');
        });
    });

    $(document).ready(function() {
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
    } );
</script>
</html>
