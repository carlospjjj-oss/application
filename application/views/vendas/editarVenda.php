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
                <span class="icon">
                    <i class="fas fa-cash-register"></i>
                </span>
                <h5>Editar Venda</h5>
            </div>
            <div class="widget-content nopadding tab-content">
                <div class="span12" id="divProdutosServicos" style=" margin-left: 0">
                    <ul class="nav nav-tabs">
                        <li class="active" id="tabDetalhes"><a href="#tab1" data-toggle="tab">Detalhes da Venda</a></li>
                        <li id="tabProdutos"><a href="#tab2" data-toggle="tab">Produtos</a></li>
                        <li id="tabServicos"><a href="#tab3" data-toggle="tab">Serviços</a></li>
                        <li id="tabTotal"><a href="#tab4" data-toggle="tab"><strong>Total</strong></a></li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab1">
                            <div class="span12" id="divEditarVenda">
                                <form action="<?php echo current_url(); ?>" method="post" id="formVendas">
                                    <?php echo form_hidden('idVendas', $result->idVendas) ?>
                                    <div class="span12" style="padding: 1%; margin-left: 0">
                                        <h3>Venda:
                                            <?php echo $result->idVendas ?>
                                        </h3>
                                        <div class="span2" style="margin-left: 0">
                                            <label for="dataFinal">Data Final</label>
                                            <input id="dataVenda" class="span12 datepicker" type="text" name="dataVenda" value="<?php echo date('d/m/Y', strtotime($result->dataVenda)); ?>" />
                                        </div>
                                        <div class="span3">
                                            <label for="cliente">Cliente<span class="required">*</span></label>
                                            <input id="cliente" class="span12" type="text" name="cliente" value="<?php echo $result->nomeCliente ?>" />
                                            <input id="clientes_id" class="span12" type="hidden" name="clientes_id" value="<?php echo $result->clientes_id ?>" />
                                            <input id="valorTotal" type="hidden" name="valorTotal" value="" />
                                        </div>
                                        <div class="span3">
                                            <label for="tecnico">Vendedor<span class="required">*</span></label>
                                            <input id="tecnico" class="span12" type="text" name="tecnico" value="<?php echo !empty($result->vendedor) ? $result->vendedor : $result->nome; ?>" />
                                            <input id="usuarios_id" class="span12" type="hidden" name="usuarios_id" value="<?php echo $result->usuarios_id ?>" />
                                        </div>
                                        <div class="span2">
                                            <label for="status">Status<span class="required">*</span></label>
                                            <select class="span12" name="status" id="status" value="">
                                                <option <?= $result->status == 'Orçamento' ? 'selected' : '' ?> value="Orçamento">Orçamento</option>
                                                <option <?= $result->status == 'Em Andamento' ? 'selected' : '' ?> value="Em Andamento">Em Andamento</option>
                                                <option <?= $result->status == 'Aprovado' ? 'selected' : '' ?> value="Aprovado">Aprovado</option>
                                                <option <?= $result->status == 'Finalizado' ? 'selected' : '' ?> value="Finalizado">Finalizado</option>
                                                <option <?= $result->status == 'Cancelado' ? 'selected' : '' ?> value="Cancelado">Cancelado</option>
                                            </select>
                                        </div>
                                        <div class="span2">
                                            <label for="garantia">Garantia (dias)</label>
                                            <input id="garantia" type="number" placeholder="Em dias" min="0" max="9999"
                                                class="span12" name="garantia"
                                                value="<?php echo $result->garantia ?>" />
                                            <?php echo form_error('garantia'); ?>
                                        </div>
                                    </div>

                                    <div class="span6" style="padding: 1%; margin-left: 0">
                                        <label for="observacoes">
                                            <h4>Resultado</h4>
                                        </label>
                                        <textarea class="editor" name="observacoes" id="observacoes" cols="30" rows="5"><?php echo $result->observacoes ?></textarea>
                                    </div>

                                    <div class="span6" style="padding: 1%; margin-left: 0">
                                        <label for="observacoes_cliente">
                                            <h4>Observações ao Cliente</h4>
                                        </label>
                                        <textarea class="editor" name="observacoes_cliente" id="observacoes_cliente" cols="30" rows="5"><?php echo $result->observacoes_cliente ?></textarea>
                                    </div>

                                    <div class="span12" style="padding: 1%; margin-left: 0">
                                        <div class="span12" style="display:flex; justify-content: center;">
                                            <?php if ($result->faturado == 0) { ?>
                                                <a href="#modal-faturar" id="btn-faturar" role="button" data-toggle="modal" class="button btn btn-danger">
                                                    <span class="button__icon"><i class='bx bx-dollar'></i></span> 
                                                    <span class="button__text2">Faturar</span>
                                                </a>
                                            <?php } ?>
                                            <button class="button btn btn-primary" id="btnContinuar">
                                                <span class="button__icon"><i class="bx bx-sync"></i></span>
                                                <span class="button__text2">Atualizar</span>
                                            </button>
                                            <a href="<?php echo base_url() ?>index.php/vendas/visualizar/<?php echo $result->idVendas; ?>" class="button btn btn-primary">
                                                <span class="button__icon"><i class="bx bx-show"></i></span>
                                                <span class="button__text2">Visualizar</span>
                                            </a>
                                            <a href="<?php echo base_url() ?>index.php/vendas" class="button btn btn-warning">
                                                <span class="button__icon"><i class="bx bx-undo"></i></span>
                                                <span class="button__text2">Voltar</span>
                                            </a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="tab-pane" id="tab2">
                            <div class="span12 well" style="padding: 1%; margin-left: 0">
                                <div class="span11">
                                    <form id="formProdutos" action="<?php echo base_url(); ?>index.php/vendas/adicionarProduto" method="post">
                                        <div class="span5">
                                            <input type="hidden" name="idProduto" id="idProduto" />
                                            <input type="hidden" name="idVendasProduto" id="idVendasProduto" value="<?php echo $result->idVendas ?>" />
                                            <input type="hidden" name="estoque" id="estoque" value="" />
                                            <label for="">Produto</label>
                                            <input type="text" class="span12" name="produto" id="produto" placeholder="Digite o nome do produto" />
                                        </div>
                                        <div class="span2">
                                            <label for="">Preço</label>
                                            <input type="text" placeholder="Preço" id="preco" name="preco" class="span12 money" />
                                        </div>
                                        <div class="span1">
                                            <label for="">Qtd</label>
                                            <input type="text" placeholder="Qtd" id="quantidade" name="quantidade" class="span12" />
                                        </div>
                                        <div class="span1">
                                            <label for="">% Margem</label>
                                            <input type="text" placeholder="0,00" id="margem" name="margem" class="span12" value="<?= isset($margem) ? $margem : '0' ?>" />
                                        </div>
                                        <div class="span2">
                                            <label for="">Total</label>
                                            <input type="text" id="totalMargem" class="span12" readonly />
                                        </div>
                                        <div class="span1">
                                            <label for="">&nbsp</label>
                                            <button class="button btn btn-success" id="btnAdicionarProduto">
                                                <span class="button__icon"><i class='bx bx-plus-circle'></i></span><span class="button__text2">Adicionar</span></button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="span12" id="divProdutos" style="margin-left: 0">
                                <table class="table table-bordered" id="tblProdutos">
                                    <thead>
                                        <tr>
                                            <th>Produto</th>
                                            <th width="8%">Quantidade</th>
                                            <th width="10%">Preço</th>
                                            <th width="8%">% Margem</th>
                                            <th width="6%">Ações</th>
                                            <th width="10%">Sub-total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $total = 0;
