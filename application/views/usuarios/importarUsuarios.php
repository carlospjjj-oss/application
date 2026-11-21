<div class="new122">
    <div class="widget-title" style="margin: -20px 0 0">
        <span class="icon"><i class="bx bx-import"></i></span>
        <h5>Importar Usuários via Planilha</h5>
    </div>

    <div class="widget-box">
        <div class="widget-content" style="padding:20px;">

            <?php if ($this->session->flashdata('error')) { ?>
                <div class="alert alert-error"><?= $this->session->flashdata('error') ?></div>
            <?php } ?>

            <div style="background:#f9f9f9;border:1px solid #ddd;border-radius:8px;padding:20px;margin-bottom:20px;">
                <h5 style="margin-top:0"><i class="bx bx-download"></i> Passo 1 — Baixe o modelo</h5>
                <p>Faça o download da planilha modelo, preencha com os dados dos usuários e suba no passo 2.</p>
                <a href="<?= site_url('usuarios/modeloPlanilha') ?>" class="button btn btn-mini btn-success">
                    <span class="button__icon"><i class='bx bx-file'></i></span>
                    <span class="button__text2">Baixar Modelo Excel</span>
                </a>
                <div style="margin-top:15px;font-size:0.9em;color:#666;">
                    <strong>Colunas da planilha:</strong>
                    <table class="table table-bordered" style="margin-top:8px;max-width:900px;">
                        <thead><tr>
                            <th>Nome*</th><th>CPF*</th><th>Email*</th><th>Senha*</th>
                            <th>Telefone</th><th>Celular</th><th>RG</th>
                            <th>CEP</th><th>Rua</th><th>Número</th>
                            <th>Bairro</th><th>Cidade</th><th>Estado</th>
                            <th>Situação</th><th>Expira em</th>
                        </tr></thead>
                        <tbody><tr>
                            <td>João Silva</td><td>123.456.789-00</td><td>joao@email.com</td><td>senha123</td>
                            <td>(11) 99999-0001</td><td>(11) 99999-0002</td><td>12.345.678</td>
                            <td>01310-100</td><td>Av. Paulista</td><td>1000</td>
                            <td>Bela Vista</td><td>São Paulo</td><td>SP</td>
                            <td>Ativo</td><td>2027-12-31</td>
                        </tr></tbody>
                    </table>
                    <small>* Campos obrigatórios. Situação: "Ativo" ou "Inativo". Expira em: formato AAAA-MM-DD. A permissão padrão será aplicada a todos os usuários importados (pode ser alterada depois).</small>
                </div>
            </div>

            <div style="background:#f9f9f9;border:1px solid #ddd;border-radius:8px;padding:20px;">
                <h5 style="margin-top:0"><i class="bx bx-upload"></i> Passo 2 — Envie a planilha preenchida</h5>
                <form method="post" action="<?= site_url('usuarios/importarPlanilha') ?>" enctype="multipart/form-data">
                    <div style="display:flex;align-items:center;gap:15px;flex-wrap:wrap;">
                        <input type="file" name="planilha" accept=".xlsx" required style="max-width:350px;" />
                        <button type="submit" class="button btn btn-mini btn-warning">
                            <span class="button__icon"><i class='bx bx-upload'></i></span>
                            <span class="button__text2">Importar Usuários</span>
                        </button>
                        <a href="<?= site_url('usuarios') ?>" class="button btn btn-mini">
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
