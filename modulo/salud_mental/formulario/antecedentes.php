<?php

include "../../../php/config.php";
include "../../../php/objetos/persona.php";

$rut = $_POST['rut'];
$fecha_registro = $_POST['fecha_registro'];

$paciente = new persona($rut);


//$imc =  $paciente->getParametro_M('imc');
$imc =  'null';

$patologia_diabetes  = $paciente->getParametro_M('patologia_dm');
$patologia_hipertension  = $paciente->getParametro_M('patologia_hta');
$patologia_vih  = $paciente->getParametro_M('patologia_vih');

$regulacion_fertilidad  = $paciente->getParametro_M('regulacion_fertilidad');
$gestacion  = $paciente->getParametro_M('gestacion');
$climaterio  = $paciente->getParametro_M('climaterio');


$esatdo  = $paciente->getParametro_M('estado_paciente');


?>

<form class="content card-panel">
    <input type="hidden" name="fecha_antecedentes" id="fecha_antecedentes" value="<?php echo $fecha_registro; ?>" />
    <div class="row">
        <div class="col l4 m6 s12">
            <!-- IMC  -->
            <div class="row">
                <div class="col l12 m12 s12">
                    <div class="card-panel green lighten-2">
                        <div class="row">
                            <div class="col l10 m10 s10" >
                                <strong style="line-height: 2em;font-size: 1.5em;">VIOLENCIA <strong class="tooltipped" style="cursor: help" data-position="bottom" data-delay="50" data-tooltip="EL REGISTRO SERÁ GUARDADO AUTOMATICAMENTE">(?)</strong></strong>
                            </div>
                            <div class="col l2 center-align">
                                <i class="mdi-editor-insert-chart"
                                   onclick="loadHistorialParametro_SM('<?php echo $rut ?>','violencia')"></i>
                            </div>
                            <div class="col l12 m12 s12">
                                <div class="row">
                                    <input type="hidden" name="fecha_violencia" id="fecha_violencia" value="<?php echo $fecha_registro; ?>" />
                                    <select class="browser-default"
                                            name="violencia" id="violencia"
                                            style="font-size: 1.5em;"
                                            onchange="updateAntecedente_sm('violencia')">

                                        <option></option>
                                        <option>VICTIMA</option>
                                        <option>AGRESOR</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col l10 m10 s10" >
                                <strong style="line-height: 2em;font-size: 1.5em;">ABUSO SEXUAL <strong class="tooltipped" style="cursor: help" data-position="bottom" data-delay="50" data-tooltip="EL REGISTRO SERÁ GUARDADO AUTOMATICAMENTE">(?)</strong></strong>
                            </div>
                            <div class="col l2 center-align">
                                <i class="mdi-editor-insert-chart"
                                   onclick="loadHistorialParametro_SM('<?php echo $rut ?>','abuso_sexual')"></i>
                            </div>
                            <div class="col l12 m12 s12">
                                <div class="row">
                                    <input type="hidden" name="fecha_abuso_sexual" id="fecha_abuso_sexual" value="<?php echo $fecha_registro; ?>" />
                                    <select class="browser-default"
                                            name="abuso_sexual" id="abuso_sexual"
                                            style="font-size: 1.5em;"
                                            onchange="updateAntecedente_sm('abuso_sexual')">

                                        <option></option>
                                        <option>SI</option>
                                        <option>NO</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col l10 m10 s10" >
                                <strong style="line-height: 2em;font-size: 1.5em;">SUICIDIO <strong class="tooltipped" style="cursor: help" data-position="bottom" data-delay="50" data-tooltip="EL REGISTRO SERÁ GUARDADO AUTOMATICAMENTE">(?)</strong></strong>
                            </div>
                            <div class="col l2 center-align">
                                <i class="mdi-editor-insert-chart"
                                   onclick="loadHistorialParametro_SM('<?php echo $rut ?>','suicidio')"></i>
                            </div>
                            <div class="col l12 m12 s12">
                                <div class="row">
                                    <input type="hidden" name="fecha_suicidio" id="fecha_suicidio" value="<?php echo $fecha_registro; ?>" />
                                    <select class="browser-default"
                                            name="suicidio" id="suicidio"
                                            style="font-size: 1.5em;"
                                            onchange="updateAntecedente_sm('suicidio')">

                                        <option></option>
                                        <option>IDEACION</option>
                                        <option>INTENTO</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col l10 m10 s10" >
                                <strong style="line-height: 2em;font-size: 1.5em;">GESTANTE <strong class="tooltipped" style="cursor: help" data-position="bottom" data-delay="50" data-tooltip="EL REGISTRO SERÁ GUARDADO AUTOMATICAMENTE">(?)</strong></strong>
                            </div>
                            <div class="col l2 center-align">
                                <i class="mdi-editor-insert-chart"
                                   onclick="loadHistorialParametro_SM('<?php echo $rut ?>','gestante')"></i>
                            </div>
                            <div class="col l12 m12 s12">
                                <div class="row">
                                    <input type="hidden" name="fecha_gestante" id="fecha_gestante" value="<?php echo $fecha_registro; ?>" />
                                    <select class="browser-default"
                                            name="gestante" id="gestante"
                                            style="font-size: 1.5em;"
                                            onchange="updateAntecedente_sm('gestante')">

                                        <option></option>
                                        <option>SI</option>
                                        <option>NO</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <!-- PATOLOGIA BASE  -->
        </div>
        <div class="col l4 m6 s12">
            <div class="row">
                <div class="col l12 m12 s12">
                    <div class="card-panel green lighten-2">
                        <div class="row">
                            <div class="col l10 m10 s10" >
                                <strong style="line-height: 2em;font-size: 1.5em;">MADRE HIJO MENOR 5 AÑOS <strong class="tooltipped" style="cursor: help" data-position="bottom" data-delay="50" data-tooltip="EL REGISTRO SERÁ GUARDADO AUTOMATICAMENTE">(?)</strong></strong>
                            </div>
                            <div class="col l2 center-align">
                                <i class="mdi-editor-insert-chart"
                                   onclick="loadHistorialParametro_SM('<?php echo $rut ?>','madre_5')"></i>
                            </div>
                            <div class="col l12 m12 s12">
                                <div class="row">
                                    <input type="hidden" name="fecha_madre_5" id="fecha_madre_5" value="<?php echo $fecha_registro; ?>" />
                                    <select class="browser-default"
                                            name="madre_5" id="madre_5"
                                            style="font-size: 1.5em;"
                                            onchange="updateAntecedente_sm('madre_5')">

                                        <option></option>
                                        <option>SI</option>
                                        <option>NO</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col l10 m10 s10" >
                                <strong style="line-height: 2em;font-size: 1.5em;">POBLACION SENAME <strong class="tooltipped" style="cursor: help" data-position="bottom" data-delay="50" data-tooltip="EL REGISTRO SERÁ GUARDADO AUTOMATICAMENTE">(?)</strong></strong>
                            </div>
                            <div class="col l2 center-align">
                                <i class="mdi-editor-insert-chart"
                                   onclick="loadHistorialParametro_SM('<?php echo $rut ?>','sename')"></i>
                            </div>
                            <div class="col l12 m12 s12">
                                <div class="row">
                                    <input type="hidden" name="fecha_sename" id="fecha_sename" value="<?php echo $fecha_registro; ?>" />
                                    <select class="browser-default"
                                            name="sename" id="sename"
                                            style="font-size: 1.5em;"
                                            onchange="updateAntecedente_sm('sename')">

                                        <option></option>
                                        <option>SI</option>
                                        <option>NO</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col l10 m10 s10" >
                                <strong style="line-height: 2em;font-size: 1.5em;">PLANA INTEGRAL<strong class="tooltipped" style="cursor: help" data-position="bottom" data-delay="50" data-tooltip="EL REGISTRO SERÁ GUARDADO AUTOMATICAMENTE">(?)</strong></strong>
                            </div>
                            <div class="col l2 center-align">
                                <i class="mdi-editor-insert-chart"
                                   onclick="loadHistorialParametro_SM('<?php echo $rut ?>','plana_integral')"></i>
                            </div>
                            <div class="col l12 m12 s12">
                                <input type="hidden" name="fecha_plana_integral" id="fecha_plana_integral" value="" />
                                <div class="row">
                                    <input type="date"
                                           onkeydown="return false"
                                           onchange="updateAntecedente_sm('plana_integral')"
                                           name="plana_integral" id="plana_integral"
                                           value="<?php echo $fecha_registro; ?>" />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col l10 m10 s10" >
                                <strong style="line-height: 2em;font-size: 1.5em;">GES <strong class="tooltipped" style="cursor: help" data-position="bottom" data-delay="50" data-tooltip="EL REGISTRO SERÁ GUARDADO AUTOMATICAMENTE">(?)</strong></strong>
                            </div>
                            <div class="col l2 center-align">
                                <i class="mdi-editor-insert-chart"
                                   onclick="loadHistorialParametro_SM('<?php echo $rut ?>','ges')"></i>
                            </div>
                            <div class="col l12 m12 s12">
                                <div class="row">
                                    <input type="hidden" name="fecha_ges" id="fecha_ges" value="<?php echo $fecha_registro; ?>" />
                                    <select class="browser-default"
                                            name="ges" id="ges"
                                            style="font-size: 1.5em;"
                                            onchange="updateAntecedente_sm('ges')">

                                        <option></option>
                                        <option>DEPRESION EN PERSONAS DE 15 AÑOS Y MAS</option>
                                        <option>TRASTORNO BIPOLAR EN PERSONAS DE 15 AÑOS Y MAS</option>
                                        <option>CONSUMO PERJUDICIAL O DEPENDENCIA DE RIESGO BAJO A MODERADO DE ALCOHOL Y DROGRAS EN PERSONAS</option>
                                        <option>ESQUIZOFRENIA</option>
                                        <option>DEMENCIA</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
