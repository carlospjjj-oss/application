<link rel="stylesheet" href="<?php echo base_url(); ?>assets/js/jquery-ui/css/smoothness/jquery-ui-1.9.2.custom.css" />
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/table-custom.css" />
<script type="text/javascript" src="<?php echo base_url() ?>assets/js/jquery-ui/js/jquery-ui-1.9.2.custom.js"></script>
<script src="<?php echo base_url() ?>assets/js/sweetalert2.all.min.js"></script>
<style>
  select {
    width: 70px;
  }
</style>
<div class="new122">
    <div class="widget-title" style="margin: -20px 0 0">
            <span class="icon">
                <i class="fas fa-diagnoses"></i>
            </span>
            <h5>Ordens de Serviço</h5>
        </div>
    <div class="span12" style="margin-left: 0">
        <?php if ($this->permission->checkPermission($this->session->userdata('permissao'), 'aOs')) { ?>
            <a href="<?php echo base_url(); ?>index.php/os/adicionar" class="button btn btn-mini btn-success" style="max-width: 160px;margin-bottom:10px">
                <span class="button__icon"><i class='bx bx-plus-circle'></i></span>
                <span class="button__text2">Ordem de Serviço</span>
            </a>
        <?php } ?>
        <form method="get" action="<?php echo base_url(); ?>index.php/os/gerenciar">
        <?php
        $labelStyle  = 'display:block;margin-bottom:4px;font-size:12px;color:#555;font-weight:600;white-space:nowrap';
        $inputStyle  = 'width:100%;box-sizing:border-box;height:30px;padding:4px 6px;border:1px solid #ccc;border-radius:4px;font-size:13px';
        $selectStyle = 'width:100%;box-sizing:border-box;height:30px;border:1px solid #ccc;border-radius:4px;font-size:13px';
        ?>
        <div style="display:flex;gap:10px;flex-wrap:nowrap;align-items:flex-start;background:#f8f9fa;padding:12px 14px;border-radius:6px;border:1px solid #ddd;margin-bottom:12px;">

            <div style="flex:0 0 200px">
                <label style="<?= $labelStyle ?>">Cliente</label>
                <input type="text" name="pesquisa" id="pesquisa" placeholder="Nome do cliente..." style="<?= $inputStyle ?>" value="<?= $this->input->get('pesquisa') ?>">
            </div>

            <div style="flex:0 0 155px">
                <label style="<?= $labelStyle ?>">Status</label>
                <select name="status" style="<?= $selectStyle ?>">
                    <option value="">Todos</option>
                    <option value="Aberto"           <?= $this->input->get('status') === 'Aberto'           ? 'selected' : '' ?>>Aberto</option>
                    <option value="Faturado"         <?= $this->input->get('status') === 'Faturado'         ? 'selected' : '' ?>>Faturado</option>
                    <option value="Negociação"       <?= $this->input->get('status') === 'Negociação'       ? 'selected' : '' ?>>Negociação</option>
                    <option value="Em Andamento"     <?= $this->input->get('status') === 'Em Andamento'     ? 'selected' : '' ?>>Em Andamento</option>
                    <option value="Orçamento"        <?= $this->input->get('status') === 'Orçamento'        ? 'selected' : '' ?>>Orçamento</option>
                    <option value="Finalizado"       <?= $this->input->get('status') === 'Finalizado'       ? 'selected' : '' ?>>Finalizado</option>
                    <option value="Cancelado"        <?= $this->input->get('status') === 'Cancelado'        ? 'selected' : '' ?>>Cancelado</option>
                    <option value="Aguardando Peças" <?= $this->input->get('status') === 'Aguardando Peças' ? 'selected' : '' ?>>Aguard. Peças</option>
                    <option value="Aprovado"         <?= $this->input->get('status') === 'Aprovado'         ? 'selected' : '' ?>>Aprovado</option>
                </select>
            </div>

            <div style="flex:0 0 115px">
                <label style="<?= $labelStyle ?>">Data (de)</label>
                <input type="text" name="data" autocomplete="off" id="data" class="datepicker" style="<?= $inputStyle ?>" value="<?= $this->input->get('data') ?>">
            </div>

            <div style="flex:0 0 115px">
                <label style="<?= $labelStyle ?>">Data (até)</label>
                <input type="text" name="data2" autocomplete="off" id="data2" class="datepicker" style="<?= $inputStyle ?>" value="<?= $this->input->get('data2') ?>">
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

    <div class="widget-box" style="margin-top: 8px">
        <div class="widget-content nopadding">
            <div class="table-responsive">
                <table class="table table-bordered ">
                    <thead>
                        <tr>
                            <th>N°</th>
                            <th>Cliente</th>
                            <th class="col-mobile-hide">Responsável</th>
                            <th class="col-mobile-hide">Data Inicial</th>
                            <th class="col-mobile-hide">Data Final</th>
                            <th class="col-mobile-hide">Venc. Garantia</th>
                            <th class="col-mobile-hide-sm">Valor Total</th>
                            <th class="col-mobile-hide">Desconto</th>
                            <th class="col-mobile-hide">Valor c/ Desc.</th>
                            <th class="col-mobile-hide">V.T (Faturado)</th>
                            <th>Status</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!$results) {
                            echo '<tr>
                            <td colspan="10">Nenhuma OS Cadastrada</td>
                            </tr>';
                        }

