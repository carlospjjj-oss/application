<?php
$totais = $resumo['totais'];
$por_categoria = $resumo['por_categoria'];
$por_mes = $resumo['por_mes'];

$saldo = ($totais->total_receitas ?? 0) - ($totais->total_despesas ?? 0);
$saldo_realizado = ($totais->receitas_pagas ?? 0) - ($totais->despesas_pagas ?? 0);
?>
<style>
.fin-cards { display:flex; gap:12px; flex-wrap:wrap; margin-bottom:16px; }
.fin-card  { flex:1; min-width:160px; border-radius:8px; padding:14px 16px; color:#fff; }
.fin-card h4 { margin:0 0 4px; font-size:13px; opacity:.85; }
.fin-card .valor { font-size:22px; font-weight:700; }
.fin-card.receita  { background:#27ae60; }
.fin-card.despesa  { background:#e74c3c; }
.fin-card.saldo    { background:#2980b9; }
.fin-card.pendente { background:#f39c12; }
.fin-filtro { display:flex; gap:10px; align-items:flex-end; flex-wrap:wrap; margin-bottom:16px; }
.fin-filtro label { font-size:12px; margin-bottom:3px; display:block; }
table.fin-table th { background:#f5f5f5; }
.badge-receita { background:#27ae60; color:#fff; padding:2px 7px; border-radius:10px; font-size:11px; }
.badge-despesa { background:#e74c3c; color:#fff; padding:2px 7px; border-radius:10px; font-size:11px; }
</style>

<div class="new122">
  <div class="widget-title" style="margin:-20px 0 0">
    <span class="icon"><i class="fas fa-chart-line"></i></span>
    <h5>Dashboard Financeiro</h5>
  </div>

  <!-- Filtro de período -->
  <form method="get" action="<?= site_url('relatorios/financeiroDashboard') ?>" class="fin-filtro" style="margin-top:12px;">
    <div>
      <label>De:</label>
      <input type="date" name="dataInicial" value="<?= htmlspecialchars($dataInicial) ?>" class="span2" style="width:150px;">
    </div>
    <div>
      <label>Até:</label>
      <input type="date" name="dataFinal" value="<?= htmlspecialchars($dataFinal) ?>" class="span2" style="width:150px;">
    </div>
    <button class="button btn btn-mini btn-warning">
      <span class="button__icon"><i class='bx bx-search-alt'></i></span>
      <span class="button__text2">Filtrar</span>
    </button>
    <a href="<?= site_url('relatorios/financeiro') ?>" class="btn btn-mini btn-inverse">
      <i class='bx bx-printer'></i> Imprimir PDF/XLS
    </a>
  </form>

  <!-- Cards de totais -->
  <div class="fin-cards">
    <div class="fin-card receita">
      <h4>Total Receitas</h4>
      <div class="valor">R$ <?= number_format($totais->total_receitas ?? 0, 2, ',', '.') ?></div>
      <small>Pagas: R$ <?= number_format($totais->receitas_pagas ?? 0, 2, ',', '.') ?></small>
    </div>
    <div class="fin-card despesa">
      <h4>Total Despesas</h4>
      <div class="valor">R$ <?= number_format($totais->total_despesas ?? 0, 2, ',', '.') ?></div>
      <small>Pagas: R$ <?= number_format($totais->despesas_pagas ?? 0, 2, ',', '.') ?></small>
    </div>
    <div class="fin-card saldo" style="background:<?= $saldo >= 0 ? '#2980b9' : '#c0392b' ?>">
      <h4>Saldo do Período</h4>
      <div class="valor">R$ <?= number_format($saldo, 2, ',', '.') ?></div>
      <small>Realizado: R$ <?= number_format($saldo_realizado, 2, ',', '.') ?></small>
    </div>
    <div class="fin-card pendente">
      <h4>Pendentes</h4>
      <div class="valor">R$ <?= number_format(($totais->receitas_pendentes ?? 0) + ($totais->despesas_pendentes ?? 0), 2, ',', '.') ?></div>
      <small><?= $totais->total_lancamentos ?? 0 ?> lançamento(s) no período</small>
    </div>
  </div>

  <!-- Por mês -->
  <?php if (!empty($por_mes)): ?>
  <div class="widget-box" style="margin-bottom:16px;">
    <div class="widget-title"><span class="icon"><i class="fas fa-calendar-alt"></i></span><h5>Por Mês</h5></div>
    <div class="widget-content nopadding">
      <table class="table table-bordered fin-table">
        <thead><tr><th>Mês</th><th>Receitas</th><th>Despesas</th><th>Saldo</th></tr></thead>
        <tbody>
        <?php foreach ($por_mes as $m):
            $s = $m->receitas - $m->despesas;
            list($ano, $mes) = explode('-', $m->mes);
            $mesNome = strftime('%b/%Y', mktime(0,0,0,(int)$mes,1,(int)$ano));
        ?>
          <tr>
            <td><strong><?= htmlspecialchars($mesNome) ?></strong></td>
            <td style="color:#27ae60">R$ <?= number_format($m->receitas, 2, ',', '.') ?></td>
            <td style="color:#e74c3c">R$ <?= number_format($m->despesas, 2, ',', '.') ?></td>
            <td style="color:<?= $s >= 0 ? '#27ae60' : '#e74c3c' ?>;font-weight:700">
              R$ <?= number_format($s, 2, ',', '.') ?>
            </td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
  <?php endif; ?>

  <!-- Por categoria -->
  <?php if (!empty($por_categoria)): ?>
  <div class="widget-box">
    <div class="widget-title"><span class="icon"><i class="fas fa-tags"></i></span><h5>Por Categoria</h5></div>
    <div class="widget-content nopadding">
      <table class="table table-bordered fin-table">
        <thead><tr><th>Categoria</th><th>Tipo</th><th>Lançamentos</th><th>Total</th></tr></thead>
        <tbody>
        <?php foreach ($por_categoria as $c): ?>
          <tr>
            <td><?= htmlspecialchars($c->categoria ?: '(sem categoria)') ?></td>
            <td>
              <?php if ($c->tipo === 'receita'): ?>
                <span class="badge-receita">Receita</span>
              <?php else: ?>
                <span class="badge-despesa">Despesa</span>
              <?php endif; ?>
            </td>
            <td><?= $c->qtd ?></td>
            <td style="color:<?= $c->tipo === 'receita' ? '#27ae60' : '#e74c3c' ?>;font-weight:600">
              R$ <?= number_format($c->total, 2, ',', '.') ?>
            </td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
  <?php endif; ?>

</div>
