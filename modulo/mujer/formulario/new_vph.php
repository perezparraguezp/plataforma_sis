<?php
$rut = $_POST['rut'];
$fecha_registro = $_POST['fecha_registro'];
?>
<form id="form_VPH" class="container" style="padding: 20px;">
    <input type="hidden" name="rut" value="<?php echo $rut; ?>" />
    <input type="hidden" name="fecha_registro" value="<?php echo $fecha_registro; ?>" />
    <input type="hidden" name="tipo_examen" value="VPH" />
    <div class="row">
        <div class="col l4 s4 m4">FECHA</div>
        <div class="col l8 s8 m8">
            <input type="date" name="fecha" id="fecha" value="<?php echo date('Y-m-d'); ?>" />
        </div>
    </div>
    <div class="row">
        <div class="col l4 s4 m4">ORIGEN</div>
        <div class="col l8 s8 m8">
            <select name="origen_examen" id="origen_examen">
                <option>INTRASISTEMA</option>
                <option>EXTRASISTEMA</option>
            </select>
        </div>
    </div>
    <div class="row">
        <div class="col l4 s4 m4">RESULTADO</div>
        <div class="col l8 s8 m8">
            <select name="valor_examen" id="valor_examen">
                <option>VPH (-)</option>
                <option>VPH 16(+)</option>
                <option>VPH 18(+)</option>
                <option>VPH ALTO RIESGO</option>
            </select>
        </div>
    </div>
    <div class="row">
        <div class="col l4 s4 m4">OBSERVACIONES</div>
        <div class="col l8 s8 m8">
            <textarea name="obs"></textarea>
        </div>
    </div>

    <div class="row">
        <div class="btn blue" style="width: 100%;" onclick="insertExamenVPH()"> REGISTRAR EXAMEN</div>
    </div>

</form>
<div class="modal-footer">
    <a href="#" id="close_modal" class="waves-effect waves-red btn-flat modal-action modal-close">CERRAR</a>
</div>
<script type="text/javascript">
    $(function(){
        $('#valor_examen').jqxDropDownList({
            width: '100%',
            height: '25px'
        });
        $('#origen_examen').jqxDropDownList({
            width: '100%',
            height: '25px'
        });

    });
    function insertExamenVPH(){
        if("¿Seguro que desea registrar este examen al paciente?"){
            $.post('db/insert/examen_mujer.php',
                $("#form_VPH").serialize()
                ,function (data) {
                    load_m_examenes('<?php echo $rut; ?>');
                    document.getElementById("close_modal").click();
                });
        }
    }
</script>
