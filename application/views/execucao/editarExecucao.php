<link rel="stylesheet" href="<?= base_url(); ?>assets/js/jquery-ui/css/smoothness/jquery-ui-1.9.2.custom.css" />
<script src="<?= base_url() ?>assets/js/jquery-ui/js/jquery-ui-1.9.2.custom.js"></script>

<div class="new122">
    <div class="widget-title" style="margin:-20px 0 0">
        <span class="icon"><i class='bx bx-plug'></i></span>
        <h5>Editar Execução</h5>
    </div>

    <!-- Botões de ação -->
    <div style="margin:10px 0; display:flex; gap:8px; flex-wrap:wrap;">
        <a href="<?= base_url() ?>index.php/execucao/visualizar/<?= $result->os_id ?>" class="button btn btn-mini btn-primary">
            <span class="button__icon"><i class='bx bx-show'></i></span><span class="button__text2">Visualizar</span>
        </a>
        <a href="<?= base_url() ?>index.php/execucao" class="button btn btn-mini btn-warning">
            <span class="button__icon"><i class='bx bx-undo'></i></span><span class="button__text2">Voltar</span>
        </a>
    </div>

    <!-- Tabs -->
    <ul class="nav nav-tabs" id="execucaoTabs">
        <li class="active"><a href="#tab-detalhes" data-toggle="tab">Detalhes da Execução</a></li>
        <li><a href="#tab-produtos"  data-toggle="tab">Produtos</a></li>
        <li><a href="#tab-servicos"  data-toggle="tab">Serviços</a></li>
        <li><a href="#tab-anotacoes" data-toggle="tab">Anotações</a></li>
        <li><a href="#tab-anexos"    data-toggle="tab">Anexos</a></li>
        <li><a href="#tab-checklist" data-toggle="tab">✅ Checklist</a></li>
    </ul>

    <div class="tab-content" style="padding:20px 10px;">

        <!-- ============================================================
             ABA: DETALHES
        ============================================================ -->
        <div class="tab-pane active" id="tab-detalhes">
            <h4 style="font-weight:700; margin-bottom:16px;">
                N° OS Execução: <span style="color:#337ab7"><?= $result->os_id ?></span>
            </h4>

            <form action="<?= base_url() ?>index.php/execucao/salvar" method="post">
                <input type="hidden" name="idExecucao" value="<?= $result->idExecucao ?>">
                <input type="hidden" name="os_id"      value="<?= $result->os_id ?>">

                <div class="row-fluid">
                    <div class="span6">
                        <label>Cliente</label>
                        <input type="text" class="span12" value="<?= $result->nomeCliente ?>" disabled>
                    </div>
                    <div class="span6">
                        <label>Técnico Responsável</label>
                        <input type="text" class="span12" value="<?= $result->tecnico ?>" disabled>
                        <label style="margin-top:8px;">Engenheiro Responsável</label>
                        <input type="text" class="span12" value="<?= $result->engenheiro ?>" disabled>
                    </div>
                </div>

                <div class="row-fluid" style="margin-top:12px;">
                    <div class="span3">
                        <label>Status <span style="color:red">*</span></label>
                        <select name="status" class="span12">
                            <?php foreach (['Aberto','Em Andamento','Executado','Finalizado','Cancelado'] as $s): ?>
                                <option value="<?= $s ?>" <?= $result->status == $s ? 'selected' : '' ?>><?= $s ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>
                    <div class="span3">
                        <label>Garantia (dias)</label>
                        <input type="text" class="span12" value="<?= $result->garantia ?>" disabled>
                    </div>
                    <div class="span3">
                        <label>Data Inicial</label>
                        <input type="text" class="span12" value="<?= (!empty($result->dataInicial) && $result->dataInicial != '0000-00-00') ? date('d/m/Y', strtotime($result->dataInicial)) : '' ?>" disabled>
                    </div>
                    <div class="span3">
                        <label>Data Final</label>
                        <input type="text" class="span12" value="<?= (!empty($result->dataFinal) && $result->dataFinal != '0000-00-00') ? date('d/m/Y', strtotime($result->dataFinal)) : '' ?>" disabled>
                    </div>
                </div>

                <div style="margin-top:16px; border-top:1px solid #eee; padding-top:14px;">
                    <strong style="font-size:13px; display:block; margin-bottom:8px;">Tipo de Instalação</strong>
                    <div class="row-fluid">
                        <div class="span6">
                            <label>Tipo de Instalação</label>
                            <input type="text" class="span12" value="<?= htmlspecialchars($result->tipo_instalacao ?? '') ?>" disabled>
                            <input type="hidden" name="tipo_instalacao" value="<?= htmlspecialchars($result->tipo_instalacao ?? '') ?>">
                        </div>
                        <div class="span3">
                            <label>Início da Instalação</label>
                            <input type="text" name="data_inicio_instalacao" class="span12 datepicker" autocomplete="off"
                                value="<?= (!empty($result->data_inicio_instalacao) && $result->data_inicio_instalacao != '0000-00-00') ? date('d/m/Y', strtotime($result->data_inicio_instalacao)) : '' ?>">
                        </div>
                        <div class="span3">
                            <label>Fim da Instalação</label>
                            <input type="text" name="data_fim_instalacao" class="span12 datepicker" autocomplete="off"
                                value="<?= (!empty($result->data_fim_instalacao) && $result->data_fim_instalacao != '0000-00-00') ? date('d/m/Y', strtotime($result->data_fim_instalacao)) : '' ?>">
                        </div>
                    </div>
                </div>

                <div style="margin-top:20px; display:flex; gap:8px;">
                    <button type="submit" class="button btn btn-success">
                        <span class="button__icon"><i class='bx bx-save'></i></span><span class="button__text2">Atualizar</span>
                    </button>
                    <a href="<?= base_url() ?>index.php/execucao" class="button btn btn-warning">
                        <span class="button__icon"><i class='bx bx-undo'></i></span><span class="button__text2">Voltar</span>
                    </a>
                </div>
            </form>
        </div>

        <!-- ============================================================
             ABA: PRODUTOS
        ============================================================ -->
        <div class="tab-pane" id="tab-produtos">
            <?php
            $totalProdutos = 0;
            if ($produtos): ?>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Produto</th>
                        <th style="text-align:center">Quantidade</th>
                        <th>Preço unit.</th>
                        <th>Sub-total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($produtos as $p):
                        $sub = $p->quantidade * $p->preco;
                        $totalProdutos += $sub;
                    ?>
                    <tr>
                        <td><?= $p->descricao ?></td>
                        <td style="text-align:center"><?= $p->quantidade ?></td>
                        <td>R$: <?= number_format($p->preco, 2, ',', '.') ?></td>
                        <td>R$: <?= number_format($sub, 2, ',', '.') ?></td>
                    </tr>
                    <?php endforeach ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" style="text-align:right; font-weight:700;">Total:</td>
                        <td style="font-weight:700;">R$: <?= number_format($totalProdutos, 2, ',', '.') ?></td>
                    </tr>
                </tfoot>
            </table>
            <?php else: ?>
                <p style="color:#999; padding:20px 0;">Nenhum produto nesta OS.</p>
            <?php endif ?>
        </div>

        <!-- ============================================================
             ABA: SERVIÇOS
        ============================================================ -->
        <div class="tab-pane" id="tab-servicos">
            <?php
            $totalServicos = 0;
            if ($servicos): ?>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Serviço</th>
                        <th style="text-align:center">Quantidade</th>
                        <th>Preço</th>
                        <th>Sub-total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($servicos as $s):
                        $sub = $s->quantidade * $s->preco;
                        $totalServicos += $sub;
                    ?>
                    <tr>
                        <td><?= $s->nome ?></td>
                        <td style="text-align:center"><?= $s->quantidade ?></td>
                        <td>R$: <?= number_format($s->preco, 2, ',', '.') ?></td>
                        <td>R$: <?= number_format($sub, 2, ',', '.') ?></td>
                    </tr>
                    <?php endforeach ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" style="text-align:right; font-weight:700;">Total:</td>
                        <td style="font-weight:700;">R$: <?= number_format($totalServicos, 2, ',', '.') ?></td>
                    </tr>
                </tfoot>
            </table>
            <?php else: ?>
                <p style="color:#999; padding:20px 0;">Nenhum serviço nesta OS.</p>
            <?php endif ?>
        </div>

        <!-- ============================================================
             ABA: ANOTAÇÕES
        ============================================================ -->
        <div class="tab-pane" id="tab-anotacoes">
            <?php if ($anotacoes): ?>
            <table class="table table-bordered">
                <thead><tr><th>Data/Hora</th><th>Anotação</th></tr></thead>
                <tbody>
                    <?php foreach ($anotacoes as $a): ?>
                    <tr>
                        <td style="white-space:nowrap"><?= date('d/m/Y H:i:s', strtotime($a->data_hora)) ?></td>
                        <td><?= $a->anotacao ?></td>
                    </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
            <?php else: ?>
                <p style="color:#999; padding:20px 0;">Nenhuma anotação.</p>
            <?php endif ?>
        </div>

        <!-- ============================================================
             ABA: ANEXOS
        ============================================================ -->
        <div class="tab-pane" id="tab-anexos">
            <p style="color:#999; font-size:12px; margin-bottom:12px;">
                Máximo: 6 fotos, 1 vídeo (até 1:30), 1 PDF e 1 documento (DOC/DOCX).
            </p>

            <?php if ($this->permission->checkPermission($this->session->userdata('permissao'), 'eArquivo')): ?>
            <?php
                $qtdFotos = 0; $qtdVideos = 0; $qtdPdf = 0; $qtdDoc = 0;
                foreach ($anexos as $a) {
                    $ext = strtolower(pathinfo($a->anexo, PATHINFO_EXTENSION));
                    if (in_array($ext, ['jpg','jpeg','png','gif','webp'])) $qtdFotos++;
                    elseif (in_array($ext, ['mp4','mov','webm'])) $qtdVideos++;
                    elseif ($ext === 'pdf') $qtdPdf++;
                    elseif (in_array($ext, ['doc','docx'])) $qtdDoc++;
                }
            ?>
            <div style="display:flex;gap:10px;flex-wrap:wrap;margin-bottom:16px;">
                <?php if ($qtdFotos < 6 || $qtdVideos < 1): ?>
                <div>
                    <label style="font-size:12px;font-weight:600;display:block;margin-bottom:4px;">Foto/Vídeo (<?= $qtdFotos ?>/6 fotos · <?= $qtdVideos ?>/1 vídeo)</label>
                    <label id="lblFotoVideo" style="cursor:pointer;display:inline-flex;align-items:center;gap:6px;background:#5cb85c;color:#fff;padding:0 14px;height:30px;border-radius:4px;font-size:13px;font-weight:600;">
                        <i class="fas fa-upload"></i> Anexar Foto/Vídeo
                    </label>
                    <input type="file" id="inputFotoVideo" accept="image/*,video/mp4,video/quicktime,video/webm" multiple style="display:none">
                    <span id="statusFotoVideo" style="font-size:12px;color:#888;margin-left:8px;"></span>
                </div>
                <?php endif ?>
                <?php if ($qtdPdf < 1): ?>
                <div>
                    <label style="font-size:12px;font-weight:600;display:block;margin-bottom:4px;">PDF (<?= $qtdPdf ?>/1)</label>
                    <label id="lblPdf" style="cursor:pointer;display:inline-flex;align-items:center;gap:6px;background:#d9534f;color:#fff;padding:0 14px;height:30px;border-radius:4px;font-size:13px;font-weight:600;">
                        <i class="bx bx-file-pdf"></i> Anexar PDF
                    </label>
                    <input type="file" id="inputPdf" accept=".pdf" style="display:none">
                    <span id="statusPdf" style="font-size:12px;color:#888;margin-left:8px;"></span>
                </div>
                <?php endif ?>
                <?php if ($qtdDoc < 1): ?>
                <div>
                    <label style="font-size:12px;font-weight:600;display:block;margin-bottom:4px;">Documento (<?= $qtdDoc ?>/1)</label>
                    <label id="lblDoc" style="cursor:pointer;display:inline-flex;align-items:center;gap:6px;background:#337ab7;color:#fff;padding:0 14px;height:30px;border-radius:4px;font-size:13px;font-weight:600;">
                        <i class="bx bx-file"></i> Anexar DOC
                    </label>
                    <input type="file" id="inputDoc" accept=".doc,.docx" style="display:none">
                    <span id="statusDoc" style="font-size:12px;color:#888;margin-left:8px;"></span>
                </div>
                <?php endif ?>
            </div>
            <?php endif ?>

            <div id="listaAnexos">
            <?php if ($anexos): ?>
            <table class="table table-bordered">
                <thead><tr><th>Arquivo</th><th>Tipo</th><th>Ações</th></tr></thead>
                <tbody>
                    <?php foreach ($anexos as $a):
                        $ext = strtolower(pathinfo($a->anexo, PATHINFO_EXTENSION));
                        $tipo = in_array($ext, ['jpg','jpeg','png','gif','webp']) ? 'Foto'
                              : (in_array($ext, ['mp4','mov','webm']) ? 'Vídeo'
                              : ($ext === 'pdf' ? 'PDF'
                              : (in_array($ext, ['doc','docx']) ? 'Documento' : strtoupper($ext))));
                    ?>
                    <tr>
                        <td><a href="<?= $a->url ?>" target="_blank"><?= $a->anexo ?></a></td>
                        <td><?= $tipo ?></td>
                        <td>
                            <form action="<?= base_url() ?>index.php/execucao/excluir_anexo" method="post" style="display:inline">
                                <input type="hidden" name="idAnexo" value="<?= $a->idAnexo ?>">
                                <input type="hidden" name="os_id"   value="<?= $result->os_id ?>">
                                <button type="submit" class="btn-nwe4" title="Excluir"><i class="bx bx-trash-alt bx-xs"></i></button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
            <?php else: ?>
                <p style="color:#ccc; text-align:center; padding:30px 0; font-size:13px;">
                    <i class="bx bx-paperclip" style="font-size:30px; display:block; margin-bottom:8px;"></i>
                    Nenhum anexo adicionado ainda.
                </p>
            <?php endif ?>
            </div>
        </div>

        <!-- ============================================================
             ABA: CHECKLIST
        ============================================================ -->
        <div class="tab-pane" id="tab-checklist">
            <form action="<?= base_url() ?>index.php/execucao/salvar" method="post">
                <input type="hidden" name="idExecucao" value="<?= $result->idExecucao ?>">
                <input type="hidden" name="os_id"      value="<?= $result->os_id ?>">
                <input type="hidden" name="status"     value="<?= $result->status ?>">

                <!-- Instalação elétrica -->
                <strong style="display:block; font-size:13px; color:#555; margin-bottom:10px; border-bottom:1px solid #eee; padding-bottom:6px;">
                    Instalação Elétrica
                </strong>
                <?php
                $checklistInstalacao = [
                    'chk_quadro'     => 'Verificação do quadro elétrico',
                    'chk_disjuntor'  => 'Instalação do disjuntor dedicado',
                    'chk_cabos'      => 'Passagem de cabos e eletrodutos',
                    'chk_fixacao'    => 'Fixação e cabeamento do carregador',
                    'chk_teste'      => 'Teste de funcionamento e carga',
                    'chk_orientacao' => 'Orientação ao cliente sobre uso',
                ];
                ?>
                <div style="display:grid; grid-template-columns:repeat(auto-fill,minmax(220px,1fr)); gap:7px 14px; margin-bottom:20px;">
                    <?php foreach ($checklistInstalacao as $field => $label):
                        $checked = !empty($result->$field) ? 'checked' : '';
                        $bg      = $checked ? 'background:#e8f8f0; border-color:#1a9e5c;' : 'background:#fff;';
                    ?>
                    <label style="display:flex; align-items:center; gap:8px; padding:7px 10px; border:1px solid #d0d8ee; border-radius:5px; cursor:pointer; font-weight:normal; <?= $bg ?>">
                        <input type="checkbox" name="<?= $field ?>" value="1" <?= $checked ?> style="width:15px;height:15px;cursor:pointer;accent-color:#1a9e5c;">
                        <span style="font-size:12px;"><?= $label ?></span>
                    </label>
                    <?php endforeach ?>
                </div>

                <!-- Documentação -->
                <strong style="display:block; font-size:13px; color:#555; margin-bottom:10px; border-bottom:1px solid #eee; padding-bottom:6px;">
                    Documentação entregue ao cliente
                </strong>
                <?php
                $checklistDocs = [
                    'doc_projeto_eletrico'          => 'Projeto elétrico',
                    'doc_as_built'                  => 'Projeto As-Built',
                    'doc_croqui'                    => 'Croqui',
                    'doc_diagrama_unifilar'         => 'Diagrama unifilar',
                    'doc_memorial_descritivo'       => 'Memorial descritivo',
                    'doc_memorial_calculo'          => 'Memorial de cálculo',
                    'doc_laudo_tecnico'             => 'Laudo técnico',
                    'doc_art_projeto'               => 'ART de projeto',
                    'doc_art_execucao'              => 'ART de execução',
                    'doc_relatorio_fotografico'     => 'Relatório fotográfico',
                    'doc_relatorio_comissionamento' => 'Relatório de comissionamento',
                    'doc_orientacao_operacao'       => 'Orientação de operação ao cliente',
                    'doc_orientacao_emergencia'     => 'Orientação sobre desligamento de emergência',
                ];
                ?>
                <div style="display:grid; grid-template-columns:repeat(auto-fill,minmax(220px,1fr)); gap:7px 14px; margin-bottom:20px;">
                    <?php foreach ($checklistDocs as $field => $label):
                        $checked = !empty($result->$field) ? 'checked' : '';
                        $bg      = $checked ? 'background:#e8f8f0; border-color:#1a9e5c;' : 'background:#fff;';
                    ?>
                    <label style="display:flex; align-items:center; gap:8px; padding:7px 10px; border:1px solid #d0d8ee; border-radius:5px; cursor:pointer; font-weight:normal; <?= $bg ?>">
                        <input type="checkbox" name="<?= $field ?>" value="1" <?= $checked ?> style="width:15px;height:15px;cursor:pointer;accent-color:#1a9e5c;">
                        <span style="font-size:12px;"><?= $label ?></span>
                    </label>
                    <?php endforeach ?>
                </div>

                <div style="text-align:center;">
                    <button type="submit" class="button btn btn-mini btn-success">
                        <span class="button__icon"><i class='bx bx-save'></i></span><span class="button__text2">Salvar Checklist</span>
                    </button>
                </div>
            </form>
        </div>

    </div><!-- /tab-content -->
