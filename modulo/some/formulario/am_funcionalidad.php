<?php

include "../../../php/config.php";
include "../../../php/objetos/persona.php";

$rut = $_POST['rut'];
$fecha_registro = $_POST['fecha_registro'];

$paciente = new persona($rut);


$riesgo_cv =  $paciente->getIndicadorPSCV('riesgo_cv');

$patologia_hta =  $paciente->getIndicadorPSCV('patologia_hta');
$patologia_hta_sigges =  $paciente->getIndicadorPSCV('patologia_hta_sigges');
$patologia_dm =  $paciente->getIndicadorPSCV('patologia_dm');
$patologia_dm_sigges =  $paciente->getIndicadorPSCV('patologia_dm_sigges');
$patologia_dlp =  $paciente->getIndicadorPSCV('patologia_dlp');

$factor_riesgo_tabaquismo   =  $paciente->getIndicadorPSCV('factor_riesgo_tabaquismo');
$factor_riesgo_iam          =  $paciente->getIndicadorPSCV('factor_riesgo_iam');
$factor_riesgo_enf_cv       =  $paciente->getIndicadorPSCV('factor_riesgo_enf_cv');
$tratamiento_aas            =  $paciente->getIndicadorPSCV('tratamiento_aas');
$tratamiento_ieeca          =  $paciente->getIndicadorPSCV('tratamiento_ieeca');
$tratamiento_estatina       =  $paciente->getIndicadorPSCV('tratamiento_estatina');
$tratamiento_araii          =  $paciente->getIndicadorPSCV('tratamiento_araii');


