<style>
    @media (max-width: 768px) {
        .vt-table-wrap { overflow-x: auto; -webkit-overflow-scrolling: touch; }
        .vt-table-wrap table { min-width: 520px; }
    }
</style>
<div class="new122">
    <div class="widget-title" style="margin:-15px -10px 0">
        <h5>Visitas Técnicas</h5>
    </div>
    <div style="margin-bottom:10px;margin-top:10px;">
        <a href="<?php echo site_url('visita_tecnica/adicionar') ?>" class="button btn btn-mini btn-success" style="max-width:160px">
            <span class="button__icon"><i class='bx bx-plus-circle'></i></span>
            <span class="button__text2">Nova Visita</span>
        </a>
    </div>
    <div class="widget-box">
        <div class="widget-content nopadding vt-table-wrap">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Cliente</th>
                            <th>Técnico</th>
                            <th>Data</th>
                            <th>Horário</th>
                            <th>Status</th>
                            <th>Tipo de Serviço</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($results): foreach ($results as $r): ?>
                        <tr>
                            <td><?php echo $r->id ?></td>
                            <td><?php echo $r->nomeCliente ?></td>
                            <td><?php echo $r->tecnico ?></td>
                            <td><?php echo date('d/m/Y', strtotime($r->data_visita)) ?></td>
                            <td><?php echo $r->hora_visita ? substr($r->hora_visita, 0, 5) : '-' ?></td>
                            <td>
                                <?php
                                $cores = [
                                    'Agendada'   => 'info',
                                    'Realizada'  => 'success',
                                    'Cancelada'  => 'danger',
                                    'Reagendada' => 'warning',
                                ];
                                $cor = $cores[$r->status] ?? 'default';
                                ?>
                                <span class="label label-<?php echo $cor ?>"><?php echo $r->status ?></span>
                            </td>
                            <td><?php echo $r->tipo_servico ?></td>
                            <td>
                                <a href="<?php echo site_url('visita_tecnica/editar/' . $r->id) ?>" class="btn btn-mini btn-primary" title="Editar">
                                    <i class="bx bx-edit"></i>
                                </a>
                                <form method="post" action="<?php echo site_url('visita_tecnica/excluir') ?>" style="display:inline" onsubmit="return confirm('Excluir esta visita?')">
                                    <input type="hidden" name="id" value="<?php echo $r->id ?>">
                                    <button type="submit" class="btn btn-mini btn-danger" title="Excluir">
                                        <i class="bx bx-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; else: ?>
                        <tr><td colspan="8" style="text-align:center">Nenhuma visita cadastrada.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
                <?php echo $this->pagination->create_links(); ?>
        </div>
    </div>
</div>
