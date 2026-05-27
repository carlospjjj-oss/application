<script type="text/javascript" src="<?php echo base_url() ?>assets/js/jquery-ui/js/jquery-ui-1.9.2.custom.js"></script>
<div class="row-fluid" style="margin-top:0">
    <div class="span12">
        <div class="widget-box">
            <div class="widget-title" style="margin: -20px 0 0">
                <span class="icon"><i class="fas fa-calendar-plus"></i></span>
                <h5>Agendar Visita Técnica</h5>
            </div>
            <div class="widget-content nopadding tab-content">
                <?php echo $custom_error; ?>
                <form action="<?php echo current_url(); ?>" method="post" id="formVisita" class="form-horizontal">
                    <div class="control-group">
                        <label class="control-label">Cliente<span class="required">*</span></label>
                        <div class="controls">
                            <input id="cliente" type="text" name="cliente" class="span6" placeholder="Digite o nome do cliente..." />
                            <input id="clientes_id" type="hidden" name="clientes_id" value="" />
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label">Data da Visita<span class="required">*</span></label>
                        <div class="controls">
                            <input id="data_visita" type="text" name="data_visita" class="datepicker" autocomplete="off" />
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label">Horário</label>
                        <div class="controls">
                            <input id="hora_visita" type="time" name="hora_visita" />
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label">Status<span class="required">*</span></label>
                        <div class="controls">
                            <select name="status" class="span4">
                                <option value="Agendada">Agendada</option>
                                <option value="Realizada">Realizada</option>
                                <option value="Cancelada">Cancelada</option>
                                <option value="Reagendada">Reagendada</option>
                            </select>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label">Tipo de Serviço</label>
                        <div class="controls">
                            <select name="tipo_servico" class="span4">
                                <option value="">Selecione...</option>
                                <option value="Instalação de Carregador EV">Instalação de Carregador EV</option>
                                <option value="Manutenção Preventiva">Manutenção Preventiva</option>
                                <option value="Manutenção Corretiva">Manutenção Corretiva</option>
                                <option value="Vistoria Técnica">Vistoria Técnica</option>
                                <option value="Outro">Outro</option>
                            </select>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label">Endereço</label>
                        <div class="controls">
                            <input type="text" name="endereco" class="span6" placeholder="Endereço da visita" />
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label">Observações</label>
                        <div class="controls">
                            <textarea name="observacoes" rows="3" class="span6"></textarea>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label">Resultado</label>
                        <div class="controls">
                            <textarea name="resultado" rows="3" class="span6"></textarea>
                        </div>
                    </div>
                    <div class="form-actions">
                        <div class="span12">
                            <div class="span6 offset3" style="display:flex;justify-content:center">
                                <button type="submit" class="button btn btn-mini btn-success">
                                    <span class="button__icon"><i class='bx bx-plus-circle'></i></span>
                                    <span class="button__text2">Agendar</span>
                                </button>
                                <a href="<?php echo site_url('visita_tecnica/gerenciar') ?>" class="button btn btn-mini btn-warning">
                                    <span class="button__icon"><i class="bx bx-undo"></i></span>
                                    <span class="button__text2">Voltar</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script src="<?php echo base_url() ?>assets/js/jquery.validate.js"></script>
<script>
$(document).ready(function() {
    $("#cliente").autocomplete({
        source: "<?php echo base_url(); ?>index.php/os/autoCompleteCliente",
        minLength: 1,
        select: function(event, ui) { $("#clientes_id").val(ui.item.id); }
    });
    $(".datepicker").datepicker({ dateFormat: 'dd/mm/yy' });
    $('#formVisita').validate({
        rules: {
            cliente:    { required: true },
            clientes_id:{ required: true },
            data_visita:{ required: true },
            status:     { required: true },
        },
        messages: {
            cliente:    { required: 'Campo Requerido.' },
            clientes_id:{ required: 'Selecione um cliente da lista.' },
            data_visita:{ required: 'Campo Requerido.' },
            status:     { required: 'Campo Requerido.' },
        },
        errorClass: "help-inline",
        errorElement: "span",
        highlight: function(el) { $(el).parents('.control-group').addClass('error'); },
        unhighlight: function(el) { $(el).parents('.control-group').removeClass('error').addClass('success'); }
    });
});
</script>
