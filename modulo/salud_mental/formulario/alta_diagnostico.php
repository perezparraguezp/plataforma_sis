<?php
include "../../../php/config.php";
include "../../../php/objetos/persona.php";

$rut = $_POST['rut'];
$id = $_POST['id'];
$sql = "select * from paciente_diagnosticos_sm 
                  where id='$id' limit 1";
$row = mysql_fetch_array(mysql_query($sql));

?>
<style type="text/css">
    #form_Hormona .row{
        margin: 10px;
        margin-top: 10px;
    }
</style>
<form id="form_Hormona" class="container" style="padding: 20px;">
    <input type="hidden" name="rut" value="<?php echo $rut; ?>" />
    <input type="hidden" name="id" value="<?php echo $id; ?>" />

    <div class="row">
        <div class="col l4 s4 m4">FECHA ALTA</div>
        <div class="col l8 s8 m8">
            <input type="date" name="fecha_egreso" id="fecha_egreso" value="<?php echo date('Y-m-d'); ?>" />
        </div>
    </div>
    <div class="row">
        <div class="col l4 s4 m4">OBSERVACIONES ALTA</div>
        <div class="col l8 s8 m8">
            <textarea name="obs_alta" rows="10"></textarea>
        </div>
    </div>
    <div class="row">
        <div class="btn blue" style="width: 100%;" onclick="updateDiagnosticoAlta()"> REGISTRAR ALTA </div>
    </div>

</form>
<div class="modal-footer">
    <a href="#" id="close_modal" class="waves-effect waves-red btn-flat modal-action modal-close">CERRAR</a>
</div>
<script type="text/javascript">
    $(function(){

    });
    function updateDiagnosticoAlta(){
        if("¿Seguro que desea actualizar esta información?"){
            $.post('db/update/diagnostico.php',
                $("#form_Hormona").serialize()
                ,function (data) {
                    load_sm_diagnosticos('<?php echo $rut; ?>');
                    document.getElementById("close_modal").click();
                });
        }
    }
</script>
