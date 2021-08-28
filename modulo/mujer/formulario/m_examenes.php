<?php

include "../../../php/config.php";
include "../../../php/objetos/persona.php";
include "../../../php/objetos/profesional.php";

$rut = $_POST['rut'];
$fecha_registro = $_POST['fecha_registro'];

$paciente = new persona($rut);

?>
<div class="container">
    <div class="row">
        <div class="col l6 m6 s12">
            <!--  PAP -->
            <div class="card-panel green lighten-2">
                <div class="row">
                    <div class="col l8 m8 s8">
                        <strong style="line-height: 2em;font-size: 1.5em;">PAP <strong class="tooltipped"
                                                                                       style="cursor: help" data-position="bottom" data-delay="50" data-tooltip="EL REGISTRO SERÁ GUARDADO AUTOMATICAMENTE">(?)</strong></strong>
                    </div>
                    <div class="col l4 m4 s4">
                        <div class="btn blue" onclick="boxNewPap()"> + AGREGAR </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col l2 s2 m2">FECHA</div>
                    <div class="col l3 s3 m3">ORIGEN</div>
                    <div class="col l5 s5 m5">RESULTADO</div>
                    <div class="col l2 s2 m2"></div>
                </div>
                <hr class="row" />
                <?php
                $sql1 = "select * from examen_mujer 
                            where rut='$rut'  
                            AND tipo_examen='PAP'
                            order by id_examen desc";
                $res1 = mysql_query($sql1);
                while($row1 = mysql_fetch_array($res1)){
                    ?>
                    <div class="row tooltipped rowInfoSis"
                         data-position="bottom" data-delay="50" data-tooltip="OBS: <?php echo $row1['obs_examen']; ?>" >
                        <div class="col l2 s4 m2"><?PHP echo fechaNormal($row1['fecha_examen']); ?></div>
                        <div class="col l3 s3 m3"><?PHP echo $row1['origen_examen']; ?></div>
                        <div class="col l5 s5 m5"><?PHP echo $row1['valor_examen']; ?></div>
                        <div class="col l2 s2 m2">
                            <?PHP
                            IF($row1['fecha_examen']==date('Y-m-d')){
                                ?>
                                <a href="#" onclick="delte_examen('PAP','<?php echo $row1['id_examen']; ?> ')">ELIMINAR</a>
                                <?php
                            }
                            ?>
                        </div>
                    </div>
                    <?php
                }
                ?>
            </div>
            <!-- VPH -->
            <div class="card-panel green lighten-2">
                <div class="row">
                    <div class="col l8 m8 s8">
                        <strong style="line-height: 2em;font-size: 1.5em;">VPH <strong class="tooltipped"
                                                                                       style="cursor: help" data-position="bottom" data-delay="50" data-tooltip="EL REGISTRO SERÁ GUARDADO AUTOMATICAMENTE">(?)</strong></strong>
                    </div>
                    <div class="col l4 m4 s4">
                        <div class="btn blue" onclick="boxNewVph()"> + AGREGAR </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col l2 s2 m2">FECHA</div>
                    <div class="col l3 s3 m3">ORIGEN</div>
                    <div class="col l5 s5 m5">RESULTADO</div>
                    <div class="col l2 s2 m2"></div>
                </div>
                <hr class="row" />
                <?php
                $sql1 = "select * from examen_mujer 
                            where rut='$rut'  
                            AND tipo_examen='VPH'
                            order by id_examen desc";
                $res1 = mysql_query($sql1);
                while($row1 = mysql_fetch_array($res1)){
                    ?>
                    <div class="row tooltipped rowInfoSis"
                         data-position="bottom" data-delay="50" data-tooltip="OBS: <?php echo $row1['obs_examen']; ?>" >
                        <div class="col l2 s4 m2"><?PHP echo fechaNormal($row1['fecha_examen']); ?></div>
                        <div class="col l3 s3 m3"><?PHP echo $row1['origen_examen']; ?></div>
                        <div class="col l5 s5 m5"><?PHP echo $row1['valor_examen']; ?></div>
                        <div class="col l2 s2 m2">
                            <?PHP
                            IF($row1['fecha_examen']==date('Y-m-d')){
                                ?>
                                <a href="#" onclick="delte_examen('VPH','<?php echo $row1['id_examen']; ?> ')">ELIMINAR</a>
                                <?php
                            }
                            ?>
                        </div>
                    </div>
                    <?php
                }
                ?>
            </div>
        </div>
        <div class="col l6 m6 s12">
            <!-- MAMOGRAFIAS -->
            <div class="card-panel green lighten-2">
                <div class="row">
                    <div class="col l8 m8 s8">
                        <strong style="line-height: 2em;font-size: 1.5em;">MAMOGRAFIAS <strong class="tooltipped"
                                                                                               style="cursor: help" data-position="bottom" data-delay="50" data-tooltip="EL REGISTRO SERÁ GUARDADO AUTOMATICAMENTE">(?)</strong></strong>
                    </div>
                    <div class="col l4 m4 s4">
                        <div class="btn blue" onclick="boxNewMamografia()"> + AGREGAR </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col l2 s2 m2">FECHA</div>
                    <div class="col l3 s3 m3">ORIGEN</div>
                    <div class="col l5 s5 m5"></div>
                    <div class="col l2 s2 m2"></div>
                </div>
                <hr class="row" />
                <?php
                $sql1 = "select * from examen_mujer 
                            where rut='$rut'  
                            AND tipo_examen='MAMOGRAFIA'
                            order by id_examen desc";
                $res1 = mysql_query($sql1);
                while($row1 = mysql_fetch_array($res1)){
                    ?>
                    <div class="row tooltipped rowInfoSis"
                         data-position="bottom" data-delay="50" data-tooltip="OBS: <?php echo $row1['obs_examen']; ?>" >
                        <div class="col l2 s4 m2"><?PHP echo fechaNormal($row1['fecha_examen']); ?></div>
                        <div class="col l3 s3 m3"><?PHP echo $row1['origen_examen']; ?></div>
                        <div class="col l5 s5 m5"></div>
                        <div class="col l2 s2 m2">
                            <?PHP
                            IF($row1['fecha_examen']==date('Y-m-d')){
                                ?>
                                <a href="#" onclick="delte_examen('MAMOGRAFIA','<?php echo $row1['id_examen']; ?> ')">ELIMINAR</a>
                                <?php
                            }
                            ?>
                        </div>
                    </div>
                    <?php
                }
                ?>
            </div>
            <!-- EXAMEN FISICO MAMAS -->
            <div class="card-panel green lighten-2">
                <div class="row">
                    <div class="col l8 m8 s8">
                        <strong style="line-height: 2em;font-size: 1.5em;">EXAMEN FISICO MAMAS <strong class="tooltipped"
                                                                                                       style="cursor: help" data-position="bottom" data-delay="50" data-tooltip="EL REGISTRO SERÁ GUARDADO AUTOMATICAMENTE">(?)</strong></strong>
                    </div>
                    <div class="col l4 m4 s4">
                        <div class="btn blue" onclick="boxNewEXAMENFISICOMAMAS()"> + AGREGAR </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col l2 s2 m2">FECHA</div>
                    <div class="col l3 s3 m3">ORIGEN</div>
                    <div class="col l5 s5 m5"></div>
                    <div class="col l2 s2 m2"></div>
                </div>
                <hr class="row" />
                <?php
                $sql1 = "select * from examen_mujer 
                            where rut='$rut'  
                            AND tipo_examen='EXAMEN FISICO MAMAS'
                            order by id_examen desc";
                $res1 = mysql_query($sql1);
                while($row1 = mysql_fetch_array($res1)){
                    ?>
                    <div class="row tooltipped rowInfoSis"
                         data-position="bottom" data-delay="50" data-tooltip="OBS: <?php echo $row1['obs_examen']; ?>" >
                        <div class="col l2 s4 m2"><?PHP echo fechaNormal($row1['fecha_examen']); ?></div>
                        <div class="col l3 s3 m3"><?PHP echo $row1['origen_examen']; ?></div>
                        <div class="col l5 s5 m5"></div>
                        <div class="col l2 s2 m2">
                            <?PHP
                            IF($row1['fecha_examen']==date('Y-m-d')){
                                ?>
                                <a href="#" onclick="delte_examen('MAMOGRAFIA','<?php echo $row1['id_examen']; ?> ')">ELIMINAR</a>
                                <?php
                            }
                            ?>
                        </div>
                    </div>
                    <?php
                }
                ?>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(function () {
        $('.tooltipped').tooltip({delay: 50});
    });
    function delte_examen(tipo,id_examen){
        if(confirm("Desea Eliminar este Examen")){
            $.post('db/delete/examen.php',{
                id_examen:id_examen,
                tipo:tipo,
                rut:'<?php echo $rut ?>'
            },function(data){
                if(data !== 'ERROR_SQL'){
                    load_m_examenes('<?php echo $rut; ?>');
                }
            });
        }
    }
    function boxNewPap(){
        $.post('formulario/new_pap.php',{
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
    function boxNewVph(){
        $.post('formulario/new_vph.php',{
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
    function boxNewMamografia(){
        $.post('formulario/new_mamografia.php',{
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
    function boxNewEXAMENFISICOMAMAS(){
        $.post('formulario/new_examen_mamas.php',{
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

</script>
