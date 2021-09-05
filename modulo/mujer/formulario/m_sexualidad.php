<?php

include "../../../php/config.php";
include "../../../php/objetos/persona.php";

$rut = $_POST['rut'];
$fecha_registro = $_POST['fecha_registro'];

$paciente = new persona($rut);


$imc =  $paciente->getParametro_M('imc');

$lubricante  = $paciente->getParametroTabla_M('practica_sexual_mujer','lubricante');
$regulacion_mas_preservativo  = $paciente->getParametroTabla_M('practica_sexual_mujer','regulacion_mas_preservativo');
$condon_femenino  = $paciente->getParametroTabla_M('practica_sexual_mujer','condon_femenino');
$preservativo_masculino  = $paciente->getParametroTabla_M('practica_sexual_mujer','preservativo_masculino');




?>

<form class="content card-panel">
    <input type="hidden" name="fecha_antecedentes" id="fecha_antecedentes" value="<?php echo $fecha_registro; ?>" />
    <div class="row">
        <div class="col l6 m12 s12">
            <!-- HORMONAL -->
            <div class="row">
                <div class="col l12 m12 s12">
                    <div class="card-panel green lighten-2">
                        <div class="row">
                            <div class="col l8 m8 s8">
                                <strong style="line-height: 2em;font-size: 1.5em;">REGULACION DE FERTILIDAD <strong class="tooltipped" style="cursor: help" data-position="bottom" data-delay="50" data-tooltip="EL REGISTRO SERÁ GUARDADO AUTOMATICAMENTE">(?)</strong></strong>
                                <br /><strong onclick="$('.H_SUSPENDIDA').show();$('.H_VENCIDA').show()">VER HISTORIAL</strong>
                            </div>
                            <div class="col l4 m4 s4">
                                <div class="btn blue" onclick="boxNewHormonal()"> + AGREGAR </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col l2 s1 m2">DESDE</div>
                            <div class="col l4 s4 m8">TIPO</div>
                            <div class="col l2 s1 m2">VENCIMIENTO</div>
                            <div class="col l4 s1 m4">RETIRO</div>
                        </div>
                        <hr class="row" />
                        <?php
                        $sql1 = "select * from mujer_historial_hormonal where rut='$rut' order by id_historial desc";
                        $res1 = mysql_query($sql1);
                        while($row1 = mysql_fetch_array($res1)){
                            ?>
                            <div class="row tooltipped rowInfoSis <?php echo 'H_'.$row1['estado_hormona']; ?>"
                                 data-position="bottom" data-delay="50" data-tooltip='Estado: <?php echo $row1['estado_hormona']; ?> | Obs: <?php echo $row1['observacion']; ?>' >
                                <div class="col l2 s4 m2"><?PHP echo fechaNormal($row1['fecha_registro']); ?></div>
                                <div class="col l4 s4 m8"><?PHP echo $row1['tipo']; ?></div>
                                <div class="col l2 s4 m2"><?PHP echo fechaNormal($row1['vencimiento']); ?></div>
                                <div class="col l4 s4 m4">
                                    <?PHP
                                    IF($row1['fecha_registro']==date('Y-m-d')){
                                        ?>
                                        <a href="#" onclick="deleteHormonaSQL('<?php echo $row1['id_historial']; ?> ')">ELIMINAR</a>
                                    <?php
                                    }else{
                                        if($row1['estado_hormona']=='ACTIVA'){
                                            ?>
                                            <a href="#" onclick="boxRetiroHormonalAnticipado('<?php echo $row1['id_historial']; ?> ')">RETIRO ANTICIPADO</a>
                                            <?php
                                        }else{
                                            if($row1['estado_hormona']=='SUSPENDIDA'){
                                                echo fechaNormal($row1['fecha_retiro_hormonal']);
                                            }else{
                                                echo $row1['vencimiento'];
                                            }
                                        }
                                    }

                                    ?>
                                </div>
                            </div>
                            <?php
                        }
                        ?>
                        <hr class="row" />
                        <div class="row H_VENCIDA right-align">
                            <strong onclick="$('.H_SUSPENDIDA').hide();$('.H_VENCIDA').hide()">OCULTAR HISTORIAL</strong>
                        </div>
                        <style type="text/css">
                            .H_ACTIVA{
                                display: block;
                                cursor: pointer;
                            }
                            .H_SUSPENDIDA{
                                display: none;
                                background-color: #ff5f69;
                                cursor: pointer;
                            }
                            .H_VENCIDA{
                                display: none;
                                background-color: yellow;
                                cursor: pointer;
                            }
                        </style>
                    </div>
                </div>
            </div>
        </div>
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
                                        <input type="checkbox" id="regulacion_fertilidad"
                                               onchange="updateIndicadorM_sexual('regulacion_fertilidad')"
                                            <?php echo $regulacion_mas_preservativo=='SI'?'checked="checked"':'' ?>
                                               name="regulacion_fertilidad"  />
                                        <label class="white-text" for="regulacion_fertilidad">REGULACIÓN FERTILIDAD MAS PRESERVATIVO</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col l12 m12 s12">
                                <div class="row">
                                    <div class="col l12 m12 s12">
                                        <input type="checkbox" id="lubricante"
                                               onchange="updateIndicadorM_sexual('lubricante')"
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
                                               onchange="updateIndicadorM_sexual('condon_femenino')"
                                            <?php echo $condon_femenino=='SI'?'checked="checked"':'' ?>
                                               name="condon_femenino"  />
                                        <label class="white-text" for="condon_femenino">CONDÓN FEMENINO</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col l12 m12 s12">
                                <div class="row">
                                    <div class="col l12 m12 s12">
                                        <input type="checkbox" id="preservativo_masculino"
                                               onchange="updateIndicadorM_sexual('preservativo_masculino')"
                                            <?php echo $preservativo_masculino=='SI'?'checked="checked"':'' ?>
                                               name="preservativo_masculino"  />
                                        <label class="white-text" for="preservativo_masculino">PRESERVATIVO MASCULINO</label>
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
    function deleteHormonaSQL(historial){
        if(confirm("Desea Eliminar este registro de Hoy")){
            $.post('db/delete/hormona.php',{
                id_historial:historial,
                rut:'<?php echo $rut ?>'
            },function(data){
                if(data !== 'ERROR_SQL'){
                    load_m_sexualidad('<?php echo $rut; ?>');
                }
            });
        }
    }
    function boxNewHormonal(){
        $.post('formulario/new_hormonal.php',{
            rut:'<?php echo $rut ?>',
            fecha_registro:'<?php echo $fecha_registro ?>',
        },function(data){
            if(data !== 'ERROR_SQL'){
                $("#modal").html(data);
                $("#modal").css({'width':'800px'});
                document.getElementById("btn-modal").click();
            }
        });
    }

    function boxRetiroHormonalAnticipado(id_historial){
        $.post('formulario/retiro_hormonal_anticipado.php',{
            rut:'<?php echo $rut ?>',
            id_historial:id_historial
        },function(data){
            if(data !== 'ERROR_SQL'){
                $("#modal").html(data);
                $("#modal").css({'width':'800px'});
                document.getElementById("btn-modal").click();
            }
        });
    }

    function updateIndicadorM_sexual(indicador){
        var value = '';
        if($('#'+indicador).prop('checked')){
            value = 'SI';
        }else{
            value = 'NO';
        }
        var fecha = $("#fecha_antecedentes").val();
        $.post('db/update/m_indicador_sexualidad.php',{
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
