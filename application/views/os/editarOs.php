<link rel="stylesheet" href="<?php echo base_url(); ?>assets/js/jquery-ui/css/smoothness/jquery-ui-1.9.2.custom.css" />
<script type="text/javascript" src="<?php echo base_url() ?>assets/js/jquery-ui/js/jquery-ui-1.9.2.custom.js"></script>
<script type="text/javascript" src="<?php echo base_url() ?>assets/js/jquery.validate.js"></script>
<script src="<?php echo base_url() ?>assets/js/sweetalert2.all.min.js"></script>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/trumbowyg/ui/trumbowyg.css">
<script type="text/javascript" src="<?php echo base_url() ?>assets/trumbowyg/trumbowyg.js"></script>
<script type="text/javascript" src="<?php echo base_url() ?>assets/trumbowyg/langs/pt_br.js"></script>

<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/custom.css" />

<div class="row-fluid" style="margin-top:0">
    <div class="span12">
        <div class="widget-box">
            <div class="widget-title" style="margin: -20px 0 0">
                <span class="icon"><i class="fas fa-diagnoses"></i></span>
                <h5>Editar Ordem de Serviço</h5>
                <div class="buttons">
                    <?php if ($result->faturado == 0) { ?>
                        <a href="#modal-faturar" id="btn-faturar" role="button" data-toggle="modal" class="button btn btn-mini btn-danger">
                            <span class="button__icon"><i class='bx bx-dollar'></i></span> <span class="button__text">Faturar</span>
                        </a>
                    <?php } ?>
                    <a title="Visualizar OS" class="button btn btn-primary" href="<?php echo site_url() ?>/os/visualizar/<?php echo $result->idOs; ?>">
                        <span class="button__icon"><i class="bx bx-show"></i></span><span class="button__text">Visualizar OS</span>
                    </a>
                    <div class="button-container">
                        <a target="_blank" title="Imprimir Ordem de Serviço" class="button btn btn-mini btn-inverse">
                            <span class="button__icon"><i class="bx bx-printer"></i></span><span class="button__text">Imprimir</span>
                        </a>
                        <div class="cascading-buttons">
                            <a target="_blank" title="Impressão em Papel A4" class="button btn btn-mini btn-inverse" href="<?php echo site_url() ?>/os/imprimir/<?php echo $result->idOs; ?>">
                                <span class="button__icon"><i class='bx bx-file'></i></span> <span class="button__text">Papel A4</span>
                            </a>
                            <a target="_blank" title="Impressão Cupom Não Fical" class="button btn btn-mini btn-inverse" href="<?php echo site_url() ?>/os/imprimirTermica/<?php echo $result->idOs; ?>">
                                <span class="button__icon"><i class='bx bx-receipt'></i></span> <span class="button__text">Cupom 80mm</span>
                            </a>
                            <?php if ($result->garantias_id) { ?>
                                <a target="_blank" title="Imprimir Termo de Garantia" class="button btn btn-mini btn-inverse" href="<?php echo site_url() ?>/garantias/imprimirGarantiaOs/<?php echo $result->idOs; ?>">
                                    <span class="button__icon"><i class="bx bx-paperclip"></i></span> <span class="button__text">Termo Garantia</span>
                                </a>
                            <?php } ?>
                        </div>
                    </div>
                    <?php if ($this->permission->checkPermission($this->session->userdata('permissao'), 'eOs')) {
                        $this->load->model('os_model');
                        $zapnumber = preg_replace("/[^0-9]/", "", $result->celular_cliente);
                        $troca = [$result->nomeCliente, $result->idOs, $result->status, 'R$ ' . ($result->desconto != 0 && $result->valor_desconto != 0 ? number_format($result->valor_desconto, 2, ',', '.') : number_format($totalProdutos + $totalServico, 2, ',', '.')), strip_tags($result->descricaoProduto), ($emitente ? $emitente->nome : ''), ($emitente ? $emitente->telefone : ''), strip_tags($result->observacoes), strip_tags($result->defeito), strip_tags($result->laudoTecnico), date('d/m/Y', strtotime($result->dataFinal)), date('d/m/Y', strtotime($result->dataInicial)), $result->garantia . ' dias'];
                        $texto_de_notificacao = $this->os_model->criarTextoWhats($texto_de_notificacao, $troca);
                        if (!empty($zapnumber)) {
                            echo '<a title="Via WhatsApp" class="button btn btn-mini btn-success" id="enviarWhatsApp" target="_blank" href="https://wa.me/send?phone=55' . $zapnumber . '&text=' . $texto_de_notificacao . '" ' . ($zapnumber == '' ? 'disabled' : '') . '>
                                <span class="button__icon"><i class="bx bxl-whatsapp"></i></span> <span class="button__text">WhatsApp</span>
                            </a>';
                        }
                    } ?>
                    <a title="Enviar por E-mail" class="button btn btn-mini btn-warning" href="<?php echo site_url() ?>/os/enviar_email/<?php echo $result->idOs; ?>">
                        <span class="button__icon"><i class="bx bx-envelope"></i></span> <span class="button__text">Via E-mail</span>
                    </a>
                </div>
            </div>
            <div class="widget-content nopadding tab-content">
                <div class="span12" id="divProdutosServicos" style=" margin-left: 0">
                    <ul class="nav nav-tabs">
                        <li class="active" id="tabDetalhes"><a href="#tab1" data-toggle="tab">Detalhes da OS</a></li>
                        <li id="tabProdutos"><a href="#tab3" data-toggle="tab">Produtos</a></li>
                        <li id="tabServicos"><a href="#tab4" data-toggle="tab">Serviços</a></li>
                        <li id="tabAnotacoes"><a href="#tab6" data-toggle="tab">Anotações</a></li>
                        <li id="tabAnexos"><a href="#tab5" data-toggle="tab">Anexos</a></li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab1">
                            <div class="span12" id="divCadastrarOs">
                                <form action="<?php echo current_url(); ?>" method="post" id="formOs">
                                    <?php echo form_hidden('idOs', $result->idOs) ?>
                                    <div class="span12" style="padding: 1%; margin-left: 0">
                                        <h3>N° OS: <?php echo $result->idOs; ?></h3>
                                        <div class="span6" style="margin-left: 0">
                                            <label for="cliente">Cliente<span class="required">*</span></label>
                                            <input id="cliente" class="span12" type="text" name="cliente" value="<?php echo $result->nomeCliente ?>" />
                                            <input id="clientes_id" class="span12" type="hidden" name="clientes_id" value="<?php echo $result->clientes_id ?>" />
                                            <input id="valor" type="hidden" name="valor" value="" />
                                        </div>
                                        <div class="span6">
                                            <label for="tecnico">Técnico Responsável<span class="required">*</span></label>
                                            <input id="tecnico" class="span12" type="text" name="tecnico" value="<?php echo $result->nome ?>" />
                                            <input id="usuarios_id" class="span12" type="hidden" name="usuarios_id" value="<?php echo $result->usuarios_id ?>" />
                                            <label for="engenheiro" style="margin-top:8px;">Engenheiro Responsável</label>
                                            <input id="engenheiro" class="span12" type="text" name="engenheiro" value="<?php echo isset($result->engenheiro) ? $result->engenheiro : '' ?>" />
                                            <input id="engenheiro_id" class="span12" type="hidden" name="engenheiro_id" value="<?php echo isset($result->engenheiro_id) ? $result->engenheiro_id : '' ?>" />
                                        </div>
                                    </div>
                                    <div class="span12" style="padding: 1%; margin-left: 0">
                                        <div class="span3">
                                            <label for="status">Status<span class="required">*</span></label>
                                            <select class="span12" name="status" id="status" value="">
                                                <option <?php if ($result->status == 'Aberto') {
                                                    echo 'selected';
                                                } ?> value="Aberto">Aberto</option>
                                                <option <?php if ($result->status == 'Aguardando Peças') {
                                                    echo 'selected';
                                                } ?> value="Aguardando Peças">Aguardando Peças</option>
                                                <option <?php if ($result->status == 'Finalizado') {
                                                    echo 'selected';
                                                } ?> value="Finalizado">Finalizado</option>
                                                <option <?php if ($result->status == 'Cancelado') {
                                                    echo 'selected';
                                                } ?> value="Cancelado">Cancelado</option>
                                            </select>
                                        </div>
                                        <div class="span2">
                                            <label for="garantia">Garantia (dias)</label>
                                            <input id="garantia" type="number" placeholder="365" min="0" max="9999" class="span12" name="garantia" value="<?php echo $result->garantia ?: 365 ?>" />
                                            <?php echo form_error('garantia'); ?>
                                        </div>
                                        <div class="span3">
                                            <label for="dataInicial">Data Inicial<span class="required">*</span></label>
                                            <input id="dataInicial" autocomplete="off" class="span12 datepicker" type="text" name="dataInicial" value="<?php echo date('d/m/Y', strtotime($result->dataInicial)); ?>" />
                                        </div>
                                        <div class="span3">
                                            <label for="dataFinal">Data Final<span class="required">*</span></label>
                                            <input id="dataFinal" autocomplete="off" class="span12 datepicker" type="text" name="dataFinal" value="<?php echo date('d/m/Y', strtotime($result->dataFinal)); ?>" />
                                        </div>
                                        <input type="hidden" name="garantias_id" value="<?php echo $result->garantias_id ?>" />
                                    </div>
                                    <input type="hidden" name="descricaoProduto" value="<?php echo htmlspecialchars($result->descricaoProduto) ?>">
                                    <input type="hidden" name="defeito" value="<?php echo htmlspecialchars($result->defeito) ?>">
                                    <input type="hidden" name="observacoes" value="<?php echo htmlspecialchars($result->observacoes) ?>">
                                    <input type="hidden" name="laudoTecnico" value="<?php echo htmlspecialchars($result->laudoTecnico) ?>">

                                    <!-- Checklist: Documentação e entrega -->
                                    <div class="span12" style="margin-left:0; margin-top:14px; padding:0;">
                                        <div style="background:transparent; border:1px solid #ddd; border-radius:8px; padding:16px 18px;">
                                            <div style="font-size:13px; font-weight:700; color:#333; margin-bottom:12px;">
                                                Documentação e entrega ao cliente, síndico ou responsável
                                            </div>
                                            <div style="display:grid; grid-template-columns:repeat(auto-fill,minmax(220px,1fr)); gap:7px 14px;">
                                                <?php
                                                $checklistDocs = [
                                                    'doc_projeto_eletrico'        => 'Projeto elétrico',
                                                    'doc_as_built'                => 'Projeto As-Built',
                                                    'doc_croqui'                  => 'Croqui',
                                                    'doc_diagrama_unifilar'       => 'Diagrama unifilar',
                                                    'doc_memorial_descritivo'     => 'Memorial descritivo',
                                                    'doc_memorial_calculo'        => 'Memorial de cálculo',
                                                    'doc_laudo_tecnico'           => 'Laudo técnico',
                                                    'doc_art_projeto'             => 'ART de projeto',
                                                    'doc_art_execucao'            => 'ART de execução',
                                                    'doc_relatorio_fotografico'   => 'Relatório fotográfico',
                                                    'doc_relatorio_comissionamento' => 'Relatório de comissionamento',
                                                    'doc_orientacao_operacao'     => 'Orientação de operação ao cliente',
                                                    'doc_orientacao_emergencia'   => 'Orientação sobre desligamento de emergência',
                                                ];
                                                foreach ($checklistDocs as $field => $label):
                                                    $checked = !empty($result->$field) ? 'checked' : '';
                                                    $bg      = $checked ? 'background:#e8f8f0; border-color:#1a9e5c;' : 'background:#fff;';
                                                ?>
                                                <label style="display:flex; align-items:center; gap:8px; padding:7px 10px; border:1px solid #d0d8ee; border-radius:5px; cursor:pointer; font-weight:normal; <?php echo $bg ?>">
                                                    <input type="checkbox" name="<?php echo $field ?>" value="1" <?php echo $checked ?> style="width:15px;height:15px;cursor:pointer;accent-color:#1a9e5c;">
                                                    <span style="font-size:12px;"><?php echo $label ?></span>
                                                </label>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Tipo de Instalação -->
                                    <div class="span12" style="padding: 1%; margin-left: 0; margin-top:10px;">
                                        <h4 style="border-bottom:1px solid #ddd; padding-bottom:8px; margin-bottom:12px;">
                                            <i class="fas fa-tools" style="color:#337ab7"></i> Tipo de Instalação
                                        </h4>
                                        <label for="tipo_instalacao">Tipo de Instalação</label>
                                        <div style="margin-left:0;">
                                            <select class="span12" name="tipo_instalacao" id="tipo_instalacao">
                                                <option value="">Selecione...</option>
                                                <?php
                                                $tiposInstalacao = [
                                                    'INSTALAÇÃO DO CARREGADOR.',
                                                    'INSTALAÇÃO DO CARREGADOR+ BOTOEIRA+PLACAS IT-41.',
                                                    'INSTALAÇÃO DO CARREDOR + QUADRO VE+BOTOEIRA+PLACAS IT-41.',
                                                    'INSTALAÇÃO DO CARREDOR + QUADRO VE+INFRAESTRUTURA+BOTOEIRA+PLACAS IT-41',
                                                    'INSTALAÇÃO DO CARREDOR + QUADRO VE +INFRAESTRUTURA+ALIMENTADOR+BOTOEIRA+PLACAS IT-41',
                                                    'INSTALAÇÃO DO CARREDOR + QUADRO VE +ALIMENTADOR+BOTOEIRA+PLACAS IT-41.',
                                                    'INSTALAÇÃO DE INFRAESTRUTURA + QUADRO VE GERAL+BOTOEIRA+PLACAS IT-41',
                                                    'INSTALAÇÃO DOS ALIMENTADORES',
                                                ];
                                                foreach ($tiposInstalacao as $t): ?>
                                                <option value="<?php echo $t ?>" <?php echo (isset($result->tipo_instalacao) && $result->tipo_instalacao == $t) ? 'selected' : '' ?>><?php echo $t ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="span12" style="margin-left:0; margin-top:10px; padding:0;">
                                            <div class="span3" style="margin-left:0;">
                                                <label for="data_inicio_instalacao">Data Início da Instalação</label>
                                                <input id="data_inicio_instalacao" autocomplete="off" class="span12 datepicker" type="text" name="data_inicio_instalacao" value="<?php echo (!empty($result->data_inicio_instalacao) && $result->data_inicio_instalacao != '0000-00-00') ? date('d/m/Y', strtotime($result->data_inicio_instalacao)) : '' ?>" />
                                            </div>
                                            <div class="span3">
                                                <label for="data_fim_instalacao">Data Fim da Instalação</label>
                                                <input id="data_fim_instalacao" autocomplete="off" class="span12 datepicker" type="text" name="data_fim_instalacao" value="<?php echo (!empty($result->data_fim_instalacao) && $result->data_fim_instalacao != '0000-00-00') ? date('d/m/Y', strtotime($result->data_fim_instalacao)) : '' ?>" />
                                            </div>
                                        </div>
                                    </div>

                                    <!-- SEÇÃO DADOS INSTALAÇÃO EV: guardada em _snippet_dados_instalacao_ev.php para uso na aba Anexos -->
                                    <div class="span12" style="padding: 0; margin-left: 0">
                                        <div class="span12" style="display:flex; justify-content: center;">
                                            <button class="button btn btn-primary" id="btnContinuar"><span class="button__icon"><i class="bx bx-sync"></i></span><span class="button__text2">Atualizar</span></button>
                                            <a href="<?php echo base_url() ?>index.php/os" class="button btn btn-mini btn-warning"><span class="button__icon"><i class="bx bx-undo"></i></span> <span class="button__text2">Voltar</span></a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!--Desconto-->
                        <?php $total = 0;
