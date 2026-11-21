<link rel="stylesheet" href="<?php echo base_url(); ?>assets/js/jquery-ui/css/smoothness/jquery-ui-1.9.2.custom.css" />
<script type="text/javascript" src="<?php echo base_url() ?>assets/js/jquery-ui/js/jquery-ui-1.9.2.custom.js"></script>
<style>
  .widget-content select { width: 70px; }
  #modal-gerar-pagamento select { width: 100%; }
</style>
<div class="new122">
    <div class="widget-title" style="margin: -20px 0 0">
            <span class="icon">
                <i class="fas fa-cash-register"></i>
            </span>
            <h5>Cobranças</h5>
    </div>
    <?php if ($this->permission->checkPermission($this->session->userdata('permissao'), 'aCobranca')): ?>
    <div style="margin:10px 0;">
        <a href="#modal-gerar-pagamento" data-toggle="modal" class="button btn btn-mini btn-success">
            <span class="button__icon"><i class='bx bx-plus'></i></span><span class="button__text2">Nova Cobrança</span>
        </a>
    </div>
    <?php endif ?>

    <div class="widget-box">
        <h5 style="padding: 3px 0"></h5>
        <div class="widget-content nopadding tab-content">
            <table id="tabela" class="table table-bordered ">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Gateway</th>
                        <th>Tipo</th>
                        <th>Data de Vencimento</th>
                        <th>Referência</th>
                        <th>Status</th>
                        <th>Valor</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (!$results) {
                        echo '<tr>
                                <td colspan="8">Nenhuma cobrança Cadastrada</td>
                            </tr>';
                    }
                    foreach ($results as $r) {
                        $dataVenda = date(('d/m/Y'), strtotime($r->expire_at));
                        $cobrancaStatus = getCobrancaTransactionStatus(
                            $this->config->item('payment_gateways'),
                            $r->payment_gateway,
                            $r->status
                        );

                        echo '<tr>';
                        echo '<td>' . $r->idCobranca . '</td>';
                        echo '<td>' . $r->payment_gateway . '</td>';
                        echo '<td>' . $r->payment_method . '</td>';
                        echo '<td>' . $dataVenda . '</td>';

                        if ($r->os_id != '') {
                            echo '<td><a href="' . base_url() . 'index.php/os/visualizar/' . $r->os_id . '"> Ordem de Serviço: #' . $r->os_id . '</a></td>';
                        }
                        if ($r->vendas_id != '') {
                            echo '<td><a href="' . base_url() . 'index.php/vendas/visualizar/' . $r->vendas_id . '"> Venda: #' . $r->vendas_id . '</a></td>';
                        }

                        echo '<td>' .  $cobrancaStatus . '</td>';
                        echo '<td>R$ ' . number_format($r->total / 100, 2, ',', '.') . '</td>';
                        echo '<td>';
                        if ($this->permission->checkPermission($this->session->userdata('permissao'), 'vCobranca')) {
                            echo '<a style="margin-right: 1%" href="#modal-cancelar" role="button" data-toggle="modal" cancela_id="' . $r->idCobranca . '" class="btn-nwe4" title="Cancelar Cobrança"><i class="bx bx-x" ></i></a>';
                            echo '<a style="margin-right: 1%" href="' . base_url() . 'index.php/cobrancas/atualizar/' . $r->idCobranca . '" class="btn-nwe" title="Atualizar Cobrança"><i class="bx bx-refresh"></i></a>';
                            echo '<a style="margin-right: 1%" href="#modal-confirmar" role="button" data-toggle="modal" confirma_id="' . $r->idCobranca . '" class="btn-nwe3" title="Confirmar pagamento"><i class="bx bx-check"></i></a>';
                            echo '<a style="margin-right: 1%" href="' . base_url() . 'index.php/cobrancas/visualizar/' . $r->idCobranca . '" class="btn-nwe2" title="Ver mais detalhes"><i class="bx bx-show" ></i></a>';
                            echo '<a style="margin-right: 1%" href="' . base_url() . 'index.php/cobrancas/enviarEmail/' . $r->idCobranca . '" class="btn-nwe5" title="Enviar por E-mail"><i class="bx bx-envelope" ></i></a>';
                        }
                        if ($this->permission->checkPermission($this->session->userdata('permissao'), 'eCobranca') && $r->barcode != '') {
                            echo '<a style="margin-right: 1%" href="' . $r->link . '" target="_blank" class="btn-nwe" title="Visualizar boleto"><i class="bx bx-barcode" ></i></a>';
                        }
                        if ($this->permission->checkPermission($this->session->userdata('permissao'), 'dCobranca')) {
                            echo '<a href="#modal-excluir" role="button" data-toggle="modal" excluir_id="' . $r->idCobranca . '" class="btn-nwe4" title="Excluir Cobrança"><i class="bx bx-trash-alt"></i></a>';
                        }
                        echo '</td>';
                        echo '</tr>';
                    } ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php echo $this->pagination->create_links(); ?>

    <!-- Modal Nova Cobrança -->
    <?php if ($this->permission->checkPermission($this->session->userdata('permissao'), 'aCobranca')): ?>
    <div class="modal hide fade" id="modal-gerar-pagamento" tabindex="-1" role="dialog">
        <form id="form-gerar-cobranca" method="post" action="<?= base_url() ?>index.php/cobrancas/adicionar">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Nova Cobrança</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Tipo de Referência</label>
                    <select name="tipo" id="cobr_tipo" class="span12" onchange="cobr_tipo_change(this.value)">
                        <option value="">Selecione...</option>
                        <option value="os">Ordem de Serviço</option>
                        <option value="venda">Venda</option>
                    </select>
                </div>
                <div class="form-group" id="cobr_grupo_numero" style="display:none">
                    <label id="cobr_label_numero">Número</label>
                    <div style="display:flex;gap:6px;align-items:center">
                        <input type="number" name="id" id="cobr_id" class="span10" placeholder="Ex: 42" min="1">
                        <button type="button" id="cobr_btn_buscar" class="btn btn-mini btn-info" style="white-space:nowrap">
                            <i class='bx bx-search'></i> Buscar
                        </button>
                    </div>
                    <small id="cobr_info_ref" style="color:#666;display:none"></small>
                </div>
                <div class="form-group">
                    <label>Descrição</label>
                    <input type="text" name="descricao" id="cobr_descricao" class="span12" placeholder="Ex: Serviço de instalação">
                </div>
                <div class="form-group">
                    <label>Forma de Pagamento</label>
                    <input type="text" name="forma_pagamento" id="cobr_forma_pag" class="span12" placeholder="Ex: PIX, Cartão de Crédito...">
                </div>
                <div class="form-group">
                    <label>Vencimento</label>
                    <input type="text" name="vencimento" id="cobr_vencimento" class="span12" placeholder="dd/mm/aaaa">
                </div>
            </div>
            <div class="modal-footer" style="display:flex;gap:8px;justify-content:flex-end">
                <button type="button" class="button btn btn-mini btn-warning" data-dismiss="modal">
                    <span class="button__icon"><i class='bx bx-x'></i></span><span class="button__text2">Cancelar</span>
                </button>
                <button type="submit" class="button btn btn-mini btn-success" id="btn-registrar-cobranca">
                    <span class="button__icon"><i class='bx bx-dollar'></i></span><span class="button__text2">Registrar Cobrança</span>
                </button>
            </div>
        </form>
    </div>
    <script>
    // Função global — sem depender de $(document).ready para o onchange
    function cobr_tipo_change(tipo) {
        var grp   = document.getElementById('cobr_grupo_numero');
        var label = document.getElementById('cobr_label_numero');
        var info  = document.getElementById('cobr_info_ref');
        var inp   = document.getElementById('cobr_id');
        if (tipo) {
            label.textContent = tipo === 'os' ? 'Número da Ordem de Serviço' : 'Número da Venda';
            grp.style.display = 'block';
            inp.value = '';
            info.style.display = 'none';
            info.textContent   = '';
            inp.focus();
        } else {
            grp.style.display = 'none';
        }
    }

    $(function(){
        // Datepicker no campo vencimento do modal
        $("#cobr_vencimento").datepicker({ dateFormat:'dd/mm/yy' });

        // Resetar modal ao fechar
        $("#modal-gerar-pagamento").on("hidden", function(){
            document.getElementById('form-gerar-cobranca').reset();
            document.getElementById('cobr_grupo_numero').style.display = 'none';
            document.getElementById('cobr_info_ref').style.display     = 'none';
            $("#cobr_forma_pag").val('');
        });

        // Buscar OS ou Venda
        $("#cobr_btn_buscar").on("click", function(){
            var tipo = $("#cobr_tipo").val();
            var id   = $("#cobr_id").val();
            if (!tipo || !id) return;
            var url = tipo === 'os'
                ? "<?= base_url() ?>index.php/os/getOsJson/" + id
                : "<?= base_url() ?>index.php/vendas/getVendaJson/" + id;
            $.getJSON(url, function(resp){
                console.log('getJson resp:', JSON.stringify(resp));
                var info = document.getElementById('cobr_info_ref');
                if (resp && resp.result) {
                    // Preenche descrição
                    if (resp.descricao) $("#cobr_descricao").val(resp.descricao);

                    // Preenche forma de pagamento direto
                    $("#cobr_forma_pag").val(resp.forma_pagamento || '');

                    info.textContent   = "✓ Cliente: " + resp.cliente;
                    info.style.color   = "#3a7d3a";
                    info.style.display = 'block';
                } else {
                    info.textContent   = "✗ " + (tipo==='os'?'OS':'Venda') + " #" + id + " não encontrada.";
                    info.style.color   = "#c0392b";
                    info.style.display = 'block';
                }
            }).fail(function(){
                var info = document.getElementById('cobr_info_ref');
                info.textContent   = "Erro ao buscar dados.";
                info.style.color   = "#c0392b";
                info.style.display = 'block';
            });
        });

        // Enter no campo número dispara busca
        $("#cobr_id").on("keypress", function(e){
            if (e.which === 13) { e.preventDefault(); $("#cobr_btn_buscar").click(); }
        });

        // Submit
        $("#form-gerar-cobranca").on("submit", function(e){
            e.preventDefault();
            var tipo = $("#cobr_tipo").val();
            if (!tipo)                        { return; }
            var refId = $("#cobr_id").val();
            if (!refId || parseInt(refId)<1)  { return; }
            if (!$("#cobr_vencimento").val())  { return; }

            var $btn = $("#btn-registrar-cobranca").prop("disabled", true);
            $.ajax({
                url: $(this).attr("action"),
                method: "POST",
                data: $(this).serialize(),
                dataType: "json",
                success: function(resp){
                    if (resp.result) {
                        location.reload();
                    } else {
                        $btn.prop("disabled", false);
                    }
                },
                error: function(){
                    $btn.prop("disabled", false);
                }
            });
        });
    });
    </script>
    <?php endif ?>

    <!-- Modal -->
    <div id="modal-excluir" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <form action="<?php echo base_url() ?>index.php/cobrancas/excluir" method="post">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h5 id="myModalLabel">Excluir cobrança</h5>
            </div>
            <div class="modal-body">
                <input type="hidden" id="excluir_id" name="excluir_id" value="" />
                <h5 style="text-align: center">Deseja realmente excluir esta cobrança? A cobrança será cancelada.</h5>
            </div>
            <div class="modal-footer" style="display:flex;justify-content: center">
                <button class="button btn btn-warning" data-dismiss="modal" aria-hidden="true"><span class="button__icon"><i class="bx bx-x"></i></span><span class="button__text2">Cancelar</span></button>
                <button class="button btn btn-danger"><span class="button__icon"><i class='bx bx-trash'></i></span> <span class="button__text2">Excluir</span></button>
            </div>
        </form>
    </div>

    <div id="modal-confirmar" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <form action="<?php echo base_url() ?>index.php/cobrancas/confirmarpagamento" method="post">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h5 id="myModalLabel">Confirmar pagamento</h5>
            </div>
            <div class="modal-body">
                <input type="hidden" id="confirma_id" name="confirma_id" value="" />
                <h5 style="text-align: center">Deseja realmente confirmar pagamento desta cobrança?</h5>
            </div>
            <div class="modal-footer">
                <button class="btn" data-dismiss="modal" aria-hidden="true">Cancelar</button>
                <button class="btn btn-success">Confirmar</button>
            </div>
        </form>
    </div>

    <div id="modal-cancelar" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <form action="<?php echo base_url() ?>index.php/cobrancas/cancelar" method="post">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h5 id="myModalLabel">Cancelar cobrança</h5>
            </div>
            <div class="modal-body">
                <input type="hidden" id="cancela_id" name="cancela_id" value="" />
                <h5 style="text-align: center">Deseja realmente Cancelar esta cobrança?</h5>
            </div>
            <div class="modal-footer">
                <button class="btn" data-dismiss="modal" aria-hidden="true">Cancelar</button>
                <button class="btn btn-danger">Confirmar</button>
            </div>
        </form>
    </div>
</div>
    <script type="text/javascript">
        $(document).ready(function() {
            $(document).on('click', 'a', function(event) {
                var cobranca = $(this).attr('excluir_id');
                $('#excluir_id').val(cobranca);
            });

            $(document).on('click', 'a', function(event) {
                var cobranca = $(this).attr('confirma_id');
                $('#confirma_id').val(cobranca);
            });

            $(document).on('click', 'a', function(event) {
                var cobranca = $(this).attr('cancela_id');
                $('#cancela_id').val(cobranca);
            });
        });
    </script>