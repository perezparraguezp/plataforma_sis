<?php

include "../../../php/config.php";
include "../../../php/objetos/persona.php";

$rut = $_POST['rut'];
$fecha_registro = $_POST['fecha_registro'];

$paciente = new persona($rut);


$imc =  $paciente->getParametro_M('imc');

$lubricante  = $paciente->getParametro_M('lubricante');



?>

<form class="content card-panel">
    <input type="hidden" name="fecha_antecedentes" id="fecha_antecedentes" value="<?php echo $fecha_registro; ?>" />
    <div class="row">
        <div class="col l6 m12 s12">
            <!-- PRACTICA SEXUAL SEGURA  -->
            <div class="row">
                <div class="col l12 m12 s12">
                    <div class="card-panel green lighten-2">
                        <div class="row">
                            <div class="col l12 m12 s12">
                                <strong style="line-height: 2em;font-size: 1.5em;">PRÁCTICA SEXUAL SEGURA <strong class="tooltipped" style="cursor: help" data-position="bottom" data-delay="50" data-tooltip="EL REGISTRO SERÁ GUARDADO AUTOMATICAMENTE">(?)</strong></strong>
                            </div>
                            <div class="col l12 m12 s12">
                                <div class="row">
                                    <div class="col l12 m12 s12">
                                        <input type="checkbox" id="lubricante"
                                               onchange="updateIndicadorM('lubricante')"
                                            <?php echo $lubricante=='SI'?'checked="checked"':'' ?>
                                               name="lubricante"  />
                                        <label class="white-text" for="lubricante">LUBRICANTE</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col l12 m12 s12">
                                <div class="row">
                                    <div class="col l12 m12 s12">
                                        <input type="checkbox" id="condon_femenino"
                                               onchange="updateIndicadorM('condon_femenino')"
                                            <?php echo $lubricante=='SI'?'checked="checked"':'' ?>
                                               name="condon_femenino"  />
                                        <label class="white-text" for="condon_femenino">CONDÓN FEMENINO</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col l12 m12 s12">
                                <div class="row">
                                    <div class="col l12 m12 s12">
                                        <input type="checkbox" id="preservativo_masculino"
                                               onchange="updateIndicadorM('preservativo_masculino')"
                                            <?php echo $lubricante=='SI'?'checked="checked"':'' ?>
                                               name="preservativo_masculino"  />
                                        <label class="white-text" for="preservativo_masculino">PRESERVATIVO MASCULINO</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- HORMONAL -->
            <div class="row">
                <div class="col l12 m12 s12">
                    <div class="card-panel green lighten-2">
                        <div class="row">
                            <div class="col l8 m8 s8">
                                <strong style="line-height: 2em;font-size: 1.5em;">HISTORIAL HORMONAL <strong class="tooltipped"
                                                                                                              style="cursor: help" data-position="bottom" data-delay="50" data-tooltip="EL REGISTRO SERÁ GUARDADO AUTOMATICAMENTE">(?)</strong></strong>
                            </div>
                            <div class="col l4 m4 s4">
                                <div class="btn blue" onclick="boxNewHormonal()"> + AGREGAR </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col l4 s4 m2">FECHA REGISTRO</div>
                            <div class="col l4 s4 m8">TIPO</div>
                            <div class="col l4 s4 m2">VENCIMIENTO</div>
                        </div>
                        <?php
                        $sql1 = "select * from mujer_historial_hormonal where rut='$rut' order by id_historial desc";
                        $res1 = mysql_query($sql1);
                        while($row1 = mysql_fetch_array($res1)){
                            ?>
                            <div class="row tooltipped rowInfoSis"
                                 data-position="bottom" data-delay="50" data-tooltip="Estado: <?php echo $row1['estado_hormona']; ?>" >
                                <div class="col l4 s4 m2"><?PHP echo fechaNormal($row1['fecha_registro']); ?></div>
                                <div class="col l4 s4 m8"><?PHP echo $row1['tipo']; ?></div>
                                <div class="col l4 s4 m2"><?PHP echo fechaNormal($row1['vencimiento']); ?></div>
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="col l6 m12 s12">

        </div>
    </div>
</form>
<style type="text/css">
    .hoverClass:hover{
        background-color: #f1ffc5;
    }
    .rowInfoSis:hover{
        background-color: #f1ffc5;
        cursor: help;
    }
</style>


<script type="text/javascript">
    $(function () {
        $('.tooltipped').tooltip({delay: 50});
        $("#imc_<?php echo strtolower($imc); ?>").attr('checked','cheched');

    });
    function boxNewHormonal(){
        $.post('formulario/new_hormonal.php',{
            rut:'<?php echo $rut ?>'
        },function(data){
            if(data !== 'ERROR_SQL'){
                $("#modal").html(data);
                $("#modal").css({'width':'800px'});
                document.getElementById("btn-modal").click();
            }
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
