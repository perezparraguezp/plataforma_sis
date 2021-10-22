<?php

include "../../../php/config.php";
include "../../../php/objetos/persona.php";
$rut = $_POST['rut'];
$fecha_registro = $_POST['fecha_registro'];

$paciente = new persona($rut);

$paciente->calcularEdadFecha($fecha_registro);

$hba1c          =  $paciente->getDiabetesPSCV('hba1c');
$fondo_ojo      =  $paciente->getDiabetesPSCV('fondo_ojo');
$podologia      =  $paciente->getDiabetesPSCV('podologia');
$insulina       =  $paciente->getDiabetesPSCV('insulina');
$nph            =  $paciente->getDiabetesPSCV('nph');
$rapida         =  $paciente->getDiabetesPSCV('rapida');
$urapida        =  $paciente->getDiabetesPSCV('urapida');
$ev_pie          =  $paciente->getDiabetesPSCV('ev_pie');
$ulceras       =  $paciente->getDiabetesPSCV('ulceras');
$amputacion     =  $paciente->getDiabetesPSCV('amputacion');
$opcion_hba1c = '';

if($paciente->anios<80){
    $opcion_hba1c .= '<option></option>';
    $opcion_hba1c .= '<option>< 7%</option>';
    $opcion_hba1c .= '<option>>= 7%</option>';
    $opcion_hba1c .= '<option>>= 9%</option>';
}else{
    $opcion_hba1c .= '<option></option>';
    $opcion_hba1c .= '<option>< 8%</option>';
    $opcion_hba1c .= '<option>>= 8%</option>';
    $opcion_hba1c .= '<option>>= 9%</option>';

}


?>
<style type="text/css">
    #form_diabetes_pscv select{
        height: 35px;
        font-size: 1em;
    }
</style>
<form class="content card-panel" id="form_diabetes_pscv">
    <div class="row">
        <div class="col l6 m12 s12">
            <div class="row">
                <div class="col l12 m12 s12">
                    <div class="card-panel green lighten-2">
                        <div class="row">
                            <div class="col l3 m6 s12">
                                <strong style="line-height: 2em;font-size: 1.5em;">HbA1c <strong class="tooltipped" style="cursor: help" data-position="bottom" data-delay="50" data-tooltip="EL REGISTRO SERÁ GUARDADO AUTOMATICAMENTE">(?)</strong></strong>
                            </div>
                            <div class="col l7 m6 s12">
                                <div class="row">
                                    <div class="col l6 m12 s12">
                                        <input type="date" name="fecha_hba1c" id="fecha_hba1c" />
                                    </div>
                                    <div class="col l6 m12 s12">
                                        <select class="browser-default"
                                                style="font-size: 1.5em"
                                                name="hba1c" id="hba1c"
                                                onchange="updateDiabetesPSCV('hba1c')">
<!--                                            <option>--><?php //echo $hba1c; ?><!--</option>-->
                                            <?php echo $opcion_hba1c; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col l2 center-align">
                                <i class="mdi-editor-insert-chart"
                                   onclick="loadHistorialDiabetesPSCV('<?php echo $rut ?>','hba1c')"></i>
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
                                <strong style="line-height: 1.5em;font-size: 1.5em;">FONDO DE OJOS <strong class="tooltipped" style="cursor: help" data-position="bottom" data-delay="50" data-tooltip="EL REGISTRO SERÁ GUARDADO AUTOMATICAMENTE">(?)</strong></strong>
                            </div>
                            <div class="col l7 m6 s12">
                                <div class="row">
                                    <div class="col l6 m12 s12">
                                        <input type="date" name="fecha_fondo_ojo" id="fecha_fondo_ojo" va />
                                    </div>
                                    <div class="col l6 m12 s12">
                                        <select class="browser-default"
                                                name="fondo_ojo" id="fondo_ojo"
                                                style="font-size: 1.5em"
                                                onchange="updateDiabetesPSCV('fondo_ojo')">
                                            <option></option>
