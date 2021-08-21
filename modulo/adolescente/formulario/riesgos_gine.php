<?php

include "../../../php/config.php";
include "../../../php/objetos/persona.php";

$rut = $_POST['rut'];
$fecha_registro = $_POST['fecha_registro'];
$paciente = new persona($rut);

$educacion =  $paciente->getParametro_AD('educacion');


?>

<form class="content card-panel">
    <input type="hidden" name="fecha_funcionalidad" id="fecha_funcionalidad" value="<?php echo $fecha_registro; ?>" />
    <div class="row">
        <div class="col l6 m12 s12">
            <!-- IMC  -->
            <div class="row">
                <div class="col l12 m12 s12">
                    <div class="card-panel green lighten-2" style="font-size: 1em;">
                        <div class="row">
                            <div class="col l12 m12 s12">
                                <?php
                                $sql1 = "select * from tipo_riesgo_adolescente where tipo_area='GINE' order by nombre_riesgo";
                                $res1 = mysql_query($sql1);
                                while($row1 = mysql_fetch_array($res1)){
                                    ?>
                                    <div class="row">
                                        <div class="col l10 m10 s10"><?php echo $row1['nombre_riesgo']; ?></div>
                                        <div class="col l2 m2 s2">
                                            <input type="checkbox"
                                                   style="position: relative;visibility: visible;left: 0px;"
                                                <?php echo $paciente->getRiesgoAD($row1['id_tipo_riesgo'])=='SI'?'checked="checked"':''; ?>

                                                   onclick="updateIndicadorAD('riesgo','<?php echo $row1['id_tipo_riesgo']; ?>'),loadHistorialRiesgosAD('<?php echo $rut; ?>','educacion');"
                                                   name="riesgo[<?php echo $row1['id_tipo_riesgo']; ?>]" value="<?php echo $row1['id_tipo_riesgo']; ?>" />
                                        </div>
                                    </div>
                                    <?php
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col l1 m12 s12" style="padding: 30px;">
            -
        </div>
        <div class="col l5 m12 s12" style="background-color: #f1ffc5;padding: 10px;">
            <header style="padding-left: 10px;">HISTORIAL</header>
            <div class="container" id="infoHistotialRiesgos_gine"></div>
        </div>

    </div>
</form>
<script type="text/javascript">
    $(function () {
        $("#ed_<?php echo $educacion; ?>").attr('checked','cheched');
        //DM
        loadHistorialRiesgosAD('<?php echo $rut; ?>','educacion');
        $('.tooltipped').tooltip({delay: 50});
    });
    function loadHistorialRiesgosAD(rut,indicador) {
        $.post('grid/historial_riesgos_ad.php',{
            rut:rut,
            indicador:indicador,
            tipo_riesgo:'GINE'
        },function(data){
            if(data !== 'ERROR_SQL'){
                $("#infoHistotialRiesgos_gine").html(data);
            }
        });
    }

</script>