foreach ($produtos as $p) {
    $total = $total + $p->subTotal;
}?>
                        <!--Produtos-->
                        <div class="tab-pane" id="tab3">
                            <div class="span12 well" style="padding: 1%; margin-left: 0">
                                <form id="formProdutos" action="<?php echo base_url() ?>index.php/os/adicionarProduto" method="post">
                                    <div class="span6">
                                        <input type="hidden" name="idProduto" id="idProduto" />
                                        <input type="hidden" name="idOsProduto" id="idOsProduto" value="<?php echo $result->idOs; ?>" />
                                        <input type="hidden" name="estoque" id="estoque" value="" />
                                        <label for="">Produto</label>
                                        <input type="text" class="span12" name="produto" id="produto" placeholder="Digite o nome do produto" />
                                    </div>
                                    <div class="span2">
                                        <label for="">Preço</label>
                                        <input type="text" placeholder="Preço" id="preco" name="preco" class="span12 money" data-affixes-stay="true" data-thousands="" data-decimal="." />
                                    </div>
                                    <div class="span2">
                                        <label for="">Quantidade</label>
                                        <input type="text" placeholder="Quantidade" id="quantidade" name="quantidade"
                                            class="span12" />
                                    </div>
                                    <div class="span2">
                                        <label for="">&nbsp;</label>
                                        <button class="button btn btn-success" id="btnAdicionarProduto">
                                            <span class="button__icon"><i class='bx bx-plus-circle'></i></span> <span class="button__text2">Adicionar</span>
                                        </button>
                                    </div>
                                </form>
                            </div>
                            <div class="widget-box" id="divProdutos">
                                <div class="widget_content nopadding">
                                    <table width="100%" class="table table-bordered" id="tblProdutos">
                                        <thead>
                                            <tr>
                                                <th>Produto</th>
                                                <th width="8%">Quantidade</th>
                                                <th width="10%">Preço unit.</th>
                                                <th width="6%">Ações</th>
                                                <th width="10%">Sub-total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                    $total = 0;
