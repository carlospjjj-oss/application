<div class="new122">
    <div class="widget-title" style="margin: -20px 0 0">
        <span class="icon"><i class="bx bx-import"></i></span>
        <h5>Importar Produtos via Planilha</h5>
    </div>

    <div class="widget-box">
        <div class="widget-content" style="padding:20px;">

            <?php if ($this->session->flashdata('error')) { ?>
                <div class="alert alert-error"><?= $this->session->flashdata('error') ?></div>
            <?php } ?>

            <div style="background:#f9f9f9;border:1px solid #ddd;border-radius:8px;padding:20px;margin-bottom:20px;">
                <h5 style="margin-top:0"><i class="bx bx-download"></i> Passo 1 — Baixe o modelo</h5>
                <p>Faça o download da planilha modelo, preencha com seus produtos e suba no passo 2.</p>
                <a href="<?= site_url('produtos/modeloPlanilha') ?>" class="button btn btn-mini btn-success">
                    <span class="button__icon"><i class='bx bx-file'></i></span>
                    <span class="button__text2">Baixar Modelo Excel</span>
                </a>
                <div style="margin-top:15px;font-size:0.9em;color:#666;">
                    <strong>Colunas da planilha:</strong>
                    <table class="table table-bordered" style="margin-top:8px;max-width:700px;">
                        <thead><tr>
                            <th>Código de Barra</th><th>Descrição*</th><th>Unidade</th>
                            <th>Preço Compra</th><th>Preço Venda</th><th>Estoque</th><th>Estoque Mínimo</th>
                        </tr></thead>
                        <tbody><tr>
                            <td>7891234560001</td><td>Nome do produto</td><td>UN</td>
                            <td>10,00</td><td>15,00</td><td>100</td><td>5</td>
                        </tr></tbody>
                    </table>
                    <small>* Campo obrigatório. Preços aceitam vírgula ou ponto como decimal.</small>
                </div>
            </div>

            <div style="background:#f9f9f9;border:1px solid #ddd;border-radius:8px;padding:20px;">
                <h5 style="margin-top:0"><i class="bx bx-upload"></i> Passo 2 — Envie a planilha preenchida</h5>
                <form method="post" action="<?= site_url('produtos/importarPlanilha') ?>" enctype="multipart/form-data">
                    <div style="display:flex;align-items:center;gap:15px;flex-wrap:wrap;">
                        <input type="file" name="planilha" accept=".xlsx" required style="max-width:350px;" />
                        <button type="submit" class="button btn btn-mini btn-warning">
                            <span class="button__icon"><i class='bx bx-upload'></i></span>
                            <span class="button__text2">Importar Produtos</span>
                        </button>
                        <a href="<?= site_url('produtos') ?>" class="button btn btn-mini">
                            <span class="button__icon"><i class='bx bx-arrow-back'></i></span>
                            <span class="button__text2">Voltar</span>
                        </a>
                    </div>
                    <small style="display:block;margin-top:10px;color:#888;">Somente arquivos .xlsx. A primeira linha (cabeçalho) é ignorada automaticamente.</small>
                </form>
            </div>

        </div>
    </div>
</div>
