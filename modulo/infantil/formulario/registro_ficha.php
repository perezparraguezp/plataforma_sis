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
if($paciente->existe==false){
    echo "ERROR_RUT";
}else{

}

if($paciente->getModuloPaciente('m_infancia')=='NO'){
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
            $('#tabs_registro').jqxTabs({ width: '100%', height: 450,
                position: 'top',scrollPosition: 'both',selectedItem: 1});

            loadInfoPaciente('<?php echo $rut; ?>');
            //antropometria
            loadFormAntropometria('<?php echo $rut; ?>');
            loadFormVacunas('<?php echo $rut; ?>');
            historial_psicomotor('<?php echo $rut; ?>');
            loadFormPendientes('<?php echo $rut; ?>');
            loadFormPsicomotor('<?php echo $rut; ?>');
            loadFormDental('<?php echo $rut; ?>');
            loadInfoNacimiento('<?php echo $rut; ?>');
        });
        function loadInfoPaciente(rut){
            $.post('../default/banner_paciente.php',{
                rut:rut,
                fecha_registro:'<?php echo $fecha_registro; ?>'

            },function(data){
                $("#info_paciente").html(data);
            });
        }
        function loadInfoNacimiento(rut){
            $.post('formulario/datos_nacimiento.php',{
                rut:rut,
                fecha_registro:'<?php echo $fecha_registro; ?>'

            },function(data){
                $("#form_datos_nacimiento").html(data);
            });
        }
        function loadHistorialPaciente(rut) {
            $.post('php/info/registro_ficha/historial_general.php',{
                rut:rut,
                fecha_registro:'<?php echo $fecha_registro; ?>'

            },function(data){
                $("#form_historial").html(data);
            });
        }
        function historial_psicomotor(rut){
            $.post('info/historial_psicomotor.php',{
                rut:rut,
                fecha_registro:'<?php echo $fecha_registro; ?>'

            },function(data){
                $("#historial_psicomotor").html(data);
            });
        }
        function historial_dental(rut){
            $.post('info/historial_dental.php',{
                rut:rut,
                fecha_registro:'<?php echo $fecha_registro; ?>'

            },function(data){
                $("#historial_dental").html(data);
            });
        }
        function loadFormPsicomotor(rut){
            $.post('formulario/psicomotor.php',{
                rut:rut,
                fecha_registro:'<?php echo $fecha_registro; ?>'

            },function(data){
                $("#form_psicomotor").html(data);
            });
        }
        function loadFormDental(rut){
            $.post('formulario/dental.php',{
                rut:rut,
                fecha_registro:'<?php echo $fecha_registro; ?>'

            },function(data){
                $("#form_dental").html(data);
            });
        }
        function loadFormAntropometria(rut){
            $.post('formulario/antropometria.php',{
                rut:rut,
                fecha_registro:'<?php echo $fecha_registro; ?>'

            },function(data){
                $("#form_antropometria").html(data);
            });
        }
        function loadFormVacunas(rut){
            $.post('formulario/vacunas.php',{
                rut:rut,
                fecha_registro:'<?php echo $fecha_registro; ?>'
            },function(data){
                $("#form_vacunas").html(data);
            });
        }
        function loadFormPendientes(rut){
            $.post('formulario/pendientes.php',{
                rut:rut,
                fecha_registro:'<?php echo $fecha_registro; ?>'
            },function(data){
                $("#form_pendientes").html(data);
            });
        }
        function insertAntropometria() {
            $.post('php/db/insert/antropometria.php',
                $("#form_antropometria").serialize()
                ,function(data){
                    $("#form_vacunas").html(data);
                });
        }
        function updatePaciente(){
            $.post('php/db/update/paciente.php',
                $("#form_perfil").serialize(),function(data){
                    alertaLateral(data);
                    loadInfoPaciente('<?php echo $rut; ?>');
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
        function boxHistorialPaciente(rut){
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
        function boxEditarPaciente(rut) {
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
        function loadHistorialVacunas(){
            $.post('info/vacunas.php',{
                rut:'<?php echo $rut; ?>'
            },function(data){
                $("#div_historialVacunas").html(data);
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
                                onclick="boxEditarPaciente('<?php echo $rut; ?>')"
                                class="btn-large lime lighten-2 black-text">
                            <i class="mdi-image-edit right " style="font-size: 0.9em;"></i> EDITAR
                        </button>
                    </div>
                    <div class="row">
                        <button style="width: 100%;height: 30px;line-height: 20px;"
                                onclick="boxHistorialPaciente('<?php echo $rut; ?>')"
                                class="btn-large lime lighten-2 black-text">
                            <i class="mdi-action-history right" style="font-size: 0.9em;"></i> HISTORIAL
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="row" style="font-size: 0.9em;">
            <div class="col l8">PROFESIONAL: <strong><?php echo $profesional->nombre; ?></strong></div>
            <div class="col l4 right-align">FECHA REGISTRO <?php echo fechaNormal($fecha_registro); ?></div>
        </div>
        <div id='tabs_registro' style="font-size: 0.8em;">
            <ul>
                <li style="margin-left: 30px;text-align: center;background-color: #ff5f69;cursor: pointer;" onclick="loadMenu_Infantil('menu_1','registro_tarjetero','<?php echo $rut; ?>')">VOLVER</li>
                <li style="margin-left: 30px;text-align: center">DATOS DE NACIMIENTO</li>
                <li style="margin-left: 30px;">ANTROPOMETRIA</li>
                <li onclick="loadHistorialVacunas()">REGISTRO VACUNAS</li>
                <li onclick="historial_psicomotor('<?php echo $rut; ?>');">DESARROLLO PSICOMOTOR</li>
                <li onclick="historial_dental('<?php echo $rut; ?>')">PROGRAMA DENTAL</li>
                <li onclick="loadFormPendientes('<?php echo $rut; ?>');">PENDIENTES</li>
                <li ONCLICK="boxAgendamiento()" style="background-color: #5cff9a;cursor: pointer;">FINALIZAR ATENCIÓN</li>
            </ul>
            <div></div>
            <div>
                <!-- DATOS DE NACIMIENTO -->
                <form name="form_datos_nacimiento" id="form_datos_nacimiento" class="col l12"></form>
            </div>
            <div>
                <!-- ANTROPOMETRIA -->
                <form name="form_antropometria" id="form_antropometria" class="col l12"></form>
            </div>
            <div>
                <!-- VACUNAS -->
                <form name="form_vacunas" id="form_vacunas" class="col l12"></form>
            </div>
            <div>
                <!-- PSICOMOTOR -->
                <form name="form_psicomotor" id="form_psicomotor" class="col l12"></form>
            </div>
            <div>
                <!-- DENTAL -->
                <form name="form_dental" id="form_dental" class="col l12"></form>
            </div>
            <div>
                <!-- PENDIENTES -->
                <form name="form_pendientes" id="form_pendientes" class="col l12"></form>
            </div>
            <div></div>
        </div>
        <div class="row">
            <div class="col l3">
                <input type="button"
                       style="width: 100%;"
                       onclick="loadMenu_Infantil('menu_1','registro_tarjetero','<?php echo $rut; ?>')"
                       class="btn-large red lighten-2 white-text"
                       value=" <-- VOLVER" />
            </div>
            <div class="col l9">
                <input type="button" style="width: 100%;"
                       onclick="boxAgendamiento()"
                       class="btn-large light-green darken-2" VALUE="FINALIZAR ATENCIÓN" />
            </div>
        </div>

    </div>

    <?php
}

?>