foreach ($produtos as $p) {
    $precoReal = floatval($p->precoVenda);
    $subtotalReal = $precoReal * $p->quantidade;
    $total = $total + $subtotalReal;
    echo '<tr>';
    echo '<td>' . $p->descricao . '</td>';
    echo '<td><div align="center">' . $p->quantidade . '</td>';
    echo '<td><div align="center">R$: ' . number_format($precoReal, 2, ',', '.') . '</td>';
    echo (strtolower($result->status) != "cancelado") ? '<td><div align="center"><a href="" idAcao="' . $p->idProdutos_os . '" prodAcao="' . $p->idProdutos . '" quantAcao="' . $p->quantidade . '" title="Excluir Produto" class="btn-nwe4"><i class="bx bx-trash-alt"></i></a></td>' : '<td></td>';
    echo '<td><div align="center">R$: ' . number_format($subtotalReal, 2, ',', '.') . '</td>';
    echo '</tr>';
} ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="4" style="text-align: right"><strong>Total:</strong>
                                                </td>
                                                <td>
                                                    <div align="center"><strong>R$
                                                            <?php echo number_format($total, 2, ',', '.'); ?><input
                                                                type="hidden" id="total-venda"
                                                                value="<?php echo number_format($total, 2); ?>"></strong>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!--Serviços-->
                        <div class="tab-pane" id="tab4">
                            <div class="span12 well" style="padding: 1%; margin-left: 0">
                                <form id="formServicos" action="<?php echo base_url() ?>index.php/os/adicionarServico"
                                    method="post">
                                    <div class="span6">
                                        <input type="hidden" name="idServico" id="idServico" />
                                        <input type="hidden" name="idOsServico" id="idOsServico"
                                            value="<?php echo $result->idOs; ?>" />
                                        <label for="">Serviço</label>
                                        <input type="text" class="span12" name="servico" id="servico"
                                            placeholder="Digite o nome do serviço" />
                                    </div>
                                    <div class="span2">
                                        <label for="">Preço</label>
                                        <input type="text" placeholder="Preço" id="preco_servico" name="preco"
                                            class="span12 money" data-affixes-stay="true" data-thousands=""
                                            data-decimal="." />
                                    </div>
                                    <div class="span2">
                                        <label for="">Quantidade</label>
                                        <input type="text" placeholder="Quantidade" id="quantidade_servico"
                                            name="quantidade" class="span12" />
                                    </div>
                                    <div class="span2">
                                        <label for="">&nbsp;</label>
                                        <button class="button btn btn-success">
                                            <span class="button__icon"><i class='bx bx-plus-circle'></i></span><span
                                                class="button__text2">Adicionar</span></button>
                                    </div>
                                </form>
                            </div>
                            <div class="widget-box" id="divServicos">
                                <div class="widget_content nopadding">
                                    <table width="100%" class="table table-bordered" id="tblServicos">
                                        <thead>
                                            <tr>
                                                <th>Serviço</th>
                                                <th width="8%">Quantidade</th>
                                                <th width="10%">Preço</th>
                                                <th width="6%">Ações</th>
                                                <th width="10%">Sub-totals</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
