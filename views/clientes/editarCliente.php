<script src="<?php echo base_url() ?>assets/js/jquery.mask.min.js"></script>
<script src="<?php echo base_url() ?>assets/js/sweetalert2.all.min.js"></script>
<script src="<?php echo base_url() ?>assets/js/funcoes.js"></script>
<style>
    #imgSenha {
        width: 18px;
        cursor: pointer;
    }

    /* Hiding the checkbox, but allowing it to be focused */
    .badgebox {
        opacity: 0;
    }

    .badgebox+.badge {
        /* Move the check mark away when unchecked */
        text-indent: -999999px;
        /* Makes the badge's width stay the same checked and unchecked */
        width: 27px;
    }

    .badgebox:focus+.badge {
        /* Set something to make the badge looks focused */
        /* This really depends on the application, in my case it was: */
        /* Adding a light border */
        box-shadow: inset 0px 0px 5px;
        /* Taking the difference out of the padding */
    }

    .badgebox:checked+.badge {
        /* Move the check mark back when checked */
        text-indent: 0;
    }

    .control-group.error .help-inline {
        display: flex;
    }

    .form-horizontal .control-group {
        border-bottom: 1px solid #ffffff;
    }

    .form-horizontal .controls {
        margin-left: 20px;
        padding-bottom: 8px 0;
    }

    .form-horizontal .control-label {
        text-align: left;
        padding-top: 15px;
    }

    .nopadding {
        padding: 0 20px !important;
        margin-right: 20px;
    }

    .widget-title h5 {
        padding-bottom: 30px;
        text-align-last: left;
        font-size: 2em;
        font-weight: 500;
    }

    @media (max-width: 480px) {
        form {
            display: contents !important;
        }

        .form-horizontal .control-label {
            margin-bottom: -6px;
        }

        .btn-xs {
            position: initial !important;
        }
    }
