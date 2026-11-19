<?php
$totalProdutos = 0;
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <title><?= $this->config->item('app_name') ?> - Orçamento da Venda Nº#<?= $result->idVendas ?> - <?= $result->nomeCliente ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="<?= base_url() ?>assets/css/bootstrap5.3.2.min.css" />
    <link rel="stylesheet" href="<?= base_url() ?>assets/font-awesome/css/font-awesome.css" />
    <link rel="stylesheet" href="<?= base_url() ?>assets/css/imprimir-orcamento.css">
</head>
<body>
    <div class="main-page">
        <div class="sub-page">
            <header>
                <?php if ($emitente == null) : ?>
                    <div class="alert alert-danger" role="alert">
                        Você precisa configurar os dados do emitente. >>> <a href="<?=base_url()?>index.php/mapos/emitente">Configurar</a>
                    </div>
                <?php else : ?>
                    <div style="display:flex;align-items:center;gap:10px;flex:1;min-width:0;">
                        <div class="imgLogo">
                            <img src="<?= $emitente->url_logo ?>" style="width:60px;height:60px;object-fit:contain;display:block;">
                        </div>
                        <div class="emitente">
                            <span style="font-size: 15px;"><b><?= $emitente->nome ?></b></span><br>
                            <?php if ($emitente->cnpj != "00.000.000/0000-00") : ?>
                                <span>CNPJ: <?= $emitente->cnpj ?></span><br>
                            <?php endif; ?>
                            <span>
                                <?= $emitente->rua.', '.$emitente->numero.', '.$emitente->bairro ?><br>
                                <?= $emitente->cidade.' - '.$emitente->uf.' - '.$emitente->cep ?>
                            </span>
                        </div>
                    </div>
                    <div class="contatoEmitente">
                        <span style="font-weight: bold;">Tel: <?= $emitente->telefone ?></span><br>
                        <span style="font-weight: bold;"><?= $emitente->email ?></span><br>
                    </div>
                <?php endif; ?>
            </header>
            <section>
                <div class="title">
                    ORÇAMENTO DA VENDA Nº#<?= str_pad($result->idVendas, 4, 0, STR_PAD_LEFT) ?>
                    <span class="emissao">Emissão: <?= date('d/m/Y H:i:s') ?></span>
                </div>

                <?php if ($result->dataVenda != null): ?>
                    <div class="tabela">
                        <table class="table table-bordered">
                            <thead>
                                <tr class="table-secondary">
                                    <th class="text-center">STATUS</th>
                                    <th class="text-center">DATA DA VENDA</th>
                                    <th class="text-center">VALIDADE</th>
                                    <th class="text-center">VENC. DO ORÇAMENTO</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="text-center"><?= $result->status ?></td>
                                    <td class="text-center"><?= date('d/m/Y', strtotime($result->dataVenda)) ?></td>
                                    <td class="text-center">
                                        <?php if ($result->garantia > 0): ?>
                                            <?= $result->garantia . ' dia(s)' ?>
                                        <?php else: ?>
                                            Sem garantia
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <?php if ($result->garantia > 0): ?>
                                            <?php
                                                $dataVenda = strtotime($result->dataVenda);
                                            $vencimentoGarantia = date('d/m/Y', strtotime("+{$result->garantia} days", $dataVenda));
                                            echo $vencimentoGarantia;
                                            ?>
                                        <?php else: ?>
                                            Sem garantia
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>

                <div class="subtitle">DADOS DO CLIENTE</div>
                <div class="dados">
                    <div>
                        <span><b><?= $result->nomeCliente ?></b></span><br />
                        <span>CPF/CNPJ: <?= $result->documento ?></span><br />
                        <span><?= $result->contato.' '.$result->telefone ?><?= $result->telefone && $result->celular ? ' / '.$result->celular : $result->celular ?></span><br />
                        <span><?= $result->emailCliente ?></span><br />
                    </div>
                    <div style="text-align: right;">
                        <span><?= $result->rua.', '.$result->numero.', '.$result->bairro ?></span><br />
                        <span><?= $result->complemento.' - '.$result->cidade.' - '.$result->estado ?></span><br />
                        <span>CEP: <?= $result->cep ?></span><br />
                    </div>
                </div>

                <?php if ($result->observacoes_cliente) : ?>
                    <div class="subtitle">OBSERVAÇÕES</div>
                    <div class="dados">
                        <div>
                            <?= printSafeHtml($result->observacoes_cliente) ?>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if ($produtos) : ?>
                    <div class="tabela">
                        <table class="table table-bordered">
                            <thead>
                                <tr class="table-secondary">
                                    <th>PRODUTO(S)</th>
                                    <th class="text-center" width="10%">QTD</th>
                                    <th class="text-center" width="10%">UNT</th>
                                    <th class="text-end" width="15%" >SUBTOTAL</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($produtos as $p) :
                                    $totalProdutos = $totalProdutos + $p->subTotal;
                                    echo '<tr>';
                                    echo '  <td>' . $p->descricao . '</td>';
                                    echo '  <td class="text-center">' . $p->quantidade . '</td>';
                                    echo '  <td class="text-center">' . number_format($p->preco ?: $p->precoVenda, 2, ',', '.') . '</td>';
                                    echo '  <td class="text-end">R$ ' . number_format($p->subTotal, 2, ',', '.') . '</td>';
                                    echo '</tr>';
                                endforeach; ?>
                                <tr>
                                    <td colspan="3" class="text-end"><b>TOTAL PRODUTOS:</b></td>
                                    <td class="text-end"><b>R$ <?= number_format($totalProdutos, 2, ',', '.') ?></b></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>

                <?php if ($totalProdutos != 0) : ?>
                    <div class="pagamento">
                        <div class="qrcode">
                            <?php if ($this->data['configuration']['pix_key']) : ?>
                                <div><img width="130px" src="<?= $qrCode ?>" alt="QR Code de Pagamento" /></div>
                                <div style="display: flex; flex-wrap: wrap; align-content: center;">
                                    <div style="width: 100%; text-align:center;"><i class="fas fa-camera"></i><br />Escaneie o QRCode ao lado para pagar por Pix</div>
                                    <div class="chavePix">Chave Pix: <b><?= $chaveFormatada ?></b></div>
                                </div>
                            <?php else: ?>
                                <div></div>
                                <div></div>
                            <?php endif; ?>
                        </div>
                        <div>
                            <div class="tabela">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr class="table-secondary">
                                            <th colspan="2">RESUMO DOS VALORES</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if ($result->valor_desconto != 0) : ?>
                                            <tr>
                                                <td width="65%">SUBTOTAL</td>
                                                <td>R$ <b><?= number_format($totalProdutos, 2, ',', '.') ?></b></td>
                                            </tr>
                                            <tr>
                                                <td>DESCONTO</td>
                                                <td>R$ <b><?= number_format($result->valor_desconto - $totalProdutos, 2, ',', '.') ?></b></td>
                                            </tr>
                                            <tr>
                                                <td>TOTAL</td>
                                                <td>R$ <?= number_format($result->valor_desconto, 2, ',', '.') ?></td>
                                            </tr>
                                        <?php else : ?>
                                            <tr>
                                                <td style="width:290px">TOTAL</td>
                                                <td>R$ <?= number_format($totalProdutos, 2, ',', '.') ?></td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>


                <?php
                // Calcula total final (com desconto se houver)
                $totalFinalOrc = $totalProdutos;
                if ($result->valor_desconto != 0) $totalFinalOrc = $result->valor_desconto;

                // Monta lista de condições marcadas
                $condicoes = [];
                if ($result->cond_aceite > 0)
                    $condicoes[] = ['nome' => 'Condição Especial - Pagamento no Aceite',              'valor' => $result->cond_aceite];
                if ($result->cond_entrega_serv > 0)
                    $condicoes[] = ['nome' => 'Condição Especial - Pagamento na Entrega dos Serviços','valor' => $result->cond_entrega_serv];
                if ($result->cond_pix > 0)
                    $condicoes[] = ['nome' => 'Condição Especial - Pagamento PIX',                    'valor' => $result->cond_pix];
                if ($result->cond_parcelado > 0 && $result->cond_parcelas > 0)
                    $condicoes[] = ['nome' => 'Condição Especial - Pagamento em ' . $result->cond_parcelas . 'x Sem Juros',
                                    'valor' => $result->cond_parcelado,
                                    'parcelas' => $result->cond_parcelas];
                ?>
                <?php if (!empty($condicoes)) : ?>
                    <div class="subtitle">CONDIÇÕES DE PAGAMENTO</div>
                    <div class="tabela">
                        <table class="table table-bordered">
                            <thead>
                                <tr class="table-secondary">
                                    <th>CONDIÇÃO</th>
                                    <th class="text-end" width="20%">VALOR</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($condicoes as $c) : ?>
                                <tr>
                                    <td><?= $c['nome'] ?></td>
                                    <td class="text-end">
                                        <?php if (isset($c['parcelas'])) : ?>
                                            <b><?= $c['parcelas'] ?>x de R$ <?= number_format($c['valor'] / $c['parcelas'], 2, ',', '.') ?></b>
                                            <small style="display:block; color:#666;">Total: R$ <?= number_format($c['valor'], 2, ',', '.') ?></small>
                                        <?php else : ?>
                                            <b>R$ <?= number_format($c['valor'], 2, ',', '.') ?></b>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>

            </section>
            <footer>
                <div class="detalhes">
                    <span>Data da Venda: <b><?= date('d/m/Y', strtotime($result->dataVenda)) ?></b></span>
                    <span>VENDA <b>#<?= str_pad($result->idVendas, 4, 0, STR_PAD_LEFT) ?></b></span>
                </div>
                <div class="vendedor">
                    <span>Vendedor : <b><?php echo $result->nome; ?> </b></span>
                </div>
            </footer>
        </div>
        </div>
    </div>
    <script type="text/javascript">
        window.print();
    </script>
</body>
</html>
