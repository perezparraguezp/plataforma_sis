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
            <div class="row">
                <div class="col l12 m12 s12">
                    <div class="card-panel green lighten-2">
                        <div class="row">
                            <div class="col l3 m6 s12">
                                <strong style="line-height: 2em;font-size: 1.5em;">RIESGO CV <strong class="tooltipped" style="cursor: help" data-position="bottom" data-delay="50" data-tooltip="EL REGISTRO SERÁ GUARDADO AUTOMATICAMENTE">(?)</strong></strong>
                            </div>
                            <div class="col l7 m6 s12">
                                <div class="row">
                                    <div class="col l12 m12 s12">
                                        <input type="radio" id="riesgo_cv_BAJO"
                                               class="item_riesgo_cv"
                                               onclick="updateRiesgoCV('BAJO')"
                                            <?php echo $riesgo_cv=='BAJO'?'checked="checked"':'' ?>
                                               name="riesgo_cv" value="BAJO"  />
                                        <label class="white-text" for="riesgo_cv_BAJO">BAJO</label>
                                    </div>
                                    <div class="col l12 m12 s12">
                                        <input type="radio" id="riesgo_cv_MODERADO"
                                               class="item_riesgo_cv"
                                               onclick="updateRiesgoCV('MODERADO')"
                                            <?php echo $riesgo_cv=='MODERADO'?'checked="checked"':'' ?>
                                               name="riesgo_cv" value="MODERADO"  />
                                        <label class="white-text" for="riesgo_cv_MODERADO">MODERADO</label>
                                    </div>
                                    <div class="col l12 m12 s12">
                                        <input type="radio" id="riesgo_cv_ALTO"
                                               class="item_riesgo_cv"
                                               onclick="updateRiesgoCV('ALTO')"
                                            <?php echo $riesgo_cv=='ALTO'?'checked="checked"':'' ?>
                                               name="riesgo_cv" value="ALTO"  />
                                        <label class="white-text" for="riesgo_cv_ALTO">ALTO</label>
                                    </div>
                                </div>
                            </div>

                            <div class="col l2">
                                <i class="mdi-editor-insert-chart"
                                   onclick="loadHistorialPSCV('<?php echo $rut ?>','riesgo_cv')"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
<!--    patologia        -->
            <div class="row">
                <div class="col l12 m12 s12">
                    <div class="card-panel green lighten-2">
                        <div class="row">
                            <div class="col l3 m6 s12">
                                <strong style="line-height: 2em;font-size: 1.5em;">PATOLOGÍA <strong class="tooltipped" style="cursor: help" data-position="bottom" data-delay="50" data-tooltip="EL REGISTRO SERÁ GUARDADO AUTOMATICAMENTE">(?)</strong></strong>
                            </div>
                            <div class="col l9 m6 s12">
                                <div class="row">
                                    <div class="col l6 m12 s12">
                                        <input type="checkbox" id="patologia_hta"
                                               onchange="updateIndicadorPSCV('patologia_hta')"
                                            <?php echo $patologia_hta=='SI'?'checked="checked"':'' ?>
                                               name="patologia_hta"  />
                                        <label class="white-text" for="patologia_hta">HTA</label>
                                    </div>
                                    <div class="col l6 m12 s12">
                                        <input type="checkbox" id="patologia_hta_sigges"
                                               onchange="updateIndicadorPSCV('patologia_hta_sigges')"
                                            <?php echo $patologia_hta_sigges=='SI'?'checked="checked"':'' ?>
                                               name="patologia_hta_sigges"  />
                                        <label class="white-text" for="patologia_hta_sigges">SIGGES</label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col l6 m12 s12">
                                        <input type="checkbox" id="patologia_dm"
                                               onchange="updateIndicadorPSCV('patologia_dm')"
                                            <?php echo $patologia_dm=='SI'?'checked="checked"':'' ?>
                                               name="patologia_dm"  />
                                        <label class="white-text" for="patologia_dm">DM</label>
                                    </div>
                                    <div class="col l6 m12 s12">
                                        <input type="checkbox" id="patologia_dm_sigges"
                                               onchange="updateIndicadorPSCV('patologia_dm_sigges')"
                                            <?php echo $patologia_dm_sigges=='SI'?'checked="checked"':'' ?>
                                               name="patologia_dm_sigges"  />
                                        <label class="white-text" for="patologia_dm_sigges">SIGGES</label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col l6 m12 s12">
                                        <input type="checkbox" id="patologia_dlp"
                                               onchange="updateIndicadorPSCV('patologia_dlp')"
                                            <?php echo $patologia_dlp=='SI'?'checked="checked"':'' ?>
                                               name="patologia_dlp"  />
                                        <label class="white-text" for="patologia_dlp">DLP</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