</style>
<div class="row-fluid" style="margin-top:0">
    <div class="span12">
        <div class="widget-box">
            <div class="widget-title" style="margin: -20px 0 0">
                <span class="icon">
                    <i class="fas fa-user"></i>
                </span>
                <h5>Editar Cliente</h5>
            </div>
            <?php if ($custom_error != '') {
                echo '<div class="alert alert-danger">' . $custom_error . '</div>';
            } ?>
            <form action="<?php echo current_url(); ?>" id="formCliente" method="post" class="form-horizontal">
                <div class="widget-content nopadding tab-content">
                    <div class="span6">
                        <div class="control-group">
                            <label for="documento" class="control-label">CPF/CNPJ<span class="required">*</span></label>
                            <div class="controls">
                                <input id="documento" class="cpfcnpj" type="text" name="documento" value="<?php echo $result->documento; ?>" />
                                <button id="buscar_info_cnpj" class="btn btn-xs" type="button">Buscar(CNPJ)</button>
                            </div>
                        </div>
                        <div class="control-group">
                            <?php echo form_hidden('idClientes', $result->idClientes) ?>
                            <label for="nomeCliente" class="control-label">Nome/Razão Social<span class="required">*</span></label>
                            <div class="controls">
                                <input id="nomeCliente" type="text" name="nomeCliente" value="<?php echo $result->nomeCliente; ?>" />
                            </div>
                        </div>
                        <div class="control-group">
                            <label for="contato" class="control-label">Contato:<span class="required">*</span></label>
                            <div class="controls">
                                <input class="contato" type="text" name="contato" value="<?php echo $result->contato; ?>" />
                            </div>
                        </div>
                        <div class="control-group">
                            <label for="telefone" class="control-label">Telefone<span class="required">*</span></label>
                            <div class="controls">
                                <input id="telefone" type="text" name="telefone" value="<?php echo $result->telefone; ?>" />
                            </div>
                        </div>
                        <div class="control-group">
                            <label for="celular" class="control-label">Celular<span class="required">*</span></label>
                            <div class="controls">
                                <input id="celular" type="text" name="celular" value="<?php echo $result->celular; ?>" />
                            </div>
                        </div>
                        <div class="control-group">
                            <label for="email" class="control-label">Email<span class="required">*</span></label>
                            <div class="controls">
                                <input id="email" type="text" name="email" value="<?php echo $result->email; ?>" />
                            </div>
                        </div>
                        <div class="control-group">
                            <label for="senha" class="control-label">Senha</label>
                            <div class="controls">
                                <input id="senha" type="password" name="senha" value="" placeholder="Não preencha se não quiser alterar." />
                                <img id="imgSenha" src="<?php echo base_url() ?>assets/img/eye.svg" alt="">
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label">Tipo de Cliente</label>
                            <div class="controls">
                                <label for="fornecedor" class="btn btn-default">Fornecedor
                                    <input type="checkbox" id="fornecedor" name="fornecedor" class="badgebox" value="1" <?= ($result->fornecedor == 1) ? 'checked' : '' ?>>
                                    <span class="badge">&check;</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="span6">
                        <div class="control-group" class="control-label">
                            <label for="cep" class="control-label">CEP<span class="required">*</span></label>
                            <div class="controls">
                                <input id="cep" type="text" name="cep" value="<?php echo $result->cep; ?>" />
                            </div>
                        </div>
                        <div class="control-group" class="control-label">
                            <label for="rua" class="control-label">Rua</label>
                            <div class="controls">
                                <input id="rua" type="text" name="rua" value="<?php echo $result->rua; ?>" />
                            </div>
                        </div>
                        <div class="control-group">
                            <label for="numero" class="control-label">Número<span class="required">*</span></label>
                            <div class="controls">
                                <input id="numero" type="text" name="numero" value="<?php echo $result->numero; ?>" />
                            </div>
                        </div>
                        <div class="control-group">
                            <label for="complemento" class="control-label">Complemento<span class="required">*</span></label>
                            <div class="controls">
                                <input id="complemento" type="text" name="complemento" value="<?php echo $result->complemento; ?>" />
                            </div>
                        </div>
                        <div class="control-group" class="control-label">
                            <label for="bairro" class="control-label">Bairro</label>
                            <div class="controls">
                                <input id="bairro" type="text" name="bairro" value="<?php echo $result->bairro; ?>" />
                            </div>
                        </div>
                        <div class="control-group" class="control-label">
                            <label for="cidade" class="control-label">Cidade</label>
                            <div class="controls">
                                <input id="cidade" type="text" name="cidade" value="<?php echo $result->cidade; ?>" />
                            </div>
                        </div>
                        <div class="control-group" class="control-label">
                            <label for="estado" class="control-label">Estado</label>
                            <div class="controls">
                                <select id="estado" name="estado" class="">
                                    <option value="">Selecione...</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ===== SEÇÃO: DADOS ELÉTRICOS E VEÍCULO ===== -->
                <div class="widget-content nopadding">
                    <div class="span12" style="padding: 10px 0 0 10px;">
                        <h4 style="border-bottom:1px solid #ddd; padding-bottom:8px; margin-bottom:12px;">
                            <i class="fas fa-bolt" style="color:#f0ad4e"></i> Dados do Imóvel e Veículo Elétrico
                        </h4>
                    </div>
                    <div class="span6">
                        <div class="control-group">
                            <label for="tipo_imovel" class="control-label">Tipo de Imóvel</label>
                            <div class="controls">
                                <select id="tipo_imovel" name="tipo_imovel">
                                    <option value="">Selecione...</option>
                                    <option value="Casa" <?php if($result->tipo_imovel=='Casa') echo 'selected'; ?>>Casa</option>
                                    <option value="Apartamento" <?php if($result->tipo_imovel=='Apartamento') echo 'selected'; ?>>Apartamento</option>
                                    <option value="Condomínio" <?php if($result->tipo_imovel=='Condomínio') echo 'selected'; ?>>Condomínio</option>
                                    <option value="Empresa" <?php if($result->tipo_imovel=='Empresa') echo 'selected'; ?>>Empresa</option>
                                    <option value="Outro" <?php if($result->tipo_imovel=='Outro') echo 'selected'; ?>>Outro</option>
                                </select>
                            </div>
                        </div>
                        <div class="control-group">
                            <label for="concessionaria_energia" class="control-label">Concessionária de Energia</label>
                            <div class="controls">
                                <input id="concessionaria_energia" type="text" name="concessionaria_energia" value="<?php echo $result->concessionaria_energia; ?>" placeholder="Ex: Enel, CPFL, Cemig..." />
                            </div>
                        </div>
                        <div class="control-group">
                            <label for="numero_uc" class="control-label">Número da UC (Unidade Consumidora)</label>
                            <div class="controls">
                                <input id="numero_uc" type="text" name="numero_uc" value="<?php echo $result->numero_uc; ?>" placeholder="Código na conta de energia" />
                            </div>
                        </div>
                    </div>
                    <div class="span6">
                        <div class="control-group">
                            <label for="veiculo_marca_modelo" class="control-label">Veículo Elétrico (Marca/Modelo)</label>
                            <div class="controls">
                                <input id="veiculo_marca_modelo" type="text" name="veiculo_marca_modelo" value="<?php echo $result->veiculo_marca_modelo; ?>" placeholder="Ex: BYD Dolphin, Volvo XC40..." />
                            </div>
                        </div>
                        <div class="control-group">
                            <label for="veiculo_tipo_conector" class="control-label">Tipo de Conector do Veículo</label>
                            <div class="controls">
                                <select id="veiculo_tipo_conector" name="veiculo_tipo_conector">
                                    <option value="">Selecione...</option>
                                    <option value="Type 2" <?php if($result->veiculo_tipo_conector=='Type 2') echo 'selected'; ?>>Type 2 (Mennekes)</option>
                                    <option value="CCS Combo 2" <?php if($result->veiculo_tipo_conector=='CCS Combo 2') echo 'selected'; ?>>CCS Combo 2</option>
                                    <option value="CHAdeMO" <?php if($result->veiculo_tipo_conector=='CHAdeMO') echo 'selected'; ?>>CHAdeMO</option>
                                    <option value="J1772" <?php if($result->veiculo_tipo_conector=='J1772') echo 'selected'; ?>>J1772</option>
                                    <option value="GB/T" <?php if($result->veiculo_tipo_conector=='GB/T') echo 'selected'; ?>>GB/T</option>
                                    <option value="Outro" <?php if($result->veiculo_tipo_conector=='Outro') echo 'selected'; ?>>Outro</option>
                                </select>
                            </div>
                        </div>
                        <div class="control-group">
                            <label for="carregador_atual" class="control-label">Carregador Atual (se houver)</label>
                            <div class="controls">
                                <input id="carregador_atual" type="text" name="carregador_atual" value="<?php echo $result->carregador_atual; ?>" placeholder="Marca/modelo já instalado" />
                            </div>
                        </div>
                    </div>
                </div>
                <!-- ===== FIM SEÇÃO EV ===== -->

                <div class="form-actions">
                    <div class="span12">
                        <div class="span6 offset3" style="display:flex;justify-content: center">
                            <button type="submit" class="button btn btn-primary" style="max-width: 160px">
                                <span class="button__icon"><i class="bx bx-sync"></i></span><span class="button__text2">Atualizar</span></button>
                            <a title="Voltar" class="button btn btn-warning" href="<?php echo site_url() ?>/clientes"><span class="button__icon"><i class="bx bx-undo"></i></span> <span class="button__text2">Voltar</span></a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script src="<?php echo base_url() ?>assets/js/jquery.validate.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        let container = document.querySelector('div');
        let input = document.querySelector('#senha');
        let icon = document.querySelector('#imgSenha');

        icon.addEventListener('click', function() {
            container.classList.toggle('visible');
            if (container.classList.contains('visible')) {
                icon.src = '<?php echo base_url() ?>assets/img/eye-off.svg';
                input.type = 'text';
            } else {
                icon.src = '<?php echo base_url() ?>assets/img/eye.svg'
                input.type = 'password';
            }
        });

        $.getJSON('<?php echo base_url() ?>assets/json/estados.json', function(data) {
            for (i in data.estados) {
                $('#estado').append(new Option(data.estados[i].nome, data.estados[i].sigla));
            }
            var curState = '<?php echo $result->estado; ?>';
            if (curState) {
                $("#estado option[value=" + curState + "]").prop("selected", true);
            }

        });
        $('#formCliente').validate({
            rules: {
                nomeCliente: { required: true },
                documento:   { required: true },
                telefone:    { required: true },
                celular:     { required: true },
                email:       { required: true },
                contato:     { required: true },
                cep:         { required: true },
                numero:      { required: true },
                complemento: { required: true },
            },
            messages: {
                nomeCliente: { required: 'Campo Requerido.' },
                documento:   { required: 'Campo Requerido.' },
                telefone:    { required: 'Campo Requerido.' },
                celular:     { required: 'Campo Requerido.' },
                email:       { required: 'Campo Requerido.' },
                contato:     { required: 'Campo Requerido.' },
                cep:         { required: 'Campo Requerido.' },
                numero:      { required: 'Campo Requerido.' },
                complemento: { required: 'Campo Requerido.' },
            },

            errorClass: "help-inline",
            errorElement: "span",
            highlight: function(element, errorClass, validClass) {
                $(element).parents('.control-group').addClass('error');
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).parents('.control-group').removeClass('error');
                $(element).parents('.control-group').addClass('success');
            }
        });
    });
</script>
