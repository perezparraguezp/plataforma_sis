<?php
$rut = $_POST['rut'];
$fecha_registro = $_POST['fecha_registro'];
?>
<form id="form_HormonaReemplazo" class="container" style="padding: 20px;">
    <input type="hidden" name="rut" value="<?php echo $rut; ?>" />
    <input type="hidden" name="fecha_registro" value="<?php echo $fecha_registro; ?>" />
    <div class="row">
        <div class="col l4 s4 m4">TIPO HORMONA</div>
        <div class="col l8 s8 m8">
            <select name="tipo_hormona" id="tipo_hormona">
                <option>ESTRADIOL MICRONIZADO 1mg</option>
                <option>ESTRADIOL GEL</option>
                <option>PROGESTERONA MICRONIZADA 100mg</option>
                <option>PROGESTERONA MICRONIZADA 200mg</option>
                <option>NONMEGESTROL 5mg COMP.</option>
                <option>TIBOLONA 2,5mg COMP.</option>
                <option>OTROS</option>
            </select>
        </div>
    </div>
    <div class="row">
        <div class="col l4 s4 m4">DESDE</div>
        <div class="col l8 s8 m8">
            <input type="date" name="desde" id="desde" value="<?php echo date('Y-m-d'); ?>" />
        </div>
    </div>
    <div class="row">
        <div class="col l4 s4 m4">OBSERVACION</div>
        <div class="col l8 s8 m8">
            <textarea name="obs"></textarea>
        </div>
    </div>
    <div class="row">
        <div class="btn blue" style="width: 100%;" onclick="insertHormonaReemplazo()"> REGISTRAR </div>
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
    function insertHormonaReemplazo(){
        if("¿Seguro que desea asignar esta hormona al paciente?"){
            $.post('db/insert/hormona_reemplazo.php',
                $("#form_HormonaReemplazo").serialize()
                ,function (data) {
                    load_m_climaterio('<?php echo $rut; ?>');
                    document.getElementById("close_modal").click();
                });
        }
    }
</script>
