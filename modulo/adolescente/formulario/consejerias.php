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
                                <div class="row">
                                    <div class="col l1 m1 s1">
                                        #
                                    </div>
                                    <div class="col l7 m7 s7">
                                        TIPO DE
                                    </div>
                                    <div class="col l4 m4 s4">
                                        ESPACIO AMIGABLE
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col l1 m1 s1">
                                        #
                                    </div>
                                    <div class="col l7 m7 s7">
                                        CONSERJERIA
                                    </div>
                                    <div class="col l2 m2 s2">
                                        SI
                                    </div>
                                    <div class="col l2 m2 s2">
                                        NO
                                    </div>
                                </div>
                                <hr class="row" />
                                <?php
                                $sql1 = "select * from tipo_consejerias_adolescente order by nombre_consejeria";
                                $res1 = mysql_query($sql1);
                                while($row1 = mysql_fetch_array($res1)){
                                    ?>
                                    <div class="row">
                                        <div class="col l1 m1 s1">
                                            <input type="checkbox"
                                                   style="position: relative;visibility: visible;left: 0px;"
                                                <?php echo $paciente->getConsejeriaAD($row1['id_tipo_consejeria'])=='SI'? 'checked="checked"':''; ?>
                                                   onclick="updateIndicadorAD_consejeria('consejeria','<?php echo $row1['id_tipo_consejeria']; ?>','NO'),loadHistorialConsejeriaAD('<?php echo $rut; ?>','consejeria');"
                                                   name="conserjeria[<?php echo $row1['id_tipo_consejeria']; ?>]" value="<?php echo $row1['id_tipo_consejeria']; ?>" />
                                        </div>
                                        <div class="col l7 m7 s7"><?php echo $row1['nombre_consejeria']; ?></div>
                                        <div class="col l2 m2 s2">
                                            <input type="radio"
                                                <?php echo $paciente->getConsejeriaAD($row1['id_tipo_consejeria'])=='SI'? '':'disabled="disabled"'; ?>
                                                   style="position: relative;visibility: visible;left: 0px;"
                                                <?php echo $paciente->getConsejeriaAD_amigable($row1['id_tipo_consejeria'])=='SI'?'checked="checked"':''; ?>
                                                   onclick="updateIndicadorAD_consejeria_amigable('consejeria','<?php echo $row1['id_tipo_consejeria']; ?>','SI'),loadHistorialConsejeriaAD('<?php echo $rut; ?>','consejeria');"
                                                   name="amigable[<?php echo $row1['id_tipo_consejeria']; ?>]" value="SI" />
                                        </div>
                                        <div class="col l2 m2 s2">
                                            <input type="radio"
                                                   style="position: relative;visibility: visible;left: 0px;"
                                                <?php echo $paciente->getConsejeriaAD($row1['id_tipo_consejeria'])=='SI'? '':'disabled="disabled"'; ?>
                                                <?php echo $paciente->getConsejeriaAD_amigable($row1['id_tipo_consejeria'])=='NO'?'checked="checked"':''; ?>
                                                   onclick="updateIndicadorAD_consejeria_amigable('consejeria','<?php echo $row1['id_tipo_consejeria']; ?>','NO'),loadHistorialConsejeriaAD('<?php echo $rut; ?>','consejeria');"
                                                   name="amigable[<?php echo $row1['id_tipo_consejeria']; ?>]" value="NO" />
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
            <div class="container" id="infoHistotialConsejeria"></div>
        </div>

    </div>
</form>
<script type="text/javascript">
    $(function () {
        $("#ed_<?php echo $educacion; ?>").attr('checked','cheched');
        //DM
        loadHistorialConsejeriaAD('<?php echo $rut; ?>','consejeria');
        $('.tooltipped').tooltip({delay: 50});
    });
    function loadHistorialConsejeriaAD(rut,indicador) {
        $.post('grid/historial_consejeria_ad.php',{
            rut:rut,
            indicador:indicador
        },function(data){
            if(data !== 'ERROR_SQL'){
                $("#infoHistotialConsejeria").html(data);
            }
        });
    }

</script>