$postrado = $paciente->getIndicadorPSCV('postrado');
$hemodialisis = $paciente->getIndicadorPSCV('hemodialisis');


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
                            <div class="col l3 m6 s12">
                                <strong style="line-height: 2em;font-size: 1.5em;">IMC <strong class="tooltipped" style="cursor: help" data-position="bottom" data-delay="50" data-tooltip="EL REGISTRO SERÁ GUARDADO AUTOMATICAMENTE">(?)</strong></strong>
                            </div>
                            <div class="col l7 m6 s12">
                                <div class="row">
                                    <input type="hidden" name="fecha_imc" id="fecha_imc" value="<?php echo $fecha_registro; ?>" />
                                    <div class="col l3 m3 s3">
                                        <div class="row center-align">
                                            <label class="white-text" for="imc_bp">
                                                <img src="../../images/pscv/bp.png" height="100px" />
                                            </label><br />
                                            <input type="radio"
                                                   style="position: relative;visibility: visible;left: 0px;"
                                                   onclick="updateParametroAM_IMC('BP')"
                                                   id="imc_bp" name="imc" value="BP" >
                                        </div>
                                    </div>
                                    <div class="col l3 m3 s3">
                                        <div class="row center-align">
                                            <label class="white-text" for="imc_n">
                                                <img src="../../images/pscv/n.png" height="100px" />
                                            </label><br />
                                            <input type="radio"
                                                   style="position: relative;visibility: visible;left: 0px;"
                                                   onclick="updateParametroAM_IMC('N')"
                                                   id="imc_n" name="imc" value="N" >
                                        </div>
                                    </div>
                                    <div class="col l3 m3 s3">
                                        <div class="row center-align">
                                            <label class="white-text" for="imc_sp">
                                                <img src="../../images/pscv/sp.png" height="100px"  />
                                            </label><br />
                                            <input type="radio"
                                                   style="position: relative;visibility: visible;left: 0px;"
                                                   onclick="updateParametroAM_IMC('SP')"
                                                   id="imc_sp" name="imc" value="SP" >
                                        </div>
                                    </div>
                                    <div class="col l3 m3 s3">
                                        <div class="row center-align">
                                            <label class="white-text" for="imc_ob">
                                                <img src="../../images/pscv/ob.png" height="100px"  />
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
                                   onclick="loadHistorialParametroAM('<?php echo $rut ?>','imc')"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- ACTIVIDAD FISICA  -->
            <div class="row">
                <div class="col l12 m12 s12">
                    <div class="card-panel green lighten-2">
                        <div class="row">
                            <div class="col l3 m6 s12">
                                <strong style="line-height: 2em;font-size: 1.5em;">ACTIVIDAD FISICA <strong class="tooltipped" style="cursor: help" data-position="bottom" data-delay="50" data-tooltip="EL REGISTRO SERÁ GUARDADO AUTOMATICAMENTE">(?)</strong></strong>
                            </div>
                            <div class="col l7 m6 s12">
                                <div class="row">
                                    <input type="hidden" name="fecha_imc" id="fecha_imc" value="<?php echo $fecha_registro; ?>" />
                                    <div class="col l6 m6 s6">
                                        <div class="row center-align" title="SI">
                                            <label class="white-text" for="af_si">
                                                <img src="../../images/am/activiad_fisica_si.png" height="100px" />
                                            </label><br />
                                            <input type="radio"
                                                   style="position: relative;visibility: visible;left: 0px;"
                                                   onclick="updateIndicadorAM_variable('actividad_fisica','SI')"
                                                   id="af_si" name="af" value="SI" >
                                        </div>
                                    </div>
                                    <div class="col l6 m6 s6">
                                        <div class="row center-align">
                                            <label class="white-text" for="af_no">
                                                <img src="../../images/am/activiad_fisica_no.png"
                                                     height="100px" />
                                            </label><br />
                                            <input type="radio"
                                                   style="position: relative;visibility: visible;left: 0px;"
                                                   onclick="updateIndicadorAM_variable('actividad_fisica','NO')"
                                                   id="af_no" name="af" value="NO" >
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col l2 center-align">
                                <i class="mdi-editor-insert-chart"
                                   onclick="loadHistorialParametroAM('<?php echo $rut ?>','imc')"></i>
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
                            <div class="col l3 m6 s12">
                                <strong style="line-height: 2em;font-size: 1.5em;">RIESGO DE CAIDAS <strong class="tooltipped" style="cursor: help" data-position="bottom" data-delay="50" data-tooltip="EL REGISTRO SERÁ GUARDADO AUTOMATICAMENTE">(?)</strong></strong>
                            </div>
                            <div class="col l9 m6 s12">
                                <div class="row">
                                    <select class="browser-default"
                                            style="font-size: 1.5em"
                                            name="riesgo_caida" id="riesgo_caida"
                                            onchange="updateIndicadorAM('riesgo_caida')">
                                        <option>NORMAL</option>
                                        <option>LEVE</option>
                                        <option>ALTO</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col l12 m12 s12">
                    <div class="card-panel green lighten-2">
                        <div class="row">
                            <div class="col l3 m6 s12">
                                <strong style="line-height: 2em;font-size: 1.5em;">SOSPECHA DE MALTRATO <strong class="tooltipped" style="cursor: help" data-position="bottom" data-delay="50" data-tooltip="EL REGISTRO SERÁ GUARDADO AUTOMATICAMENTE">(?)</strong></strong>
                            </div>
                            <div class="col l9 m6 s12">
                                <div class="row">
                                    <select class="browser-default"
                                            style="font-size: 1.5em"
                                            name="sospecha_maltrato" id="sospecha_maltrato"
                                            onchange="updateIndicadorAM('sospecha_maltrato')">
                                        <option>NORMAL</option>
                                        <option>ALTERADO</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col l12 m12 s12">
                    <div class="card-panel green lighten-2">
                        <div class="row">
                            <div class="col l3 m6 s12">
                                <strong style="line-height: 2em;font-size: 1.5em;">YESAVAGE <strong class="tooltipped" style="cursor: help" data-position="bottom" data-delay="50" data-tooltip="EL REGISTRO SERÁ GUARDADO AUTOMATICAMENTE">(?)</strong></strong>
                            </div>
                            <div class="col l9 m6 s12">
                                <div class="row">
                                    <select class="browser-default"
                                            style="font-size: 1.5em"
                                            name="yesavage" id="yesavage"
                                            onchange="updateIndicadorAM('yesavage')">
                                        <option>NORMAL</option>
                                        <option>DEPRESION LEVE</option>
                                        <option>DEPRESION ESTABLECIDA</option>
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
<script type="text/javascript">
    $(function () {
        //DM

        <?php echo $patologia_dm=='SI'?"$('#tabs_registro').jqxTabs('enableAt', 2);":"$('#tabs_registro').jqxTabs('disableAt', 2)"; ?>

        $("#patologia_dm").on('change',function () {
            if($('#patologia_dm').prop('checked')){
                $('#tabs_registro').jqxTabs('enableAt', 2);
            }else{
                $('#tabs_registro').jqxTabs('disableAt', 2);
            }
        });


        $('.tooltipped').tooltip({delay: 50});


        $(".postrado").on('change',function(){
            var value = $(this).val();
            $.post('db/update/pscv_paciente.php',{
                column:'postrado',
                value:value,
                rut:'<?php echo $rut ?>',
                fecha_registro:'<?php echo $fecha_registro ?>'
            },function (data) {
                alertaLateral(data);
            });
        });
        $(".hemodialisis").on('change',function(){
            var value = $(this).val();
            $.post('db/update/pscv_paciente.php',{
                column:'hemodialisis',
                value:value,
                rut:'<?php echo $rut ?>',
                fecha_registro:'<?php echo $fecha_registro ?>'
            },function (data) {
                alertaLateral(data);
            });
        });
    });

    function loadHistorialAM(rut,indicador) {
        $.post('grid/historial_pscv.php',{
            rut:rut,
            fecha_registro:'<?php echo $fecha_registro; ?>',
            indicador:indicador
        },function(data){
            if(data !== 'ERROR_SQL'){
                $("#modal").html(data);
                $("#modal").css({'width':'800px'});
                document.getElementById("btn-modal").click();
            }
        });
    }
    function updateIndicadorAM(indicador) {
        var value = $('#'+indicador).val();
        var fecha = $("#fecha_antecedentes").val();
        $.post('db/update/am_parametros.php',{
            column:indicador,
            value:value,
            fecha_registro:fecha,
            rut:'<?php echo $rut ?>'
        },function (data) {
            alertaLateral(data);
        });
    }
    function updateIndicadorAM_variable(indicador,value) {
        var fecha = $("#fecha_antecedentes").val();
        $.post('db/update/am_parametros.php',{
            column:indicador,
            value:value,
            fecha_registro:fecha,
            rut:'<?php echo $rut ?>'
        },function (data) {
            alertaLateral(data);
        });
    }
    function updateParametroAM_IMC(value) {
        var fecha = $("#fecha_imc").val();
        $.post('db/update/am_parametros.php',{
            column:'imc',
            value:value,
            fecha_registro:fecha,
            rut:'<?php echo $rut ?>'
        },function (data) {
            alertaLateral(data);
        });
    }
    function loadHistorialParametroAM(rut,indicador) {
        $.post('grid/historial_parametros_am.php',{
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
