<div class="modal fade" id="modal-gerar-pagamento" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <form id="form-gerar-cobranca" name="cobranca" method="post" action="<?php echo base_url() . 'index.php/cobrancas/adicionar'; ?>">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Registrar Cobrança</h4>
                </div>
                <div class="modal-body">
                    <input value="<?php echo $id ?>" name="id" type="hidden">
                    <input value="<?php echo $tipo ?>" name="tipo" type="hidden">

                    <div class="form-group">
                        <label for="cobr_descricao">Descrição</label>
                        <input type="text" id="cobr_descricao" name="descricao" class="span12"
                               placeholder="Ex: OS #<?php echo $id ?> — Serviço de instalação" required>
                    </div>

                    <div class="form-group">
                        <label for="cobr_forma_pagamento">Forma de Pagamento</label>
                        <select id="cobr_forma_pagamento" name="forma_pagamento" class="span12" required>
                            <option value="">Selecione...</option>
                            <option value="Dinheiro">Dinheiro</option>
                            <option value="PIX">PIX</option>
                            <option value="Cartão de Crédito">Cartão de Crédito</option>
                            <option value="Cartão de Débito">Cartão de Débito</option>
                            <option value="Boleto">Boleto</option>
                            <option value="Transferência">Transferência Bancária</option>
                            <option value="Cheque">Cheque</option>
                            <option value="A combinar">A combinar</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="cobr_vencimento">Vencimento</label>
                        <input type="text" id="cobr_vencimento" name="vencimento" class="span12 datepicker"
                               placeholder="dd/mm/aaaa" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="button btn btn-mini btn-default" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="button btn btn-mini btn-info">
                        <span class="button__icon"><i class='bx bx-dollar'></i></span>
                        <span class="button__text">Registrar Cobrança</span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
$(function () {
    $("#cobr_vencimento").datepicker({ dateFormat: 'dd/mm/yy' });

    $("#form-gerar-cobranca").on("submit", function (e) {
        e.preventDefault();
        var $btn = $(this).find('[type=submit]');
        $btn.prop('disabled', true).text('Aguarde...');

        $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function (resp) {
                if (resp.result) {
                    toastr.success('Cobrança registrada com sucesso!');
                    $('#modal-gerar-pagamento').modal('hide');
                    setTimeout(function () { location.reload(); }, 1200);
                } else {
                    toastr.error(resp.message || 'Erro ao registrar cobrança.');
                    $btn.prop('disabled', false).text('Registrar Cobrança');
                }
            },
            error: function (xhr) {
                var msg = 'Erro ao registrar cobrança.';
                try { msg = JSON.parse(xhr.responseText).message || msg; } catch(x) {}
                toastr.error(msg);
                $btn.prop('disabled', false).text('Registrar Cobrança');
            }
        });
    });
});
</script>
