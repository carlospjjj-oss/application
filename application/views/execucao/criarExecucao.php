<link rel="stylesheet" href="<?php echo base_url(); ?>assets/js/jquery-ui/css/smoothness/jquery-ui-1.9.2.custom.css" />
<script type="text/javascript" src="<?php echo base_url() ?>assets/js/jquery-ui/js/jquery-ui-1.9.2.custom.js"></script>
<script type="text/javascript" src="<?php echo base_url() ?>assets/js/jquery.validate.js"></script>
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/custom.css" />

<div class="row-fluid" style="margin-top:0">
    <div class="span12">
        <div class="widget-box">
            <div class="widget-title">
                <span class="icon"><i class='bx bx-plug'></i></span>
                <h5>Cadastro de OS</h5>
            </div>
            <div class="widget-content nopadding tab-content">
                <div class="span12" style="margin-left: 0">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#tab1" data-toggle="tab">Detalhes da OS</a></li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab1">
                            <div class="span12">
                                <form action="<?= site_url('execucao/salvar_novo') ?>" method="post" id="formExecucao">
                                    <div class="span12" style="padding: 1%; margin-left: 0">
                                        <div class="span6" style="margin-left: 0">
                                            <label for="cliente">Cliente<span class="required">*</span></label>
                                            <input id="cliente" class="span12" type="text" name="cliente" value="" />
                                            <input id="clientes_id" class="span12" type="hidden" name="clientes_id" value="" />
                                        </div>
                                        <div class="span6">
                                            <label for="tecnico">Técnico Responsável<span class="required">*</span></label>
                                            <input id="tecnico" class="span12" type="text" name="tecnico" value="<?= $this->session->userdata('nome_admin') ?>" />
                                            <input id="usuarios_id" class="span12" type="hidden" name="usuarios_id" value="<?= $this->session->userdata('id_admin') ?>" />
                                            <label for="engenheiro" style="margin-top:8px;">Engenheiro Responsável<span class="required">*</span></label>
                                            <input id="engenheiro" class="span12" type="text" name="engenheiro" value="" />
                                        </div>
                                    </div>
                                    <div class="span12" style="padding: 1%; margin-left: 0">
                                        <div class="span3">
                                            <label for="status">Status<span class="required">*</span></label>
                                            <select class="span12" name="status" id="status">
                                                <option value="Aberto">Aberto</option>
                                                <option value="Em Andamento">Em Andamento</option>
                                                <option value="Executado">Executado</option>
                                                <option value="Finalizado">Finalizado</option>
                                                <option value="Cancelado">Cancelado</option>
                                            </select>
                                        </div>
                                        <div class="span3">
                                            <label for="garantia">Garantia (dias)</label>
                                            <input id="garantia" type="number" min="0" max="9999" class="span12" name="garantia" value="365" />
                                        </div>
                                        <div class="span3">
                                            <label for="dataInicial">Data Inicial<span class="required">*</span></label>
                                            <input id="dataInicial" autocomplete="off" class="span12 datepicker" type="text" name="dataInicial" value="<?= date('d/m/Y') ?>" />
                                        </div>
                                        <div class="span3">
                                            <label for="dataFinal">Data Final<span class="required">*</span></label>
                                            <input id="dataFinal" autocomplete="off" class="span12 datepicker" type="text" name="dataFinal" value="" />
                                        </div>
                                    </div>
                                    <div class="span12" style="padding: 1%; margin-left: 0">
                                        <div class="span12" style="display:flex; justify-content: center; gap: 8px;">
                                            <button type="submit" class="button btn btn-success" id="btnContinuar">
                                                <span class="button__icon"><i class='bx bx-chevrons-right'></i></span><span class="button__text2">Continuar</span>
                                            </button>
                                            <a href="<?= site_url('execucao') ?>" class="button btn btn-warning">
                                                <span class="button__icon"><i class="bx bx-undo"></i></span><span class="button__text2">Voltar</span>
                                            </a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $("#cliente").autocomplete({
            source: "<?= base_url() ?>index.php/os/autoCompleteCliente",
            minLength: 1,
            select: function(event, ui) {
                $("#clientes_id").val(ui.item.id);
            }
        });
        $("#tecnico").autocomplete({
            source: "<?= base_url() ?>index.php/os/autoCompleteUsuario",
            minLength: 1,
            select: function(event, ui) {
                $("#usuarios_id").val(ui.item.id);
            }
        });

        $("#formExecucao").validate({
            rules: {
                cliente:     { required: true },
                tecnico:     { required: true },
                engenheiro:  { required: true },
                dataInicial: { required: true },
                dataFinal:   { required: true }
            },
            messages: {
                cliente:     { required: 'Campo Requerido.' },
                tecnico:     { required: 'Campo Requerido.' },
                engenheiro:  { required: 'Campo Requerido.' },
                dataInicial: { required: 'Campo Requerido.' },
                dataFinal:   { required: 'Campo Requerido.' }
            },
            errorClass: "help-inline",
            errorElement: "span",
            highlight: function(element, errorClass, validClass) {
                $(element).parents('.control-group').addClass('error');
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).parents('.control-group').removeClass('error');
                $(element).parents('.control-group').addClass('success');
            },
            submitHandler: function(form) {
                // Garante que clientes_id foi preenchido pelo autocomplete
                if (!$("#clientes_id").val()) {
                    alert('Selecione um cliente da lista.');
                    return false;
                }
                if (!$("#usuarios_id").val()) {
                    alert('Selecione um técnico da lista.');
                    return false;
                }
                form.submit();
            }
        });

        $(".datepicker").datepicker({ dateFormat: 'dd/mm/yy' });
    });
</script>
