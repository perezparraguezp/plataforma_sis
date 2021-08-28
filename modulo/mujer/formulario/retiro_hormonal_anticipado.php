<?php
include "../../../php/config.php";
include "../../../php/objetos/persona.php";

$rut = $_POST['rut'];
$id_historial = $_POST['id_historial'];
$sql = "select * from mujer_historial_hormonal where id_historial='$id_historial' limit 1";
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
    <input type="hidden" name="id_historial" value="<?php echo $id_historial; ?>" />
    <div class="row">
        <div class="col l4 s4 m4">TIPO HORMONA</div>
        <div class="col l8 s8 m8">
            <strong><?php echo $row['tipo']; ?></strong>
        </div>
    </div>
    <div class="row">
        <div class="col l4 s4 m4">RETIRO ANCICIPADO</div>
        <div class="col l8 s8 m8">
            <input type="date" name="retiro_anticipado" id="retiro_anticipado" value="<?php echo date('Y-m-d'); ?>" />
        </div>
    </div>
    <div class="row">
        <div class="col l4 s4 m4">MOTIVO RETIRO</div>
        <div class="col l8 s8 m8">
            <textarea name="motivo_retiro" rows="10"></textarea>
        </div>
    </div>
    <div class="row">
        <div class="btn blue" style="width: 100%;" onclick="updateHormona()"> ACTUALIZAR </div>
    </div>

</form>
<div class="modal-footer">
    <a href="#" id="close_modal" class="waves-effect waves-red btn-flat modal-action modal-close">CERRAR</a>
</div>
<script type="text/javascript">
    $(function(){

    });
    function updateHormona(){
        if("¿Seguro que desea actualizar esta información?"){
            $.post('db/update/hormona.php',
                $("#form_Hormona").serialize()
                ,function (data) {
                    load_m_sexualidad('<?php echo $rut; ?>');
                    document.getElementById("close_modal").click();
                });
        }
    }
</script>
