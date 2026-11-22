<style>
@media (max-width: 768px) {
    .pesq-grid { display: block !important; }
    .pesq-col { width: 100% !important; float: none !important; margin-left: 0 !important; margin-bottom: 12px; }
    .pesq-search-wrap { flex-direction: column !important; }
    .pesq-search-wrap input { width: 100% !important; }
}
</style>

<div class="new122">
    <!-- Busca -->
    <form action="<?php echo current_url() ?>" method="get">
    <div style="display:flex;gap:10px;align-items:flex-end;flex-wrap:wrap;background:#f8f9fa;padding:12px 14px;border-radius:6px;border:1px solid #ddd;margin-bottom:16px;" class="pesq-search-wrap">
        <div style="flex:1;min-width:200px">
            <label style="display:block;margin-bottom:4px;font-size:12px;color:#555;font-weight:600">Pesquisar</label>
            <input type="text" class="pesq-search-wrap" name="termo" placeholder="Digite o termo a pesquisar"
                style="width:100%;box-sizing:border-box;height:30px;padding:4px 8px;border:1px solid #ccc;border-radius:4px;font-size:13px"
                value="<?php echo htmlspecialchars($this->input->get('termo') ?? '') ?>"/>
        </div>
        <div style="flex-shrink:0">
            <button type="submit" style="height:30px;padding:0 14px;white-space:nowrap;display:inline-flex;align-items:center;gap:5px;background:#f0810f;border:none;border-radius:4px;color:#fff;font-size:13px;font-weight:600;cursor:pointer">
                <i class='bx bx-search-alt' style="font-size:15px"></i> Pesquisar
            </button>
        </div>
    </div>
    </form>

    <!-- Grid de resultados: 2 colunas no desktop, 1 no mobile -->
    <div class="pesq-grid" style="display:grid;grid-template-columns:1fr 1fr;gap:16px">

        <!-- Produtos -->
        <div class="pesq-col">
            <div class="widget-box" style="min-height:200px">
                <div class="widget-title" style="margin:-20px 0 0">
                    <span class="icon"><i class="fas fa-shopping-bag"></i></span>
                    <h5>Produtos</h5>
                </div>
                <div class="widget-content nopadding" style="overflow-x:auto;-webkit-overflow-scrolling:touch">
                    <table class="table table-bordered" style="min-width:360px">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Nome</th>
                            <th>Preço</th>
                            <th>Estoque</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if ($produtos == null): ?>
                            <tr><td colspan="5">Nenhum produto foi encontrado.</td></tr>
                        <?php else: foreach ($produtos as $r): ?>
                            <tr>
                                <td><?= $r->idProdutos ?></td>
                                <td><?= $r->descricao ?></td>
                                <td><?= $r->precoVenda ?></td>
                                <td><?= $r->estoque ?></td>
                                <td>
                                    <?php if ($this->permission->checkPermission($this->session->userdata('permissao'), 'vProduto')): ?>
                                        <a href="<?= base_url() ?>index.php/produtos/visualizar/<?= $r->idProdutos ?>" class="btn-nwe" title="Ver"><i class="bx bx-show"></i></a>
                                    <?php endif ?>
                                    <?php if ($this->permission->checkPermission($this->session->userdata('permissao'), 'eProduto')): ?>
                                        <a href="<?= base_url() ?>index.php/produtos/editar/<?= $r->idProdutos ?>" class="btn-nwe3" title="Editar"><i class="bx bx-edit"></i></a>
                                    <?php endif ?>
                                </td>
                            </tr>
                        <?php endforeach; endif ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Clientes -->
        <div class="pesq-col">
            <div class="widget-box" style="min-height:200px">
                <div class="widget-title" style="margin:-20px 0 0">
                    <span class="icon"><i class="fas fa-user"></i></span>
                    <h5>Clientes</h5>
                </div>
                <div class="widget-content nopadding" style="overflow-x:auto;-webkit-overflow-scrolling:touch">
                    <table class="table table-bordered" style="min-width:340px">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Nome</th>
                            <th>CPF/CNPJ</th>
                            <th>Tipo</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if ($clientes == null): ?>
                            <tr><td colspan="5">Nenhum cliente foi encontrado.</td></tr>
                        <?php else: foreach ($clientes as $r): ?>
                            <?php $cor = ($r->fornecedor ? '#CDB380' : '#CD0000'); ?>
                            <tr>
                                <td><?= $r->idClientes ?></td>
                                <td><?= $r->nomeCliente ?></td>
                                <td><?= $r->documento ?></td>
                                <td><span class="badge" style="background-color:<?= $cor ?>;border-color:<?= $cor ?>"><?= $r->fornecedor ? 'Fornecedor' : 'Cliente' ?></span></td>
                                <td>
                                    <?php if ($this->permission->checkPermission($this->session->userdata('permissao'), 'vCliente')): ?>
                                        <a href="<?= base_url() ?>index.php/clientes/visualizar/<?= $r->idClientes ?>" class="btn-nwe" title="Ver"><i class="bx bx-show"></i></a>
                                    <?php endif ?>
                                    <?php if ($this->permission->checkPermission($this->session->userdata('permissao'), 'eCliente')): ?>
                                        <a href="<?= base_url() ?>index.php/clientes/editar/<?= $r->idClientes ?>" class="btn-nwe3" title="Editar"><i class="bx bx-edit"></i></a>
                                    <?php endif ?>
                                </td>
                            </tr>
                        <?php endforeach; endif ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Serviços -->
        <div class="pesq-col">
            <div class="widget-box" style="min-height:200px">
                <div class="widget-title" style="margin:-20px 0 0">
                    <span class="icon"><i class="fas fa-wrench"></i></span>
                    <h5>Serviços</h5>
                </div>
                <div class="widget-content nopadding" style="overflow-x:auto;-webkit-overflow-scrolling:touch">
                    <table class="table table-bordered" style="min-width:280px">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Nome</th>
                            <th>Preço</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if ($servicos == null): ?>
                            <tr><td colspan="4">Nenhum serviço foi encontrado.</td></tr>
                        <?php else: foreach ($servicos as $r): ?>
                            <tr>
                                <td><?= $r->idServicos ?></td>
                                <td><?= $r->nome ?></td>
                                <td><?= $r->preco ?></td>
                                <td>
                                    <?php if ($this->permission->checkPermission($this->session->userdata('permissao'), 'eServico')): ?>
                                        <a href="<?= base_url() ?>index.php/servicos/editar/<?= $r->idServicos ?>" class="btn-nwe3" title="Editar"><i class="bx bx-edit"></i></a>
                                    <?php endif ?>
                                </td>
                            </tr>
                        <?php endforeach; endif ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Ordens de Serviço -->
        <div class="pesq-col">
            <div class="widget-box" style="min-height:200px">
                <div class="widget-title" style="margin:-20px 0 0">
                    <span class="icon"><i class="fas fa-diagnoses"></i></span>
                    <h5>Ordens de Serviço</h5>
                </div>
                <div class="widget-content nopadding" style="overflow-x:auto;-webkit-overflow-scrolling:touch">
                    <table class="table table-bordered" style="min-width:360px">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Data</th>
                            <th>Descrição</th>
                            <th>Defeito</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if ($os == null): ?>
                            <tr><td colspan="5">Nenhuma OS foi encontrada.</td></tr>
                        <?php else: foreach ($os as $r): ?>
                            <?php $dataInicial = date('d/m/Y', strtotime($r->dataInicial)); ?>
                            <tr>
                                <td><?= $r->idOs ?></td>
                                <td><?= $dataInicial ?></td>
                                <td><?= $r->descricaoProduto ?></td>
                                <td><?= $r->defeito ?></td>
                                <td>
                                    <?php if ($this->permission->checkPermission($this->session->userdata('permissao'), 'vOs')): ?>
                                        <a href="<?= base_url() ?>index.php/os/visualizar/<?= $r->idOs ?>" class="btn-nwe" title="Ver"><i class="bx bx-show"></i></a>
                                    <?php endif ?>
                                    <?php if ($this->permission->checkPermission($this->session->userdata('permissao'), 'eOs')): ?>
                                        <a href="<?= base_url() ?>index.php/os/editar/<?= $r->idOs ?>" class="btn-nwe3" title="Editar"><i class="bx bx-edit"></i></a>
                                    <?php endif ?>
                                </td>
                            </tr>
                        <?php endforeach; endif ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div><!-- /pesq-grid -->
</div>