<!--            FACTOR DE RIESGO-->
            <div class="row">
                <div class="col l12 m12 s12">
                    <div class="card-panel green lighten-2">
                        <div class="row">
                            <div class="col l3 m6 s12">
                                <strong style="line-height: 2em;font-size: 1.5em;">FACTOR RIESGO <strong class="tooltipped" style="cursor: help" data-position="bottom" data-delay="50" data-tooltip="EL REGISTRO SERÁ GUARDADO AUTOMATICAMENTE">(?)</strong></strong>
                            </div>
                            <div class="col l9 m6 s12">
                                <div class="row">
                                    <div class="col l12 m12 s12">
                                        <input type="checkbox" id="factor_riesgo_tabaquismo"
                                               onchange="updateIndicadorPSCV('factor_riesgo_tabaquismo')"
                                            <?php echo $factor_riesgo_tabaquismo=='SI'?'checked="checked"':'' ?>
                                               name="factor_riesgo_tabaquismo"  />
                                        <label class="white-text" for="factor_riesgo_tabaquismo">TABAQUISMO</label>
                                    </div>
                                    <div class="col l12 m12 s12">
                                        <input type="checkbox" id="factor_riesgo_iam"
                                               onchange="updateIndicadorPSCV('factor_riesgo_iam')"
                                            <?php echo $factor_riesgo_iam=='SI'?'checked="checked"':'' ?>
                                               name="factor_riesgo_iam"  />
                                        <label class="white-text" for="factor_riesgo_iam">IAM</label>
                                    </div>
                                    <div class="col l12 m12 s12">
                                        <input type="checkbox" id="factor_riesgo_enf_cv"
                                               onchange="updateIndicadorPSCV('factor_riesgo_enf_cv')"
                                            <?php echo $factor_riesgo_enf_cv=='SI'?'checked="checked"':'' ?>
                                               name="factor_riesgo_enf_cv"  />
                                        <label class="white-text" for="factor_riesgo_enf_cv">ENF. CEREBRO VASCULAR</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