foreach ($produtos as $p) {
    $preco = $p->preco ?: $p->precoVenda;
    $margem = isset($p->margem) ? $p->margem : 0;
    $subTotal = isset($p->subTotal) ? $p->subTotal : ($preco * $p->quantidade * (1 + $margem/100));
    $total = $total + $subTotal;
    echo '<tr id="linha-produto-' . $p->idItens . '">';
    echo '<td>' . $p->descricao . '</td>';
    echo '<td><div align="center">' . $p->quantidade . '</td>';
    echo '<td><div align="center">R$: ' . number_format($preco, 2, '.', '') . '</td>';
    echo '<td><div align="center"><input type="text" class="margem-inline" id="margem-inline-' . $p->idItens . '" data-iditem="' . $p->idItens . '" data-preco="' . $preco . '" data-qtd="' . $p->quantidade . '" value="' . number_format($margem, 2, ',', '') . '" style="width:65px;text-align:center;" /></div></td>';
    echo '<td><div align="center"><a href="" idAcao="' . $p->idItens . '" prodAcao="' . $p->idProdutos . '" quantAcao="' . $p->quantidade . '" title="Excluir Produto" class="btn-nwe4"><i class="bx bx-trash-alt"></i></a></div></td>';
    echo '<td><div align="center" id="subtotal-' . $p->idItens . '">R$: ' . number_format($subTotal, 2, '.', '') . '</div></td>';
    echo '</tr>';
} ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="5" style="text-align: right"><strong>Total:</strong></td>
                                            <td style="text-align: right; white-space:nowrap;"><strong>R$: <?php echo number_format($total, 2, '.', ''); ?></strong><input type="hidden" id="total-venda" value="<?php echo number_format($total, 2, '.', ''); ?>"></td>
                                        </tr>
                                        <?php if ($result->valor_desconto != 0 && $result->desconto != 0) {
                                            ?>
                                            <tr>
                                                <td colspan="5" style="text-align: right"><strong>Desconto:</strong></td>
                                                <td style="text-align: right; white-space:nowrap;"><strong><?php echo $result->tipo_desconto == "real" ? "R$ " : ""; ?><?php echo number_format($result->desconto, 2, '.', ''); ?><?php echo $result->tipo_desconto == "porcento" ? " %" : ""; ?></strong></td>
                                            </tr>
                                            <tr>
                                                <td colspan="5" style="text-align: right"><strong>Total Com Desconto:</strong></td>
                                                <td style="text-align: right; white-space:nowrap;"><strong>R$: <?php echo number_format($result->valor_desconto, 2, '.', ''); ?></strong><input type="hidden" id="total-desconto" value="<?php echo number_format($result->valor_desconto, 2, '.', ''); ?>"></td>
                                            </tr>
                                        <?php
                                        } ?>
                                    </tfoot>
                                </table>
                            </div>
                            <div class="span12" style="margin-left: 0; margin-top: 10px;">
                                <form id="formDesconto" action="<?php echo base_url(); ?>index.php/vendas/adicionarDesconto" method="POST">
                                    <input type="hidden" name="idVendas" id="idVendas" value="<?php echo $result->idVendas; ?>" />
                                    <div style="display:flex; align-items:flex-end; gap:10px; justify-content:flex-end;">
                                        <div>
                                            <label for="">Desconto</label>
                                            <input style="width: 6em;" id="desconto" name="desconto" type="text" placeholder="0.00" maxlength="6" />
                                            <strong><span style="color: red" id="errorAlert"></span></strong>
                                        </div>
                                        <div>
                                            <label for="">Tipo</label>
                                            <select style="width: 5em;" name="tipoDesconto" id="tipoDesconto">
                                                <option value="real">R$</option>
                                                <option value="porcento" <?=$result->tipo_desconto == "porcento" ? "selected" : "" ?>>%</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label for="">Total com Desconto</label>
                                            <input class="money" id="resultado" type="text" data-affixes-stay="true" data-thousands="" data-decimal="." name="resultado" value="" readonly style="width:9em;" />
                                        </div>
                                        <div>
                                            <button class="button btn btn-success" id="btnAdicionarDesconto">
                                                <span class="button__icon"><i class='bx bx-plus-circle'></i></span><span class="button__text2">Aplicar</span>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="tab-pane" id="tab3">
                            <div class="span12 well" style="padding: 1%; margin-left: 0">
                                <div class="span11">
                                    <form id="formServicos" action="<?php echo base_url(); ?>index.php/vendas/adicionarServico" method="post">
                                        <div class="span5">
                                            <input type="hidden" name="idServico" id="idServico" />
                                            <input type="hidden" name="idVendasServico" id="idVendasServico" value="<?php echo $result->idVendas ?>" />
                                            <label for="">Serviço</label>
                                            <input type="text" class="span12" name="servico" id="servico" placeholder="Digite o nome do serviço" />
                                        </div>
                                        <div class="span2">
                                            <label for="">Preço</label>
                                            <input type="text" placeholder="Preço" id="precoServico" name="precoServico" class="span12 money" />
                                        </div>
                                        <div class="span1">
                                            <label for="">Qtd</label>
                                            <input type="text" placeholder="1" id="quantidadeServico" name="quantidadeServico" class="span12" value="1" />
                                        </div>
                                        <div class="span1">
                                            <label for="">% Margem</label>
                                            <input type="text" placeholder="0" id="margemServico" name="margemServico" class="span12" value="0" />
                                        </div>
                                        <div class="span2">
                                            <label for="">Total</label>
                                            <input type="text" id="totalMargemServico" class="span12" readonly />
                                        </div>
                                        <div class="span1">
                                            <label for="">&nbsp</label>
                                            <button class="button btn btn-success" id="btnAdicionarServico" type="button">
                                                <span class="button__icon"><i class='bx bx-plus-circle'></i></span><span class="button__text2">Adicionar</span></button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="span12" id="divServicos" style="margin-left: 0">
                                <table class="table table-bordered" id="tblServicos">
                                    <thead>
                                        <tr>
                                            <th>Serviço</th>
                                            <th width="8%">Quantidade</th>
                                            <th width="10%">Preço</th>
                                            <th width="8%">% Margem</th>
                                            <th width="6%">Ações</th>
                                            <th width="10%">Sub-total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($servicos)): foreach ($servicos as $s):
                                            $precoS = $s->preco ?? 0;
                                            $margemS = isset($s->margem) ? floatval($s->margem) : 0;
                                            $subS = isset($s->subTotal) && $s->subTotal > 0 ? $s->subTotal : ($precoS * $s->quantidade * (1 + $margemS / 100));
                                        ?>
                                        <tr>
                                            <td><?php echo $s->nome ?></td>
                                            <td><div align="center"><?php echo $s->quantidade ?></div></td>
                                            <td><div align="center">R$: <?php echo number_format($precoS,2,'.','')?></div></td>
                                            <td><div align="center"><input type="text" class="margem-servico-inline" id="margem-servico-inline-<?php echo $s->idServicos_venda ?>" data-iditem="<?php echo $s->idServicos_venda ?>" data-preco="<?php echo $precoS ?>" data-qtd="<?php echo $s->quantidade ?>" value="<?php echo number_format($margemS,2,',','') ?>" style="width:65px;text-align:center;" /></div></td>
                                            <td><div align="center"><a href="#" idAcao="<?php echo $s->idServicos_venda ?>" title="Excluir Serviço" class="btn-excluir-servico"><i class="bx bx-trash-alt"></i></a></div></td>
                                            <td><div align="center" id="subtotal-servico-<?php echo $s->idServicos_venda ?>">R$: <?php echo number_format($subS,2,'.','')?></div></td>
                                        </tr>
                                        <?php endforeach; endif; ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="5" style="text-align:right"><strong>Total:</strong></td>
                                            <td style="text-align:right; white-space:nowrap;">
                                                <?php
                                                $totalServicos = 0;
                                                if (!empty($servicos)) {
                                                    foreach ($servicos as $s) {
                                                        $precoS = $s->preco ?? 0;
                                                        $margemS = isset($s->margem) ? floatval($s->margem) : 0;
                                                        $totalServicos += isset($s->subTotal) && $s->subTotal > 0 ? $s->subTotal : ($precoS * $s->quantidade * (1 + $margemS / 100));
                                                    }
                                                }
                                                ?>
                                                <strong id="total-servicos-label">R$: <?php echo number_format($totalServicos, 2, '.', ''); ?></strong>
                                                <input type="hidden" id="total-servicos" value="<?php echo number_format($totalServicos, 2, '.', ''); ?>">
                                            </td>
                                        </tr>
                                        <?php if (!empty($result->desconto_servico) && $result->desconto_servico != 0): ?>
                                        <tr>
                                            <td colspan="5" style="text-align:right"><strong>Desconto:</strong></td>
                                            <td style="text-align:right; white-space:nowrap;"><strong><?php echo $result->tipo_desconto_servico == "real" ? "R$ " : ""; ?><?php echo number_format($result->desconto_servico, 2, '.', ''); ?><?php echo $result->tipo_desconto_servico == "porcento" ? " %" : ""; ?></strong></td>
                                        </tr>
                                        <tr>
                                            <td colspan="5" style="text-align:right"><strong>Total Com Desconto:</strong></td>
                                            <td style="text-align:right; white-space:nowrap;"><strong>R$: <?php echo number_format($result->valor_desconto_servico, 2, '.', ''); ?></strong></td>
                                        </tr>
                                        <?php endif; ?>
                                    </tfoot>
                                </table>
                            </div>
                            <div class="span12" style="margin-left: 0; margin-top: 10px;">
                                <form id="formDescontoServico" action="<?php echo base_url(); ?>index.php/vendas/adicionarDescontoServico" method="POST" onsubmit="return false;">
                                    <input type="hidden" name="idVendasDesc" id="idVendasDescServico" value="<?php echo $result->idVendas; ?>" />
                                    <div style="display:flex; align-items:flex-end; gap:10px; justify-content:flex-end;">
                                        <div>
                                            <label for="">Desconto</label>
                                            <input style="width: 6em;" id="descontoServico" name="descontoServico" type="text" placeholder="0.00" maxlength="6" />
                                        </div>
                                        <div>
                                            <label for="">Tipo</label>
                                            <select style="width: 5em;" name="tipoDescontoServico" id="tipoDescontoServico">
                                                <option value="real">R$</option>
                                                <option value="porcento">%</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label for="">Total com Desconto</label>
                                            <input id="resultadoServico" type="text" name="resultadoServico" value="" readonly style="width:9em;" />
                                        </div>
                                        <div>
                                            <button class="button btn btn-success" id="btnAdicionarDescontoServico" type="button">
                                                <span class="button__icon"><i class='bx bx-plus-circle'></i></span><span class="button__text2">Aplicar</span>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Aba Total -->
                        <div class="tab-pane" id="tab4">
                            <div class="span12" style="margin-left: 0; padding: 10px 0;">
                                <?php
                                // Calcular totais de produtos
                                $totalProdutos = 0;
                                foreach ($produtos as $p) {
                                    $precoP = $p->preco ?: $p->precoVenda;
                                    $margemP = isset($p->margem) ? floatval($p->margem) : 0;
                                    $totalProdutos += isset($p->subTotal) && $p->subTotal > 0 ? $p->subTotal : ($precoP * $p->quantidade * (1 + $margemP / 100));
                                }
                                // Calcular totais de serviços
                                $totalServicosGeral = 0;
                                if (!empty($servicos)) {
                                    foreach ($servicos as $s) {
                                        $precoSG = $s->preco ?? 0;
                                        $margemSG = isset($s->margem) ? floatval($s->margem) : 0;
                                        $totalServicosGeral += isset($s->subTotal) && $s->subTotal > 0 ? $s->subTotal : ($precoSG * $s->quantidade * (1 + $margemSG / 100));
                                    }
                                }
                                $totalGeral = $totalProdutos + $totalServicosGeral;
                                // Descontos
                                $descontoProd = isset($result->desconto) ? floatval($result->desconto) : 0;
                                $tipoProd = isset($result->tipo_desconto) ? $result->tipo_desconto : 'real';
                                $valorDescontoProd = isset($result->valor_desconto) ? floatval($result->valor_desconto) : 0;
                                $descontoServ = isset($result->desconto_servico) ? floatval($result->desconto_servico) : 0;
                                $tipoServ = isset($result->tipo_desconto_servico) ? $result->tipo_desconto_servico : 'real';
                                $valorDescontoServ = isset($result->valor_desconto_servico) ? floatval($result->valor_desconto_servico) : 0;
                                // Total final considerando descontos
                                $totalFinalProd = $valorDescontoProd > 0 ? $valorDescontoProd : $totalProdutos;
                                $totalFinalServ = $valorDescontoServ > 0 ? $valorDescontoServ : $totalServicosGeral;
                                $totalFinal = $totalFinalProd + $totalFinalServ;
                                ?>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr style="background:#f5f5f5;">
                                            <th>Descrição</th>
                                            <th width="15%" style="text-align:right;">Valor</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($produtos as $p):
                                            $precoP = $p->preco ?: $p->precoVenda;
                                            $margemP = isset($p->margem) ? floatval($p->margem) : 0;
                                            $subP = isset($p->subTotal) && $p->subTotal > 0 ? $p->subTotal : ($precoP * $p->quantidade * (1 + $margemP / 100));
                                        ?>
                                        <tr>
                                            <td><?php echo $p->descricao ?> <small style="color:#888;">(<?php echo $p->quantidade ?>x · <?php echo $margemP > 0 ? $margemP.'% margem' : 'sem margem' ?>)</small></td>
                                            <td style="text-align:right;">R$ <?php echo number_format($subP, 2, ',', '.') ?></td>
                                        </tr>
                                        <?php endforeach; ?>
                                        <?php if (!empty($servicos)): foreach ($servicos as $s):
                                            $precoSG = $s->preco ?? 0;
                                            $margemSG = isset($s->margem) ? floatval($s->margem) : 0;
                                            $subSG = isset($s->subTotal) && $s->subTotal > 0 ? $s->subTotal : ($precoSG * $s->quantidade * (1 + $margemSG / 100));
                                        ?>
                                        <tr>
                                            <td><?php echo $s->nome ?> <small style="color:#888;">(<?php echo $s->quantidade ?>x · <?php echo $margemSG > 0 ? $margemSG.'% margem' : 'sem margem' ?>)</small></td>
                                            <td style="text-align:right;">R$ <?php echo number_format($subSG, 2, ',', '.') ?></td>
                                        </tr>
                                        <?php endforeach; endif; ?>
                                    </tbody>
                                    <tfoot>
                                        <tr style="background:#f9f9f9;">
                                            <td style="text-align:right;"><strong>Subtotal Materiais/Produtos</strong></td>
                                            <td style="text-align:right;"><strong>R$ <?php echo number_format($totalProdutos, 2, ',', '.') ?></strong></td>
                                        </tr>
                                        <?php if ($descontoProd > 0): ?>
                                        <tr>
                                            <td style="text-align:right; color:#c0392b;">Desconto Produtos (<?php echo $tipoProd == 'real' ? 'R$ '.number_format($descontoProd,2,',','.') : number_format($descontoProd,2,',','.').'%' ?>)</td>
                                            <td style="text-align:right; color:#c0392b;">- R$ <?php echo number_format($totalProdutos - $valorDescontoProd, 2, ',', '.') ?></td>
                                        </tr>
                                        <?php endif; ?>
                                        <tr style="background:#f9f9f9;">
                                            <td style="text-align:right;"><strong>Subtotal Serviços</strong></td>
                                            <td style="text-align:right;"><strong>R$ <?php echo number_format($totalServicosGeral, 2, ',', '.') ?></strong></td>
                                        </tr>
                                        <?php if ($descontoServ > 0): ?>
                                        <tr>
                                            <td style="text-align:right; color:#c0392b;">Desconto Serviços (<?php echo $tipoServ == 'real' ? 'R$ '.number_format($descontoServ,2,',','.') : number_format($descontoServ,2,',','.').'%' ?>)</td>
                                            <td style="text-align:right; color:#c0392b;">- R$ <?php echo number_format($totalServicosGeral - $valorDescontoServ, 2, ',', '.') ?></td>
                                        </tr>
                                        <?php endif; ?>
                                        <tr style="background:#2f2b53; color:#fff;">
                                            <td style="text-align:right; font-size:1.1em;"><strong>Total Geral Materiais e Serviços</strong></td>
                                            <td style="text-align:right; font-size:1.2em;"><strong>R$ <?php echo number_format($totalFinal, 2, ',', '.') ?></strong></td>
                                        </tr>
                                    </tfoot>
                                </table>

                                <!-- Condições de Pagamento -->
                                <div style="margin-top:18px; border:1px solid #ddd; border-radius:6px; padding:16px; background:#fafafa;">
                                    <h4 style="margin:0 0 14px 0; color:#2f2b53; font-size:1em; border-bottom:2px solid #2f2b53; padding-bottom:6px;">Condições de Pagamento</h4>
                                    <form id="formCondicoesPagamento" onsubmit="return false;">
                                        <input type="hidden" id="condVendasId" value="<?php echo $result->idVendas; ?>" />
                                        <input type="hidden" id="condTotalGeralVal" value="<?php echo number_format($totalFinal, 2, '.', ''); ?>" />
                                        <?php
                                        // % salvas no banco para as 3 condições percentuais
                                        $pctAceite = isset($result->cond_aceite_pct) ? floatval($result->cond_aceite_pct) : 0;
                                        $pctEntrega = isset($result->cond_entrega_serv_pct) ? floatval($result->cond_entrega_serv_pct) : 0;
                                        $pctPix = isset($result->cond_pix_pct) ? floatval($result->cond_pix_pct) : 0;
                                        // valor R$ calculado (salvo no banco)
                                        $valAceite = isset($result->cond_aceite) ? floatval($result->cond_aceite) : 0;
                                        $valEntrega = isset($result->cond_entrega_serv) ? floatval($result->cond_entrega_serv) : 0;
                                        $valPix = isset($result->cond_pix) ? floatval($result->cond_pix) : 0;
                                        ?>
                                        <div style="display:flex; flex-direction:column; gap:8px; margin-bottom:12px;">

                                            <?php // Linha Aceite ?>
                                            <div style="display:flex; align-items:center; gap:12px; padding:8px 10px; background:#fff; border:1px solid #e0e0e0; border-radius:4px;">
                                                <input type="checkbox" id="chkAceite" <?php echo $valAceite > 0 ? 'checked' : '' ?> style="margin:0;" />
                                                <span style="flex:1;">Condição Especial - Pagamento no Aceite</span>
                                                <div id="wrapAceite" style="display:flex; align-items:center; gap:4px; <?php echo $valAceite > 0 ? '' : 'visibility:hidden;' ?>">
                                                    <input type="number" id="condAceitePct" min="0" max="100" step="0.01" placeholder="0" value="<?php echo $pctAceite > 0 ? $pctAceite : '' ?>" style="width:60px; text-align:right;" />
                                                    <span style="color:#666;">%</span>
                                                </div>
                                                <strong id="condAceiteLabel" style="color:#2f2b53; min-width:110px; text-align:right;"><?php echo $valAceite > 0 ? 'R$ '.number_format($valAceite,2,',','.') : '' ?></strong>
                                            </div>

                                            <?php // Linha Entrega Serviços ?>
                                            <div style="display:flex; align-items:center; gap:12px; padding:8px 10px; background:#fff; border:1px solid #e0e0e0; border-radius:4px;">
                                                <input type="checkbox" id="chkEntregaServ" <?php echo $valEntrega > 0 ? 'checked' : '' ?> style="margin:0;" />
                                                <span style="flex:1;">Condição Especial - Pagamento na Entrega dos Serviços</span>
                                                <div id="wrapEntregaServ" style="display:flex; align-items:center; gap:4px; <?php echo $valEntrega > 0 ? '' : 'visibility:hidden;' ?>">
                                                    <input type="number" id="condEntregaServPct" min="0" max="100" step="0.01" placeholder="0" value="<?php echo $pctEntrega > 0 ? $pctEntrega : '' ?>" style="width:60px; text-align:right;" />
                                                    <span style="color:#666;">%</span>
                                                </div>
                                                <strong id="condEntregaServLabel" style="color:#2f2b53; min-width:110px; text-align:right;"><?php echo $valEntrega > 0 ? 'R$ '.number_format($valEntrega,2,',','.') : '' ?></strong>
                                            </div>

                                            <?php // Linha PIX ?>
                                            <div style="display:flex; align-items:center; gap:12px; padding:8px 10px; background:#fff; border:1px solid #e0e0e0; border-radius:4px;">
                                                <input type="checkbox" id="chkPix" <?php echo $valPix > 0 ? 'checked' : '' ?> style="margin:0;" />
                                                <span style="flex:1;">Condição Especial - Pagamento PIX <small style="color:#888;">(% de desconto)</small></span>
                                                <div id="wrapPix" style="display:flex; align-items:center; gap:4px; <?php echo $valPix > 0 ? '' : 'visibility:hidden;' ?>">
                                                    <input type="number" id="condPixPct" min="0" max="100" step="0.01" placeholder="0" value="<?php echo $pctPix > 0 ? $pctPix : '' ?>" style="width:60px; text-align:right;" />
                                                    <span style="color:#666;">%</span>
                                                </div>
                                                <strong id="condPixLabel" style="color:#2f2b53; min-width:110px; text-align:right;"><?php echo $valPix > 0 ? 'R$ '.number_format($valPix,2,',','.') : '' ?></strong>
                                            </div>

                                            <?php // Linha Parcelado ?>
                                            <div style="display:flex; align-items:center; gap:12px; padding:8px 10px; background:#fff; border:1px solid #e0e0e0; border-radius:4px;">
                                                <input type="checkbox" id="chkParcelado" <?php echo $result->cond_parcelado > 0 ? 'checked' : '' ?> style="margin:0;" />
                                                <span style="flex:1;">Condição Especial - Pagamento em
                                                    <input type="number" id="condParcelas" min="1" max="24" value="<?php echo $result->cond_parcelas > 0 ? $result->cond_parcelas : '' ?>" placeholder="Nº" style="width:44px; display:inline-block; text-align:center;" />
                                                    x Sem Juros
                                                </span>
                                                <div id="wrapParcelado" style="display:flex; align-items:center; gap:4px; <?php echo $result->cond_parcelado > 0 ? '' : 'visibility:hidden;' ?>">
                                                    <span style="color:#666;">R$</span>
                                                    <input type="text" id="condParcelado" placeholder="0,00" value="<?php echo $result->cond_parcelado > 0 ? number_format($result->cond_parcelado,2,',','.') : '' ?>" style="width:100px; text-align:right;" />
                                                </div>
                                                <strong id="condParceladoLabel" style="color:#2f2b53; min-width:110px; text-align:right;"></strong>
                                            </div>

                                        </div>
                                        <div style="text-align:right; margin-top:4px;">
                                            <button class="button btn btn-success" id="btnSalvarCondicoes" type="button">
                                                <span class="button__icon"><i class='bx bx-save'></i></span><span class="button__text2">Salvar Condições</span>
                                            </button>
                                            <span id="condMsg" style="margin-left:10px; color:green; display:none;">Salvo!</span>
                                        </div>
                                    </form>
                                </div>

                            </div>
                        </div>

                    </div>
                </div>
                &nbsp
            </div>
        </div>
    </div>
