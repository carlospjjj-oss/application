<link rel="stylesheet" href="<?php echo base_url(); ?>assets/js/jquery-ui/css/smoothness/jquery-ui-1.9.2.custom.css" />
<script type="text/javascript" src="<?php echo base_url() ?>assets/js/jquery-ui/js/jquery-ui-1.9.2.custom.js"></script>
<div class="new122">
    <div class="widget-title" style="margin: -20px 0 0">
        <span class="icon"><i class='bx bx-plug'></i></span>
        <h5>Execução</h5>
    </div>

    <?php if ($this->permission->checkPermission($this->session->userdata('permissao'), 'eArquivo')): ?>
    <div style="margin:10px 0;">
        <a href="<?= site_url('execucao/criar') ?>" class="button btn btn-mini btn-primary">
            <span class="button__icon"><i class='bx bx-plus'></i></span><span class="button__text2">Nova Execução</span>
        </a>
    </div>
    <?php endif ?>

    <div class="span12" style="margin-left: 0">
        <form method="get" action="<?= current_url(); ?>">
        <?php
        $labelStyle  = 'display:block;margin-bottom:4px;font-size:12px;color:#555;font-weight:600;white-space:nowrap';
        $inputStyle  = 'width:100%;box-sizing:border-box;height:30px;padding:4px 6px;border:1px solid #ccc;border-radius:4px;font-size:13px';
        $selectStyle = 'width:100%;box-sizing:border-box;height:30px;border:1px solid #ccc;border-radius:4px;font-size:13px';
        ?>
        <div style="display:flex;gap:10px;flex-wrap:nowrap;align-items:flex-start;background:#f8f9fa;padding:12px 14px;border-radius:6px;border:1px solid #ddd;margin-bottom:12px;">

            <div style="flex:0 0 200px">
                <label style="<?= $labelStyle ?>">Cliente</label>
                <input type="text" name="pesquisa" placeholder="Nome do cliente..." style="<?= $inputStyle ?>" value="<?= $this->input->get('pesquisa') ?>">
            </div>

            <div style="flex:0 0 155px">
                <label style="<?= $labelStyle ?>">Status</label>
                <select name="status" style="<?= $selectStyle ?>">
                    <option value="">Todos</option>
                    <?php foreach (['Aberto','Em Andamento','Executado','Finalizado','Cancelado'] as $s): ?>
                        <option value="<?= $s ?>" <?= $this->input->get('status') == $s ? 'selected' : '' ?>><?= $s ?></option>
                    <?php endforeach ?>
                </select>
            </div>

            <div style="flex:0 0 115px">
                <label style="<?= $labelStyle ?>">Data (de)</label>
                <input type="text" name="data" class="datepicker" style="<?= $inputStyle ?>" value="<?= $this->input->get('data') ?>">
            </div>

            <div style="flex:0 0 115px">
                <label style="<?= $labelStyle ?>">Data (até)</label>
                <input type="text" name="data2" class="datepicker" style="<?= $inputStyle ?>" value="<?= $this->input->get('data2') ?>">
            </div>

            <div style="flex-shrink:0">
                <label style="display:block;margin-bottom:4px;font-size:12px;visibility:hidden">x</label>
                <button type="submit" style="height:30px;padding:0 14px;white-space:nowrap;display:inline-flex;align-items:center;gap:5px;background:#f0810f;border:none;border-radius:4px;color:#fff;font-size:13px;font-weight:600;cursor:pointer">
                    <i class='bx bx-search-alt' style="font-size:15px"></i> Filtrar
                </button>
            </div>

        </div>
        </form>
    </div>

    <div class="widget-box">
        <div class="widget-content nopadding tab-content">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>N° Exec.</th>
                        <th>Cliente</th>
                        <th>Técnico</th>
                        <th>Data Inicial</th>
                        <th>Data Final</th>
                        <th>Status</th>
                        <th style="text-align:center">Ações</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (! $results): ?>
                    <tr><td colspan="7">Nenhuma execução encontrada.</td></tr>
                <?php else: foreach ($results as $r):
                    $corStatus = match($r->status) {
                        'Aberto'       => '#337ab7',
                        'Em Andamento' => '#f0ad4e',
                        'Executado'    => '#8e44ad',
                        'Finalizado'   => '#5cb85c',
                        'Cancelado'    => '#d9534f',
                        default        => '#aaa',
                    };
                    $di = (!empty($r->dataInicial) && $r->dataInicial != '0000-00-00') ? date('d/m/Y', strtotime($r->dataInicial)) : '-';
                    $df = (!empty($r->dataFinal)   && $r->dataFinal   != '0000-00-00') ? date('d/m/Y', strtotime($r->dataFinal))   : '-';
                ?>
                    <tr>
                        <td><?= $r->os_id ?></td>
                        <td><?= $r->nomeCliente ?></td>
                        <td><?= $r->tecnico ?></td>
                        <td><?= $di ?></td>
                        <td><?= $df ?></td>
                        <td><span class="badge" style="background:<?= $corStatus ?>;border-color:<?= $corStatus ?>"><?= $r->status ?></span></td>
                        <td style="text-align:center">
                            <a href="<?= base_url() ?>index.php/execucao/visualizar/<?= $r->os_id ?>" class="btn-nwe" title="Visualizar"><i class="bx bx-show bx-xs"></i></a>
                            <?php if ($this->permission->checkPermission($this->session->userdata('permissao'), 'eArquivo')): ?>
                            <a href="<?= base_url() ?>index.php/execucao/editar/<?= $r->os_id ?>" class="btn-nwe3" title="Editar"><i class="bx bx-edit bx-xs"></i></a>
                            <a href="<?= base_url() ?>index.php/execucao/excluir/<?= $r->idExecucao ?>" class="btn-nwe2" title="Excluir" onclick="return confirm('Confirma exclusão desta execução?')"><i class="bx bx-trash bx-xs"></i></a>
                            <?php endif ?>
                        </td>
                    </tr>
                <?php endforeach; endif ?>
                </tbody>
            </table>
        </div>
    </div>
    <?= $this->pagination->create_links() ?>
</div>

<script>
$(function(){ $(".datepicker").datepicker({ dateFormat:'dd/mm/yy' }); });
</script>
