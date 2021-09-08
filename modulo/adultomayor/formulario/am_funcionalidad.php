<?php

include "../../../php/config.php";
include "../../../php/objetos/persona.php";

$rut = $_POST['rut'];
$fecha_registro = $_POST['fecha_registro'];
$paciente = new persona($rut);
//$funcionalidad =  str_replace(" ","_",$paciente->getParametro_AM('funcionalidad'));


?>

<form class="content card-panel">
    <input type="hidden" name="fecha_funcionalidad" id="fecha_funcionalidad" value="<?php echo $fecha_registro; ?>" />
    <div class="row">
        <div class="col l4 m12 s12">
            <!-- IMC  -->
            <div class="row">
                <div class="col l12 m12 s12">
                    <div class="card-panel green lighten-2" style="font-size: 1.3em;">
                        <div class="row">
                            <div class="col l12 m12 s12">
                                <div class="row IMC">
                                    <div class="col l10 m10 s10">AUTOVALENTE SIN RIESGO</div>
                                    <div class="col l2 m2 s2">
                                        <input type="radio"
                                               style="position: relative;visibility: visible;left: 0px;"
                                               onclick="updateIndicadorAM_variable('funcionalidad','AUTOVALENTE SIN RIESGO'),loadHistorialParametroAM_funcionalidad('<?php echo $rut; ?>','funcionalidad');"
                                               id="af_<?php echo $funcionalidad; ?>" name="af" value="AUTOVALENTE SIN RIESGO" />
                                    </div>
                                </div>
                                <div class="row IMC">
                                    <div class="col l10 m10 s10">AUTOVALENTE CON RIESGO</div>
                                    <div class="col l2 m2 s2">
                                        <input type="radio"
                                               style="position: relative;visibility: visible;left: 0px;"
                                               onclick="updateIndicadorAM_variable('funcionalidad','AUTOVALENTE CON RIESGO'),loadHistorialParametroAM_funcionalidad('<?php echo $rut; ?>','funcionalidad');"
                                               id="af_<?php echo $funcionalidad; ?>" name="af" value="AUTOVALENTE CON RIESGO" />
                                    </div>
                                </div>
                                <div class="row IMC">
                                    <div class="col l10 m10 s10">RIESGO DEPENDENCIA</div>
                                    <div class="col l2 m2 s2">
                                        <input type="radio"
                                               style="position: relative;visibility: visible;left: 0px;"
                                               onclick="updateIndicadorAM_variable('funcionalidad','RIESGO DEPENDENCIA'),loadHistorialParametroAM_funcionalidad('<?php echo $rut; ?>','funcionalidad');"
                                               id="af_<?php echo $funcionalidad; ?>" name="af" value="RIESGO DEPENDENCIA" />
                                    </div>
                                </div>
                                <p class="row"></p>
                                <p class="row"></p>
                                <div class="row"
                                     style="background-color: #ff5f69;color: white;
                                     font-size: 1.5em;">
                                   INDICE BARTHEL
                                </div>
                                <p class="row"></p>
                                <p class="row"></p>
                                <div class="row IMC">
                                    <div class="col l10 m10 s10">DEPENDENCIA LEVE</div>
                                    <div class="col l2 m2 s2">
                                        <input type="radio"
                                               style="position: relative;visibility: visible;left: 0px;"
                                               onclick="updateIndicadorAM_variable('funcionalidad','DEPENDENCIA LEVE'),loadHistorialParametroAM_funcionalidad('<?php echo $rut; ?>','funcionalidad');"
                                               id="af_<?php echo $funcionalidad; ?>" name="af" value="DEPENDENCIA LEVE" />
                                    </div>
                                </div>
                                <div class="row IMC">
                                    <div class="col l10 m10 s10">DEPENDENCIA MODERADO</div>
                                    <div class="col l2 m2 s2">
                                        <input type="radio"
                                               style="position: relative;visibility: visible;left: 0px;"
                                               onclick="updateIndicadorAM_variable('funcionalidad','DEPENDENCIA MODERADO'),loadHistorialParametroAM_funcionalidad('<?php echo $rut; ?>','funcionalidad');"
                                               id="af_<?php echo $funcionalidad; ?>" name="af" value="DEPENDENCIA MODERADO" />
                                    </div>
                                </div>
                                <div class="row IMC">
                                    <div class="col l10 m10 s10">DEPENDENCIA GRAVE</div>
                                    <div class="col l2 m2 s2">
                                        <input type="radio"
                                               style="position: relative;visibility: visible;left: 0px;"
                                               onclick="updateIndicadorAM_variable('funcionalidad','DEPENDENCIA GRAVE'),loadHistorialParametroAM_funcionalidad('<?php echo $rut; ?>','funcionalidad');"
                                               id="af_<?php echo $funcionalidad; ?>" name="af" value="DEPENDENCIA GRAVE" />
                                    </div>
                                </div>
                                <div class="row IMC">
                                    <div class="col l10 m10 s10">DEPENDENCIA TOTAL</div>
                                    <div class="col l2 m2 s2">
                                        <input type="radio"
                                               style="position: relative;visibility: visible;left: 0px;"
                                               onclick="updateIndicadorAM_variable('funcionalidad','DEPENDENCIA TOTAL'),loadHistorialParametroAM_funcionalidad('<?php echo $rut; ?>','funcionalidad');"
                                               id="af_<?php echo $funcionalidad; ?>" name="af" value="DEPENDENCIA TOTAL" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col l2 m12 s12" style="padding: 30px;">
            <div class="row">
                <div class="col l12 m12 s12 center-align">
                    <img src="../../images/am/mas_adulto_mayor.jpg" width="90%" />
                </div>
            </div>
            <div class="row">
                <div class="col l12 m12 s12 center-align">
                    <div class="switch">
                        <label>
                            NO
                            <input type="checkbox" name="mas_adulto_mayor"
                                   onchange="updateIndicadorAM('mas_adulto_mayor',$('#mas_adulto_mayor').val()),load_am_funcionalidad('<?php echo $rut; ?>')"
                                   id="mas_adulto_mayor"
                                <?php echo $paciente->getParametro_AM('mas_adulto_mayor')=='SI'? 'checked':''; ?>
                                <?php echo $paciente->getParametro_AM('mas_adulto_mayor')=='SI'? 'value="NO"':'value="SI"'; ?>
                            />
                            <span class="lever"></span>
                            SI
                        </label>
                    </div>
                </div>
            </div>



        </div>
        <div class="col l6 m12 s12" style="background-color: #f1ffc5;padding: 10px;">
            <header style="padding-left: 10px;">HISTORIAL</header>
            <div class="container" id="infoHistotialFuncionalidad"></div>
        </div>

    </div>
</form>
<script type="text/javascript">
    $(function () {
        //DM
        loadHistorialParametroAM_funcionalidad('<?php echo $rut; ?>','funcionalidad');
        $('.tooltipped').tooltip({delay: 50});
        //$("#af_<?php //echo strtolower($funcionalidad); ?>//").attr('checked','cheched');
    });
    function loadHistorialParametroAM_funcionalidad(rut,indicador) {
        $.post('grid/historial_parametros_am.php',{
            rut:rut,
            indicador:indicador
        },function(data){
            if(data !== 'ERROR_SQL'){
                $("#infoHistotialFuncionalidad").html(data);
                //$("input:radio[value='<?php //echo $paciente->getParametro_AM('funcionalidad'); ?>//']").prop('checked',true);
            }
        });
    }



</script>
