<?php
include "../../../php/config.php";
include '../../../php/objetos/persona.php';
include '../../../php/objetos/profesional.php';
//$rut = str_replace('.','',$_POST['rut']);
list($rut,$nombre) = explode(" | ",$_POST['rut']);
$fecha_registro = $_POST['fecha_registro'];

if($fecha_registro==''){
    $fecha_registro = date('Y-m-d');
}

$paciente = new persona($rut);
$profesional = new profesional($_SESSION['id_usuario']);
?>
<script type="text/javascript">
    $(document).ready(function () {
        // Create jqxTabs.
        $('#tabs_registro').jqxTabs({ width: '100%', height: 450, position: 'top',scrollPosition: 'both'});
        loadInfoPaciente('<?php echo $rut; ?>');
        load_m_antecedentes('<?php echo $rut; ?>');
        load_m_sexualidad('<?php echo $rut; ?>');
        load_m_gestaciones('<?php echo $rut; ?>');
        //load_am_funcionalidad('<?php //echo $rut; ?>//');

    });
    function loadInfoPaciente(rut){
        $.post('info/banner_paciente.php',{
            rut:rut,
            fecha_registro:'<?php echo $fecha_registro; ?>'

        },function(data){
            $("#info_paciente").html(data);
        });
    }
    function load_m_antecedentes(rut) {
        var div = 'form_antecedentes';
        loading_div(div);
        $.post('formulario/m_antecedentes.php',{
            rut:rut,
            fecha_registro:'<?php echo $fecha_registro; ?>'
        },function(data){
            $("#"+div).html(data);
        });
    }
    function load_m_sexualidad(rut) {
        var div = 'form_sexualidad';
        loading_div(div);
        $.post('formulario/m_sexualidad.php',{
            rut:rut,
            fecha_registro:'<?php echo $fecha_registro; ?>'
        },function(data){
            $("#"+div).html(data);
        });
    }
    function load_m_gestaciones(rut){
        var div = 'form_gestaciones';
        loading_div(div);
        $.post('formulario/m_gestaciones.php',{
            rut:rut,
            fecha_registro:'<?php echo $fecha_registro; ?>'
        },function(data){
            $("#"+div).html(data);
        });
    }

    function load_PendientesPerfil(rut) {
        var div = 'form_pendientes';
        loading_div(div);
        $.post('formulario/pscv_pendientes.php',{
            rut:rut,
            fecha_registro:'<?php echo $fecha_registro; ?>'
        },function(data){
            $("#"+div).html(data);
        });
    }
    function boxEditarPaciente_AM(rut) {
        $.post('../default/formulario/editar_paciente.php',{
            rut:rut,
        },function(data){
            if(data !== 'ERROR_SQL'){
                $("#modal").html(data);
                $("#modal").css({'width':'1100px'});
                document.getElementById("btn-modal").click();
            }
        });
    }
    function boxHistorialPaciente_PSCV(rut){
        $.post('modal/historial.php',{
            rut:rut,
        },function(data){
            if(data !== 'ERROR_SQL'){
                $("#modal").html(data);
                $("#modal").css({'width':'1100px'});
                document.getElementById("btn-modal").click();
            }
        });
    }

    function boxAgendamiento(){
        $.post('modal/agenda/proxima_cita.php',{
            rut:'<?php echo $rut; ?>'
        },function(data){
            if(data !== 'ERROR_SQL'){
                $("#modal").html(data);
                $("#modal").css({'width':'800px'});
                document.getElementById("btn-modal").click();
            }
        });
    }


</script>
<div class="col l12" style="padding-top: 10px;">
    <div class="row">
        <div class="col l10" >
            <div id="info_paciente" class="card-panel" style="font-size: 0.7em;">

            </div>
        </div>
        <div class="col l2">
            <div class="card center" style="font-size: 0.7em;">
                <div class="row">
                    <button style="width: 100%;height: 30px;line-height: 20px;"
                            onclick="boxEditarPaciente_PSCV('<?php echo $rut; ?>')"
                            class="btn-large lime lighten-2 black-text">
                        <i class="mdi-image-edit right " style="font-size: 0.9em;"></i> EDITAR
                    </button>
                </div>
                <div class="row">
                    <button style="width: 100%;height: 30px;line-height: 20px;"
                            onclick="boxHistorialPaciente_PSCV('<?php echo $rut; ?>')"
                            class="btn-large lime lighten-2 black-text">
                        <i class="mdi-action-history right" style="font-size: 0.9em;"></i> HISTORIAL
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="row" style="font-size: 0.9em;">
        <div class="col l8" style="padding-right: 10px;">PROFESIONAL: <strong><?php echo $profesional->nombre; ?></strong></div>
        <div class="col l4 right-align" style="padding-right: 10px;">FECHA REGISTRO <?php echo fechaNormal($fecha_registro); ?></div>
    </div>
    <div id="tabs_registro" style="font-size: 0.8em;">
        <ul>
            <li style="margin-left: 30px;text-align: center">ANTECEDENTES</li>
            <li style="margin-left: 30px;" onclick="load_m_sexualidad('<?php echo $rut; ?>')">FERTILIDAD Y SALUD SEXUAL</li>
            <li style="margin-left: 30px;" onclick="load_m_gestaciones('<?php echo $rut; ?>')">GESTACIONES</li>
            <li style="background-color: #5cff9a;cursor: pointer;" onclick="boxAgendamiento()">FINALIZAR ATENCIÓN</li>
        </ul>
        <div>
            <!-- ANTECEDENTES -->
            <form name="form_antecedentes" id="form_antecedentes" class="col l12"></form>
        </div>
        <div>
            <!-- FUNCIONALIDAD -->
            <form name="form_sexualidad" id="form_sexualidad" class="col l12"></form>
        </div>
        <div>
            <!-- FUNCIONALIDAD -->
            <form name="form_gestaciones" id="form_gestaciones" class="col l12"></form>
        </div>
        <div></div>

    </div>
    <div class="row">
        <div class="col l12">
            <input type="button"
                   style="width: 100%;"
                   onclick="loadMenu_M('menu_1','registro_atencion','<?php echo $rut; ?>')"
                   class="btn-large red lighten-2 white-text"
                   value=" <-- VOLVER" />
        </div>
    </div>

</div>
