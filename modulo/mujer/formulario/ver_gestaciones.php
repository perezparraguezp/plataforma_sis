<?php

include "../../../php/config.php";
include "../../../php/objetos/persona.php";
include "../../../php/objetos/profesional.php";

$rut = $_POST['rut'];
$fecha_registro = $_POST['fecha_registro'];
$tipo = $_POST['tipo'];
$paciente = new persona($rut);




?>
<script type="text/javascript">
    $(document).ready(function () {
        // Create jqxTabs.
        $('#tabs_gestaciones').jqxTabs({ width: '100%',theme: 'eh-open', position: 'top'});
        $('.tooltipped').tooltip({delay: 50});
    });
</script>
<div id='tabs_gestaciones'>
    <ul>
        <?php
        $sql = "select * from gestacion_mujer 
        where rut='$rut' 
        and estado_gestacion='$tipo'
        order by id_gestacion asc";
        $res = mysql_query($sql);
        $i = 1;
        while($row = mysql_fetch_array($res)){
            ?>
            <li>GESTACIÓN Nº <?PHP echo $i; ?></li>
            <?php
            $i++;
        }
        ?>

    </ul>
    <?php
    $sql = "select * from gestacion_mujer 
        where rut='$rut' 
        and estado_gestacion='$tipo'
        order by id_gestacion asc";
    $res = mysql_query($sql);
    $i = 1;
    while($row = mysql_fetch_array($res)){
        $id_gestacion = $row['id_gestacion'];

        ?>
        <div>
            <div class="container" style="padding: 10px;">
                <div class="row">
                    <div class="col l4">NUMERO DE BEBÉS</div>
                    <div class="col l8"><?php echo $row['numero_gestacion']; ?></div>
                </div>
                <div class="row">
                    <div class="col l4">
                        <i class="mdi-editor-insert-chart"
                           onclick="loadHistorialGestacion('<?php echo $rut ?>','riesgo_biopsicosocial','<?php echo $id_gestacion; ?>')"></i>
                        RIESGO BIOPSICOSOCIAL </div>
                    <div class="col l8"><?php echo $row['riesgo_biopsicosocial']; ?></div>
                </div>
                <fieldset>
                    <legend>OBSERVACIONES</legend>
                    <p><?php echo $row['obs_gestacion']; ?></p>
                </fieldset>
                <div class="row" style="font-size: 0.7em;">
                    <div class="col l3">INICIO GESTACIÓN</div>
                    <div class="col l3"><?php echo fechaNormal($row['fecha_inicio']); ?></div>
                    <div class="col l3">TERMINO GESTACIÓN</div>
                    <div class="col l3"><?php echo fechaNormal($row['fecha_termino']); ?></div>
                </div>
                <div class="row">
                    <div class="col l4">IMC 8º MES POST-PARTO</div>
                    <div class="col l8">
                        <select name="imc_post_parto_<?php echo $id_gestacion; ?>"
                                id="imc_post_parto_<?php echo $id_gestacion; ?>">
                            <option></option>
                            <option>OBESA</option>
                            <option>SOBREPESO</option>
                            <option>NORMAL</option>
                            <option>BAJO PESO</option>
                        </select>
                        <script type="text/javascript">
                            $(function(){
                                $('.tooltipped').tooltip({delay: 50});
                                $('#imc_post_parto_<?php echo $id_gestacion; ?>').jqxDropDownList({
                                    width: '100%',
                                    theme: 'eh-open',
                                    height: '25px'
                                });

                                $("#imc_post_parto_<?php echo $id_gestacion; ?>").on('change',function(){
                                    $.post('db/update/m_gestante.php',{
                                        id_gestacion:'<?php echo $id_gestacion; ?>',
                                        value:$('#imc_post_parto_<?php echo $id_gestacion; ?>').val(),
                                        column:'imc_post_parto',
                                        fecha_registro:'<?php echo $fecha_registro; ?>',
                                        rut:'<?php echo $rut ?>'
                                    },function (data) {
                                        alertaLateral(data);
                                    });
                                });
                            })
                        </script>
                    </div>
                </div>
                <hr class="row" />
                <div class="row" style="font-size: 0.9em;">
                    <div class="col l6">
                        <label class="col l12">HISTORIAL VDI</label>
                        <?php
                        $sql1 = "select * from visita_vdi 
                        where rut='$rut' 
                        and id_gestacion='$id_gestacion'
                        order by fecha_vdi desc";
                        $res1 = mysql_query($sql1);
                        $v = 1;
                        while($row1 = mysql_fetch_array($res1)){
                            echo '<p  style="cursor: help" data-position="bottom" data-delay="50" data-tooltip="'.$row1['obs_vdi'].'" 
                                    class="col l12 tooltipped" >Visita '.$v.' Fecha '.fechaNormal($row1['fecha_vdi']).'</p>';
                        }
                        ?>

                    </div>
                    <div class="col l6">
                        <label class="col l12">ECOGRAFIAS</label>
                        <?php
                        $sql1 = "select * from ecografias_mujer 
                        where rut='$rut' 
                        and id_gestacion='$id_gestacion'
                        order by fecha_eco desc";
                        $res1 = mysql_query($sql1);
                        $v = 1;
                        while($row1 = mysql_fetch_array($res1)){
                            echo '<p  style="cursor: help" data-position="bottom" data-delay="50" data-tooltip="'.fechaNormal($row1['fecha_eco']).'" 
                                    class="col l12 tooltipped" >'.$row1['trimestre'].' TRIMESTRE - '.$row1['tipo_eco'].'</p>';
                        }
                        ?>

                    </div>
                </div>
            </div>
        </div>
        <?php
        $i++;
    }
    ?>
</div>
<div class="modal-footer">
    <a href="#" id="close_modal" class="waves-effect waves-red btn-flat modal-action modal-close">CERRAR</a>
</div>