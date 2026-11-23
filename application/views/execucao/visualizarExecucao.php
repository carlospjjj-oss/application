<div class="new122">
    <div class="widget-title" style="margin:-20px 0 0">
        <span class="icon"><i class='bx bx-plug'></i></span>
        <h5>Visualizar Execução</h5>
    </div>

    <!-- Botões -->
    <div style="margin:10px 0; display:flex; gap:8px; flex-wrap:wrap;">
        <a href="javascript:window.print()" class="button btn btn-mini btn-inverse">
            <span class="button__icon"><i class='bx bx-printer'></i></span><span class="button__text2">Imprimir</span>
        </a>
        <a href="<?= base_url() ?>index.php/execucao/editar/<?= $result->os_id ?>" class="button btn btn-mini btn-primary">
            <span class="button__icon"><i class='bx bx-edit'></i></span><span class="button__text2">Editar</span>
        </a>
        <a href="<?= base_url() ?>index.php/execucao" class="button btn btn-mini btn-warning">
            <span class="button__icon"><i class='bx bx-undo'></i></span><span class="button__text2">Voltar</span>
        </a>
    </div>

    <!-- Cabeçalho -->
    <div style="background:#2c3e50; color:#fff; padding:16px 20px; border-radius:6px 6px 0 0; display:flex; justify-content:space-between; align-items:center; margin-bottom:0;">
        <div>
            <div style="font-size:18px; font-weight:700;">N° OS Execução: <?= $result->os_id ?></div>
            <div style="font-size:11px; opacity:.7; margin-top:3px;">
                Última atualização: <?= date('d/m/Y', strtotime($result->updated_at)) ?>
            </div>
        </div>
        <?php
        $corStatus = match($result->status) {
            'Aberto'       => '#337ab7',
            'Em Andamento' => '#f0ad4e',
            'Executado'    => '#8e44ad',
            'Finalizado'   => '#5cb85c',
            'Cancelado'    => '#d9534f',
            default        => '#aaa',
        };
        ?>
        <span style="background:<?= $corStatus ?>; padding:5px 14px; border-radius:20px; font-size:12px; font-weight:700;">
            <?= $result->status ?>
        </span>
    </div>

    <div style="border:1px solid #ddd; border-top:none; border-radius:0 0 6px 6px; padding:20px;">

        <!-- Dados gerais -->
        <div style="margin-bottom:20px;">
            <div style="font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:.06em; color:#888; border-bottom:1px solid #eee; padding-bottom:6px; margin-bottom:12px;">
                Dados da Execução
            </div>
            <div style="display:grid; grid-template-columns:repeat(auto-fill,minmax(180px,1fr)); gap:14px;">
                <div><div style="font-size:11px;color:#999;">Cliente</div><strong><?= $result->nomeCliente ?></strong></div>
                <div><div style="font-size:11px;color:#999;">Técnico Responsável</div><strong><?= $result->tecnico ?></strong></div>
                <?php if (!empty($result->engenheiro)): ?>
                <div><div style="font-size:11px;color:#999;">Engenheiro Responsável</div><strong><?= $result->engenheiro ?></strong></div>
                <?php endif ?>
                <div><div style="font-size:11px;color:#999;">Garantia</div><strong><?= $result->garantia ?> dias</strong></div>
                <div><div style="font-size:11px;color:#999;">Data Inicial</div><strong><?= (!empty($result->dataInicial) && $result->dataInicial != '0000-00-00') ? date('d/m/Y', strtotime($result->dataInicial)) : '-' ?></strong></div>
                <div><div style="font-size:11px;color:#999;">Data Final</div><strong><?= (!empty($result->dataFinal) && $result->dataFinal != '0000-00-00') ? date('d/m/Y', strtotime($result->dataFinal)) : '-' ?></strong></div>
            </div>
        </div>

        <?php if (!empty($result->tipo_instalacao)): ?>
        <div style="margin-bottom:20px; border-top:1px solid #eee; padding-top:16px;">
            <div style="font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:.06em; color:#888; border-bottom:1px solid #eee; padding-bottom:6px; margin-bottom:12px;">
                Tipo de Instalação
            </div>
            <div style="display:grid; grid-template-columns:repeat(auto-fill,minmax(180px,1fr)); gap:14px;">
                <div style="grid-column:span 2"><div style="font-size:11px;color:#999;">Tipo</div><strong><?= $result->tipo_instalacao ?></strong></div>
                <?php if (!empty($result->data_inicio_instalacao) && $result->data_inicio_instalacao != '0000-00-00'): ?>
                <div><div style="font-size:11px;color:#999;">Início</div><strong><?= date('d/m/Y', strtotime($result->data_inicio_instalacao)) ?></strong></div>
                <?php endif ?>
                <?php if (!empty($result->data_fim_instalacao) && $result->data_fim_instalacao != '0000-00-00'): ?>
                <div><div style="font-size:11px;color:#999;">Fim</div><strong><?= date('d/m/Y', strtotime($result->data_fim_instalacao)) ?></strong></div>
                <?php endif ?>
            </div>
        </div>
        <?php endif ?>

        <!-- Produtos -->
        <?php if ($produtos): $totalP = 0; ?>
        <div style="margin-bottom:20px; border-top:1px solid #eee; padding-top:16px;">
            <div style="font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:.06em; color:#888; border-bottom:1px solid #eee; padding-bottom:6px; margin-bottom:12px;">Produtos</div>
            <table class="table table-bordered">
                <thead><tr><th>Produto</th><th style="text-align:center">Qtd</th><th>Preço unit.</th><th>Sub-total</th></tr></thead>
                <tbody>
                    <?php foreach ($produtos as $p): $sub = $p->quantidade * $p->preco; $totalP += $sub; ?>
                    <tr>
                        <td><?= $p->descricao ?></td>
                        <td style="text-align:center"><?= $p->quantidade ?></td>
                        <td>R$: <?= number_format($p->preco, 2, ',', '.') ?></td>
                        <td>R$: <?= number_format($sub, 2, ',', '.') ?></td>
                    </tr>
                    <?php endforeach ?>
                </tbody>
                <tfoot><tr><td colspan="3" style="text-align:right;font-weight:700">Total Produtos:</td><td style="font-weight:700">R$: <?= number_format($totalP, 2, ',', '.') ?></td></tr></tfoot>
            </table>
        </div>
        <?php endif ?>

        <!-- Serviços -->
        <?php if ($servicos): $totalS = 0; ?>
        <div style="margin-bottom:20px; border-top:1px solid #eee; padding-top:16px;">
            <div style="font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:.06em; color:#888; border-bottom:1px solid #eee; padding-bottom:6px; margin-bottom:12px;">Serviços</div>
            <table class="table table-bordered">
                <thead><tr><th>Serviço</th><th style="text-align:center">Qtd</th><th>Preço</th><th>Sub-total</th></tr></thead>
                <tbody>
                    <?php foreach ($servicos as $s): $sub = $s->quantidade * $s->preco; $totalS += $sub; ?>
                    <tr>
                        <td><?= $s->nome ?></td>
                        <td style="text-align:center"><?= $s->quantidade ?></td>
                        <td>R$: <?= number_format($s->preco, 2, ',', '.') ?></td>
                        <td>R$: <?= number_format($sub, 2, ',', '.') ?></td>
                    </tr>
                    <?php endforeach ?>
                </tbody>
                <tfoot><tr><td colspan="3" style="text-align:right;font-weight:700">Total Serviços:</td><td style="font-weight:700">R$: <?= number_format($totalS, 2, ',', '.') ?></td></tr></tfoot>
            </table>
        </div>
        <?php endif ?>

        <!-- Anotações -->
        <?php if ($anotacoes): ?>
        <div style="margin-bottom:20px; border-top:1px solid #eee; padding-top:16px;">
            <div style="font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:.06em; color:#888; border-bottom:1px solid #eee; padding-bottom:6px; margin-bottom:12px;">Anotações</div>
            <?php foreach ($anotacoes as $a): ?>
            <div style="background:#fafafa; border-left:3px solid #337ab7; padding:10px 14px; margin-bottom:8px; border-radius:0 5px 5px 0;">
                <div style="font-size:11px; color:#999; margin-bottom:4px;"><?= date('d/m/Y H:i:s', strtotime($a->data_hora)) ?></div>
                <div><?= $a->anotacao ?></div>
            </div>
            <?php endforeach ?>
        </div>
        <?php endif ?>

        <!-- Checklist -->
        <?php
        $checklistInstalacao = [
            'chk_quadro'     => 'Verificação do quadro elétrico',
            'chk_disjuntor'  => 'Instalação do disjuntor dedicado',
            'chk_cabos'      => 'Passagem de cabos e eletrodutos',
            'chk_fixacao'    => 'Fixação e cabeamento do carregador',
            'chk_teste'      => 'Teste de funcionamento e carga',
            'chk_orientacao' => 'Orientação ao cliente sobre uso',
        ];
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
        <div style="border-top:1px solid #eee; padding-top:16px;">
            <div style="font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:.06em; color:#888; border-bottom:1px solid #eee; padding-bottom:6px; margin-bottom:12px;">Checklist — Instalação Elétrica</div>
            <div style="display:grid; grid-template-columns:repeat(auto-fill,minmax(220px,1fr)); gap:7px; margin-bottom:20px;">
                <?php foreach ($checklistInstalacao as $field => $label):
                    $done = !empty($result->$field);
                    $style = $done ? 'background:#e8f8f0; color:#27ae60;' : 'background:#f9f9f9; color:#aaa;';
                ?>
                <div style="display:flex; align-items:center; gap:8px; padding:7px 10px; border-radius:5px; font-size:12px; <?= $style ?>">
                    <span style="font-size:15px;"><?= $done ? '✅' : '⬜' ?></span> <?= $label ?>
                </div>
                <?php endforeach ?>
            </div>

            <div style="font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:.06em; color:#888; border-bottom:1px solid #eee; padding-bottom:6px; margin-bottom:12px;">Checklist — Documentação entregue ao cliente</div>
            <div style="display:grid; grid-template-columns:repeat(auto-fill,minmax(220px,1fr)); gap:7px;">
                <?php foreach ($checklistDocs as $field => $label):
                    $done = !empty($result->$field);
                    $style = $done ? 'background:#e8f8f0; color:#27ae60;' : 'background:#f9f9f9; color:#aaa;';
                ?>
                <div style="display:flex; align-items:center; gap:8px; padding:7px 10px; border-radius:5px; font-size:12px; <?= $style ?>">
                    <span style="font-size:15px;"><?= $done ? '✅' : '⬜' ?></span> <?= $label ?>
                </div>
                <?php endforeach ?>
            </div>
        </div>

    </div>
</div>
