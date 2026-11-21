<style>
  select {
    width: 70px;
  }
</style>
<div class="new122" style="margin-top: 0; min-height: 100vh">
<div class="widget-title" style="margin: -20px 0 0">
        <span class="icon">
            <i class="fas fa-clock"></i>
        </span>
        <h5>Logs</h5>
</div>
  <div style="display:flex;gap:8px;align-items:center;flex-wrap:wrap;margin-bottom:8px;">
    <a href="#modal-excluir" role="button" data-toggle="modal" class="button btn btn-danger tip-top" style="max-width: 250px" title="Excluir Logs">
      <span class="button__icon"><i class='bx bx-trash'></i></span> <span class="button__text2">Remover Logs - 30 dias ou mais</span>
    </a>
    <form method="get" action="<?= site_url('auditoria') ?>" style="display:flex;gap:6px;align-items:center;">
      <select name="usuario" style="width:200px;">
        <option value="">Todos os usuários</option>
        <?php foreach ($usuarios as $u): ?>
          <option value="<?= htmlspecialchars($u->usuario) ?>" <?= ($this->input->get('usuario') == $u->usuario ? 'selected' : '') ?>><?= htmlspecialchars($u->usuario) ?></option>
        <?php endforeach; ?>
      </select>
      <button class="button btn btn-mini btn-warning"><span class="button__icon"><i class='bx bx-search-alt'></i></span></button>
      <?php if ($this->input->get('usuario')): ?>
        <a href="<?= site_url('auditoria') ?>" class="btn btn-mini">Limpar</a>
      <?php endif; ?>
    </form>
  </div>

<div class="widget-box">
    <h5 style="padding: 3px 0"></h5>
    <div class="widget-content nopadding tab-content">
        <table id="tabela" class="table table-bordered ">
            <thead>
                <tr>
                    <th>Usuário</th>
                    <th>Data</th>
                    <th>Hora</th>
                    <th>IP</th>
                    <th>Tarefa</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($results as $r) {
                    echo '<tr>';
                    echo '<td>' . $r->usuario . '</td>';
                    echo '<td>' . date('d/m/Y', strtotime($r->data)) . '</td>';
                    echo '<td>' . $r->hora . '</td>';
                    echo '<td>' . $r->ip . '</td>';
                    echo '<td>' . $r->tarefa . '</td>';
                    echo '</tr>';
                } ?>
                <?php if (!$results) { ?>
                    <tr>
                        <td colspan="5">Nenhum registro encontrado.</td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>
<?php echo $this->pagination->create_links(); ?>

<!-- Modal -->
<div id="modal-excluir" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <form action="<?php echo site_url('auditoria/clean') ?>" method="post">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h5>Limpeza de Logs</h5>
        </div>
        <div class="modal-body">
            <h5 style="text-align: center">Deseja realmente remover os logs mais antigos?</h5>
        </div>
        <div class="modal-footer">
            <button class="btn" data-dismiss="modal" aria-hidden="true">Cancelar</button>
            <button class="btn btn-danger">Excluir</button>
        </div>
    </form>
</div>
</div>