$totals = 0;
foreach ($servicos as $s) {
    $preco = $s->preco ?: $s->precoVenda;
    $subtotals = $preco * ($s->quantidade ?: 1);
    $totals = $totals + $subtotals;
    echo '<tr>';
    echo '<td>' . $s->nome . '</td>';
    echo '<td><div align="center">' . ($s->quantidade ?: 1) . '</div></td>';
    echo '<td><div align="center">R$ ' . $preco . '</div></td>';
    echo '<td><div align="center"><span idAcao="' . $s->idServicos_os . '" title="Excluir Serviço" class="btn-nwe4 servico"><i class="bx bx-trash-alt"></i></span></div></td>';
    echo '<td><div align="center">R$: ' . number_format($subtotals, 2, ',', '.') . '</div></td>';
    echo '</tr>';
} ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="4" style="text-align: right"><strong>Total:</strong>
                                                </td>
                                                <td>
                                                    <div align="center"><strong>R$
                                                            <?php echo number_format($totals, 2, ',', '.'); ?><input
                                                                type="hidden" id="total-servico"
                                                                value="<?php echo number_format($totals, 2); ?>"></strong>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!--Anexos-->
                        <div class="tab-pane" id="tab5">
                            <div class="span12" style="padding: 1%; margin-left: 0">
                                <?php
                                // Conta anexos existentes por tipo
                                $osQtdFotos = 0; $osQtdVideos = 0; $osQtdPdf = 0; $osQtdDoc = 0;
                                foreach ($anexos as $_a) {
                                    $_ext = strtolower(pathinfo($_a->anexo, PATHINFO_EXTENSION));
                                    if (in_array($_ext, ['jpg','jpeg','png','gif','webp','bmp','svg'])) $osQtdFotos++;
                                    elseif (in_array($_ext, ['mp4','mov','webm'])) $osQtdVideos++;
                                    elseif ($_ext === 'pdf') $osQtdPdf++;
                                    elseif (in_array($_ext, ['doc','docx'])) $osQtdDoc++;
                                }
                                ?>
                                <div class="span12 well" style="padding: 1%; margin-left: 0" id="form-anexos-os">
                                    <div class="row-fluid">
                                        <div style="margin-bottom:8px; color:#555; font-size:12px;">
                                            Limite: 6 fotos · 1 vídeo (máx. 1:30) · 1 PDF · 1 DOC
                                        </div>
                                        <?php if ($osQtdFotos < 6 || $osQtdVideos < 1): ?>
                                        <span class="btn btn-success btn-mini" id="os-btn-foto" style="margin-right:6px;">
                                            <i class='bx bx-image'></i> Foto/Vídeo (<?= $osQtdFotos ?>/6 fotos · <?= $osQtdVideos ?>/1 vídeo)
                                        </span>
                                        <?php endif; ?>
                                        <?php if ($osQtdPdf < 1): ?>
                                        <span class="btn btn-warning btn-mini" id="os-btn-pdf" style="margin-right:6px;">
                                            <i class='bx bx-file-pdf'></i> PDF (<?= $osQtdPdf ?>/1)
                                        </span>
                                        <?php endif; ?>
                                        <?php if ($osQtdDoc < 1): ?>
                                        <span class="btn btn-primary btn-mini" id="os-btn-doc">
                                            <i class='bx bx-file'></i> Documento (<?= $osQtdDoc ?>/1)
                                        </span>
                                        <?php endif; ?>
                                        <!-- inputs ocultos -->
                                        <input type="file" id="os-file-foto" accept="image/*,video/*" style="display:none">
                                        <input type="file" id="os-file-pdf"  accept=".pdf"            style="display:none">
                                        <input type="file" id="os-file-doc"  accept=".doc,.docx"      style="display:none">
                                        <div id="os-upload-msg" style="margin-top:8px;"></div>
                                    </div>
                                </div>
                                <div class="span12 pull-left" id="divAnexos" style="margin-left: 0">
                                    <?php
                                    foreach ($anexos as $a) {
                                        $link     = $a->url . '/' . $a->anexo;
                                        $filename = $a->anexo;
                                        $ext      = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
                                        $isImage  = in_array($ext, ['jpg','jpeg','png','gif','bmp','webp','svg']);
                                        $isPdf    = ($ext === 'pdf');

                                        if ($isImage) {
                                            // Imagem: mostra a própria imagem como preview
                                            $preview = '<img src="' . $link . '" alt="' . htmlspecialchars($filename) . '" style="width:100%;height:130px;object-fit:cover;border-radius:4px 4px 0 0;">';
                                        } elseif ($isPdf) {
                                            // PDF: ícone vermelho
                                            $preview = '<div style="display:flex;flex-direction:column;align-items:center;justify-content:center;height:130px;background:#fff5f5;border-radius:4px 4px 0 0;">
                                                            <svg width="48" height="48" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                <rect x="6" y="2" width="28" height="36" rx="3" fill="#e53935"/>
                                                                <rect x="6" y="2" width="28" height="36" rx="3" fill="#ff5252" fill-opacity=".3"/>
                                                                <path d="M28 2v10h10" fill="none" stroke="#fff" stroke-width="1.5"/>
                                                                <path d="M28 2l10 10H28V2z" fill="#fff" fill-opacity=".5"/>
                                                                <text x="24" y="30" text-anchor="middle" fill="white" font-size="8" font-weight="bold" font-family="Arial">PDF</text>
                                                            </svg>
                                                            <span style="font-size:10px;color:#e53935;margin-top:4px;font-weight:600;">PDF</span>
                                                        </div>';
                                        } else {
                                            // Outros: ícone genérico por extensão com cor
                                            $iconColors = ['doc'=>'#1565c0','docx'=>'#1565c0','xls'=>'#2e7d32','xlsx'=>'#2e7d32','ppt'=>'#e65100','pptx'=>'#e65100','txt'=>'#546e7a','csv'=>'#2e7d32'];
                                            $color = isset($iconColors[$ext]) ? $iconColors[$ext] : '#546e7a';
                                            $extUp = strtoupper($ext ?: 'FILE');
                                            $preview = '<div style="display:flex;flex-direction:column;align-items:center;justify-content:center;height:130px;background:#f5f5f5;border-radius:4px 4px 0 0;">
                                                            <svg width="48" height="56" viewBox="0 0 48 56" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                <rect x="4" y="2" width="30" height="40" rx="3" fill="' . $color . '"/>
                                                                <path d="M28 2v10h10" fill="none" stroke="#fff" stroke-width="1.5"/>
                                                                <path d="M28 2l10 10H28V2z" fill="#fff" fill-opacity=".4"/>
                                                                <text x="19" y="31" text-anchor="middle" fill="white" font-size="7" font-weight="bold" font-family="Arial">' . $extUp . '</text>
                                                            </svg>
                                                            <span style="font-size:10px;color:' . $color . ';margin-top:2px;font-weight:600;">' . $extUp . '</span>
                                                        </div>';
                                        }

                                        echo '<div class="span3" style="margin-left:0; margin-bottom:14px;">
                                                <a href="' . $link . '" target="_blank" title="' . htmlspecialchars($filename) . '" style="display:block;border:1px solid #ddd;border-radius:6px;overflow:hidden;text-decoration:none;color:#333;background:#fff;box-shadow:0 1px 4px rgba(0,0,0,.08);">
                                                    ' . $preview . '
                                                    <div style="padding:6px 8px;font-size:11px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;border-top:1px solid #eee;" title="' . htmlspecialchars($filename) . '">
                                                        ' . htmlspecialchars($filename) . '
                                                    </div>
                                                </a>
                                            </div>';
                                    }
