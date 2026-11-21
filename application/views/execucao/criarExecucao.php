<link rel="stylesheet" href="<?php echo base_url(); ?>assets/js/jquery-ui/css/smoothness/jquery-ui-1.9.2.custom.css" />
<script type="text/javascript" src="<?php echo base_url() ?>assets/js/jquery-ui/js/jquery-ui-1.9.2.custom.js"></script>

<div class="new122">
    <div class="widget-title" style="margin:-20px 0 0">
        <span class="icon"><i class='bx bx-plug'></i></span>
        <h5>Nova Execução</h5>
    </div>

    <div style="margin:10px 0 20px;">
        <a href="<?= base_url() ?>index.php/execucao" class="button btn btn-mini btn-warning">
            <span class="button__icon"><i class='bx bx-undo'></i></span><span class="button__text2">Voltar</span>
        </a>
    </div>

    <?php if ($this->session->flashdata('error')): ?>
        <div class="alert alert-error"><?= $this->session->flashdata('error') ?></div>
    <?php endif ?>

    <div class="widget-box">
        <div class="widget-content" style="padding:20px;">
            <form method="post" action="<?= site_url('execucao/salvar_novo') ?>">

                <div class="row-fluid" style="margin-bottom:15px;">
                    <div class="span6">
                        <label>Cliente *</label>
                        <select name="clientes_id" class="span12" required>
                            <option value="">Selecione o cliente...</option>
                            <?php foreach ($clientes as $c): ?>
                                <option value="<?= $c->idClientes ?>"><?= htmlspecialchars($c->nomeCliente) ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>
                    <div class="span6">
                        <label>Técnico Responsável *</label>
                        <select name="usuarios_id" class="span12" required>
                            <option value="">Selecione o técnico...</option>
                            <?php foreach ($usuarios as $u): ?>
                                <option value="<?= $u->idUsuarios ?>"><?= htmlspecialchars($u->nome) ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>
                </div>

                <div class="row-fluid" style="margin-bottom:15px;">
                    <div class="span12">
                        <label>Engenheiro Responsável *</label>
                        <input type="text" name="engenheiro" class="span12" placeholder="Nome do engenheiro responsável" required>
                    </div>
                </div>

                <div class="row-fluid" style="margin-bottom:15px;">
                    <div class="span4">
                        <label>Data Inicial</label>
                        <input type="text" name="dataInicial" class="span12 datepicker" placeholder="dd/mm/aaaa">
                    </div>
                    <div class="span4">
                        <label>Data Final</label>
                        <input type="text" name="dataFinal" class="span12 datepicker" placeholder="dd/mm/aaaa">
                    </div>
                    <div class="span4">
                        <label>Garantia (dias)</label>
                        <input type="number" name="garantia" class="span12" value="90" min="0">
                    </div>
                </div>

                <div style="margin-top:20px;">
                    <button type="submit" class="button btn btn-primary">
                        <span class="button__icon"><i class='bx bx-save'></i></span>
                        <span class="button__text2">Criar Execução</span>
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

<script>
$(function(){ $(".datepicker").datepicker({ dateFormat:'dd/mm/yy' }); });
</script>
