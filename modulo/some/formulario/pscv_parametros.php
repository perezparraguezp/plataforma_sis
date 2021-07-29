<?php

include "../../../php/config.php";
include "../../../php/objetos/persona.php";
$rut = $_POST['rut'];
$fecha_registro = $_POST['fecha_registro'];

$paciente = new persona($rut);


$pa =  $paciente->getParametroPSCV('pa');
$glicemia =  $paciente->getParametroPSCV('glicemia');
$ptgo =  $paciente->getParametroPSCV('ptgo');
$colt =  $paciente->getParametroPSCV('colt');
$ldl =  $paciente->getParametroPSCV('ldl');
$ekg =  $paciente->getParametroPSCV('ekg');
$erc_vfg =  $paciente->getParametroPSCV('erc_vfg');
$rac =  $paciente->getParametroPSCV('rac');
$imc =  $paciente->getParametroPSCV('imc');



?>
<style type="text/css">
    #form_parametros_pscv select{
        height: 35px;
    }
    #form_parametros_pscv .row{
        margin-bottom: 1px;
    }
</style>
<form class="content card-panel" id="form_parametros_pscv">
    <div class="row">
        <div class="col l6 m12 s12">
            <div class="row">
                <div class="col l12 m12 s12">
                    <div class="card-panel green lighten-2">
                        <div class="row">
                            <div class="col l3 m6 s12">
                                <strong style="line-height: 2em;font-size: 1.5em;">P/A <strong class="tooltipped" style="cursor: help" data-position="bottom" data-delay="50" data-tooltip="EL REGISTRO SERÁ GUARDADO AUTOMATICAMENTE">(?)</strong></strong>
                            </div>
                            <div class="col l7 m6 s12">
                                <input type="hidden" name="fecha_pa" id="fecha_pa" value="<?php echo $fecha_registro; ?>" />
                                <div class="row">
                                    <div class="col l12 m12 s12">
                                        <select class="browser-default"
                                                name="pa" id="pa"
                                                style="height: 35px;"
                                                onchange="updateParametroPSCV('pa')">
                                            <option></option>
                                            <option><140/90 mmHg</option>
                                            <option><150/90 mmHg</option>
                                            <option>>=160/90 mmHg</option>
                                            <option>OTRA</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col l2 center-align">
                                <i class="mdi-editor-insert-chart"
                                   onclick="loadHistorialParametroPSCV('<?php echo $rut ?>','pa')"></i>
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
                                <strong style="line-height: 2em;font-size: 1.5em;">GLICEMIA AYUNA <strong class="tooltipped" style="cursor: help" data-position="bottom" data-delay="50" data-tooltip="EL REGISTRO SERÁ GUARDADO AUTOMATICAMENTE">(?)</strong></strong>
                            </div>
                            <div class="col l7 m6 s12">
                                <div class="row">
                                    <div class="col l6 m12 s12">
                                        <input type="date"
                                               class="tooltipped"
                                               style="cursor: help"
                                               data-position="bottom"
                                               data-delay="50"
                                               data-tooltip="FECHA EN QUE SE TOMO LA GLICEMIA"
                                               style="height: 2em;"
                                               id="fecha_glicemia" name="fecha_glicemia" value="<?php echo $fecha_registro; ?>">
                                    </div>
                                    <div class="col l6 m12 s12">
                                        <select class="browser-default"
                                                name="glicemia" id="glicemia"
                                                onchange="updateParametroPSCV('glicemia')">
                                            <option></option>
                                            <option>NORMAL</option>
                                            <option>ALTERADA</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col l2 center-align">
                                <i class="mdi-editor-insert-chart"
                                   onclick="loadHistorialParametroPSCV('<?php echo $rut ?>','glicemia')"></i>
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
                                <strong style="line-height: 2em;font-size: 1.5em;">PTGO <strong class="tooltipped" style="cursor: help" data-position="bottom" data-delay="50" data-tooltip="EL REGISTRO SERÁ GUARDADO AUTOMATICAMENTE">(?)</strong></strong>
                            </div>
                            <div class="col l7 m6 s12">
                                <div class="row">
                                    <div class="col l6 m12 s12">
                                        <input type="date"
                                               class="tooltipped"
                                               style="cursor: help"
                                               data-position="bottom"
                                               data-delay="50"
                                               data-tooltip="FECHA EN QUE SE TOMO PTGO"
                                               style="height: 2em;"
                                               id="fecha_ptgo" name="fecha_ptgo" value="<?php echo $fecha_registro; ?>">
                                    </div>
                                    <div class="col l6 m12 s12">
                                        <select class="browser-default"
                                                name="ptgo" id="ptgo"
                                                onchange="updateParametroPSCV('ptgo')">
                                            <option></option>
                                            <option>NORMAL</option>
                                            <option>ALTERADA</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col l2 center-align">
                                <i class="mdi-editor-insert-chart"
                                   onclick="loadHistorialParametroPSCV('<?php echo $rut ?>','ptgo')"></i>
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
                                <strong style="line-height: 2em;font-size: 1.5em;">COL-T <strong class="tooltipped" style="cursor: help" data-position="bottom" data-delay="50" data-tooltip="EL REGISTRO SERÁ GUARDADO AUTOMATICAMENTE">(?)</strong></strong>
                            </div>
                            <div class="col l7 m6 s12">
                                <div class="row">
                                    <div class="col l6 m12 s12">
                                        <input type="date"
                                               class="tooltipped"
                                               style="cursor: help"
                                               data-position="bottom"
                                               data-delay="50"
                                               data-tooltip="FECHA EN QUE SE TOMO COL-T"
                                               style="height: 2em;"
                                               id="fecha_colt" name="fecha_colt" value="<?php echo $fecha_registro; ?>">
                                    </div>
                                    <div class="col l6 m12 s12">
                                        <select class="browser-default"
                                                name="colt" id="colt"
                                                onchange="updateParametroPSCV('colt')">
                                            <option></option>
                                            <option>>=200 mg/dl</option>
                                            <option><200 mg/dl</option>

                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col l2 center-align">
                                <i class="mdi-editor-insert-chart"
                                   onclick="loadHistorialParametroPSCV('<?php echo $rut ?>','colt')"></i>
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
                                <strong style="line-height: 2em;font-size: 1.5em;">LDL <strong class="tooltipped" style="cursor: help" data-position="bottom" data-delay="50" data-tooltip="EL REGISTRO SERÁ GUARDADO AUTOMATICAMENTE">(?)</strong></strong>
                            </div>
                            <div class="col l7 m6 s12">
                                <div class="row">
                                    <div class="col l6 m12 s12">
                                        <input type="date"
                                               class="tooltipped"
                                               style="cursor: help"
                                               data-position="bottom"
                                               data-delay="50"
                                               data-tooltip="FECHA EN QUE SE TOMO LDL"
                                               style="height: 2em;"
                                               id="fecha_ldl" name="fecha_ldl" value="<?php echo $fecha_registro; ?>">
                                    </div>
                                    <div class="col l6 m12 s12">
                                        <select class="browser-default"
                                                name="ldl" id="ldl"
                                                onchange="updateParametroPSCV('ldl')">
                                            <option></option>
                                            <option>>=100 mg/dl</option>
                                            <option><100 mg/dl</option>

                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col l2 center-align">
                                <i class="mdi-editor-insert-chart"
                                   onclick="loadHistorialParametroPSCV('<?php echo $rut ?>','ldl')"></i>
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
                                <strong style="line-height: 2em;font-size: 1.5em;">RAC <strong class="tooltipped" style="cursor: help" data-position="bottom" data-delay="50" data-tooltip="EL REGISTRO SERÁ GUARDADO AUTOMATICAMENTE">(?)</strong></strong>
                            </div>
                            <div class="col l7 m6 s12">
                                <div class="row">
                                    <div class="col l6 m12 s12">
                                        <input type="date"
                                               class="tooltipped"
                                               style="cursor: help"
                                               data-position="bottom"
                                               data-delay="50"
                                               data-tooltip="FECHA EN QUE SE TOMO RAC"
                                               style="height: 2em;"
                                               id="fecha_rac" name="fecha_rac" value="<?php echo $fecha_registro; ?>">
                                    </div>
                                    <div class="col l6 m12 s12">
                                        <select class="browser-default"
                                                name="rac" id="rac"
                                                onchange="updateParametroPSCV('rac')">
                                            <option></option>
                                            <option>< 30 mg/g</option>
                                            <option>30 a 300 mg/g</option>
                                            <option>> 300 mg/g</option>

                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col l2 center-align">
                                <i class="mdi-editor-insert-chart"
                                   onclick="loadHistorialParametroPSCV('<?php echo $rut ?>','rac')"></i>
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
                                <strong style="line-height: 2em;font-size: 1.5em;">ERC/VFG <strong class="tooltipped" style="cursor: help" data-position="bottom" data-delay="50" data-tooltip="EL REGISTRO SERÁ GUARDADO AUTOMATICAMENTE">(?)</strong></strong>
                            </div>
                            <div class="col l7 m6 s12">
                                <div class="row">
                                    <div class="col l6 m12 s12">
                                        <input type="date"
                                               class="tooltipped"
                                               style="cursor: help"
                                               data-position="bottom"
                                               data-delay="50"
                                               data-tooltip="FECHA EN QUE SE TOMO ERC/VFG"
                                               style="height: 2em;"
                                               id="fecha_erc_vfg" name="fecha_erc_vfg" value="<?php echo $fecha_registro; ?>">
                                    </div>
                                    <div class="col l6 m12 s12">
                                        <select class="browser-default"
                                                name="erc_vfg" id="erc_vfg"
                                                onchange="updateParametroPSCV('erc_vfg')">
                                            <option></option>
                                            <option>S/ERC</option>
                                            <option>ETAPA G1 Y G2 (VFG >= 60 ml/min)</option>
                                            <option>ETAPA G3a (VFG >= 45 A 59 ml/min)</option>
                                            <option>ETAPA G3b (VFG >= 30 a 44 ml/min)</option>
                                            <option>ETAPA G4 (VFG >= 15 a 29 ml/min)</option>
                                            <option>ETAPA G5 (VFG < 15 ml/min)</option>

                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col l2 center-align">
                                <i class="mdi-editor-insert-chart"
                                   onclick="loadHistorialParametroPSCV('<?php echo $rut ?>','erc_vfg')"></i>
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
                                <img src="../../images/pscv/ekg.png"  height="60px"
                                     class="tooltipped"
                                     data-position="bottom"
                                     data-delay="50"
                                     data-tooltip="EKG" />
                            </div>
                            <div class="col l7 m6 s12">
                                <div class="row">
                                    <div class="col l12 m12 s12">
                                        <input type="date"
                                               class="tooltipped"
                                               style="cursor: help"
                                               data-position="bottom"
                                               data-delay="50"
                                               onchange="updateParametroPSCV_EKG()"
                                               data-tooltip="FECHA EN QUE SE TOMO EKG"
                                               style="height: 2em;"
                                               id="fecha_ekg" name="fecha_ekg" value="">
                                    </div>
                                </div>
                            </div>
                            <div class="col l2 center-align">
                                <i class="mdi-editor-insert-chart"
                                   onclick="loadHistorialParametroPSCV('<?php echo $rut ?>','ekg')"></i>
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
                                                   onclick="updateParametroPSCV_IMC('BP')"
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
                                                   onclick="updateParametroPSCV_IMC('N')"
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
                                                   onclick="updateParametroPSCV_IMC('SP')"
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
                                                   onclick="updateParametroPSCV_IMC('OB')"
                                                   id="imc_ob" name="imc" value="OB" >
                                        </div>

                                    </div>
                                    <div class="col l12 m12 s12">

