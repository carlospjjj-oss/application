<link rel="stylesheet" href="<?php echo base_url(); ?>assets/js/jquery-ui/css/smoothness/jquery-ui-1.9.2.custom.css" />
<script type="text/javascript" src="<?php echo base_url() ?>assets/js/jquery-ui/js/jquery-ui-1.9.2.custom.js"></script>
<div class="new122">
    <div class="widget-title" style="margin: -20px 0 0">
        <span class="icon"><i class='bx bx-plug'></i></span>
        <h5>Execução</h5>
    </div>

    <div class="span12" style="margin-left: 0">
        <form method="get" action="<?= current_url(); ?>">
            <div class="span4">
                <input type="text" name="pesquisa" placeholder="Nome do cliente" class="span12" value="<?= $this->input->get('pesquisa') ?>">
            </div>
            <div class="span2">
                <select name="status" class="span12">
                    <option value="">Todos os status</option>
                    <?php foreach (['Aberto','Em Andamento','Executado','Finalizado','Cancelado'] as $s): ?>
                        <option value="<?= $s ?>" <?= $this->input->get('status') == $s ? 'selected' : '' ?>><?= $s ?></option>
                    <?php endforeach ?>
                </select>
            </div>
            <div class="span3">
                <input type="text" name="data"  placeholder="Data de"  class="span6 datepicker" value="<?= $this->input->get('data') ?>">
                <input type="text" name="data2" placeholder="Data até" class="span6 datepicker" value="<?= $this->input->get('data2') ?>">
            </div>
            <div class="span1">
                <button class="button btn btn-mini btn-warning" style="min-width:30px">
                    <span class="button__icon"><i class='bx bx-search-alt'></i></span>
                </button>
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
