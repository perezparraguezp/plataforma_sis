<?php
$rut = $_POST['rut'];
$fecha_registro = $_POST['fecha_registro'];
?>
<form id="form_Hormona" class="container" style="padding: 20px;">
    <input type="hidden" name="rut" value="<?php echo $rut; ?>" />
    <input type="hidden" name="fecha_registro" value="<?php echo $fecha_registro; ?>" />
    <div class="row">
        <div class="col l4 s4 m4">TIPO HORMONA</div>
        <div class="col l8 s8 m8">
            <select name="tipo_hormona" id="tipo_hormona">
                <option>ORAL COMBINADO</option>
                <option>ORAL PROGESTÁGENO</option>
                <option>INYECTABLE COMBINADO</option>
                <option>INYECTABLE PROGESTÁGENO</option>
                <option>IMPLANTE ETONOGESTREL (3 AÑOS)</option>
                <option>IMPLANTE LEVONORGESTREL (5 AÑOS)</option>
                <option disabled="disabled">-----------------</option>
                <option>SOLO PRESERVATIVO MAC</option>
                <option disabled="disabled">-----------------</option>
                <option>D.I.U. T DE COBRE (10 AÑOS)</option>
                <option>D.I.U. CON LEVORGESTREL (6 AÑOS)</option>
                <option disabled="disabled">-----------------</option>
                <option>ESTERILIZACION QUIRURGICA</option>
            </select>
        </div>
    </div>
    <div class="row">
        <div class="col l4 s4 m4">VENCIMIENTO</div>
        <div class="col l8 s8 m8">
            <input type="date" name="vencimiento" id="vencimiento" value="<?php echo date('Y-m-d'); ?>" />
        </div>
    </div>
    <div class="row">
        <div class="col l4 s4 m4">OBSERVACION</div>
        <div class="col l8 s8 m8">
            <textarea name="obs"></textarea>
        </div>
    </div>
    <div class="row">
        <div class="btn blue" style="width: 100%;" onclick="insertHormonaPaciente()"> REGISTRAR </div>
    </div>

</form>
<div class="modal-footer">
    <a href="#" id="close_modal" class="waves-effect waves-red btn-flat modal-action modal-close">CERRAR</a>
</div>
<script type="text/javascript">
    $(function(){
        $('#tipo_hormona').jqxDropDownList({
            width: '100%',
            theme: 'eh-open',
            height: '25px'
        });

    });
    function insertHormonaPaciente(){
        if("¿Seguro que desea asignar esta hormona al paciente?"){
            $.post('db/insert/hormona.php',
                $("#form_Hormona").serialize()
                ,function (data) {
                    load_m_sexualidad('<?php echo $rut; ?>');
                    document.getElementById("close_modal").click();
                });
        }
    }
</script>
