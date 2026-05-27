<div class="row-fluid" style="margin-top:0">
    <div class="span12">
        <div class="widget-box">
            <div class="widget-title" style="margin: -20px 0 0">
                <span class="icon"><i class="fas fa-calendar-check"></i></span>
                <h5>Visitas Técnicas</h5>
            </div>
            <div class="widget-content nopadding">
                <div style="padding:10px;">
                    <a href="<?php echo site_url('visita_tecnica/adicionar') ?>" class="button btn btn-mini btn-success">
                        <span class="button__icon"><i class='bx bx-plus-circle'></i></span>
                        <span class="button__text2">Nova Visita</span>
                    </a>
                </div>
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
</div>
