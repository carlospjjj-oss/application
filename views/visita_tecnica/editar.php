<script type="text/javascript" src="<?php echo base_url() ?>assets/js/jquery-ui/js/jquery-ui-1.9.2.custom.js"></script>
<div class="row-fluid" style="margin-top:0">
    <div class="span12">
        <div class="widget-box">
            <div class="widget-title" style="margin: -20px 0 0">
                <span class="icon"><i class="fas fa-calendar-alt"></i></span>
                <h5>Editar Visita Técnica</h5>
            </div>
            <div class="widget-content nopadding tab-content">
                <?php echo $custom_error; ?>
                <form action="<?php echo current_url(); ?>" method="post" id="formVisita" class="form-horizontal">
                    <div class="control-group">
                        <label class="control-label">Cliente<span class="required">*</span></label>
                        <div class="controls">
                            <input id="cliente" type="text" name="cliente" class="span6" value="<?php echo $result->nomeCliente ?? '' ?>" />
                            <input id="clientes_id" type="hidden" name="clientes_id" value="<?php echo $result->clientes_id ?>" />
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label">Data da Visita<span class="required">*</span></label>
                        <div class="controls">
                            <input id="data_visita" type="text" name="data_visita" class="datepicker" autocomplete="off"
                                value="<?php echo $result->data_visita ? date('d/m/Y', strtotime($result->data_visita)) : '' ?>" />
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label">Horário</label>
                        <div class="controls">
                            <input type="time" name="hora_visita" value="<?php echo $result->hora_visita ? substr($result->hora_visita,0,5) : '' ?>" />
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label">Status<span class="required">*</span></label>
                        <div class="controls">
                            <select name="status" class="span4">
                                <?php foreach (['Agendada','Realizada','Cancelada','Reagendada'] as $s): ?>
                                <option value="<?php echo $s ?>" <?php echo $result->status == $s ? 'selected' : '' ?>><?php echo $s ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label">Tipo de Serviço</label>
                        <div class="controls">
                            <select name="tipo_servico" class="span4">
                                <?php
                                $tipos = ['Instalação de Carregador EV','Manutenção Preventiva','Manutenção Corretiva','Vistoria Técnica','Outro'];
                                foreach ($tipos as $t):
                                ?>
                                <option value="<?php echo $t ?>" <?php echo $result->tipo_servico == $t ? 'selected' : '' ?>><?php echo $t ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label">Endereço</label>
                        <div class="controls">
                            <input type="text" name="endereco" class="span6" value="<?php echo $result->endereco ?>" />
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label">Observações</label>
                        <div class="controls">
                            <textarea name="observacoes" rows="3" class="span6"><?php echo $result->observacoes ?></textarea>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label">Resultado</label>
                        <div class="controls">
                            <textarea name="resultado" rows="3" class="span6"><?php echo $result->resultado ?></textarea>
                        </div>
                    </div>
                    <div class="form-actions">
                        <div class="span12">
                            <div class="span6 offset3" style="display:flex;justify-content:center">
                                <button type="submit" class="button btn btn-primary">
                                    <span class="button__icon"><i class="bx bx-sync"></i></span>
                                    <span class="button__text2">Atualizar</span>
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
});
</script>
