<link rel="stylesheet" href="<?php echo base_url() ?>assets/js/jquery-ui/css/smoothness/jquery-ui-1.9.2.custom.css" />
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
                        <label class="control-label">Técnico</label>
                        <div class="controls">
                            <select name="usuarios_id" class="span6">
                                <option value="">Selecione...</option>
                                <?php foreach ($usuariosAtivos as $u): ?>
                                <option value="<?php echo $u->idUsuarios; ?>" <?php echo ($result->usuarios_id == $u->idUsuarios) ? 'selected' : ''; ?>><?php echo $u->nome; ?></option>
                                <?php endforeach; ?>
                            </select>
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
                            <select name="status" id="status" class="span4">
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
                            <input id="endereco" type="text" name="endereco" class="span6" value="<?php echo $result->endereco ?>" />
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
                            <textarea name="resultado" id="resultado" rows="3" class="span6"><?php echo $result->resultado ?></textarea>
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

                <hr>
                <h4>Fotos e Vídeos (GED)</h4>
                <p class="text-muted">Máximo 6 fotos e 3 vídeos (até 40 segundos cada) por visita.</p>

                <div class="span12" style="margin-left:0;">
                    <label for="inputMidia" class="btn btn-success">
                        <i class="fas fa-upload"></i> Anexar Foto/Vídeo
                    </label>
                    <input type="file" id="inputMidia" accept=".jpg,.jpeg,.png,.mp4,.mov,.webm" style="display:none;">
                    <input type="hidden" id="csrfMidiaToken" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
                    <span id="uploadStatus" style="margin-left:10px;"></span>
                </div>

                <div id="galeriaMidia" class="span12" style="margin-left:0; margin-top:15px; display:flex; flex-wrap:wrap; gap:10px;">
                    <?php foreach ($midias as $m): ?>
                        <div class="midia-item" data-id="<?php echo $m->id; ?>" style="width:150px; text-align:center; border:1px solid #ddd; border-radius:4px; padding:5px;">
                            <?php if ($m->tipo == 'foto'): ?>
                                <img src="<?php echo base_url() . 'assets/visita_midia/' . $m->arquivo; ?>" style="width:100%; height:100px; object-fit:cover; border-radius:4px;">
                            <?php else: ?>
                                <video src="<?php echo base_url() . 'assets/visita_midia/' . $m->arquivo; ?>" style="width:100%; height:100px; object-fit:cover; border-radius:4px;" controls></video>
                            <?php endif; ?>
                            <button type="button" class="btn btn-danger btn-mini btn-excluir-midia" data-id="<?php echo $m->id; ?>" style="margin-top:5px; width:100%;">
                                <i class="bx bx-trash"></i> Excluir
                            </button>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="<?php echo base_url() ?>assets/js/jquery.validate.js"></script>
<script>
$(document).ready(function() {
    function toggleResultado() {
        var status = $("#status").val();
        if (status === "Realizada") {
            $("#resultado").prop("readonly", false);
        } else {
            $("#resultado").prop("readonly", true);
        }
    }
    $("#status").on("change", toggleResultado);
    toggleResultado();

    $("#cliente").autocomplete({
        source: "<?php echo base_url(); ?>index.php/os/autoCompleteCliente",
        minLength: 1,
        select: function(event, ui) {
            $("#clientes_id").val(ui.item.id);
            if (ui.item.endereco) {
                $("#endereco").val(ui.item.endereco);
            }
        }
    });
    $(".datepicker").datepicker({ dateFormat: 'dd/mm/yy' });

    var visitaId = <?php echo $result->id; ?>;

    $('#inputMidia').on('change', function() {
        var file = this.files[0];
        if (!file) return;

        var ext = file.name.split('.').pop().toLowerCase();
        var isVideo = ['mp4', 'mov', 'webm'].indexOf(ext) !== -1;

        function enviarUpload() {
            $('#uploadStatus').text('Enviando...');

            $.get("<?php echo base_url(); ?>index.php/visita_tecnica/getCsrfToken", function(tokenResp) {
                var formData = new FormData();
                formData.append('midia', file);
                formData.append('visita_tecnica_id', visitaId);
                formData.append('<?php echo $this->security->get_csrf_token_name(); ?>', tokenResp.csrf_hash);

                $.ajax({
                    url: "<?php echo base_url(); ?>index.php/visita_tecnica/uploadMidia",
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    dataType: 'json',
                    success: function(resp) {
                    if (resp.csrf_hash) {
                        $('#csrfMidiaToken').val(resp.csrf_hash);
                    }
                    if (resp.success) {
                        $('#uploadStatus').text('Enviado com sucesso!');
                        var baseUrl = "<?php echo base_url(); ?>assets/visita_midia/";
                        var mediaTag = resp.tipo === 'foto'
                            ? '<img src="' + baseUrl + resp.arquivo + '" style="width:100%; height:100px; object-fit:cover; border-radius:4px;">'
                            : '<video src="' + baseUrl + resp.arquivo + '" style="width:100%; height:100px; object-fit:cover; border-radius:4px;" controls></video>';
                        $('#galeriaMidia').append(
                            '<div class="midia-item" data-id="' + resp.id + '" style="width:150px; text-align:center; border:1px solid #ddd; border-radius:4px; padding:5px;">' +
                            mediaTag +
                            '<button type="button" class="btn btn-danger btn-mini btn-excluir-midia" data-id="' + resp.id + '" style="margin-top:5px; width:100%;"><i class="bx bx-trash"></i> Excluir</button>' +
                            '</div>'
                        );
                    } else {
                        $('#uploadStatus').text('Erro: ' + resp.message);
                    }
                        $('#inputMidia').val('');
                        setTimeout(function() { $('#uploadStatus').text(''); }, 4000);
                    },
                    error: function() {
                        $('#uploadStatus').text('Erro ao enviar arquivo.');
                        $('#inputMidia').val('');
                    }
                });
            }, 'json');
        }

        if (isVideo) {
            var videoEl = document.createElement('video');
            videoEl.preload = 'metadata';
            videoEl.onloadedmetadata = function() {
                window.URL.revokeObjectURL(videoEl.src);
                if (videoEl.duration > 40) {
                    alert('O vídeo deve ter no máximo 40 segundos. Duração atual: ' + Math.round(videoEl.duration) + 's');
                    $('#inputMidia').val('');
                } else {
                    enviarUpload();
                }
            };
            videoEl.src = URL.createObjectURL(file);
        } else {
            enviarUpload();
        }
    });

    $(document).on('click', '.btn-excluir-midia', function() {
        if (!confirm('Deseja realmente excluir este arquivo?')) return;
        var $item = $(this).closest('.midia-item');
        var id = $item.data('id');
        $.get("<?php echo base_url(); ?>index.php/visita_tecnica/getCsrfToken", function(tokenResp) {
            var csrfName = "<?php echo $this->security->get_csrf_token_name(); ?>";
            var postData = { id: id };
            postData[csrfName] = tokenResp.csrf_hash;
            $.post("<?php echo base_url(); ?>index.php/visita_tecnica/excluirMidia", postData, function(resp) {
                if (resp.success) {
                    $item.remove();
                } else {
                    alert('Erro ao excluir arquivo.');
                }
            }, 'json');
        }, 'json');
    });
});
</script>
