<?php
include '../../../php/config.php';
include '../../../php/objetos/profesional.php';
session_start();
$myId = $_SESSION['id_usuario'];
$profesional = new profesional($myId);

?>
<form id="formUpdate" class="modal-content">
    <input type="hidden" name="id" value="<?php echo $myId; ?>" />
    <div class="row">
        <div class="col l4">RUN</div>
        <div class="col l8">
            <input type="text" name="rut" value="<?php echo $profesional->rut; ?>" disabled="disabled" />
        </div>
    </div>
    <div class="row">
        <div class="col l4">NOMBRE COMPLETO</div>
        <div class="col l8">
            <input type="text" name="nombre" value="<?php echo $profesional->nombre; ?>" />
        </div>
    </div>
    <div class="row">
        <div class="col l4">TELEFONO</div>
        <div class="col l8">
            <input type="text" name="telefono" value="<?php echo $profesional->telefono; ?>" />
        </div>
    </div>
    <div class="row">
        <div class="col l4">E-MAIL</div>
        <div class="col l8">
            <input type="text" name="correo" value="<?php echo $profesional->email; ?>" />
        </div>
    </div>
    <div class="row">
        <div class="col l4">NUEVA CONTRASEÑA</div>
        <div class="col l8">
            <input type="text" name="password" value="<?php echo $profesional->clave; ?>" />
        </div>
    </div>
    <div class="row">
        <input type="button"
               onclick="updateInfoProfesional()"
               value="ACTUALIZAR INFORMACION" class="btn green white-text" style="width: 100%;" />
    </div>
</form>
<div class="modal-footer">
    <a href="#" id="close_modal" class="waves-effect waves-red btn-flat modal-action modal-close">CERRAR</a>
</div>
<script type="text/javascript">
    function updateInfoProfesional(){
        $.post('modulo/default/db/update/info_profesional.php',
            $("#formUpdate").serialize()
            ,function(data){
            if(data !== 'ERROR_SQL'){
                location.reload();
            }
        });
    }
</script>