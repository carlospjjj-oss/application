<?php
/**
 * SNIPPET: Dados da Instalação do Carregador EV
 * Guardado para uso futuro (aba Anexos ou outro lugar)
 * Removido da view editarOs.php em 2026-11-19
 */
?>
<!-- ===== SEÇÃO: DADOS DA INSTALAÇÃO EV ===== -->
<div class="span12" style="padding: 10px 10px 0 10px; margin-top:10px;">
    <h4 style="border-bottom:1px solid #ddd; padding-bottom:8px; margin-bottom:12px;">
        <i class="fas fa-plug" style="color:#5cb85c"></i> Dados da Instalação do Carregador
    </h4>
</div>
<div class="span6" style="padding: 1%; margin-left: 0">
    <label for="carregador_marca_modelo"><h4>Marca / Modelo do Carregador</h4></label>
    <input type="text" class="span12" name="carregador_marca_modelo" id="carregador_marca_modelo" value="<?php echo $result->carregador_marca_modelo ?>" placeholder="Ex: WEG, Intelbras, Wallbox..." />
</div>
<div class="span6" style="padding: 1%; margin-left: 0">
    <label for="carregador_potencia"><h4>Potência do Carregador</h4></label>
    <select class="span12" name="carregador_potencia" id="carregador_potencia">
        <option value="">Selecione...</option>
        <option value="3.7 kW" <?php if($result->carregador_potencia=='3.7 kW') echo 'selected'; ?>>3,7 kW (Monofásico 16A)</option>
        <option value="7.4 kW" <?php if($result->carregador_potencia=='7.4 kW') echo 'selected'; ?>>7,4 kW (Monofásico 32A)</option>
        <option value="11 kW" <?php if($result->carregador_potencia=='11 kW') echo 'selected'; ?>>11 kW (Trifásico 16A)</option>
        <option value="22 kW" <?php if($result->carregador_potencia=='22 kW') echo 'selected'; ?>>22 kW (Trifásico 32A)</option>
        <option value="50 kW+" <?php if($result->carregador_potencia=='50 kW+') echo 'selected'; ?>>50 kW+ (DC Fast Charging)</option>
    </select>
</div>
<div class="span6" style="padding: 1%; margin-left: 0">
    <label for="carregador_numero_serie"><h4>Número de Série do Equipamento</h4></label>
    <input type="text" class="span12" name="carregador_numero_serie" id="carregador_numero_serie" value="<?php echo $result->carregador_numero_serie ?>" />
</div>
<div class="span6" style="padding: 1%; margin-left: 0">
    <label for="laudo_emitido"><h4>Laudo Elétrico Emitido?</h4></label>
    <select class="span12" name="laudo_emitido" id="laudo_emitido">
        <option value="0" <?php if($result->laudo_emitido==0) echo 'selected'; ?>>Não</option>
        <option value="1" <?php if($result->laudo_emitido==1) echo 'selected'; ?>>Sim</option>
    </select>
</div>
<div class="span12" style="padding: 1%; margin-left: 0">
    <h4>Checklist de Instalação</h4>
    <div style="display:flex; flex-wrap:wrap; gap:10px; padding: 8px 0;">
        <label style="display:flex;align-items:center;gap:6px;font-weight:normal;">
            <input type="checkbox" name="check_quadro" value="1" <?php if($result->check_quadro) echo 'checked'; ?>> Verificação do quadro elétrico
        </label>
        <label style="display:flex;align-items:center;gap:6px;font-weight:normal;">
            <input type="checkbox" name="check_disjuntor" value="1" <?php if($result->check_disjuntor) echo 'checked'; ?>> Instalação do disjuntor dedicado
        </label>
        <label style="display:flex;align-items:center;gap:6px;font-weight:normal;">
            <input type="checkbox" name="check_cabos" value="1" <?php if($result->check_cabos) echo 'checked'; ?>> Passagem de cabos e eletrodutos
        </label>
        <label style="display:flex;align-items:center;gap:6px;font-weight:normal;">
            <input type="checkbox" name="check_fixacao" value="1" <?php if($result->check_fixacao) echo 'checked'; ?>> Fixação e cabeamento do carregador
        </label>
        <label style="display:flex;align-items:center;gap:6px;font-weight:normal;">
            <input type="checkbox" name="check_teste" value="1" <?php if($result->check_teste) echo 'checked'; ?>> Teste de funcionamento e carga
        </label>
        <label style="display:flex;align-items:center;gap:6px;font-weight:normal;">
            <input type="checkbox" name="check_orientacao" value="1" <?php if($result->check_orientacao) echo 'checked'; ?>> Orientação ao cliente sobre uso
        </label>
    </div>
</div>
<!-- ===== FIM SEÇÃO INSTALAÇÃO EV ===== -->