<!--            TRATAMIENTO-->


        </div>
        <div class="col l6 m12 s12">
            <div class="row">
                <div class="col l12 m12 s12">
                    <div class="card-panel green lighten-2">
                        <div class="row">
                            <div class="col l3 m6 s12">
                                <strong style="line-height: 2em;font-size: 1.5em;">TRATAMIENTO <strong class="tooltipped" style="cursor: help" data-position="bottom" data-delay="50" data-tooltip="EL REGISTRO SERÁ GUARDADO AUTOMATICAMENTE">(?)</strong></strong>
                            </div>
                            <div class="col l9 m6 s12">
                                <div class="row">
                                    <div class="col l6 m12 s12">
                                        <input type="checkbox" id="tratamiento_aas"
                                               onchange="updateIndicadorPSCV('tratamiento_aas')"
                                            <?php echo $tratamiento_aas=='SI'?'checked="checked"':'' ?>
                                               name="tratamiento_aas"  />
                                        <label class="white-text" for="tratamiento_aas">AAS</label>
                                    </div>
                                    <div class="col l6 m12 s12">
                                        <input type="checkbox" id="tratamiento_estatina"
                                               onchange="updateIndicadorPSCV('tratamiento_estatina')"
                                            <?php echo $tratamiento_estatina=='SI'?'checked="checked"':'' ?>
                                               name="tratamiento_estatina"  />
                                        <label class="white-text" for="tratamiento_estatina">ESTATINA</label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col l6 m12 s12">
                                        <input type="checkbox" id="tratamiento_ieeca"
                                               onchange="updateIndicadorPSCV('tratamiento_ieeca')"
                                            <?php echo $tratamiento_ieeca=='SI'?'checked="checked"':'' ?>
                                               name="tratamiento_ieeca"  />
                                        <label class="white-text" for="tratamiento_ieeca">IEECA</label>
                                    </div>
                                    <div class="col l6 m12 s12">
                                        <input type="checkbox" id="tratamiento_araii"
                                               onchange="updateIndicadorPSCV('tratamiento_araii')"
                                            <?php echo $tratamiento_araii=='SI'?'checked="checked"':'' ?>
                                               name="tratamiento_araii"  />
                                        <label class="white-text" for="tratamiento_araii">ARA II</label>
                                    </div>
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
                                <strong style="line-height: 2em;font-size: 1.5em;">POSTRADO <strong class="tooltipped" style="cursor: help" data-position="bottom" data-delay="50" data-tooltip="EL REGISTRO SERÁ GUARDADO AUTOMATICAMENTE">(?)</strong></strong>
                            </div>
                            <div class="col l9 m6 s12">
                                <div class="row">
                                    <div class="col l4 m12 s12">
                                        <input type="radio" id="postrado_si"
                                               class="postrado"
                                            <?php echo $postrado=='SI'?'checked="checked"':'' ?>
                                               name="postrado" value="SI"  />
                                        <label class="white-text" for="postrado_si">SI</label>
                                    </div>
                                    <div class="col l4 m12 s12">
                                        <input type="radio" id="postrado_no"
                                               class="postrado"
                                            <?php echo $postrado=='NO'?'checked="checked"':'' ?>
                                               name="postrado" value="NO"  />
                                        <label class="white-text" for="postrado_no">NO</label>
                                    </div>
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
                                <strong style="line-height: 2em;font-size: 1.5em;">HEMODIALISIS <strong class="tooltipped" style="cursor: help" data-position="bottom" data-delay="50" data-tooltip="EL REGISTRO SERÁ GUARDADO AUTOMATICAMENTE">(?)</strong></strong>
                            </div>
                            <div class="col l9 m6 s12">
                                <div class="row">
                                    <div class="col l4 m12 s12">
                                        <input type="radio" id="hemodialisis_si"
                                               class="hemodialisis"
                                            <?php echo $hemodialisis=='SI'?'checked="checked"':'' ?>
                                               name="hemodialisis" value="SI"  />
                                        <label class="white-text" for="hemodialisis_si">SI</label>
                                    </div>
                                    <div class="col l4 m12 s12">
                                        <input type="radio" id="hemodialisis_no"
                                               class="hemodialisis"
                                            <?php echo $hemodialisis=='NO'?'checked="checked"':'' ?>
                                               name="hemodialisis" value="NO"  />
                                        <label class="white-text" for="hemodialisis_no">NO</label>
                                    </div>
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
    function updateRiesgoCV(estado){
        var value =estado;
        var fecha = $("#fecha_antecedentes").val();
        $.post('db/update/pscv_paciente.php',{
            column:'riesgo_cv',
            value:value,
            rut:'<?php echo $rut ?>',
            fecha_registro:fecha
        },function (data) {
            alertaLateral(data);
        });
    }
    function loadHistorialPSCV(rut,indicador) {
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
    function updateIndicadorPSCV(indicador) {
        var value = '';
        if($('#'+indicador).prop('checked')){
            value = 'SI';
        }else{
            value = 'NO';
        }
        var fecha = $("#fecha_antecedentes").val();
        $.post('db/update/pscv_paciente.php',{
            column:indicador,
            value:value,
            fecha_registro:fecha,
            rut:'<?php echo $rut ?>'
        },function (data) {
            alertaLateral(data);
        });
    }
</script>