</div>

<script>
$(function(){
    $(".datepicker").datepicker({ dateFormat:'dd/mm/yy' });

    var execucaoId = <?= $result->idExecucao ?>;
    var osId       = <?= $result->os_id ?>;
    var baseUrl    = "<?= base_url() ?>";
    var csrfName   = "<?= $this->security->get_csrf_token_name() ?>";

    function getCsrf(cb) {
        $.get(baseUrl + "index.php/execucao/getCsrfToken", cb, 'json');
    }

    function uploadAnexo(file, statusEl, onDone) {
        statusEl.text('Enviando...');
        getCsrf(function(t) {
            var fd = new FormData();
            fd.append('anexo', file);
            fd.append('execucao_id', execucaoId);
            fd.append('os_id', osId);
            fd.append(csrfName, t.csrf_hash);
            $.ajax({
                url: baseUrl + 'index.php/execucao/upload_anexo_ajax',
                type: 'POST', data: fd, processData: false, contentType: false, dataType: 'json',
                success: function(r) {
                    if (r.success) {
                        statusEl.text('Enviado!');
                        setTimeout(function(){ location.reload(); }, 800);
                    } else {
                        statusEl.text('Erro: ' + r.message);
                    }
                    if (onDone) onDone();
                },
                error: function() { statusEl.text('Erro ao enviar.'); if (onDone) onDone(); }
            });
        });
    }

    // Foto/Vídeo
    $('#lblFotoVideo').on('click', function(){ $('#inputFotoVideo').click(); });
    $('#inputFotoVideo').on('change', function() {
        var files = Array.from(this.files);
        if (!files.length) return;
        var statusEl = $('#statusFotoVideo');
        var idx = 0;
        function next() {
            if (idx >= files.length) return;
            var file = files[idx++];
            var ext = file.name.split('.').pop().toLowerCase();
            var isVideo = ['mp4','mov','webm'].indexOf(ext) !== -1;
            if (isVideo) {
                var v = document.createElement('video');
                v.preload = 'metadata';
                v.onloadedmetadata = function() {
                    URL.revokeObjectURL(v.src);
                    if (v.duration > 90) {
                        alert('Vídeo "' + file.name + '" excede 1:30. (' + Math.round(v.duration) + 's)');
                        next();
                    } else { uploadAnexo(file, statusEl, next); }
                };
                v.src = URL.createObjectURL(file);
            } else { uploadAnexo(file, statusEl, next); }
        }
        next();
        $('#inputFotoVideo').val('');
    });

    // PDF
    $('#lblPdf').on('click', function(){ $('#inputPdf').click(); });
    $('#inputPdf').on('change', function() {
        if (!this.files.length) return;
        uploadAnexo(this.files[0], $('#statusPdf'), null);
        $(this).val('');
    });

    // DOC
    $('#lblDoc').on('click', function(){ $('#inputDoc').click(); });
    $('#inputDoc').on('change', function() {
        if (!this.files.length) return;
        uploadAnexo(this.files[0], $('#statusDoc'), null);
        $(this).val('');
    });
});
</script>