?>
                                </div>
                            </div>
                        </div>

                        <!--Anotações-->
                        <div class="tab-pane" id="tab6">
                            <div class="span12" style="padding: 1%; margin-left: 0">

                                <div class="span12" id="divAnotacoes" style="margin-left: 0">

                                    <a href="#modal-anotacao" id="btn-anotacao" role="button" data-toggle="modal"
                                        class="button btn btn-success" style="max-width: 160px">
                                        <span class="button__icon"><i class='bx bx-plus-circle'></i></span><span
                                            class="button__text2">Adicionar anotação</span></a>
                                    <hr>
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Data/Hora</th>
                                                <th>Anotação</th>
                                                <th>Ações</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
        foreach ($anotacoes as $a) {
            echo '<tr>';
            echo '<td>' . date('d/m/Y H:i:s', strtotime($a->data_hora)) . '</td>';
            echo '<td>' . $a->anotacao . '</td>';
            echo '<td><span idAcao="' . $a->idAnotacoes . '" title="Excluir Anotação" class="btn-nwe4 anotacao"><i class="bx bx-trash-alt"></i></span></td>';
            echo '</tr>';
        }
if (!$anotacoes) {
    echo '<tr><td colspan="3">Nenhuma anotação cadastrada</td></tr>';
}

?>
                                        </tbody>
                                    </table>
                                </div>

                            </div>
                        </div>
                        <!-- Fim tab anotações -->
                    </div>
                </div>
                &nbsp
            </div>
        </div>
    </div>
</div>

<!-- Modal visualizar anexo -->
<div id="modal-anexo" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 id="myModalLabel">Visualizar Anexo</h3>
    </div>
    <div class="modal-body">
        <div class="span12" id="div-visualizar-anexo" style="text-align: center">
            <div class='progress progress-info progress-striped active'>
                <div class='bar' style='width: 100%'></div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true">Fechar</button>
        <a href="" id-imagem="" class="btn btn-inverse" id="download">Download</a>
        <a href="" link="" class="btn btn-danger" id="excluir-anexo">Excluir Anexo</a>
    </div>
</div>

<!-- Modal cadastro anotações -->
<div id="modal-anotacao" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true">
    <form action="#" method="POST" id="formAnotacao">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h3 id="myModalLabel">Adicionar Anotação</h3>
        </div>
        <div class="modal-body">
            <div class="span12" id="divFormAnotacoes" style="margin-left: 0"></div>
            <div class="span12" style="margin-left: 0">
                <label for="anotacao">Anotação</label>
                <textarea class="span12" name="anotacao" id="anotacao" cols="30" rows="3"></textarea>
                <input type="hidden" name="os_id" value="<?php echo $result->idOs; ?>">
            </div>
        </div>
        <div class="modal-footer" style="display:flex;justify-content: center">
            <button class="btn" data-dismiss="modal" aria-hidden="true" id="btn-close-anotacao">Fechar</button>
            <button class="btn btn-primary">Adicionar</button>
        </div>
    </form>
</div>

<!-- Modal Faturar-->
<div id="modal-faturar" class="modal hide fade " tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true">
    <form id="formFaturar" action="<?php echo current_url() ?>" method="post">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h3 id="myModalLabel">Faturar OS</h3>
        </div>
        <div class="modal-body">
            <div class="span12 alert alert-info" style="margin-left: 0"> Obrigatório o preenchimento dos campos com
                asterisco.</div>
            <div class="span12" style="margin-left: 0">
                <label for="descricao">Descrição</label>
                <input class="span12" id="descricao" type="text" name="descricao"
                    value="Fatura de OS Nº: <?php echo $result->idOs; ?> " />
            </div>
            <div class="span12" style="margin-left: 0">
                <div class="span12" style="margin-left: 0">
                    <label for="cliente">Cliente*</label>
                    <input class="span12" id="cliente" type="text" name="cliente"
                        value="<?php echo $result->nomeCliente ?>" />
                    <input type="hidden" name="clientes_id" id="clientes_id" value="<?php echo $result->clientes_id ?>">
                    <input type="hidden" name="os_id" id="os_id" value="<?php echo $result->idOs; ?>">
                    <input type="hidden" name="tipoDesconto" id="tipoDesconto"
                        value="<?php echo $result->tipo_desconto; ?>">
                </div>
            </div>
            <div class="span12" style="margin-left: 0">
                <div class="span6" style="margin-left: 0">
                    <label for="valor">Valor*</label>
                    <input type="hidden" id="tipo" name="tipo" value="receita" />
                    <input class="span12 money" id="valor" type="text" data-affixes-stay="true" data-thousands=""
                        data-decimal="." name="valor"
                        value="<?php echo number_format($totals + $total, 2, '.', ''); ?>" />
                </div>
                <div class="span6" style="margin-left: 2;">
                    <label for="valor">Valor Com Desconto*</label>
                    <input class="span12 money" id="faturar-desconto" type="text" name="faturar-desconto"
                        value="<?php echo number_format($result->valor_desconto, 2, '.', ''); ?> " />
                    <strong><span style="color: red" id="resultado"></span></strong>
                </div>
            </div>
            <div class="span12" style="margin-left: 0">
                <div class="span4" style="margin-left: 0">
                    <label for="vencimento">Data Entrada*</label>
                    <input class="span12 datepicker" autocomplete="off" id="vencimento" type="text" name="vencimento" />
                </div>
            </div>
            <div class="span12" style="margin-left: 0">
                <div class="span4" style="margin-left: 0">
                    <label for="recebido">Recebido?</label>
                    &nbsp &nbsp &nbsp &nbsp <input id="recebido" type="checkbox" name="recebido" value="1" />
                </div>
                <div id="divRecebimento" class="span8" style=" display: none">
                    <div class="span6">
                        <label for="recebimento">Data Recebimento</label>
                        <input class="span12 datepicker" autocomplete="off" id="recebimento" type="text"
                            name="recebimento" />
                    </div>
                    <div class="span6">
                        <label for="formaPgto">Forma Pgto</label>
                        <select name="formaPgto" id="formaPgto" class="span12">
                            <option value="Dinheiro">Dinheiro</option>
                            <option value="Cartão de Crédito">Cartão de Crédito</option>
                            <option value="Cartão de Débito">Cartão de Débito</option>
                            <option value="Boleto">Boleto</option>
                            <option value="Depósito">Depósito</option>
                            <option value="Pix">Pix</option>
                            <option value="Cheque">Cheque</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer" style="display:flex;justify-content: center">
            <button class="button btn btn-warning" data-dismiss="modal" aria-hidden="true"
                id="btn-cancelar-faturar"><span class="button__icon"><i class="bx bx-x"></i></span><span
                    class="button__text2">Cancelar</span></button>
            <button class="button btn btn-danger"><span class="button__icon"><i class='bx bx-dollar'></i></span>
                <span class="button__text2">Faturar</span></button>
        </div>
    </form>
