<div class="new122">
    <link rel="stylesheet" href="<?= base_url(); ?>assets/js/jquery-ui/css/smoothness/jquery-ui-1.9.2.custom.css" />
    <script type="text/javascript" src="<?= base_url() ?>assets/js/jquery-ui/js/jquery-ui-1.9.2.custom.js"></script>

    <div class="widget-title" style="margin:-15px -10px 0">
        <h5>Arquivos</h5>
    </div>

    <?php if ($this->permission->checkPermission($this->session->userdata('permissao'), 'aArquivo')) : ?>
    <div style="margin:10px 0;">
        <a href="<?= base_url(); ?>index.php/arquivos/adicionar" class="button btn btn-mini btn-success" style="max-width:150px">
            <span class="button__icon"><i class='bx bx-plus-circle'></i></span><span class="button__text2">Arquivo</span>
        </a>
    </div>
    <?php endif ?>

    <?php
    $labelStyle = 'display:block;margin-bottom:4px;font-size:12px;color:#555;font-weight:600;white-space:nowrap';
    $inputStyle = 'width:100%;box-sizing:border-box;height:30px;padding:4px 6px;border:1px solid #ccc;border-radius:4px;font-size:13px';
    ?>
    <form method="get" action="<?= current_url(); ?>">
    <div style="display:flex;gap:10px;flex-wrap:wrap;align-items:flex-start;background:#f8f9fa;padding:12px 14px;border-radius:6px;border:1px solid #ddd;margin-bottom:12px;">
        <div style="flex:0 0 200px">
            <label style="<?= $labelStyle ?>">Buscar arquivo</label>
            <input type="text" name="pesquisa" id="pesquisa" placeholder="Nome do documento..." style="<?= $inputStyle ?>" value="<?= $this->input->get('pesquisa') ?>">
        </div>
        <div style="flex:0 0 115px">
            <label style="<?= $labelStyle ?>">Data de</label>
            <input type="text" name="data" id="data" placeholder="dd/mm/aaaa" class="datepicker" style="<?= $inputStyle ?>" value="<?= $this->input->get('data') ?>">
        </div>
        <div style="flex:0 0 115px">
            <label style="<?= $labelStyle ?>">Data até</label>
            <input type="text" name="data2" id="data2" placeholder="dd/mm/aaaa" class="datepicker" style="<?= $inputStyle ?>" value="<?= $this->input->get('data2') ?>">
        </div>
        <div style="flex-shrink:0">
            <label style="display:block;margin-bottom:4px;font-size:12px;visibility:hidden">x</label>
            <button type="submit" style="height:30px;padding:0 14px;white-space:nowrap;display:inline-flex;align-items:center;gap:5px;background:#f0810f;border:none;border-radius:4px;color:#fff;font-size:13px;font-weight:600;cursor:pointer">
                <i class='bx bx-search-alt' style="font-size:15px"></i> Filtrar
            </button>
        </div>
    </div>
    </form>

    <div class="widget-box">
        <div class="widget-content nopadding tab-content" style="overflow-x:auto;-webkit-overflow-scrolling:touch">
                <table id="tabela" width="100%" class="table table-bordered ">
                    <thead>
                        <tr>
                            <th width="5%">#</th>
                            <th width="10%">Miniatura</th>
                            <th width="10%">Nome</th>
                            <th width="8%">Data</th>
                            <th>Descrição</th>
                            <th width="8%">Tamanho</th>
                            <th width="5%">Extensão</th>
                            <th width="14%">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php

                        if (!$results) {
                            echo '<tr>
                                <td colspan="8">Nenhum Arquivo Encontrado</td>
                            </tr>';
                        }
    foreach ($results as $r) : ?>
                            <tr>
                                <td><?= $r->idDocumentos ?></td>
                                <td>
                                    <?php if (@getimagesize($r->path)) : ?>
                                        <a href="<?= $r->url ?>"> <img src="<?= $r->url ?> "></a>
                                    <?php else : ?>
                                        <span>-</span>
                                    <?php endif ?>
                                </td>
                                <td><?= $r->documento ?></td>
                                <td><?= date('d/m/Y', strtotime($r->cadastro)) ?></td>
                                <td><?= $r->descricao ?></td>
                                <td><?= $r->tamanho ?> KB</td>
                                <td><?= $r->tipo ?></td>
                                <td><?php if ($this->permission->checkPermission($this->session->userdata('permissao'), 'vArquivo')) : ?>
                                        <a href="<?= base_url() ?>index.php/arquivos/download/<?= $r->idDocumentos; ?>" class="btn-nwe" title="Baixar Arquivo"><i class="bx bx-download"></i>
                                        <?php endif ?>

                                        <?php if ($this->permission->checkPermission($this->session->userdata('permissao'), 'eArquivo')) : ?>
                                            <a href="<?= base_url() ?>index.php/arquivos/editar/<?= $r->idDocumentos ?>" class="btn-nwe3" title="Editar"><i class="bx bx-edit"></i></a>
                                        <?php endif ?>

                                        <?php if ($this->permission->checkPermission($this->session->userdata('permissao'), 'dArquivo')) : ?>
                                            <a href="#modal-excluir" style="margin-right: 1%" role="button" data-toggle="modal" arquivo="<?= $r->idDocumentos ?>" class="btn-nwe4" title="Excluir"><i class="bx bx-trash-alt"></i></a>
                                        </a>
                                    <?php endif ?>
                                </td>
                            </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <?= $this->pagination->create_links() ?>

    <div id="modal-excluir" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <form action="<?= base_url() ?>index.php/arquivos/excluir" method="post">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h5 id="myModalLabel">Excluir Arquivo</h5>
            </div>
            <div class="modal-body">
                <input type="hidden" id="idDocumento" name="id" value="" />
                <h5 style="text-align: center">Deseja realmente excluir este arquivo?</h5>
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
            var arquivo = $(this).attr('arquivo');
            $('#idDocumento').val(arquivo);
        });
        $(".datepicker").datepicker({
            dateFormat: 'dd/mm/yy'
        });
    });
</script>