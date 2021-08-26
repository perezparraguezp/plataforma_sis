<?php
$rut = $_POST['rut'];
$fecha_registro = $_POST['fecha_registro'];
$id_gestacion = $_POST['id_gestacion'];
?>
<form id="form_VDI" class="container" style="padding: 20px;">
    <input type="hidden" name="rut" value="<?php echo $rut; ?>" />
    <input type="hidden" name="fecha_registro" value="<?php echo $fecha_registro; ?>" />
    <input type="hidden" name="id_gestacion" value="<?php echo $id_gestacion; ?>" />
    <div class="row">
        <div class="col l4 s4 m4">FECHA VISITA</div>
        <div class="col l8 s8 m8">
            <input type="date" name="fecha_vdi" id="fecha_vdi" value="<?php echo date('Y-m-d'); ?>" />
        </div>
    </div>
    <div class="row">
        <div class="col l4 s4 m4">OBSERVACIONES</div>
        <div class="col l8 s8 m8">
            <textarea name="obs_vdi"></textarea>
        </div>
    </div>
    <div class="row">
        <div class="btn blue" style="width: 100%;" onclick="insertEcografia()"> REGISTRAR VISITA</div>
    </div>

</form>
<div class="modal-footer">
    <a href="#" id="close_modal" class="waves-effect waves-red btn-flat modal-action modal-close">CERRAR</a>
</div>
<script type="text/javascript">
    $(function(){

    });
    function insertEcografia(){
        if("¿Seguro que desea registrar esta visita al paciente?"){
            $.post('db/insert/visita_vdi.php',
                $("#form_VDI").serialize()
                ,function (data) {
                    load_m_gestaciones('<?php echo $rut; ?>');
                    document.getElementById("close_modal").click();
                });
        }
    }
</script>
