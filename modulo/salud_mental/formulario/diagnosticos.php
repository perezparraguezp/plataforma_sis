<?php

include "../../../php/config.php";
include "../../../php/objetos/persona.php";
include "../../../php/objetos/profesional.php";

$rut = $_POST['rut'];
$fecha_registro = $_POST['fecha_registro'];

$paciente = new persona($rut);

?>
<style type="text/css">
    a{
        color: white;;
    }
</style>
<div class="container">
    <div class="row">
        <div class="col l10 m10 s10">
            <!--  PAP -->
            <div class="card-panel green lighten-2">
                <div class="row">
                    <div class="col l8 m8 s8">
                        <strong style="line-height: 2em;font-size: 1.5em;">HISTORIAL DE DIAGNOSTICOS <strong class="tooltipped"
                                                                                       style="cursor: help" data-position="bottom" data-delay="50" data-tooltip="EL REGISTRO SERÁ GUARDADO AUTOMATICAMENTE">(?)</strong></strong>
                    </div>
                    <div class="col l4 m4 s4">
                        <div class="btn blue" onclick="boxNewDiagnostico()"> + AGREGAR </div>
                    </div>
                </div>
                <hr class="row" />
                <div class="row">
                    <div class="col l2 s2 m2">FECHA INGRESO</div>
                    <div class="col l5 s5 m5">TIPO DIAGNOSTICO</div>
                    <div class="col l3 s3 m3">EVALUACION</div>
                    <div class="col l2 s2 m2">FECHA EGRESO</div>
                </div>
                <hr class="row" />
                <?php
                $sql1 = "SELECT * FROM paciente_diagnosticos_sm 
                                inner join tipo_diagnostico_sm 
                                    on paciente_diagnosticos_sm.id_tipo=tipo_diagnostico_sm.id_tipo
                            where rut='$rut'  
                            order by id desc";
                $res1 = mysql_query($sql1);
                while($row1 = mysql_fetch_array($res1)){
                    ?>
                    <div class="row rowInfoSis" >
                        <div class="col l2 s4 m2"><?PHP echo fechaNormal($row1['fecha_inicio']).' 
                                <strong class="tooltipped" data-position="bottom" data-delay="50" data-tooltip="OBS INGRESO: '.$row1['obs'].'">(?)</strong>'; ?></div>
                        <div class="col l5 s5 m5"><?PHP echo $row1['nombre_tipo']; ?></div>
                        <div class="col l3 s3 m3"><?PHP echo $row1['valor_tipo']; ?></div>
                        <div class="col l2 s2 m2">
                            <?PHP

                            if($row1['fecha_inicio']==$fecha_registro){
                                ?>
                                <a href="#" onclick="delete_diagnostico('<?php echo $row1['id']; ?>')">ELIMINAR</a>
                                <?php
                            }else{
                                if($row1['fecha_egreso']==''){
                                    ?>
                                    <a href="#" onclick="AltaDiagnostico('<?php echo $row1['id']; ?>')">DAR ALTA</a>
                                    <?php
                                }else{
                                    echo fechaNormal($row1['fecha_egreso']).' <strong class="tooltipped" data-position="bottom" data-delay="50" data-tooltip="OBS EGRESO: '.$row1['obs_alta'].'">(?)</strong>';
                                }

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
    function AltaDiagnostico(id){
        $.post('formulario/alta_diagnostico.php',{
            rut:'<?php echo $rut ?>',
            id:id
        },function(data){
            if(data !== 'ERROR_SQL'){
                $("#modal").html(data);
                $("#modal").css({'width':'800px'});
                document.getElementById("btn-modal").click();
            }
        });
    }
    function delete_diagnostico(id){
        if(confirm("Desea Eliminar este Diagnostico")){
            $.post('db/delete/diagnostico.php',{
                id:id,
                rut:'<?php echo $rut ?>'
            },function(data){
                if(data !== 'ERROR_SQL'){
                    load_sm_diagnosticos('<?php echo $rut; ?>');
                }
            });
        }
    }
    function boxNewDiagnostico(){
        $.post('formulario/new_diagnostico.php',{
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
    function boxNewEcoMamaria(){
        $.post('formulario/new_ecomamaria.php',{
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