$this->load->model('os_model');
foreach ($results as $r) {
    $dataInicial = date(('d/m/Y'), strtotime($r->dataInicial));
    if ($r->dataFinal != null) {
        $dataFinal = date(('d/m/Y'), strtotime($r->dataFinal));
    } else {
        $dataFinal = "";
    }
    if ($this->input->get('pesquisa') === null && is_array(json_decode($configuration['os_status_list']))) {
        if (in_array($r->status, json_decode($configuration['os_status_list'])) != true) {
            continue;
        }
    }

    switch ($r->status) {
        case 'Aberto':
            $cor = '#00cd00';
            break;
        case 'Em Andamento':
            $cor = '#436eee';
            break;
        case 'Orçamento':
            $cor = '#CDB380';
            break;
        case 'Negociação':
            $cor = '#AEB404';
            break;
        case 'Cancelado':
            $cor = '#CD0000';
            break;
        case 'Finalizado':
            $cor = '#256';
            break;
        case 'Faturado':
            $cor = '#B266FF';
            break;
        case 'Aguardando Peças':
            $cor = '#FF7F00';
            break;
        case 'Aprovado':
            $cor = '#808080';
            break;
        default:
            $cor = '#E0E4CC';
            break;
    }
    $vencGarantia = '';

    if ($r->garantia && is_numeric($r->garantia)) {
        $vencGarantia = dateInterval($r->dataFinal, $r->garantia);
    }
    $corGarantia = '';
    if (!empty($vencGarantia)) {
        $dataGarantia = explode('/', $vencGarantia);
        $dataGarantiaFormatada = $dataGarantia[2] . '-' . $dataGarantia[1] . '-' . $dataGarantia[0];
        if (strtotime($dataGarantiaFormatada) >= strtotime(date('d-m-Y'))) {
            $corGarantia = '#4d9c79';
        } else {
            $corGarantia = '#f24c6f';
        }
    } elseif ($r->garantia == "0") {
        $vencGarantia = 'Sem Garantia';
        $corGarantia = '';
    } else {
        $vencGarantia = '';
        $corGarantia = '';
    }

    echo '<tr>';
    echo '<td>' . $r->idOs . '</td>';
    echo '<td><a href="' . base_url() . 'index.php/clientes/visualizar/' . $r->idClientes . '" style="margin-right: 1%">' . $r->nomeCliente . '</a></td>';
    echo '<td class="col-mobile-hide">' . $r->nome . '</td>';
    echo '<td class="col-mobile-hide">' . $dataInicial . '</td>';
    echo '<td class="col-mobile-hide">' . $dataFinal . '</td>';
    echo '<td class="col-mobile-hide"><span class="badge" style="background-color: ' . $corGarantia . '; border-color: ' . $corGarantia . '">' . $vencGarantia . '</span></td>';
    echo '<td class="col-mobile-hide-sm">R$ ' . number_format($r->totalProdutos + $r->totalServicos, 2, ',', '.') . '</td>';
    echo '<td class="col-mobile-hide">R$ ' . number_format(floatval($r->desconto), 2, ',', '.') . '</td>';
    echo '<td class="col-mobile-hide">R$ ' . number_format(floatval($r->valor_desconto), 2, ',', '.') . '</td>';
    echo '<td class="col-mobile-hide">R$ ' . number_format($r->faturado ? floatval($r->valor_desconto) : 0.00, 2, ',', '.') . '</td>';
    echo '<td><span class="badge" style="background-color: ' . $cor . '; border-color: ' . $cor . '">' . $r->status . '</span> </td>';
    echo '<td>';

    $editavel = $this->os_model->isEditable($r->idOs);

    if ($this->permission->checkPermission($this->session->userdata('permissao'), 'vOs')) {
        echo '<a style="margin-right: 1%" href="' . base_url() . 'index.php/os/visualizar/' . $r->idOs . '" class="btn-nwe" title="Ver mais detalhes"><i class="bx bx-show"></i></a>';
        echo '<a style="margin-right: 1%" href="' . base_url() . 'index.php/os/imprimir/' . $r->idOs . '" target="_blank" class="btn-nwe6" title="Imprimir A4"><i class="bx bx-printer bx-xs"></i></a>';
        echo '<a style="margin-right: 1%" href="' . base_url() . 'index.php/os/imprimirTermica/' . $r->idOs . '" target="_blank" class="btn-nwe6" title="Imprimir Não Fiscal"><i class="bx bx-printer bx-xs"></i></a>';
    }
    if ($editavel) {
        echo '<a style="margin-right: 1%" href="' . base_url() . 'index.php/os/editar/' . $r->idOs . '" class="btn-nwe3" title="Editar OS"><i class="bx bx-edit"></i></a>';
    }
    if ($this->permission->checkPermission($this->session->userdata('permissao'), 'dOs') && $editavel) {
        echo '<a href="#modal-excluir" role="button" data-toggle="modal" os="' . $r->idOs . '" class="btn-nwe4" title="Excluir OS"><i class="bx bx-trash-alt"></i></a>  ';
    }
    echo '</td>';
    echo '</tr>';
} ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <?php echo $this->pagination->create_links(); ?>

    <!-- Modal -->
    <div id="modal-excluir" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <form action="<?php echo base_url() ?>index.php/os/excluir" method="post">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h5 id="myModalLabel">Excluir OS</h5>
            </div>
            <div class="modal-body">
                <input type="hidden" id="idOs" name="id" value="" />
                <h5 style="text-align: center">Deseja realmente excluir esta OS?</h5>
            </div>
            <div class="modal-footer" style="display:flex;justify-content: center">
                <button class="button btn btn-warning" data-dismiss="modal" aria-hidden="true">
                    <span class="button__icon"><i class="bx bx-x"></i></span><span class="button__text2">Cancelar</span></button>
                <button class="button btn btn-danger"><span class="button__icon"><i class='bx bx-trash'></i></span> <span class="button__text2">Excluir</span></button>
            </div>
        </form>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $(document).on('click', 'a', function(event) {
            var os = $(this).attr('os');
            $('#idOs').val(os);
        });
        $(document).on('click', '#excluir-notificacao', function(event) {
            event.preventDefault();
            $.ajax({
                    url: '<?php echo site_url() ?>/os/excluir_notificacao',
                    type: 'GET',
                    dataType: 'json',
                })
                .done(function(data) {
                    if (data.result == true) {
                        Swal.fire({
                            type: "success",
                            title: "Sucesso",
                            text: "Notificação excluída com sucesso."
                        });
                        location.reload();
                    } else {
                        Swal.fire({
                            type: "success",
                            title: "Sucesso",
                            text: "Ocorreu um problema ao tentar exlcuir notificação."
                        });
                    }
                });
        });
        $(".datepicker").datepicker({
            dateFormat: 'dd/mm/yy'
        });
    });
</script>
