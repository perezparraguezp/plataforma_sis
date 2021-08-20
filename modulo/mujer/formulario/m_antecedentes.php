<?php

include "../../../php/config.php";
include "../../../php/objetos/persona.php";

$rut = $_POST['rut'];
$fecha_registro = $_POST['fecha_registro'];

$paciente = new persona($rut);


$imc =  $paciente->getParametro_M('imc');

$patologia_diabetes  = $paciente->getParametro_M('patologia_diabetes');
$patologia_hipertension  = $paciente->getParametro_M('patologia_hipertension');
$patologia_vih  = $paciente->getParametro_M('patologia_vih');





?>

<form class="content card-panel">
    <input type="hidden" name="fecha_antecedentes" id="fecha_antecedentes" value="<?php echo $fecha_registro; ?>" />
    <div class="row">
        <div class="col l6 m12 s12">
            <!-- IMC  -->
            <div class="row">
                <div class="col l12 m12 s12">
                    <div class="card-panel green lighten-2">
                        <div class="row">
                            <div class="col l3 m6 s12" >
                                <strong style="line-height: 2em;font-size: 1.5em;">IMC <strong class="tooltipped" style="cursor: help" data-position="bottom" data-delay="50" data-tooltip="EL REGISTRO SERÁ GUARDADO AUTOMATICAMENTE">(?)</strong></strong>
                            </div>
                            <div class="col l7 m6 s12">
                                <div class="row">
                                    <input type="hidden" name="fecha_imc" id="fecha_imc" value="<?php echo $fecha_registro; ?>" />
                                    <div class="col l3 m3 s3 tooltipped hoverClass" data-position="bottom" data-delay="50" data-tooltip="BAJO PESO">
                                        <div class="row center-align">
                                            <label class="white-text" for="imc_bp">
                                                <img src="../../images/imc/bp.png" height="100px" />
                                            </label><br />
                                            <input type="radio"
                                                   style="position: relative;visibility: visible;left: 0px;"
                                                   onclick="updateParametroM_IMC('BP')"
                                                   id="imc_bp" name="imc" value="BP" >
                                        </div>
                                    </div>
                                    <div class="col l3 m3 s3 tooltipped hoverClass" data-position="bottom" data-delay="50" data-tooltip="NORMAL">
                                        <div class="row center-align">
                                            <label class="white-text" for="imc_n">
                                                <img src="../../images/imc/n.png" height="100px" />
                                            </label><br />
                                            <input type="radio"
                                                   style="position: relative;visibility: visible;left: 0px;"
                                                   onclick="updateParametroAM_IMC('N')"
                                                   id="imc_n" name="imc" value="N" >
                                        </div>
                                    </div>
                                    <div class="col l3 m3 s3 tooltipped hoverClass" data-position="bottom" data-delay="50" data-tooltip="SOBREPESO">
                                        <div class="row center-align">
                                            <label class="white-text" for="imc_sp">
                                                <img src="../../images/imc/sp.png" height="100px"  />
                                            </label><br />
                                            <input type="radio"
                                                   style="position: relative;visibility: visible;left: 0px;"
                                                   onclick="updateParametroAM_IMC('SP')"
                                                   id="imc_sp" name="imc" value="SP" >
                                        </div>
                                    </div>
                                    <div class="col l3 m3 s3 tooltipped hoverClass" data-position="bottom" data-delay="50" data-tooltip="OBESIDAD">
                                        <div class="row center-align">
                                            <label class="white-text" for="imc_ob">
                                                <img src="../../images/imc/ob.png" height="100px"  />
                                            </label><br />
                                            <input type="radio"
                                                   style="position: relative;visibility: visible;left: 0px;"
                                                   onclick="updateParametroAM_IMC('OB')"
                                                   id="imc_ob" name="imc" value="OB" >
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <div class="col l2 center-align">
                                <i class="mdi-editor-insert-chart"
                                   onclick="loadHistorialParametro_M('<?php echo $rut ?>','imc')"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- PATOLOGIA BASE  -->
            <div class="row">
                <div class="col l12 m12 s12">
                    <div class="card-panel green lighten-2">
                        <div class="row">
                            <div class="col l3 m6 s12">
                                <strong style="line-height: 2em;font-size: 1.5em;">PATOLOGÍA <strong class="tooltipped" style="cursor: help" data-position="bottom" data-delay="50" data-tooltip="EL REGISTRO SERÁ GUARDADO AUTOMATICAMENTE">(?)</strong></strong>
                            </div>
                            <div class="col l9 m6 s12">
                                <div class="row">
                                    <div class="col l12 m12 s12">
                                        <input type="checkbox" id="patologia_diabetes"
                                               onchange="updateIndicadorM('patologia_diabetes')"
                                            <?php echo $patologia_diabetes=='SI'?'checked="checked"':'' ?>
                                               name="patologia_diabetes"  />
                                        <label class="white-text" for="patologia_diabetes">DIABETES MELLITUS</label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col l12 m12 s12">
                                        <input type="checkbox" id="patologia_hipertension"
                                               onchange="updateIndicadorPSCV('patologia_hipertension')"
                                            <?php echo $patologia_hipertension=='SI'?'checked="checked"':'' ?>
                                               name="patologia_hipertension"  />
                                        <label class="white-text" for="patologia_hipertension">Hipertensión Arterial</label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col l12 m12 s12">
                                        <input type="checkbox" id="patologia_vih"
                                               onchange="updateIndicadorPSCV('patologia_vih')"
                                            <?php echo $patologia_vih=='SI'?'checked="checked"':'' ?>
                                               name="patologia_vih"  />
                                        <label class="white-text" for="patologia_vih">VIH</label>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col l6 m12 s12">
            <div class="row">
                <div class="col l12 m12 s12">
                    <div class="card-panel green lighten-2">
                        <div class="row">
                            <div class="col l12 m12 s12" >
                                <strong style="line-height: 2em;font-size: 1.5em;">ESTADO DEL PACIENTE <strong class="tooltipped" style="cursor: help" data-position="bottom" data-delay="50" data-tooltip="EL REGISTRO SERÁ GUARDADO AUTOMATICAMENTE">(?)</strong></strong>
                            </div>
                            <div class="col l12 m12 s12">
                                <div class="row">
                                    <div class="col l12 m12 s12 tooltipped hoverClass"
                                         data-position="bottom" data-delay="50"
                                         data-tooltip="REGULACION DE FERTILIDAD Y SALUD SEXUAL">
                                        <div class="row center-align">
                                            <label class="black-text" for="estado_paciente">
                                                REGULACION DE FERTILIDAD Y SALUD SEXUAL
                                            </label><br />
                                            <input type="radio"
                                                   style="position: relative;visibility: visible;left: 0px;"
                                                   onclick="updateEstadoPacienteMujer('RESS')"
                                                   id="estado_paciente_RESS" name="estado_paciente" value="RESS" >
                                        </div>
                                    </div>
                                    <div class="col l12 m12 s12 tooltipped hoverClass"
                                         data-position="bottom" data-delay="50"
                                         data-tooltip="GESTANTE">
                                        <div class="row center-align">
                                            <label class="black-text" for="estado_paciente">
                                                GESTANTE
                                            </label><br />
                                            <input type="radio"
                                                   style="position: relative;visibility: visible;left: 0px;"
                                                   onclick="updateEstadoPacienteMujer('GESTANTE')"
                                                   id="estado_paciente_GESTANTE" name="estado_paciente" value="GESTANTE" >
                                        </div>
                                    </div>
                                    <div class="col l12 m12 s12 tooltipped hoverClass"
                                         data-position="bottom" data-delay="50"
                                         data-tooltip="CLIMATERIO">
                                        <div class="row center-align">
                                            <label class="black-text" for="estado_paciente">
                                                CLIMATERIO
                                            </label><br />
                                            <input type="radio"
                                                   style="position: relative;visibility: visible;left: 0px;"
                                                   onclick="updateEstadoPacienteMujer('CLIMATERIO')"
                                                   id="estado_paciente_CLIMATERIO" name="estado_paciente" value="CLIMATERIO" >
                                        </div>
                                    </div>
                                    <div class="col l12 m12 s12 tooltipped hoverClass"
                                         data-position="bottom" data-delay="50"
                                         data-tooltip="PAP">
                                        <div class="row center-align">
                                            <label class="black-text" for="estado_paciente">
                                                PAP
                                            </label><br />
                                            <input type="radio"
                                                   style="position: relative;visibility: visible;left: 0px;"
                                                   onclick="updateEstadoPacienteMujer('PAP')"
                                                   id="estado_paciente_PAP" name="estado_paciente" value="PAP" >
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col l2 center-align">
                                <i class="mdi-editor-insert-chart"
                                   onclick="loadHistorialParametro_M('<?php echo $rut ?>','imc')"></i>
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
        $("#imc_<?php echo strtolower($imc); ?>").attr('checked','cheched');

    });


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

    function updateParametroM_IMC(value) {
        var fecha = $("#fecha_imc").val();
        $.post('db/update/m_parametros.php',{
            column:'imc',
            value:value,
            fecha_registro:fecha,
            rut:'<?php echo $rut ?>'
        },function (data) {
            alertaLateral(data);
        });
    }
    function loadHistorialParametro_M(rut,indicador) {
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
