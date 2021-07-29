<?php

include "../../../php/config.php";
include "../../../php/objetos/persona.php";

$rut = $_POST['rut'];
$fecha_registro = $_POST['fecha_registro'];

$paciente = new persona($rut);


$imc =  $paciente->getParametro_AD('imc');
$cintura =  $paciente->getParametro_AD('cintura');
$talla =  $paciente->getParametro_AD('talla_edad');


?>
<style type="text/css">
    .IMC:hover{
        background-color: #f1ffc5;
        cursor: help;
    }
</style>

<form class="content card-panel">
    <input type="hidden" name="fecha_antecedentes" id="fecha_antecedentes" value="<?php echo $fecha_registro; ?>" />
    <div class="row">
        <div class="col l4 m12 s12">
            <!-- IMC  -->
            <div class="row">
                <div class="col l12 m12 s12">
                    <div class="card-panel green lighten-2">
                        <div class="row">
                            <div class="col l12 m12 s12">
                                <strong style="line-height: 2em;font-size: 1.5em;">ESTADO NUTRICIONAL <strong class="tooltipped" style="cursor: help" data-position="bottom" data-delay="50" data-tooltip="EL REGISTRO SERÁ GUARDADO AUTOMATICAMENTE">(?)</strong></strong>
                                <i class="mdi-editor-insert-chart" onclick="loadHistorialParametroAD('<?php echo $rut ?>','imc')"></i>
                            </div>
                            <div class="col l12 m12 s12">
                                <div class="row">
                                    <input type="hidden" name="fecha_imc" id="fecha_imc" value="<?php echo $fecha_registro; ?>" />
                                    <div class="col l2 m2 s2 tooltipped IMC" data-position="bottom" data-delay="50" data-tooltip="DESNUTRICION">
                                        <div class="row center-align">
                                            <label class="white-text" for="imc_dn">
                                                <img src="../../images/ad/DN.png" height="100px" /><br />
                                            </label><br />
                                            <input type="radio"
                                                   style="position: relative;visibility: visible;left: 0px;"
                                                   onclick="updateParametroAD_IMC('DN')"
                                                   id="imc_dn" name="imc" value="DN" >
                                        </div>
                                    </div>
                                    <div class="col l2 m2 s2 tooltipped IMC" data-position="bottom" data-delay="50" data-tooltip="BAJO PESO">
                                        <div class="row center-align">
                                            <label class="white-text" for="imc_bp">
                                                <img src="../../images/ad/BP.png" height="100px" /><br />
                                            </label><br />
                                            <input type="radio"
                                                   style="position: relative;visibility: visible;left: 0px;"
                                                   onclick="updateParametroAD_IMC('BP')"
                                                   id="imc_bp" name="imc" value="BP" >
                                        </div>
                                    </div>
                                    <div class="col l2 m2 s2 tooltipped IMC" data-position="bottom" data-delay="50" data-tooltip="NORMAL">
                                        <div class="row center-align">
                                            <label class="white-text" for="imc_n">
                                                <img src="../../images/ad/N.png" height="100px" /><br />
                                            </label><br />
                                            <input type="radio"
                                                   style="position: relative;visibility: visible;left: 0px;"
                                                   onclick="updateParametroAD_IMC('N')"
                                                   id="imc_n" name="imc" value="N" >
                                        </div>
                                    </div>
                                    <div class="col l2 m2 s2 tooltipped IMC" data-position="bottom" data-delay="50" data-tooltip="SOBRE PESO">
                                        <div class="row center-align">
                                            <label class="white-text" for="imc_sp">
                                                <img src="../../images/ad/SP.png" height="100px" /><br />
                                            </label><br />
                                            <input type="radio"
                                                   style="position: relative;visibility: visible;left: 0px;"
                                                   onclick="updateParametroAD_IMC('SP')"
                                                   id="imc_sp" name="imc" value="SP" >
                                        </div>
                                    </div>
                                    <div class="col l2 m2 s2 tooltipped IMC" data-position="bottom" data-delay="50" data-tooltip="OBESIDAD">
                                        <div class="row center-align">
                                            <label class="white-text" for="imc_ob">
                                                <img src="../../images/ad/OB.png" height="100px" /><br />
                                            </label><br />
                                            <input type="radio"
                                                   style="position: relative;visibility: visible;left: 0px;"
                                                   onclick="updateParametroAD_IMC('OB')"
                                                   id="imc_ob" name="imc" value="OB" >
                                        </div>
                                    </div>
                                    <div class="col l2 m2 s2 tooltipped IMC" data-position="bottom" data-delay="50" data-tooltip="OBESIDAD MORBIDA">
                                        <div class="row center-align">
                                            <label class="white-text" for="imc_obm">
                                                <img src="../../images/ad/OBM.png" height="100px" /><br />
                                            </label><br />
                                            <input type="radio"
                                                   style="position: relative;visibility: visible;left: 0px;"
                                                   onclick="updateParametroAD_IMC('OBM')"
                                                   id="imc_obm" name="imc" value="OBM" >
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- ACTIVIDAD FISICA  -->
        </div>
        <div class="col l4 m12 s12">
            <div class="row">
                <div class="col l12 m12 s12">
                    <div class="card-panel green lighten-2">
                        <div class="row">
                            <div class="col l12 m12 s12">
                                <strong style="line-height: 2em;font-size: 1.5em;">CIRCUNFERENCIA DE CINTURA<strong class="tooltipped" style="cursor: help" data-position="bottom" data-delay="50" data-tooltip="EL REGISTRO SERÁ GUARDADO AUTOMATICAMENTE">(?)</strong></strong>
                                <i class="mdi-editor-insert-chart" onclick="loadHistorialParametroAD('<?php echo $rut ?>','cintura')"></i>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col l4 m4 s4 tooltipped IMC" data-position="bottom" data-delay="50" data-tooltip="NORMAL">
                                <div class="row center-align">
                                    <label class="white-text" for="cin_normal">
                                        <img src="../../images/ad/NORMAL.png" height="100px" /><br />
                                    </label><br />
                                    <input type="radio"
                                           style="position: relative;visibility: visible;left: 0px;"
                                           onclick="updateParametroAD_CINTURA('NORMAL')"
                                           id="cin_normal" name="cintura" value="NORMAL" >
                                </div>
                            </div>
                            <div class="col l4 m4 s4 tooltipped IMC" data-position="bottom" data-delay="50" data-tooltip="RIESGO OBESIDAD">
                                <div class="row center-align">
                                    <label class="white-text" for="cin_riob">
                                        <img src="../../images/ad/RIOB.png" height="100px" /><br />
                                    </label><br />
                                    <input type="radio"
                                           style="position: relative;visibility: visible;left: 0px;"
                                           onclick="updateParametroAD_CINTURA('RIOB')"
                                           id="cin_riob" name="cintura" value="RIOB" >
                                </div>
                            </div>
                            <div class="col l4 m4 s4 tooltipped IMC" data-position="bottom" data-delay="50" data-tooltip="OBESIDAD ABDOMINAL">
                                <div class="row center-align">
                                    <label class="white-text" for="cin_obad">
                                        <img src="../../images/ad/OBAD.png" height="100px" /><br />
                                    </label><br />
                                    <input type="radio"
                                           style="position: relative;visibility: visible;left: 0px;"
                                           onclick="updateParametroAD_CINTURA('OBAD')"
                                           id="cin_obad" name="cintura" value="OBAD" >
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col l4 m12 s12">
            <div class="row">
                <div class="col l12 m12 s12">
                    <div class="card-panel green lighten-2">
                        <div class="row">
                            <div class="col l12 m12 s12">
                                <strong style="line-height: 2em;font-size: 1.5em;">TALLA EDAD<strong class="tooltipped" style="cursor: help" data-position="bottom" data-delay="50" data-tooltip="EL REGISTRO SERÁ GUARDADO AUTOMATICAMENTE">(?)</strong></strong>
                                <i class="mdi-editor-insert-chart" onclick="loadHistorialParametroAD('<?php echo $rut ?>','talla_edad')"></i>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col l2 m2 s2 tooltipped IMC" data-position="bottom" data-delay="50" data-tooltip="BAJA">
                                <div class="row center-align">
                                    <label class="white-text" for="talla_baja">
                                        <img src="../../images/ad/BAJA.png"  /><br />
                                    </label><br />
                                    <input type="radio"
                                           style="position: relative;visibility: visible;left: 0px;"
                                           onclick="updateParametroAD_TALLA('BAJA')"
                                           id="talla_baja" name="talla" value="BAJA" >
                                </div>
                            </div>
                            <div class="col l2 m2 s2 tooltipped IMC" data-position="bottom" data-delay="50" data-tooltip="NORMAL BAJA">
                                <div class="row center-align">
                                    <label class="white-text" for="talla_nbaja">
                                        <img src="../../images/ad/NBAJA.png" /><br />
                                    </label><br />
                                    <input type="radio"
                                           style="position: relative;visibility: visible;left: 0px;"
                                           onclick="updateParametroAD_TALLA('NBAJA')"
                                           id="talla_nbaja" name="talla" value="NBAJA" >
                                </div>
                            </div>
                            <div class="col l2 m2 s2 tooltipped IMC" data-position="bottom" data-delay="50" data-tooltip="NORMAL">
                                <div class="row center-align">
                                    <label class="white-text" for="talla_normal">
                                        <img src="../../images/ad/TALLA_NORMAL.png"  /><br />
                                    </label><br />
                                    <input type="radio"
                                           style="position: relative;visibility: visible;left: 0px;"
                                           onclick="updateParametroAD_TALLA('NORMAL')"
                                           id="talla_normal" name="talla" value="NORMAL" >
                                </div>
                            </div>
                            <div class="col l2 m2 s2 tooltipped IMC" data-position="bottom" data-delay="50" data-tooltip="NORMAL ALTA">
                                <div class="row center-align">
                                    <label class="white-text" for="talla_nalta">
                                        <img src="../../images/ad/NALTO.png" /><br />
                                    </label><br />
                                    <input type="radio"
                                           style="position: relative;visibility: visible;left: 0px;"
                                           onclick="updateParametroAD_TALLA('NALTA')"
                                           id="talla_nalta" name="talla" value="NALTA" >
                                </div>
                            </div>
                            <div class="col l2 m2 s2 tooltipped IMC" data-position="bottom" data-delay="50" data-tooltip="ALTA">
                                <div class="row center-align">
                                    <label class="white-text" for="talla_alta">
                                        <img src="../../images/ad/ALTO.png"  /><br />
                                    </label><br />
                                    <input type="radio"
                                           style="position: relative;visibility: visible;left: 0px;"
                                           onclick="updateParametroAD_TALLA('ALTA')"
                                           id="talla_alta" name="talla" value="ALTA" >
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

        $('.tooltipped').tooltip({delay: 50});
        $("#imc_<?php echo strtolower($imc); ?>").attr('checked','cheched');
        $("#cin_<?php echo strtolower($cintura); ?>").attr('checked','cheched');
        $("#talla_<?php echo strtolower($talla); ?>").attr('checked','cheched');

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
    function updateIndicadorEducacionAD(indicador,value) {
        var fecha = $("#fecha_antecedentes").val();
        $.post('db/update/ad_parametros.php',{
            column:indicador,
            value:value,
            fecha_registro:fecha,
            rut:'<?php echo $rut ?>'
        },function (data) {
            alertaLateral(data);

        });
    }
    function updateIndicadorAD(indicador,value) {
        var fecha = $("#fecha_antecedentes").val();
        $.post('db/update/ad_riesgos.php',{
            column:indicador,
            value:value,
            fecha_registro:fecha,
            rut:'<?php echo $rut ?>'
        },function (data) {
            alertaLateral(data);

        });
    }
    function updateIndicadorAD_consejeria(indicador,value) {
        var fecha = $("#fecha_antecedentes").val();
        $.post('db/update/ad_consejerias.php',{
            column:indicador,
            value:value,
            fecha_registro:fecha,
            rut:'<?php echo $rut ?>'
        },function (data) {
            alertaLateral(data);
            loadHistorialRiesgosAD('<?php echo $rut; ?>','educacion');
        });
    }
    function updateParametroAD_IMC(value) {
        var fecha = $("#fecha_imc").val();
        $.post('db/update/ad_parametros.php',{
            column:'imc',
            value:value,
            fecha_registro:fecha,
            rut:'<?php echo $rut ?>'
        },function (data) {
            alertaLateral(data);
        });
    }
    function updateParametroAD_CINTURA(value) {
        var fecha = $("#fecha_imc").val();
        $.post('db/update/ad_parametros.php',{
            column:'cintura',
            value:value,
            fecha_registro:fecha,
            rut:'<?php echo $rut ?>'
        },function (data) {
            alertaLateral(data);
        });
    }
    function updateParametroAD_TALLA(value) {
        var fecha = $("#fecha_imc").val();
        $.post('db/update/ad_parametros.php',{
            column:'talla_edad',
            value:value,
            fecha_registro:fecha,
            rut:'<?php echo $rut ?>'
        },function (data) {
            alertaLateral(data);
        });
    }
    function loadHistorialParametroAD(rut,indicador) {
        $.post('grid/historial_parametros_ad.php',{
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
