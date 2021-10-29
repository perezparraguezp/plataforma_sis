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
if($paciente->getModuloPaciente('m_salud_mental')=='NO'){
    ?>
    <div class="container">
        <div class="row">
            <div class="col l4 center-align">
                <img src="../no_modulo.png" width="200" />
            </div>
            <div class="col l8 center-align">
                <fieldset>
                    <legend>INFORMACIÓN</legend>
                    <p><header>EL RUN <strong><?php echo formatoRUT($rut); ?></strong> ES VALIDO, PERO NO SE ENCUENTRA EN LOS REGISTROS DE ESTE MODULO.</header></p>
                    <p>DEBE DIRIGIRSE AL MODULO DE INGRESO DE PACIENTES Y VERIFICAR ESTA INFORMACIÓN.</p>
                    <div class="card-panel PANEL_MENU_SIS" style="background-color: #f1ffc5;">
                        <div class="row">
                            <div class="col l4 m4 s4">
                                <i class="mdi-social-people"></i>
                            </div>
                            <div class="col l8 m8 s8">
                                <a style="color: black" href="../some/index.php" target="_blank">
                                    <strong>INGRESO DE PACIENTES</strong>
                                </a>
                            </div>
                        </div>
                    </div>
                </fieldset>
            </div>
        </div>
        <div class="row">
            <div class="col l12">
                <input type="button"
                       style="width: 100%;"
                       onclick="loadMenu_AM('menu_1','registro_atencion','<?php echo $rut; ?>')"
                       class="btn-large red lighten-2 white-text"
                       value=" <-- VOLVER" />
            </div>
        </div>
    </div>
    <?php
}else{
    ?>
    <script type="text/javascript">
        $(document).ready(function () {
            // Create jqxTabs.
            $('#tabs_registro').jqxTabs({ width: '100%', theme: 'eh-open',height: 450, position: 'top',scrollPosition: 'both'});
            loadInfoPaciente('<?php echo $rut; ?>');

            load_sm_antecedentes('<?php echo $rut; ?>');
            load_sm_diagnosticos('<?php echo $rut; ?>');
            load_sm_dependiente('<?php echo $rut; ?>');


        });
        function loadInfoPaciente(rut){
            $.post('info/banner_paciente.php',{
                rut:rut,
                fecha_registro:'<?php echo $fecha_registro; ?>'

            },function(data){
                $("#info_paciente").html(data);
            });
        }
        function load_sm_antecedentes2(rut){
            var div = 'form_antecedentes2';
            loading_div(div);
            $.post('formulario/antecedentes2.php',{
                rut:rut,
                fecha_registro:'<?php echo $fecha_registro; ?>'
            },function(data){
                $("#"+div).html(data);
            });
        }
        function load_sm_antecedentes(rut) {
            var div = 'form_antecedentes';
            loading_div(div);
            $.post('formulario/antecedentes.php',{
                rut:rut,
                fecha_registro:'<?php echo $fecha_registro; ?>'
            },function(data){
                $("#"+div).html(data);
            });
        }
        function load_sm_diagnosticos(rut) {
            var div = 'form_examenes';
            loading_div(div);
            $.post('formulario/diagnosticos.php',{
                rut:rut,
                fecha_registro:'<?php echo $fecha_registro; ?>'
            },function(data){
                $("#"+div).html(data);
            });
        }

        function load_sm_dependiente(rut) {
            var div = 'form_climaterio';
            loading_div(div);
            $.post('formulario/dependiente.php',{
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
        function boxHistorialPaciente_SM(rut){
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
                <li style="margin-left: 30px;text-align: center" onclick="load_sm_antecedentes2('<?php echo $rut; ?>')">ANTECEDENTES 1</li>
                <li style="margin-left: 30px;text-align: center" onclick="load_sm_antecedentes('<?php echo $rut; ?>')">ANTECEDENTES 2</li>
                <li style="margin-left: 30px;text-align: center" onclick="('<?php echo $rut; ?>')">DIAGNOSTICOS</li>
                <li style="margin-left: 30px;" onclick="load_sm_dependiente('<?php echo $rut; ?>')">ACOMPAÑAMIENTO</li>
                <li style="background-color: #5cff9a;cursor: pointer;" onclick="boxAgendamiento()">FINALIZAR ATENCIÓN</li>
            </ul>
            <div>
                <!-- ANTECEDENTES2 -->
                <form name="form_antecedentes2" id="form_antecedentes2" class="col l12"></form>
            </div>
            <div>
                <!-- ANTECEDENTES -->
                <form name="form_antecedentes" id="form_antecedentes" class="col l12"></form>
            </div>
            <div>
                <!-- DIAGNOSTICOS -->
                <form name="form_examenes" id="form_examenes" class="col l12"></form>
            </div>
            <div>
                <!-- ACOMPAÑAMIENTO -->
                <form name="form_sexualidad" id="form_sexualidad" class="col l12"></form>
            </div>
            <div>
                <!-- CLIMATERIO -->
                <form name="form_climaterio" id="form_climaterio" class="col l12"></form>
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
<?php
}
?>