</div>

<!-- Modal Faturar-->
<div id="modal-faturar" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <form id="formFaturar" action="<?php echo current_url() ?>" method="post">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h3 id="myModalLabel">Faturar Venda</h3>
        </div>
        <div class="modal-body">
            <div class="span12 alert alert-info" style="margin-left: 0"> Obrigatório o preenchimento dos campos com asterisco.</div>
            <div class="span12" style="margin-left: 0">
                <label for="descricao">Descrição</label>
                <input class="span12" id="descricao" type="text" name="descricao" value="Fatura de Venda Nº: <?php echo $result->idVendas; ?> " />
            </div>
            <div class="span12" style="margin-left: 0">
                <div class="span12" style="margin-left: 0">
                    <label for="cliente">Cliente*</label>
                    <input class="span12" id="cliente" type="text" name="cliente" value="<?php echo $result->nomeCliente ?>" />
                    <input type="hidden" name="clientes_id" id="clientes_id" value="<?php echo $result->clientes_id ?>">
                    <input type="hidden" name="vendas_id" id="vendas_id" value="<?php echo $result->idVendas; ?>">
                </div>
            </div>
            <div class="span12" style="margin-left: 0">
                <div class="span5" style="margin-left: 0">
                    <label for="valor">Valor*</label>
                    <input type="hidden" id="tipo" name="tipo" value="receita" />
                    <input class="span12 money" id="valor" type="text" name="valor" value="<?php echo number_format($total, 2, '.', ''); ?> " />
                </div>
                <div class="span5" style="margin-left: 2">
                    <label for="valor">Valor Com Desconto*</label>
                    <input class="span12 money" id="faturar-desconto" type="text" name="faturar-desconto" value="<?php echo number_format($result->valor_desconto, 2, '.', ''); ?> " />
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
                    &nbsp &nbsp &nbsp &nbsp<input id="recebido" type="checkbox" name="recebido" value="1" />
                </div>
                <div id="divRecebimento" class="span8" style=" display: none">
                    <div class="span6">
                        <label for="recebimento">Data Recebimento</label>
                        <input class="span12 datepicker" autocomplete="off" id="recebimento" type="text" name="recebimento" />
                    </div>
                    <div class="span6">
                        <label for="formaPgto">Forma Pgto</label>
                        <select name="formaPgto" id="formaPgto" class="span12">
                            <option value="Dinheiro">Dinheiro</option>
                            <option value="Cartão de Crédito">Cartão de Crédito</option>
                            <option value="Débito">Débito</option>
                            <option value="Boleto">Boleto</option>
                            <option value="Depósito">Depósito</option>
                            <option value="Pix">Pix</option>
                            <option value="Cheque">Cheque</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer" style="display:flex">
            <button class="button btn btn-warning" data-dismiss="modal" aria-hidden="true" id="btn-cancelar-faturar">
                <span class="button__icon"><i class="bx bx-x"></i></span><span class="button__text2">Cancelar</span></button>
            <button class="button btn btn-danger"><span class="button__icon"><i class='bx bx-dollar'></i></span> <span class="button__text2">Faturar</span></button>
        </div>        
    </form>