<!--                                        <select class="browser-default"-->
<!--                                                name="imc" id="imc"-->
<!--                                                onchange="updateParametroPSCV('imc')">-->
<!--                                            <option></option>-->
<!--                                            <option>BP</option>-->
<!--                                            <option>NORMAL</option>-->
<!--                                            <option>SP</option>-->
<!--                                            <option>OB</option>-->
<!---->
<!--                                        </select>-->
                                    </div>
                                </div>
                            </div>
                            <div class="col l2 center-align">
                                <i class="mdi-editor-insert-chart"
                                   onclick="loadHistorialParametroPSCV('<?php echo $rut ?>','imc')"></i>
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
        $('.tooltipped').tooltip({delay: 50});
        $(".item_riesgo_cv").on('change',function(){
            var value = $(this).val();
            $.post('db/update/pscv_paciente.php',{
                column:'riesgo_cv',
                value:value,
                rut:'<?php echo $rut ?>'
            },function (data) {
                alertaLateral(data);
            });
        });
        $(".postrado").on('change',function(){
            var value = $(this).val();
            $.post('db/update/pscv_paciente.php',{
                column:'postrado',
                value:value,
                rut:'<?php echo $rut ?>'
            },function (data) {
                alertaLateral(data);
            });
        });
        $(".hemodialisis").on('change',function(){
            var value = $(this).val();
            $.post('db/update/pscv_paciente.php',{
                column:'hemodialisis',
                value:value,
                rut:'<?php echo $rut ?>'
            },function (data) {
                alertaLateral(data);
            });
        });
    });

    function updateParametroPSCV(indicador) {
        var value = $("#"+indicador).val();
        var fecha = $("#fecha_"+indicador).val();
        $.post('db/update/pscv_parametros.php',{
            column:indicador,
            value:value,
            fecha_registro:fecha,
            rut:'<?php echo $rut ?>'
        },function (data) {
            alertaLateral(data);
        });
    }



</script>