<style type="text/css">
    .hoverClass:hover{
        background-color: #f1ffc5;
    }
</style>


<script type="text/javascript">
    $(function () {
        $('.tooltipped').tooltip({delay: 50});
        //$("#imc_<?php //echo strtolower($imc); ?>//").attr('checked','checked');
        $("#estado_paciente_<?php echo $esatdo; ?>").attr('checked','checked');
    });

    function updateEstadoPacienteMujer(estado){
        var fecha = $("#fecha_antecedentes").val();
        $.post('db/update/m_indicador.php',{
            column:'estado_paciente',
            value:estado,
            fecha_registro:fecha,
            rut:'<?php echo $rut ?>'
        },function (data) {
            alertaLateral(data);
            if(estado==='GESTANTE'){
                $('#tabs_registro').jqxTabs('enableAt', 3);
            }else{
                $('#tabs_registro').jqxTabs('disableAt', 3);
            }
        });
    }
    function updateIndicadorM_check(indicador) {
        var value = '';
        if($('#'+indicador).prop('checked')){
            value = 'SI';
        }else{
            value = 'NO';
        }
        var fecha = $("#fecha_antecedentes").val();
        $.post('db/update/m_indicador.php',{
            column:indicador,
            value:value,
            fecha_registro:fecha,
            rut:'<?php echo $rut ?>'
        },function (data) {
            alertaLateral(data);
        });
    }
    function updateIndicadorM(indicador) {
        var value = $('#'+indicador).val();
        var fecha = $("#fecha_antecedentes").val();
        $.post('db/update/m_indicador.php',{
            column:indicador,
            value:value,
            fecha_registro:fecha,
            rut:'<?php echo $rut ?>'
        },function (data) {
            alertaLateral(data);
        });
    }

    function updateAntecedente_sm(value) {
        var fecha = $("#fecha_"+value).val();
        $.post('db/update/antecedentes.php',{
            column:value,
            value:$("#"+value).val(),
            fecha:fecha,
            rut:'<?php echo $rut ?>'
        },function (data) {
            alertaLateral(data);
        });
    }
    function loadHistorialParametro_SM(rut,indicador) {
        $.post('grid/historial_parametros_m.php',{
            rut:rut,
            indicador:indicador
        },function(data){
            if(data !== 'ERROR_SQL'){
                $("#modal").html(data);
                $("#modal").css({'width':'800px'});
                document.getElementById("btn-modal").click();
            }
        });
    }
</script>