</div>
<script src="<?php echo base_url(); ?>assets/js/maskmoney.js"></script>
<script type="text/javascript">
    
    $("#quantidade").keyup(function() {
        this.value = this.value.replace(/[^0-9.]/g, '');
    });

    function calcDesconto(valor, desconto, tipoDesconto) {
        var resultado = 0;
        if (tipoDesconto == 'real') {
            resultado = valor - desconto;
        }
        if (tipoDesconto == 'porcento') {
            resultado = valor - (desconto * valor / 100);
        }
        if (resultado < 0) resultado = 0;
        return resultado;
    }

    function validarDesconto(resultado, valor) {
        if (resultado == valor) {
            return resultado = "";
        } else {
            return resultado.toFixed(2);
        }
    }
    var valorBackup = $("#total-venda").val();

    $("#desconto").keyup(function() {

        this.value = this.value.replace(/[^0-9.,]/g, '').replace(',', '.');
        if ($("#total-venda").val() == null || $("#total-venda").val() == '') {
            $('#errorAlert').text('Valor não pode ser apagado.').css("display", "inline").fadeOut(5000);
            $('#desconto').val('');
            $('#resultado').val('');
            $("#total-venda").val(valorBackup);
            $("#desconto").focus();

        } else if (Number($("#desconto").val().replace(',','.')) >= 0) {
            $('#resultado').val(calcDesconto(Number($("#total-venda").val()), Number($("#desconto").val().replace(',','.')), $("#tipoDesconto").val()));
            $('#resultado').val(validarDesconto(Number($('#resultado').val()), Number($("#total-venda").val())));
        } else {
            $('#errorAlert').text('Erro desconhecido.').css("display", "inline").fadeOut(5000);
            $('#desconto').val('');
            $('#resultado').val('');
        }
    });
    $('#tipoDesconto').on('change', function() {
        if (Number($("#desconto").val().replace(',','.')) >= 0) {
            $('#resultado').val(calcDesconto(Number($("#total-venda").val()), Number($("#desconto").val().replace(',','.')), $("#tipoDesconto").val()));
            $('#resultado').val(validarDesconto(Number($('#resultado').val()), Number($("#total-venda").val())));
        }
    });

    $("#total-venda").focusout(function() {
        $("#total-venda").val(valorBackup);
        if ($("#total-venda").val() == '0.00' && $('#resultado').val() != '') {
            $('#errorAlert').text('Você não pode apagar o valor.').css("display", "inline").fadeOut(6000);
            $('#resultado').val('');
            $("#total-venda").val(valorBackup);
            $('#resultado').val(calcDesconto(Number($("#total-venda").val()), Number($("#desconto").val().replace(',','.')), $("#tipoDesconto").val()));
            $('#resultado').val(validarDesconto(Number($('#resultado').val()), Number($("#total-venda").val())));
            $("#desconto").focus();
        } else {
            $('#resultado').val(calcDesconto(Number($("#total-venda").val()), Number($("#desconto").val().replace(',','.')), $("#tipoDesconto").val()));
            $('#resultado').val(validarDesconto(Number($('#resultado').val()), Number($("#total-venda").val())));
        }
    });

    $('#resultado').focusout(function() {
        if (Number($('#resultado').val()) > Number($("#total-venda").val())) {
            $('#errorAlert').text('Desconto não pode ser maior que o Valor.').css("display", "inline").fadeOut(6000);
            $('#resultado').val('');
        }
        if ($("#desconto").val() != "" || $("#desconto").val() != null) {
            $('#resultado').val(calcDesconto(Number($("#total-venda").val()), Number($("#desconto").val().replace(',','.')), $("#tipoDesconto").val()));
            $('#resultado').val(validarDesconto(Number($('#resultado').val()), Number($("#total-venda").val())));
        }
    });

    // --- Desconto de Serviços ---
    var valorBackupServico = $("#total-servicos").val();

    $("#descontoServico").keyup(function() {
        this.value = this.value.replace(/[^0-9.,]/g, '').replace(',', '.');
        var total = parseFloat($("#total-servicos").val()) || 0;
        var desc = parseFloat($(this).val()) || 0;
        var tipo = $("#tipoDescontoServico").val();
        var resultado = calcDesconto(total, desc, tipo);
        $("#resultadoServico").val(resultado > 0 ? resultado.toFixed(2) : '');
    });

    $("#tipoDescontoServico").on("change", function() {
        var total = parseFloat($("#total-servicos").val()) || 0;
        var desc = parseFloat($("#descontoServico").val().replace(',', '.')) || 0;
        var resultado = calcDesconto(total, desc, $(this).val());
        $("#resultadoServico").val(resultado > 0 ? resultado.toFixed(2) : '');
    });

    $("#btnAdicionarDescontoServico").on("click", function(e) {
        e.preventDefault();
        var total = parseFloat($("#total-servicos").val()) || 0;
        var desc = parseFloat($("#descontoServico").val().replace(',', '.')) || 0;
        var tipo = $("#tipoDescontoServico").val();
        var idVendas = $("#idVendasDescServico").val();
        if (desc <= 0) { alert('Informe um valor de desconto.'); return; }
        var resultado = calcDesconto(total, desc, tipo);
        if (resultado < 0) { alert('Desconto não pode ser maior que o total.'); return; }
        $.ajax({
            url: "<?php echo base_url(); ?>index.php/vendas/adicionarDescontoServico",
            type: 'POST',
            data: { idVendas: idVendas, desconto: desc, tipoDesconto: tipo, valor_desconto: resultado },
            dataType: 'json',
            success: function(data) {
                if (data.result) {
                    window.location.reload(true);
                } else {
                    alert('Erro ao aplicar desconto: ' + (data.messages || ''));
                }
            }
        });
    });

    $(document).ready(function() {

        function toggleCamposVenda() {
            var status = $("#status").val();
            var statusFinalizado = ['Finalizado', 'Cancelado', 'Aprovado'];
            if (statusFinalizado.indexOf(status) !== -1) {
                // Status finalizado: bloqueia observacoes internas (Resultado)
                $("#observacoes").prop("readonly", true).css("background", "#f5f5f5");
                $("#observacoes_cliente").prop("readonly", false).css("background", "");
            } else {
                // Status em andamento (Negociação, Agendado, etc): bloqueia observacoes_cliente
                $("#observacoes").prop("readonly", false).css("background", "");
                $("#observacoes_cliente").prop("readonly", true).css("background", "#f5f5f5");
            }
        }
        toggleCamposVenda();
        $("#status").on("change", toggleCamposVenda);

        $(".money").maskMoney();
        $('#recebido').click(function(event) {
            var flag = $(this).is(':checked');
            if (flag == true) {
                $('#divRecebimento').show();
            } else {
                $('#divRecebimento').hide();
            }
        });
        $(document).on('click', '#btn-faturar', function(event) {
            event.preventDefault();
            valor = $('#total-venda').val();
            valor_desconto = $('#total-desconto').val();
            valor_desconto != 0.00 || valor_desconto ? $('#valor').attr('readonly', false) : $('#faturar-desconto').attr('readonly', false);
            valor = valor.replace(',', '');
            $('#valor').val(valor);
        });
        $('#formDesconto').submit(function(e) {
            e.preventDefault();
            var form = $(this);
            $("#divProdutos").html("<div class='progress progress-info progress-striped active'><div class='bar' style='width: 100%'></div></div>");
            $.ajax({
                url: form.attr('action'),
                type: form.attr('method'),
                data: form.serialize(),
                beforeSend: function() {
                    Swal.fire({
                        title: 'Processando',
                        text: 'Registrando desconto...',
                        icon: 'info',
                        showCloseButton: false,
                        showConfirmButton: false,
                        allowOutsideClick: false,
                        allowEscapeKey: false
                    });
                },
                success: function(response) {
                    if (response.result) {
                        Swal.fire({
                            type: "success",
                            title: "Sucesso",
                            text: response.messages
                        });
                        $("#divProdutos").load("<?php echo current_url(); ?> #divProdutos");
                        $("#desconto").val("");
                        $("#resultado").val("");
                        /*setTimeout(function() {
                            window.location.href = window.BaseUrl + 'index.php/vendas/editar/' + <?php echo $result->idVendas ?>;
                        }, 2000);*/
                    } else {
                        Swal.fire({
                            type: "error",
                            title: "Atenção",
                            text: response.messages
                        });
                        $("#divProdutos").load("<?php echo current_url(); ?> #divProdutos");
                        $("#desconto").val("");
                        $("#resultado").val("");
                    }

                },
                error: function(response) {
                    Swal.fire({
                        type: "error",
                        title: "Atenção",
                        text: response.responseJSON.messages
                    });
                    $("#divProdutos").load("<?php echo current_url(); ?> #divProdutos");
                    $("#desconto").val("");
                    $("#resultado").val("");
                }
            });
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
            submitHandler: function(form) {
                var dados = $(form).serialize();
                var qtdProdutos = $('#tblProdutos >tbody >tr').length;

                $('#btn-cancelar-faturar').trigger('click');

                if (qtdProdutos <= 0) {
                    Swal.fire({
                        type: "error",
                        title: "Atenção",
                        text: "Não é possível faturar uma venda sem produtos"
                    });
                } else if (qtdProdutos > 0) {
                    $.ajax({
                        type: "POST",
                        url: "<?php echo base_url(); ?>index.php/vendas/faturar",
                        data: dados,
                        dataType: 'json',
                        success: function(data) {
                            if (data.result == true) {
                                window.location.reload(true);
                            } else {
                                Swal.fire({
                                    type: "error",
                                    title: "Atenção",
                                    text: "Ocorreu um erro ao tentar faturar venda."
                                });
                                $('#progress-fatura').hide();
                            }
                        }
                    });

                    return false;
                }
            }
        });
        // Calcula Total c/ Margem no formulário
        function parseMargem(val) {
            return parseFloat((val || "0").toString().replace(",", ".")) || 0;
        }
        function calcTotalMargem() {
            var preco = parseFloat($("#preco").val()) || 0;
            var qtd = parseFloat($("#quantidade").val()) || 0;
            var margem = parseMargem($("#margem").val());
            var desconto = parseFloat($("#desconto").val()) || 0;
            var tipoDesconto = $("#tipoDesconto").val();
            var total = preco * qtd * (1 + margem / 100);
            if (desconto > 0) {
                if (tipoDesconto === 'porcento') {
                    total = total - (total * desconto / 100);
                } else {
                    total = total - desconto;
                }
            }
            if (total < 0) total = 0;
            $("#totalMargem").val("R$: " + total.toFixed(2));
        }
        $("#preco, #quantidade, #margem, #desconto, #tipoDesconto").on("input change", calcTotalMargem);

        // Preview total no formulário de serviços
        function calcTotalMargemServico() {
            var preco = parseFloat($("#precoServico").val()) || 0;
            var qtd = parseFloat($("#quantidadeServico").val()) || 0;
            var margem = parseFloat($("#margemServico").val().replace(',', '.')) || 0;
            var total = preco * qtd * (1 + margem / 100);
            $("#totalMargemServico").val(total > 0 ? "R$: " + total.toFixed(2) : "");
        }
        $("#precoServico, #quantidadeServico, #margemServico").on("input change", calcTotalMargemServico);

        // Editar margem inline na tabela de produtos
        $(document).on("keydown", ".margem-inline", function(e) {
            if (e.key === "Enter" || e.keyCode === 9) {
                $(this).trigger("blur");
            }
        });
        $(document).on("change blur", ".margem-inline", function() {
            var $input = $(this);
            var idItem = $input.data("iditem");
            var preco = parseFloat($input.data("preco")) || 0;
            var qtd = parseFloat($input.data("qtd")) || 1;
            var novaMargem = parseFloat(($input.val() || "0").replace(",", ".")) || 0;
            var novoSubtotal = preco * qtd * (1 + novaMargem / 100);
            $.post("<?php echo base_url(); ?>index.php/vendas/atualizarMargem", {
                idItem: idItem,
                margem: novaMargem,
                subTotal: novoSubtotal
            }, function(data) {
                if (data.result) {
                    $("#subtotal-" + idItem).text("R$: " + novoSubtotal.toFixed(2));
                    // Recalcular total somando todos os subtotais
                    var novoTotal = 0;
                    $("[id^='subtotal-']").each(function() {
                        var val = parseFloat($(this).text().replace("R$: ", "").replace(",", ".")) || 0;
                        novoTotal += val;
                    });
                    $("#total-venda").val(novoTotal.toFixed(2));
                    $("#total-venda").closest("td").find("strong").text("R$: " + novoTotal.toFixed(2));
                }
            }, "json");
        });

        $("#produto").autocomplete({
            source: "<?php echo base_url(); ?>index.php/os/autoCompleteProdutoSaida",
            minLength: 2,
            select: function(event, ui) {
                $("#idProduto").val(ui.item.id);
                $("#estoque").val(ui.item.estoque);
                $("#preco").val(ui.item.preco);
                $("#quantidade").focus();
                calcTotalMargem();
            }
        });
        $("#servico").autocomplete({
            source: "<?php echo base_url(); ?>index.php/os/autoCompleteServico",
            minLength: 2,
            select: function(event, ui) {
                $("#idServico").val(ui.item.id);
                $("#precoServico").val(ui.item.preco);
                $("#quantidadeServico").focus();
            }
        });
        $("#btnAdicionarServico").on("click", function(e) {
            e.preventDefault();
            var idServico = $("#idServico").val();
            var preco = $("#precoServico").val();
            var quantidade = $("#quantidadeServico").val();
            var margem = $("#margemServico").val().replace(',', '.');
            var idVendas = $("#idVendasServico").val();
            if (!idServico || !preco || !quantidade) {
                alert('Preencha o serviço, preço e quantidade.');
                return;
            }
            $.ajax({
                url: "<?php echo base_url(); ?>index.php/vendas/adicionarServico",
                type: 'POST',
                data: { idServico: idServico, precoServico: preco, quantidadeServico: quantidade, margemServico: margem, idVendasServico: idVendas },
                dataType: 'json',
                success: function(data) {
                    if (data.result) {
                        $("#divServicos").load("<?php echo current_url(); ?> #divServicos", function() {
                            $("#servico").val('').focus();
                            $("#idServico").val('');
                            $("#precoServico").val('');
                            $("#quantidadeServico").val('1');
                            $("#margemServico").val('0');
                        });
                    } else {
                        alert('Erro ao adicionar serviço: ' + data.messages);
                    }
                }
            });
        });

        // Editar margem inline na tabela de serviços
        $(document).on("keydown", ".margem-servico-inline", function(e) {
            if (e.key === "Enter" || e.keyCode === 9) { $(this).trigger("blur"); }
        });
        $(document).on("change blur", ".margem-servico-inline", function() {
            var $input = $(this);
            var idItem = $input.data("iditem");
            var preco = parseFloat($input.data("preco")) || 0;
            var qtd = parseFloat($input.data("qtd")) || 1;
            var novaMargem = parseFloat($input.val().replace(',', '.')) || 0;
            var novoSubtotal = preco * qtd * (1 + novaMargem / 100);
            $.post("<?php echo base_url(); ?>index.php/vendas/atualizarMargemServico", {
                idItem: idItem,
                margem: novaMargem,
                subTotal: novoSubtotal
            }, function(data) {
                if (data.result) {
                    $("#subtotal-servico-" + idItem).text("R$: " + novoSubtotal.toFixed(2));
                    var novoTotal = 0;
                    $("[id^='subtotal-servico-']").each(function() {
                        var val = parseFloat($(this).text().replace("R$: ", "").replace(",", ".")) || 0;
                        novoTotal += val;
                    });
                    $("#total-servicos").val(novoTotal.toFixed(2));
                    $("#total-servicos-label").text("R$: " + novoTotal.toFixed(2));
                }
            }, 'json');
        });
        $(document).on("click", ".btn-excluir-servico", function(e) {
            e.preventDefault();
            var idAcao = $(this).attr('idAcao');
            if (!confirm('Deseja excluir este serviço?')) return;
            $.ajax({
                url: "<?php echo base_url(); ?>index.php/vendas/excluirServico",
                type: 'POST',
                data: { idServicos_venda: idAcao },
                dataType: 'json',
                success: function(data) {
                    if (data.result) {
                        $("#divServicos").load("<?php echo current_url(); ?> #divServicos");
                    }
                }
            });
        });
        $("#cliente").autocomplete({
            source: "<?php echo base_url(); ?>index.php/os/autoCompleteCliente",
            minLength: 2,
            select: function(event, ui) {
                $("#clientes_id").val(ui.item.id);
                $.get("<?php echo base_url(); ?>index.php/vendas/getUltimoResultadoVisita", { clientes_id: ui.item.id }, function(resp) {
                    if (resp && resp.resultado) {
                        $('#observacoes').trumbowyg('html', resp.resultado);
                    }
                }, 'json');
            }
        });
        $("#tecnico").autocomplete({
            source: "<?php echo base_url(); ?>index.php/vendas/autoCompleteVendedor",
            minLength: 2,
            select: function(event, ui) {
                if (ui.item.source_type === 'usuario') {
                    $("#usuarios_id").val(ui.item.id);
                }
            }
        });
        $("#formVendas").validate({
            rules: {
                cliente: {
                    required: true
                },
                tecnico: {
                    required: true
                },
                dataVenda: {
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
                dataVenda: {
                    required: 'Campo Requerido.'
                }
            },
            errorClass: "help-inline",
            errorElement: "span",
            highlight: function(element, errorClass, validClass) {
                $(element).parents('.control-group').addClass('error');
            },
            unhighlight: function(element, errorClass, validClass) {
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
                    required: 'Insira o preço'
                },
                quantidade: {
                    required: 'Insira a quantidade'
                }
            },
            submitHandler: function(form) {
                var quantidade = parseInt($("#quantidade").val());
                var estoque = parseInt($("#estoque").val());

                <?php if (!$configuration['control_estoque']) {
                    echo 'estoque = 1000000';
                }; ?>

                if (estoque < quantidade) {
                    Swal.fire({
                        type: "warning",
                        title: "Atenção",
                        text: "Você não possui estoque suficiente."
                    });
                } else {
                    var dados = $(form).serialize();
                    $("#divProdutos").html("<div class='progress progress-info progress-striped active'><div class='bar' style='width: 100%'></div></div>");
                    $.ajax({
                        type: "POST",
                        url: "<?php echo base_url(); ?>index.php/vendas/adicionarProduto",
                        data: dados,
                        dataType: 'json',
                        success: function(data) {
                            if (data.result == true) {
                                $("#divProdutos").load("<?php echo current_url(); ?> #divProdutos");
                                $("#quantidade").val('');
                                $("#preco").val('');
                                $("#produto").val('').focus();
                                $("#resultado").val("");
                                $("#desconto").val("");
                            } else {
                                Swal.fire({
                                    type: "error",
                                    title: "Atenção",
                                    html: "Ocorreu um erro ao tentar adicionar produto. <br /><br />Error: " + data.messages
                                });
                                $("#divProdutos").load("<?php echo current_url(); ?> #divProdutos");
                                $('#formProdutos')[0].reset();
                            }
                        }
                    });
                    return false;
                }
            }
        });
        $(document).on('click', 'a', function(event) {
            var idProduto = $(this).attr('idAcao');
            var quantidade = $(this).attr('quantAcao');
            var produto = $(this).attr('prodAcao');
            if ((idProduto % 1) == 0) {
                $("#divProdutos").html("<div class='progress progress-info progress-striped active'><div class='bar' style='width: 100%'></div></div>");
                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url(); ?>index.php/vendas/excluirProduto",
                    data: "idProduto=" + idProduto + "&idVendas=" + <?= $result->idVendas ?> + "&quantidade=" + quantidade + "&produto=" + produto,
                    dataType: 'json',
                    success: function(data) {
                        if (data.result == true) {
                            $("#divProdutos").load("<?php echo current_url(); ?> #divProdutos");
                            $("#resultado").val("");
                            $("#desconto").val("");
                        } else {
                            Swal.fire({
                                type: "error",
                                title: "Atenção",
                                html: "Ocorreu um erro ao tentar excluir produto." + data.messages
                            });
                            $("#divProdutos").load("<?php echo current_url(); ?> #divProdutos");
                        }
                    }
                });
                return false;
            }
        });
        $(".datepicker").datepicker({
            dateFormat: 'dd/mm/yy'
        });
        $('.editor').trumbowyg({
            lang: 'pt_br',
            semantic: { 'strikethrough': 's', }
        });
    });

    // Recarrega aba Total ao clicar nela (garante valores atualizados)
    $('#tabTotal a').on('click', function() {
        $('#tab4').load('<?php echo current_url(); ?> #tab4 > *', function() {
            initCondicoesPagamento();
        });
    });

    // Condições de Pagamento — encapsulado para re-executar após reload da aba
    function initCondicoesPagamento() {
        var condTotalGeral = parseFloat($('#condTotalGeralVal').val()) || 0;

        function pctParaReais(pct) { return condTotalGeral * pct / 100; }

        function atualizarLabelAceite() {
            var pct = parseFloat($('#condAceitePct').val()) || 0;
            $('#condAceiteLabel').text(pct > 0 ? 'R$ ' + pctParaReais(pct).toFixed(2).replace('.',',') : '');
        }
        function atualizarLabelEntrega() {
            var pct = parseFloat($('#condEntregaServPct').val()) || 0;
            $('#condEntregaServLabel').text(pct > 0 ? 'R$ ' + pctParaReais(pct).toFixed(2).replace('.',',') : '');
        }
        function atualizarLabelPix() {
            var pct = parseFloat($('#condPixPct').val()) || 0;
            if (pct > 0) {
                var v = condTotalGeral * (1 - pct / 100);
                $('#condPixLabel').text('R$ ' + v.toFixed(2).replace('.',',') + ' (desc. ' + pct + '%)');
            } else { $('#condPixLabel').text(''); }
        }
        function atualizarParcelaLabel() {
            var total = parseFloat($('#condParcelado').val().replace(',','.')) || 0;
            var parcelas = parseInt($('#condParcelas').val()) || 0;
            $('#condParceladoLabel').text(total > 0 && parcelas > 0
                ? '→ ' + parcelas + 'x de R$ ' + (total/parcelas).toFixed(2).replace('.',',') : '');
        }

        function pctRestante(excluirId) {
            var soma = 0;
            [['condAceitePct','#chkAceite'],['condEntregaServPct','#chkEntregaServ'],['condPixPct','#chkPix']].forEach(function(pair) {
                if (pair[0] !== excluirId && $(pair[1]).is(':checked')) soma += parseFloat($('#'+pair[0]).val()) || 0;
            });
            if ($('#chkParcelado').is(':checked') && condTotalGeral > 0)
                soma += (parseFloat($('#condParcelado').val().replace(',','.')) || 0) / condTotalGeral * 100;
            var resto = 100 - soma;
            return resto > 0 ? parseFloat(resto.toFixed(2)) : 0;
        }

        // Remove listeners antigos antes de re-registrar
        $('#chkAceite,#chkEntregaServ,#chkPix,#chkParcelado').off('change.cond');
        $('#condAceitePct,#condEntregaServPct,#condPixPct,#condParcelado,#condParcelas').off('input.cond change.cond');
        $('#btnSalvarCondicoes').off('click.cond');

        $('#chkAceite').on('change.cond', function() {
            if ($(this).is(':checked')) { $('#wrapAceite').css('visibility','visible'); $('#condAceitePct').val(pctRestante('condAceitePct')); atualizarLabelAceite(); }
            else { $('#wrapAceite').css('visibility','hidden'); $('#condAceitePct').val(''); $('#condAceiteLabel').text(''); }
        });
        $('#chkEntregaServ').on('change.cond', function() {
            if ($(this).is(':checked')) { $('#wrapEntregaServ').css('visibility','visible'); $('#condEntregaServPct').val(pctRestante('condEntregaServPct')); atualizarLabelEntrega(); }
            else { $('#wrapEntregaServ').css('visibility','hidden'); $('#condEntregaServPct').val(''); $('#condEntregaServLabel').text(''); }
        });
        $('#chkPix').on('change.cond', function() {
            if ($(this).is(':checked')) { $('#wrapPix').css('visibility','visible'); $('#condPixPct').val(pctRestante('condPixPct')); atualizarLabelPix(); }
            else { $('#wrapPix').css('visibility','hidden'); $('#condPixPct').val(''); $('#condPixLabel').text(''); }
        });
        $('#chkParcelado').on('change.cond', function() {
            if ($(this).is(':checked')) { $('#wrapParcelado').css('visibility','visible'); }
            else { $('#wrapParcelado').css('visibility','hidden'); $('#condParcelado').val(''); atualizarParcelaLabel(); }
        });

        $('#condAceitePct').on('input.cond', atualizarLabelAceite);
        $('#condEntregaServPct').on('input.cond', atualizarLabelEntrega);
        $('#condPixPct').on('input.cond', atualizarLabelPix);
        $('#condParcelado,#condParcelas').on('input.cond change.cond', atualizarParcelaLabel);

        // Inicializa labels para valores já salvos
        atualizarLabelAceite();
        atualizarLabelEntrega();
        atualizarLabelPix();
        atualizarParcelaLabel();

        $('#btnSalvarCondicoes').on('click.cond', function() {
            var idVendas = $('#condVendasId').val();
            var pctAceite    = $('#chkAceite').is(':checked') ? (parseFloat($('#condAceitePct').val()) || 0) : 0;
            var pctEntrega   = $('#chkEntregaServ').is(':checked') ? (parseFloat($('#condEntregaServPct').val()) || 0) : 0;
            var pctPix       = $('#chkPix').is(':checked') ? (parseFloat($('#condPixPct').val()) || 0) : 0;
            var valParcelado = $('#chkParcelado').is(':checked') ? (parseFloat($('#condParcelado').val().replace(',','.')) || 0) : 0;
            $.ajax({
                url: '<?php echo base_url(); ?>index.php/vendas/salvarCondicoesPagamento',
                type: 'POST',
                data: {
                    idVendas: idVendas,
                    cond_aceite_pct: pctAceite,         cond_aceite: pctParaReais(pctAceite),
                    cond_entrega_serv_pct: pctEntrega,  cond_entrega_serv: pctParaReais(pctEntrega),
                    cond_pix_pct: pctPix,               cond_pix: condTotalGeral * (1 - pctPix/100) * (pctPix > 0 ? 1 : 0),
                    cond_parcelado: valParcelado,
                    cond_parcelas: parseInt($('#condParcelas').val()) || 0
                },
                dataType: 'json',
                success: function(resp) {
                    if (resp.result) { $('#condMsg').fadeIn().delay(2000).fadeOut(); }
                    else { alert('Erro ao salvar: ' + (resp.messages || '')); }
                }
            });
        });
    }
    initCondicoesPagamento();
</script>
