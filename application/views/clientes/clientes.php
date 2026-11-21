<style>
    select { width: 70px; }
    #tabela_wrapper .dataTables_filter { display: none !important; }
</style>
<div class="new122">
    <div class="widget-title" style="margin: -20px 0 0">
        <span class="icon">
            <i class="fas fa-user"></i>
        </span>
        <h5>Clientes</h5>
    </div>
    <div class="span12" style="margin-left: 0">
        <?php if ($this->permission->checkPermission($this->session->userdata('permissao'), 'aCliente')) { ?>
            <div style="display:flex;gap:8px;flex-wrap:wrap;margin-bottom:10px;">
                <a href="<?= base_url() ?>index.php/clientes/adicionar" class="button btn btn-mini btn-success" style="max-width:165px">
                    <span class="button__icon"><i class='bx bx-plus-circle'></i></span><span class="button__text2">Cliente / Fornecedor</span>
                </a>
                <a href="<?= site_url('clientes/importar') ?>" class="button btn btn-mini btn-info" style="max-width:180px;">
                    <span class="button__icon"><i class='bx bx-import'></i></span><span class="button__text2">Importar Planilha</span>
                </a>
            </div>
        <?php } ?>
        <form method="get" action="<?= base_url() ?>index.php/clientes">
        <?php
        $labelStyle  = 'display:block;margin-bottom:4px;font-size:12px;color:#555;font-weight:600;white-space:nowrap';
        $inputStyle  = 'width:100%;box-sizing:border-box;height:30px;padding:4px 6px;border:1px solid #ccc;border-radius:4px;font-size:13px';
        $selectStyle = 'width:100%;box-sizing:border-box;height:30px;border:1px solid #ccc;border-radius:4px;font-size:13px';
        ?>
        <div style="display:flex;gap:10px;flex-wrap:nowrap;align-items:flex-start;background:#f8f9fa;padding:12px 14px;border-radius:6px;border:1px solid #ddd;margin-bottom:12px;">

            <div style="flex:0 0 130px">
                <label style="<?= $labelStyle ?>">Tipo</label>
                <select name="tipo" style="<?= $selectStyle ?>">
                    <option value="">Todos</option>
                    <option value="cliente"    <?= $this->input->get('tipo') == 'cliente'    ? 'selected' : '' ?>>Cliente</option>
                    <option value="fornecedor" <?= $this->input->get('tipo') == 'fornecedor' ? 'selected' : '' ?>>Fornecedor</option>
                    <option value="terceiro"   <?= $this->input->get('tipo') == 'terceiro'   ? 'selected' : '' ?>>Terceiro</option>
                </select>
            </div>

            <div style="flex:0 0 300px">
                <label style="<?= $labelStyle ?>">Buscar cliente</label>
                <input type="text" name="pesquisa" id="pesquisa" placeholder="Nome, Doc, Email ou Tel..." style="<?= $inputStyle ?>" value="<?= $this->input->get('pesquisa') ?>">
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
        <h5 style="padding: 3px 0"></h5>
        <div class="widget-content nopadding tab-content">
            <table id="tabela" class="table table-bordered ">
                <thead>
                    <tr>
                        <th>Cod.</th>
                        <th>Nome</th>
                        <th>Contato</th>
                        <th>CPF/CNPJ</th>
                        <th>Telefone</th>
                        <th>Celular</th>
                        <th>Email</th>
                        <th>Tipo</th> <!-- Nova coluna para Fornecedor/Cliente -->
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (!$results) {
                        echo '<tr>
                    <td colspan="9">Nenhum Cliente Cadastrado</td>
                  </tr>';
                    }
        foreach ($results as $r) {
            echo '<tr>';
            echo '<td>' . $r->idClientes . '</td>';
            echo '<td><a href="' . base_url() . 'index.php/clientes/visualizar/' . $r->idClientes . '" style="margin-right: 1%">' . $r->nomeCliente . '</a></td>';
            echo '<td>' . $r->contato . '</td>';
            echo '<td>' . $r->documento . '</td>';
            echo '<td>' . $r->telefone . '</td>';
            echo '<td>' . $r->celular . '</td>';
            echo '<td>' . $r->email . '</td>';

            // Verifica se é Fornecedor, Terceiro ou Cliente
            if ($r->fornecedor == 1) {
                echo '<td><span class="label label-primary">Fornecedor</span></td>';
            } elseif (!empty($r->terceiro) && $r->terceiro == 1) {
                echo '<td><span class="label label-warning">Terceiro</span></td>';
            } else {
                echo '<td><span class="label label-success">Cliente</span></td>';
            }

            echo '<td>';
            if ($this->permission->checkPermission($this->session->userdata('permissao'), 'vCliente')) {
                echo '<a href="' . base_url() . 'index.php/clientes/visualizar/' . $r->idClientes . '" style="margin-right: 1%" class="btn-nwe" title="Ver mais detalhes"><i class="bx bx-show bx-xs"></i></a>';
                echo '<a href="' . base_url() . 'index.php/mine?e=' . $r->email . '" target="new" style="margin-right: 1%" class="btn-nwe2" title="Área do cliente"><i class="bx bx-key bx-xs"></i></a>';
            }
            if ($this->permission->checkPermission($this->session->userdata('permissao'), 'eCliente')) {
                echo '<a href="' . base_url() . 'index.php/clientes/editar/' . $r->idClientes . '" style="margin-right: 1%" class="btn-nwe3" title="Editar Cliente"><i class="bx bx-edit bx-xs"></i></a>';
            }
            if ($this->permission->checkPermission($this->session->userdata('permissao'), 'dCliente')) {
                echo '<a href="#modal-excluir" role="button" data-toggle="modal" cliente="' . $r->idClientes . '" style="margin-right: 1%" class="btn-nwe4" title="Excluir Cliente"><i class="bx bx-trash-alt bx-xs"></i></a>';
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
<div id="modal-excluir" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true">
    <form action="<?php echo base_url() ?>index.php/clientes/excluir" method="post">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h5 id="myModalLabel">Excluir Cliente</h5>
        </div>
        <div class="modal-body">
            <input type="hidden" id="idCliente" name="id" value="" />
            <h5 style="text-align: center">Deseja realmente excluir este cliente e os dados associados a ele (OS,
                Vendas, Receitas)?</h5>
        </div>
        <div class="modal-footer" style="display:flex;justify-content: center">
            <button class="button btn btn-warning" data-dismiss="modal" aria-hidden="true"><span class="button__icon"><i
                        class="bx bx-x"></i></span><span class="button__text2">Cancelar</span></button>
            <button class="button btn btn-danger"><span class="button__icon"><i class='bx bx-trash'></i></span> <span
                    class="button__text2">Excluir</span></button>
        </div>
    </form>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $(document).on('click', 'a', function (event) {
            var cliente = $(this).attr('cliente');
            $('#idCliente').val(cliente);
        });
    });
</script>
