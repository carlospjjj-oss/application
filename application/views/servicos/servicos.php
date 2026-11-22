<style>
  select { width: 70px; }
  #tabela_wrapper .dataTables_filter { display: none !important; }
</style>
<div class="new122">
    <div class="widget-title" style="margin: -20px 0 0">
        <span class="icon">
            <i class="fas fa-wrench"></i>
        </span>
        <h5>Serviços</h5>
    </div>
    <div class="span12" style="margin-left: 0">
        <?php if ($this->permission->checkPermission($this->session->userdata('permissao'), 'aServico')) : ?>
            <div style="margin-bottom:10px;">
                <a href="<?= base_url() ?>index.php/servicos/adicionar" class="button btn btn-mini btn-success" style="max-width: 160px">
                    <span class="button__icon"><i class='bx bx-plus-circle'></i></span><span class="button__text2"> Serviços</span>
                </a>
            </div>
        <?php endif; ?>
        <form method="get" action="<?= base_url() ?>index.php/servicos">
        <?php
        $labelStyle = 'display:block;margin-bottom:4px;font-size:12px;color:#555;font-weight:600;white-space:nowrap';
        $inputStyle = 'width:100%;box-sizing:border-box;height:30px;padding:4px 6px;border:1px solid #ccc;border-radius:4px;font-size:13px';
        ?>
        <div style="display:flex;gap:10px;flex-wrap:nowrap;align-items:flex-start;background:#f8f9fa;padding:12px 14px;border-radius:6px;border:1px solid #ddd;margin-bottom:12px;">

            <div style="flex:0 0 300px">
                <label style="<?= $labelStyle ?>">Buscar serviço</label>
                <input type="text" name="pesquisa" id="pesquisa" placeholder="Nome ou Descrição..." style="<?= $inputStyle ?>" value="<?= $this->input->get('pesquisa') ?>">
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
                        <th>Preço</th>
                        <th class="col-mobile-hide">Descrição</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        if (!$results) {
                            echo '<tr>
                                    <td colspan="5">Nenhum Serviço Cadastrado</td>
                                </tr>';
                        }
        foreach ($results as $r) {
            echo '<tr>';
            echo '<td>' . $r->idServicos . '</td>';
            echo '<td>' . $r->nome . '</td>';
            echo '<td>' . number_format($r->preco, 2, ',', '.') . '</td>';
            echo '<td class="col-mobile-hide">' . $r->descricao . '</td>';
            echo '<td>';
            if ($this->permission->checkPermission($this->session->userdata('permissao'), 'eServico')) {
                echo '<a style="margin-right: 1%" href="' . base_url() . 'index.php/servicos/editar/' . $r->idServicos . '" class="btn-nwe3" title="Editar Serviço"><i class="bx bx-edit bx-xs"></i></a>';
            }
            if ($this->permission->checkPermission($this->session->userdata('permissao'), 'dServico')) {
                echo '<a href="#modal-excluir" role="button" data-toggle="modal" servico="' . $r->idServicos . '" class="btn-nwe4" title="Excluir Serviço"><i class="bx bx-trash-alt bx-xs"></i></a>  ';
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
    <form action="<?php echo base_url() ?>index.php/servicos/excluir" method="post">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h5 id="myModalLabel">Excluir Serviço</h5>
        </div>
        <div class="modal-body">
            <input type="hidden" id="idServico" name="id" value="" />
            <h5 style="text-align: center">Deseja realmente excluir este serviço?</h5>
        </div>
        <div class="modal-footer" style="display:flex;justify-content: center">
          <button class="button btn btn-warning" data-dismiss="modal" aria-hidden="true"><span class="button__icon"><i class="bx bx-x"></i></span><span class="button__text2">Cancelar</span></button>
          <button class="button btn btn-danger"><span class="button__icon"><i class='bx bx-trash'></i></span> <span class="button__text2">Excluir</span></button>
        </div>
    </form>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $(document).on('click', 'a', function(event) {
            var servico = $(this).attr('servico');
            $('#idServico').val(servico);
        });
    });
</script>
