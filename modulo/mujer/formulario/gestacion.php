<?php
include "../../../php/config.php";
include "../../../php/objetos/persona.php";
include "../../../php/objetos/profesional.php";

$rut = $_POST['rut'];
$fecha_registro = $_POST['fecha_registro'];
$id_gestacion = $_POST['id_gestacion'];
$paciente = new persona($rut);
?>
<form id="form_GestacionConfig" class="container" style="padding: 20px;">
    <input type="hidden" name="id_gestacion" value="<?php echo $id_gestacion; ?>" />
    <input type="hidden" name="rut" value="<?php echo $rut; ?>" />
    <input type="hidden" name="fecha_registro" value="<?php echo $fecha_registro; ?>" />
    <fieldset>
        <legend>CONFIGURACIÓN DE LA GESTACIÓN</legend>
        En esta ventana el usuario podrá brindar informacion necesaria.
    </fieldset>
    <hr class="row" />
    <div class="row">
        <div class="col l4">
            <div class="row">
                <div class="col l12">INICIO</div>
                <div class="col l12">
                    <input type="date"

                           name="inicio" id="inicio"
                           value="<?php echo $paciente->getParametroGestacion_M('fecha_inicio',$id_gestacion); ?>" />
                </div>
            </div>
        </div>
        <div class="col l4">
            <div class="row">
                <div class="col l12">PROYECTADA</div>
                <div class="col l12">
                    <input type="date"
                           name="proyectada" id="proyectada"
                           value="<?php echo $paciente->getParametroGestacion_M('fecha_proyectada',$id_gestacion); ?>" />
                </div>
            </div>
        </div>
        <div class="col l4">
            <div class="row">
                <div class="col l12">TERMINO</div>
                <div class="col l12">
                    <input type="date"
                           name="termino" id="termino"
                           value="<?php echo $paciente->getParametroGestacion_M('fecha_termino',$id_gestacion); ?>" />
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col l4">CANTIDAD DE BEBES</div>
        <div class="col l8">
            <input type="number" min="1" value="<?php echo $paciente->getParametroGestacion_M('candidad_bebes',$id_gestacion); ?>" name="cantidad_bebes"  />
        </div>
    </div>
    <div class="row">
        <div class="col l4">OBSERVACIONES GENERALES</div>
        <div class="col l8">
            <textarea name="obs_generales"><?php echo $paciente->getParametroGestacion_M('obs_gestacion',$id_gestacion); ?></textarea>
        </div>
    </div>
    <hr />
    <div class="row">
        <div class="col l10">
            <p class="red-text">CONFIRMAR TERMINO DE LA GESTACIÓN SEGUN LAS DISPOSICIONES ESTABLECIDAS.</p>
        </div>
        <div class="col l2">
            <input type="checkbox" id="finalizar_gestacion"
                   onchange="";
                   name="finalizar_gestacion"  />
            <label class="white-text" for="finalizar_gestacion">SI</label>
        </div>
    </div>
    <hr />
    <div class="row" id="div_finalizar" style="display: none;">
        <div class="col l6 m6 s6">
            <div class="red darken-4 white-text"
                 onclick="updateEstadoGestacion('INTERRUMPIDA')"
                 style="width: 100%;padding: 20px;text-align: center;">INTERRUPCION DE GESTACIÓN</div>
        </div>
        <div class="col l6 m6 s6">
            <div class="light-blue darken-4 white-text"
                 onclick="updateEstadoGestacion('EXITOSA')"
                 style="width: 100%;padding: 20px;text-align: center;">GESTACIÓN EXITOSA</div>
        </div>
    </div>
</form>
<div class="modal-footer">
    <a href="#" id="close_modal" class="waves-effect waves-red btn-flat modal-action modal-close">CERRAR</a>
</div>
<script type="text/javascript">
    $(function (){
        $("#finalizar_gestacion").on('change',function () {
            if($('#finalizar_gestacion').prop('checked')){
                $('#div_finalizar').show();
            }else{
                $('#div_finalizar').hide()
            }
        });
        $("#form_GestacionConfig").on('change',function(){
            updateInfoGestacion();
        });
    });
    function updateInfoGestacion(){
        $.post('db/update/datos_gestacion.php',
            $("#form_GestacionConfig").serialize()
            ,function (data) {
                alertaLateral(data);
            });
    }
    function updateEstadoGestacion(estado){
        if (confirm("AL REALIZAR ESTA OPERACION, LA GESTACIÓN NO PODRA SER MODIFICADA")){
            updateInfoGestacion();
            $.post('db/update/estado_gestacion.php',
                {
                    estado:estado,
                    rut:'<?php echo $rut ?>',
                    id_gestacion:$("#id_gestacion").val(),
                    fecha_registro:'<?php echo $fecha_registro ?>'
                }
                ,function (data) {
                    alertaLateral(data);
                });
        }
    }
</script>