<!--                                            <option>--><?php //echo $fondo_ojo; ?><!--</option>-->
                                            <option>SIN RETINOPATIA</option>
                                            <option>CON RETINOPATIA</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col l2 center-align">
                                <i class="mdi-editor-insert-chart"
                                   onclick="loadHistorialDiabetesPSCV('<?php echo $rut ?>','fondo_ojo')"></i>
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
                                <strong style="line-height: 2em;font-size: 1.5em;">PODOLOGÍA <strong class="tooltipped" style="cursor: help" data-position="bottom" data-delay="50" data-tooltip="EL REGISTRO SERÁ GUARDADO AUTOMATICAMENTE">(?)</strong></strong>
                            </div>
                            <div class="col l7 m6 s12">

                                <div class="row">
                                    <div class="col l12 m12 s12">
                                        <input type="date"
                                               name="podologia" id="podologia"
                                               onchange="updateDiabetesPSCV_Podologia('podologia')"
                                        />
                                    </div>
                                </div>
                            </div>
                            <div class="col l2 center-align">
                                <i class="mdi-editor-insert-chart"
                                   onclick="loadHistorialDiabetesPSCV('<?php echo $rut ?>','podologia')"></i>
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
                                <strong style="line-height: 2em;font-size: 1.5em;">INSULINA <strong class="tooltipped" style="cursor: help" data-position="bottom" data-delay="50" data-tooltip="EL REGISTRO SERÁ GUARDADO AUTOMATICAMENTE">(?)</strong></strong>
                            </div>
                            <div class="col l7 m6 s12">
                                <div class="row">
                                    <div class="col l12 m12 s12">
                                        <select class="browser-default"
                                                name="insulina" id="insulina"
                                                style="font-size: 1.5em"
                                                onchange="updateDiabetesPSCV_FechaRegistro('insulina')">
                                            <option><?php echo $insulina; ?></option>
                                            <option>SI</option>
                                            <option>NO</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col l2 center-align">
                                <i class="mdi-editor-insert-chart"
                                   onclick="loadHistorialDiabetesPSCV('<?php echo $rut ?>','insulina')"></i>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col l4">
                                <input type="checkbox" id="nph"
                                       onchange="updateDiabetesPSCV_FechaRegistro('nph')"
                                    <?php echo $nph=='SI'?'checked="checked"':'' ?>
                                       name="nph"  />
                                <label class="white-text" for="nph">NPH</label>
                            </div>
                            <div class="col l4">
                                <input type="checkbox" id="rapida"
                                       onchange="updateDiabetesPSCV_FechaRegistro('rapida')"
                                    <?php echo $rapida=='SI'?'checked="checked"':'' ?>
                                       name="rapida"  />
                                <label class="white-text" for="rapida">RÀPIDA</label>
                            </div>
                            <div class="col l4">
                                <input type="checkbox" id="urapida"
                                       onchange="updateDiabetesPSCV_FechaRegistro('rapida')"
                                    <?php echo $urapida=='SI'?'checked="checked"':'' ?>
                                       name="urapida"  />
                                <label class="white-text" for="urapida">U RÀPIDA</label>
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
                                <strong style="line-height: 2em;font-size: 1.5em;">EV PIE DIABÉTICO <strong class="tooltipped" style="cursor: help" data-position="bottom" data-delay="50" data-tooltip="EL REGISTRO SERÁ GUARDADO AUTOMATICAMENTE">(?)</strong></strong>
                            </div>
                            <div class="col l7 m6 s12">
                                <div class="row">
                                    <div class="col l12 m12 s12">
                                        <select class="browser-default"
                                                name="ev_pie" id="ev_pie"
                                                style="font-size: 1.5em;"
                                                onchange="updateDiabetesPSCV_FechaRegistro('ev_pie')">

<!--                                            <option>--><?php //echo $ev_pie; ?><!--</option>-->
                                            <option></option>
                                            <option>BAJO</option>
                                            <option>MODERADO</option>
                                            <option>ALTO</option>
                                            <option>MAXIMO</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col l2 center-align">
                                <i class="mdi-editor-insert-chart"
                                   onclick="loadHistorialDiabetesPSCV('<?php echo $rut ?>','ev_pie')"></i>
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
                                <strong style="line-height: 2em;font-size: 1.5em;">ULCERAS ACTIVAS <strong class="tooltipped" style="cursor: help" data-position="bottom" data-delay="50" data-tooltip="EL REGISTRO SERÁ GUARDADO AUTOMATICAMENTE">(?)</strong></strong>
                            </div>
                            <div class="col l7 m6 s12">
                                <div class="row">
                                    <div class="col l12 m12 s12">
                                        <select class="browser-default"
                                                name="ulceras" id="ulceras"
                                                onchange="updateDiabetesPSCV_FechaRegistro('ulceras')">
                                            <option><?php echo $ulceras; ?></option>
                                            <option>CURACIÓN CONVENCIONAL</option>
                                            <option>CURACIÓN AVANZADA</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col l2 center-align">
                                <i class="mdi-editor-insert-chart"
                                   onclick="loadHistorialDiabetesPSCV('<?php echo $rut ?>','ulceras')"></i>
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
                                <strong style="line-height: 2em;font-size: 1.5em;">AMPUTACION <strong class="tooltipped" style="cursor: help" data-position="bottom" data-delay="50" data-tooltip="EL REGISTRO SERÁ GUARDADO AUTOMATICAMENTE">(?)</strong></strong>
                            </div>
                            <div class="col l7 m6 s12">
                                <div class="row">
                                    <div class="col l12 m12 s12">
                                        <select class="browser-default"
                                                name="amputacion" id="amputacion"
                                                style="font-size: 1.5em"
                                                onchange="updateDiabetesPSCV_FechaRegistro('amputacion')">
                                            <option><?php echo $amputacion; ?></option>
                                            <option>SI</option>
                                            <option>NO</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col l2 center-align">
                                <i class="mdi-editor-insert-chart"
                                   onclick="loadHistorialDiabetesPSCV('<?php echo $rut ?>','amputacion')"></i>
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
    function loadHistorialDiabetesPSCV(rut,indicador) {
        $.post('grid/historial_diabetes_pscv.php',{
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
    function updateDiabetesPSCV(indicador) {
        var value = $("#"+indicador).val();
        var fecha = $("#fecha_"+indicador).val();
        if(fecha!==''){
            $.post('db/update/pscv_diabetes.php',{
                column:indicador,
                value:value,
                fecha_registro:fecha,
                rut:'<?php echo $rut ?>'
            },function (data) {
                alertaLateral(data);
            });
        }else{
            alertaLateral('DEBE INGRESAR UNA FECHA PARA EL INDICADOR '+indicador);
            $("#fecha_"+indicador).css({'background-color':'red'});
        }

    }
    function updateDiabetesPSCV_Podologia(indicador){
        var value = $("#"+indicador).val();
        var fecha = $("#fecha_"+indicador).val();
        $.post('db/update/pscv_diabetes.php',{
            column:indicador,
            value:value,
            fecha_registro:value,
            rut:'<?php echo $rut ?>'
        },function (data) {
            alertaLateral(data);
        });
    }
    function updateDiabetesPSCV_FechaRegistro(indicador){
        var value = $("#"+indicador).val();
        var fecha = '<?php echo $fecha_registro; ?>';
        $.post('db/update/pscv_diabetes.php',{
            column:indicador,
            value:value,
            fecha_registro:value,
            rut:'<?php echo $rut ?>'
        },function (data) {
            alertaLateral(data);
        });
    }

</script>
