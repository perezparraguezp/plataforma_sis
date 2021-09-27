<?php
include "../../../php/config.php";
include '../../../php/objetos/persona.php';
include '../../../php/objetos/profesional.php';
//
list($rut,$nombre) = explode(" | ",$_POST['rut']);
$rut = str_replace('.','',$rut);
$fecha_registro = $_POST['fecha_registro'];

if($fecha_registro==''){
    $fecha_registro = date('Y-m-d');
}

$paciente = new persona($rut);
$profesional = new profesional($_SESSION['id_usuario']);
if($paciente->getModuloPaciente('m_adolescente')=='NO'){
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
            $('#tabs_registro').jqxTabs({ width: '100%',theme: 'eh-open', height: 450, position: 'top',scrollPosition: 'both'});
            loadInfoPaciente('<?php echo $rut; ?>');
            load_ad_antecedentes('<?php echo $rut; ?>');

        });
        function loadInfoPaciente(rut){
            $.post('info/banner_paciente.php',{
                rut:rut,
                fecha_registro:'<?php echo $fecha_registro; ?>'

            },function(data){
                $("#info_paciente").html(data);
            });
        }
        function load_ad_antecedentes(rut) {
            var div = 'form_antecedentes';
            loading_div(div);
            $.post('formulario/antecedentes.php',{
                rut:rut,
                fecha_registro:'<?php echo $fecha_registro; ?>'
            },function(data){
                $("#"+div).html(data);
            });
        }
        function load_ad_educacion_trabajo(rut) {
            var div = 'form_funcionalidad';
            loading_div(div);
            $.post('formulario/educacion_trabajo.php',{
                rut:rut,
                fecha_registro:'<?php echo $fecha_registro; ?>'
            },function(data){
                $("#"+div).html(data);
            });
        }

        function load_ad_riesgos(rut) {
            var div = 'form_riesgos';
            loading_div(div);
            $.post('formulario/areas_riesgo.php',{
                rut:rut,
                fecha_registro:'<?php echo $fecha_registro; ?>'
            },function(data){
                $("#"+div).html(data);
            });
        }
        function load_ad_riesgos_gine(rut) {
            var div = 'form_riesgos_gine';
            loading_div(div);
            $.post('formulario/riesgos_gine.php',{
                rut:rut,
                fecha_registro:'<?php echo $fecha_registro; ?>'
            },function(data){
                $("#"+div).html(data);
            });
        }
        function load_ad_consejerias(rut) {
            var div = 'form_consejerias';
            loading_div(div);
            $.post('formulario/consejerias.php',{
                rut:rut,
                fecha_registro:'<?php echo $fecha_registro; ?>'
            },function(data){
                $("#"+div).html(data);
            });
        }
        function boxEditarPaciente_PSCV(rut) {
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
                <li style="margin-left: 30px;text-align: center" onclick="load_ad_antecedentes('<?php echo $rut; ?>')">ANTECEDENTES</li>
                <li style="margin-left: 30px;" onclick="load_ad_educacion_trabajo('<?php echo $rut; ?>')">EDUCACIÓN Y TRABAJO</li>
                <li style="margin-left: 30px;" onclick="load_ad_riesgos('<?php echo $rut; ?>')">AREAS DE RIESGO</li>
                <li style="margin-left: 30px;" onclick="load_ad_riesgos_gine('<?php echo $rut; ?>')">GINECO UROLOGICO</li>
                <li style="margin-left: 30px;" onclick="load_ad_consejerias('<?php echo $rut; ?>')">CONSEJERIAS</li>
                <li style="background-color: #5cff9a;cursor: pointer;" onclick="boxAgendamiento()">FINALIZAR ATENCIÓN</li>
            </ul>
            <div>
                <!-- ANTECEDENTES -->
                <form name="form_antecedentes" id="form_antecedentes" class="col l12"></form>
            </div>
            <div>
                <!-- EDUCACIÓN Y TRABAJO -->
                <form name="form_funcionalidad" id="form_funcionalidad" class="col l12"></form>
            </div>
            <div>
                <!-- EDUCACIÓN Y TRABAJO -->
                <form name="form_riesgos" id="form_riesgos" class="col l12"></form>
            </div>
            <div>
                <!-- EDUCACIÓN Y TRABAJO -->
                <form name="form_riesgos_gine" id="form_riesgos_gine" class="col l12"></form>
            </div>
            <div>
                <!-- EDUCACIÓN Y TRABAJO -->
                <form name="form_consejerias" id="form_consejerias" class="col l12"></form>
            </div>
            <div></div>

        </div>
        <div class="row">
            <div class="col l12">
                <input type="button"
                       style="width: 100%;"
                       onclick="menuEhOpen_ADOLESCENTE('menu_1','registro_atencion','<?php echo $rut; ?>')"
                       class="btn-large red lighten-2 white-text"
                       value=" <-- VOLVER" />
            </div>

        </div>

    </div>
<?php
}
?>

