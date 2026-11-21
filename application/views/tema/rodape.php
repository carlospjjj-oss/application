<div class="row-fluid">
    <div id="footer" class="span12">
        <a class="pecolor" href="https://www.supremasti.com.br" target="_blank"><?= date('Y') ?> &copy; Supremasti</a> - Versão: <?= $this->config->item('app_version') ?>
    </div>
</div>
<!--end-Footer-part-->

<!-- Botão mobile menu -->
<button id="mobile-menu-toggle" title="Menu">☰</button>

<script src="<?= base_url() ?>assets/js/bootstrap.min.js"></script>
<script src="<?= base_url() ?>assets/js/matrix.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        // Toggle sidebar no mobile
        $('#mobile-menu-toggle').on('click', function() {
            $('#sidebar-left').toggleClass('mobile-open');
        });
        // Fecha sidebar ao clicar fora
        $(document).on('click', function(e) {
            if ($(window).width() <= 768) {
                if (!$(e.target).closest('#sidebar-left, #mobile-menu-toggle').length) {
                    $('#sidebar-left').removeClass('mobile-open');
                }
            }
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
