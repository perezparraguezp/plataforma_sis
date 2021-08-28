<?php
$rut = $_POST['rut'];
$fecha_registro = $_POST['fecha_registro'];
?>
<form id="form_TALLER" class="container" style="padding: 20px;">
    <input type="hidden" name="rut" value="<?php echo $rut; ?>" />
    <input type="hidden" name="fecha_registro" value="<?php echo $fecha_registro; ?>" />
    <div class="row">
        <div class="col l4 s4 m4">FECHA</div>
        <div class="col l8 s8 m8">
            <input type="date" name="fecha" id="fecha" value="<?php echo date('Y-m-d'); ?>" />
        </div>
    </div>
    <div class="row">
        <div class="col l4 s4 m4">OBSERVACIONES</div>
        <div class="col l8 s8 m8">
            <textarea name="obs"></textarea>
        </div>
    </div>

    <div class="row">
        <div class="btn blue" style="width: 100%;" onclick="insertTALLER_CLIMATERIO()"> REGISTRAR TALLER</div>
    </div>

</form>
<div class="modal-footer">
    <a href="#" id="close_modal" class="waves-effect waves-red btn-flat modal-action modal-close">CERRAR</a>
</div>
<script type="text/javascript">
    $(function(){


    });
    function insertTALLER_CLIMATERIO(){
        if("¿Seguro que desea registrar este taller al paciente?"){
            $.post('db/insert/taller_climaterio.php',
                $("#form_TALLER").serialize()
                ,function (data) {
                    load_m_climaterio('<?php echo $rut; ?>');
                    document.getElementById("close_modal").click();
                });
        }
    }
</script>