</div>

<script src="<?php echo base_url(); ?>assets/js/maskmoney.js"></script>

<script type="text/javascript">
    function calcDesconto(valor, desconto, tipoDesconto) {
        var resultado = 0;
        if (tipoDesconto == 'real') {
            resultado = valor - desconto;
        }
        if (tipoDesconto == 'porcento') {
            resultado = (valor - desconto * valor / 100).toFixed(2);
        }
        return resultado;
    }

    function validarDesconto(resultado, valor) {
        if (resultado == valor) {
            return resultado = "";
        } else {
            return resultado.toFixed(2);
        }
    }
    var valorBackup = $("#valorTotal").val();

    $("#quantidade").keyup(function () {
        this.value = this.value.replace(/[^0-9.]/g, '');
    });

    $("#quantidade_servico").keyup(function () {
        this.value = this.value.replace(/[^0-9.]/g, '');
    });
    $('#tipoDesconto').on('change', function () {
        if (Number($("#desconto").val()) >= 0) {
            $('#resultado').val(calcDesconto(Number($("#valorTotal").val()), Number($("#desconto").val()), $("#tipoDesconto").val()));
            $('#resultado').val(validarDesconto(Number($('#resultado').val()), Number($("#valorTotal").val())));
        }
    });
    $("#desconto").keyup(function () {
        this.value = this.value.replace(/[^0-9.]/g, '');
        if ($("#valorTotal").val() == null || $("#valorTotal").val() == '') {
            $('#errorAlert').text('Valor não pode ser apagado.').css("display", "inline").fadeOut(5000);
            $('#desconto').val('');
            $('#resultado').val('');
            $("#valorTotal").val(valorBackup);
            $("#desconto").focus();

        } else if (Number($("#desconto").val()) >= 0) {
            $('#resultado').val(calcDesconto(Number($("#valorTotal").val()), Number($("#desconto").val()), $("#tipoDesconto").val()));
            $('#resultado').val(validarDesconto(Number($('#resultado').val()), Number($("#valorTotal").val())));
        } else {
            $('#errorAlert').text('Erro desconhecido.').css("display", "inline").fadeOut(5000);
            $('#desconto').val('');
            $('#resultado').val('');
        }
    });

    $("#valorTotal").focusout(function () {
        $("#valorTotal").val(valorBackup);
        if ($("#valorTotal").val() == '0.00' && $('#resultado').val() != '') {
            $('#errorAlert').text('Você não pode apagar o valor.').css("display", "inline").fadeOut(6000);
            $('#resultado').val('');
            $("#valorTotal").val(valorBackup);
            $('#resultado').val(calcDesconto(Number($("#valorTotal").val()), Number($("#desconto").val())));
            $('#resultado').val(validarDesconto(Number($('#resultado').val()), Number($("#valorTotal").val())));
            $("#desconto").focus();
        } else {
            $('#resultado').val(calcDesconto(Number($("#valorTotal").val()), Number($("#desconto").val())));
            $('#resultado').val(validarDesconto(Number($('#resultado').val()), Number($("#valorTotal").val())));
        }
    });

    $('#resultado').focusout(function () {
        if (Number($('#resultado').val()) > Number($("#valorTotal").val())) {
            $('#errorAlert').text('Desconto não pode ser maior que o Valor.').css("display", "inline").fadeOut(6000);
            $('#resultado').val('');
        }
        if ($("#desconto").val() != "" || $("#desconto").val() != null) {
            $('#resultado').val(calcDesconto(Number($("#valorTotal").val()), Number($("#desconto").val())));
            $('#resultado').val(validarDesconto(Number($('#resultado').val()), Number($("#valorTotal").val())));
        }
    });
    $(document).ready(function () {

        $(".money").maskMoney();

        $('#recebido').click(function (event) {
            var flag = $(this).is(':checked');
            if (flag == true) {
                $('#divRecebimento').show();
            } else {
                $('#divRecebimento').hide();
            }
        });

        $("#formFaturar").validate({
            rules: {
                descricao: {
                    required: true
                },
                cliente: {
                    required: true
                },
                valor: {
                    required: true
                },
                vencimento: {
                    required: true
                }

            },
            messages: {
                descricao: {
                    required: 'Campo Requerido.'
                },
                cliente: {
                    required: 'Campo Requerido.'
                },
                valor: {
                    required: 'Campo Requerido.'
                },
                vencimento: {
                    required: 'Campo Requerido.'
                }
            },
            submitHandler: function (form) {
                var dados = $(form).serialize();
                var qtdProdutos = $('#tblProdutos >tbody >tr').length;
                var qtdServicos = $('#tblServicos >tbody >tr').length;
                var qtdTotalProdutosServicos = qtdProdutos + qtdServicos;

                $('#btn-cancelar-faturar').trigger('click');

                if (qtdTotalProdutosServicos <= 0) {
                    Swal.fire({
                        type: "error",
                        title: "Atenção",
                        text: "Não é possível faturar uma OS sem serviços e/ou produtos"
                    });
                } else if (qtdTotalProdutosServicos > 0) {
                    $.ajax({
                        type: "POST",
                        url: "<?php echo base_url(); ?>index.php/os/faturar",
                        data: dados,
                        dataType: 'json',
                        success: function (data) {
                            if (data.result == true) {
                                window.location.reload(true);
                            } else {
                                Swal.fire({
                                    type: "error",
                                    title: "Atenção",
                                    text: "Ocorreu um erro ao tentar faturar OS."
                                });
                                $('#progress-fatura').hide();
                            }
                        }
                    });

                    return false;
                }
            }
        });

        $("#formwhatsapp").validate({
            rules: {
                descricao: {
                    required: true
                },
                cliente: {
                    required: true
                },
                valor: {
                    required: true
                },
                vencimento: {
                    required: true
                }

            },
            messages: {
                descricao: {
                    required: 'Campo Requerido.'
                },
                cliente: {
                    required: 'Campo Requerido.'
                },
                valor: {
                    required: 'Campo Requerido.'
                },
                vencimento: {
                    required: 'Campo Requerido.'
                }
            },
            submitHandler: function (form) {
                var dados = $(form).serialize();
                $('#btn-cancelar-faturar').trigger('click');
                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url(); ?>index.php/os/faturar",
                    data: dados,
                    dataType: 'json',
                    success: function (data) {
                        if (data.result == true) {

                            window.location.reload(true);
                        } else {
                            Swal.fire({
                                type: "error",
                                title: "Atenção",
                                text: "Ocorreu um erro ao tentar  OS."
                            });
                            $('#progress-fatura').hide();
                        }
                    }
                });

                return false;
            }
        });

        $("#produto").autocomplete({
            source: "<?php echo base_url(); ?>index.php/os/autoCompleteProduto",
            minLength: 2,
            select: function (event, ui) {
                $("#codDeBarra").val(ui.item.codbar);
                $("#idProduto").val(ui.item.id);
                $("#estoque").val(ui.item.estoque);
                $("#preco").val(ui.item.preco);
                $("#quantidade").focus();
            }
        });

        $("#servico").autocomplete({
            source: "<?php echo base_url(); ?>index.php/os/autoCompleteServico",
            minLength: 2,
            select: function (event, ui) {
                $("#idServico").val(ui.item.id);
                $("#preco_servico").val(ui.item.preco);
                $("#quantidade_servico").focus();
            }
        });


        $("#cliente").autocomplete({
            source: "<?php echo base_url(); ?>index.php/os/autoCompleteCliente",
            minLength: 2,
            select: function (event, ui) {
                $("#clientes_id").val(ui.item.id);
            }
        });

        $("#tecnico").autocomplete({
            source: "<?php echo base_url(); ?>index.php/os/autoCompleteUsuario",
            minLength: 2,
            select: function (event, ui) {
                $("#usuarios_id").val(ui.item.id);
            }
        });

        $("#engenheiro").autocomplete({
            source: "<?php echo base_url(); ?>index.php/os/autoCompleteUsuario",
            minLength: 2,
            select: function (event, ui) {
                $("#engenheiro_id").val(ui.item.id);
            }
        });

        $("#formOs").validate({
            rules: {
                cliente: {
                    required: true
                },
                tecnico: {
                    required: true
                },
                dataInicial: {
                    required: true
                }
            },
            messages: {
                cliente: {
                    required: 'Campo Requerido.'
                },
                tecnico: {
                    required: 'Campo Requerido.'
                },
                dataInicial: {
                    required: 'Campo Requerido.'
                }
            },
            errorClass: "help-inline",
            errorElement: "span",
            highlight: function (element, errorClass, validClass) {
                $(element).parents('.control-group').addClass('error');
            },
            unhighlight: function (element, errorClass, validClass) {
                $(element).parents('.control-group').removeClass('error');
                $(element).parents('.control-group').addClass('success');
            }
        });

        $("#formProdutos").validate({
            rules: {
                preco: {
                    required: true
                },
                quantidade: {
                    required: true
                }
            },
            messages: {
                preco: {
                    required: 'Inserir o preço'
                },
                quantidade: {
                    required: 'Insira a quantidade'
                }
            },
            submitHandler: function (form) {
                var quantidade = parseInt($("#quantidade").val());
                var estoque = parseInt($("#estoque").val());

                <?php if (!$configuration['control_estoque']) {
                    echo 'estoque = 1000000';
                }
; ?>

                if (estoque < quantidade) {
                    Swal.fire({
                        type: "error",
                        title: "Atenção",
                        text: "Você não possui estoque suficiente."
                    });
                } else {
                    var dados = $(form).serialize();
                    $("#divProdutos").html("<div class='progress progress-info progress-striped active'><div class='bar' style='width: 100%'></div></div>");
                    $.ajax({
                        type: "POST",
                        url: "<?php echo base_url(); ?>index.php/os/adicionarProduto",
                        data: dados,
                        dataType: 'json',
                        success: function (data) {
                            if (data.result == true) {
                                $("#divProdutos").load("<?php echo current_url(); ?> #divProdutos");
                                $("#quantidade").val('');
                                $("#preco").val('');
                                $("#resultado").val('');
                                $("#desconto").val('');
                                $("#divValorTotal").load("<?php echo current_url(); ?> #divValorTotal");
                                $("#produto").val('').focus();
                            } else {
                                Swal.fire({
                                    type: "error",
                                    title: "Atenção",
                                    text: "Ocorreu um erro ao tentar adicionar produto."
                                });
                            }
                        }
                    });
                    return false;
                }
            }
        });

        $("#formServicos").validate({
            rules: {
                servico: {
                    required: true
                },
                preco: {
                    required: true
                },
                quantidade: {
                    required: true
                },
            },
            messages: {
                servico: {
                    required: 'Insira um serviço'
                },
                preco: {
                    required: 'Insira o preço'
                },
                quantidade: {
                    required: 'Insira a quantidade'
                },
            },
            submitHandler: function (form) {
                var dados = $(form).serialize();

                $("#divServicos").html("<div class='progress progress-info progress-striped active'><div class='bar' style='width: 100%'></div></div>");
                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url(); ?>index.php/os/adicionarServico",
                    data: dados,
                    dataType: 'json',
                    success: function (data) {
                        if (data.result == true) {
                            $("#divServicos").load("<?php echo current_url(); ?> #divServicos");
                            $("#quantidade_servico").val('');
                            $("#preco_servico").val('');
                            $("#servico").val('').focus();
                        } else {
                            Swal.fire({
                                type: "error",
                                title: "Atenção",
                                text: "Ocorreu um erro ao tentar adicionar serviço."
                            });
                        }
                    }
                });
                return false;
            }
        });

        $("#formAnotacao").validate({
            rules: {
                anotacao: {
                    required: true
                }
            },
            messages: {
                anotacao: {
                    required: 'Insira a anotação'
                }
            },
            submitHandler: function (form) {
                var dados = $(form).serialize();
                $("#divFormAnotacoes").html("<div class='progress progress-info progress-striped active'><div class='bar' style='width: 100%'></div></div>");

                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url(); ?>index.php/os/adicionarAnotacao",
                    data: dados,
                    dataType: 'json',
                    success: function (data) {
                        if (data.result == true) {
                            $("#divAnotacoes").load("<?php echo current_url(); ?> #divAnotacoes");
                            $("#anotacao").val('');
                            $('#btn-close-anotacao').trigger('click');
                            $("#divFormAnotacoes").html('');
                        } else {
                            Swal.fire({
                                type: "error",
                                title: "Atenção",
                                text: "Ocorreu um erro ao tentar adicionar anotação."
                            });
                        }
                    }
                });
                return false;
            }
        });

        // ---- Upload AJAX de anexos OS ----
        var osBaseUrl = "<?php echo base_url(); ?>index.php/";
        var osCsrfName = "<?php echo $this->security->get_csrf_token_name(); ?>";
        var osCsrfHash = "<?php echo $this->security->get_csrf_hash(); ?>";
        var osId       = "<?php echo $result->idOs; ?>";

        function osUploadFile(file, type) {
            if (!file) return;

            // Valida duração de vídeo
            var videoExts = ['mp4','mov','webm'];
            var ext = file.name.split('.').pop().toLowerCase();
            if (videoExts.indexOf(ext) >= 0) {
                var tmpUrl = URL.createObjectURL(file);
                var vid = document.createElement('video');
                vid.preload = 'metadata';
                vid.onloadedmetadata = function() {
                    URL.revokeObjectURL(tmpUrl);
                    if (vid.duration > 90) {
                        $("#os-upload-msg").html('<div class="alert alert-danger fade in"><button type="button" class="close" data-dismiss="alert">×</button><strong>Atenção!</strong> O vídeo excede 1 minuto e 30 segundos.</div>');
                        return;
                    }
                    osDoUpload(file);
                };
                vid.src = tmpUrl;
            } else {
                osDoUpload(file);
            }
        }

        function osDoUpload(file) {
            var fd = new FormData();
            fd.append('anexo', file);
            fd.append('os_id', osId);
            fd.append(osCsrfName, osCsrfHash);

            $("#os-upload-msg").html("<div class='progress progress-info progress-striped active'><div class='bar' style='width: 100%'></div></div>");
            $.ajax({
                type: 'POST',
                url: osBaseUrl + 'os/upload_anexo_ajax',
                data: fd,
                contentType: false,
                processData: false,
                dataType: 'json',
                success: function(data) {
                    if (data.success) {
                        osCsrfHash = data.csrf_hash;
                        $("#os-upload-msg").html('');
                        // Recarrega lista e botões
                        location.reload();
                    } else {
                        $("#os-upload-msg").html('<div class="alert alert-danger fade in"><button type="button" class="close" data-dismiss="alert">×</button><strong>Atenção!</strong> ' + data.message + '</div>');
                        // Atualiza csrf
                        $.get(osBaseUrl + 'os/getCsrfToken', function(r){ if(r.csrf_hash) osCsrfHash = r.csrf_hash; }, 'json');
                    }
                },
                error: function() {
                    $("#os-upload-msg").html('<div class="alert alert-danger fade in"><button type="button" class="close" data-dismiss="alert">×</button><strong>Atenção!</strong> Erro ao enviar arquivo.</div>');
                }
            });
        }

        $('#os-btn-foto').on('click', function(){ $('#os-file-foto').val('').trigger('click'); });
        $('#os-btn-pdf').on('click',  function(){ $('#os-file-pdf').val('').trigger('click');  });
        $('#os-btn-doc').on('click',  function(){ $('#os-file-doc').val('').trigger('click');  });

        $('#os-file-foto').on('change', function(){ osUploadFile(this.files[0], 'foto'); });
        $('#os-file-pdf').on('change',  function(){ osUploadFile(this.files[0], 'pdf'); });
        $('#os-file-doc').on('change',  function(){ osUploadFile(this.files[0], 'doc'); });

        $(document).on('click', 'a', function (event) {
            var idProduto = $(this).attr('idAcao');
            var quantidade = $(this).attr('quantAcao');
            var produto = $(this).attr('prodAcao');
            var idOS = "<?php echo $result->idOs ?>"
            if ((idProduto % 1) == 0) {
                $("#divProdutos").html("<div class='progress progress-info progress-striped active'><div class='bar' style='width: 100%'></div></div>");
                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url(); ?>index.php/os/excluirProduto",
                    data: "idProduto=" + idProduto + "&quantidade=" + quantidade + "&produto=" + produto + "&idOs=" + idOS,
                    dataType: 'json',
                    success: function (data) {
                        if (data.result == true) {
                            $("#divProdutos").load("<?php echo current_url(); ?> #divProdutos");
                            $("#divValorTotal").load("<?php echo current_url(); ?> #divValorTotal");
                            $("#resultado").val('');
                            $("#desconto").val('');

                        } else {
                            Swal.fire({
                                type: "error",
                                title: "Atenção",
                                text: "Ocorreu um erro ao tentar excluir produto."
                            });
                        }
                    }
                });
                return false;
            }

        });

        $(document).on('click', '.servico', function (event) {
            var idServico = $(this).attr('idAcao');
            var idOS = "<?php echo $result->idOs ?>"
            if ((idServico % 1) == 0) {
                $("#divServicos").html("<div class='progress progress-info progress-striped active'><div class='bar' style='width: 100%'></div></div>");
                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url(); ?>index.php/os/excluirServico",
                    data: "idServico=" + idServico + "&idOs=" + idOS,
                    dataType: 'json',
                    success: function (data) {
                        if (data.result == true) {
                            $("#divServicos").load("<?php echo current_url(); ?> #divServicos");
                            $("#divValorTotal").load("<?php echo current_url(); ?> #divValorTotal");
                            $("#resultado").val('');
                            $("#desconto").val('');

                        } else {
                            Swal.fire({
                                type: "error",
                                title: "Atenção",
                                text: "Ocorreu um erro ao tentar excluir serviço."
                            });
                        }
                    }
                });
                return false;
            }
        });

        $(document).on('click', '.anexo', function (event) {
            event.preventDefault();
            var link = $(this).attr('link');
            var id = $(this).attr('imagem');
            var url = '<?php echo base_url(); ?>index.php/os/excluirAnexo/';
            $("#div-visualizar-anexo").html('<img src="' + link + '" alt="">');
            $("#excluir-anexo").attr('link', url + id);

            $("#download").attr('href', "<?php echo base_url(); ?>index.php/os/downloadanexo/" + id);

        });

        $(document).on('click', '#excluir-anexo', function (event) {
            event.preventDefault();
            var link = $(this).attr('link');
            var idOS = "<?php echo $result->idOs ?>"
            $('#modal-anexo').modal('hide');
            $("#divAnexos").html("<div class='progress progress-info progress-striped active'><div class='bar' style='width: 100%'></div></div>");

            $.ajax({
                type: "POST",
                url: link,
                dataType: 'json',
                data: "idOs=" + idOS,
                success: function (data) {
                    if (data.result == true) {
                        $("#divAnexos").load("<?php echo current_url(); ?> #divAnexos");
                    } else {
                        Swal.fire({
                            type: "error",
                            title: "Atenção",
                            text: data.mensagem
                        });
                    }
                }
            });
        });

        $(document).on('click', '.anotacao', function (event) {
            var idAnotacao = $(this).attr('idAcao');
            var idOS = "<?php echo $result->idOs ?>"
            if ((idAnotacao % 1) == 0) {
                $("#divAnotacoes").html("<div class='progress progress-info progress-striped active'><div class='bar' style='width: 100%'></div></div>");
                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url(); ?>index.php/os/excluirAnotacao",
                    data: "idAnotacao=" + idAnotacao + "&idOs=" + idOS,
                    dataType: 'json',
                    success: function (data) {
                        if (data.result == true) {
                            $("#divAnotacoes").load("<?php echo current_url(); ?> #divAnotacoes");

                        } else {
                            Swal.fire({
                                type: "error",
                                title: "Atenção",
                                text: "Ocorreu um erro ao tentar excluir Anotação."
                            });
                        }
                    }
                });
                return false;
            }
        });

        $(".datepicker").datepicker({
            dateFormat: 'dd/mm/yy'
        });

        // editor removido (observacoes/laudoTecnico ocultos)
    });
</script>
