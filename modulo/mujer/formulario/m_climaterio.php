<?php

include "../../../php/config.php";
include "../../../php/objetos/persona.php";
include "../../../php/objetos/profesional.php";

$rut = $_POST['rut'];
$fecha_registro = $_POST['fecha_registro'];

$paciente = new persona($rut);

$terapia_reemplazo = $paciente->getParametro_M_table('paciente_mujer','reemplazo_hormonal');

?>
<div class="container">
    <div class="row">
        <div class="col l6 m6 s12">
            <!--  TALLERES CLIMATERIO -->
            <div class="card-panel green lighten-2">
                <div class="row">
                    <div class="col l8 m8 s8">
                        <strong style="line-height: 2em;font-size: 1.5em;">TALLERES EDUCATIVOS<strong class="tooltipped"
                                                                                       style="cursor: help" data-position="bottom" data-delay="50" data-tooltip="EL REGISTRO SERÁ GUARDADO AUTOMATICAMENTE">(?)</strong></strong>
                    </div>
                    <div class="col l4 m4 s4">
                        <div class="btn blue" onclick="boxNewTallerClimaterio()"> + AGREGAR </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col l2 s2 m2">FECHA</div>
                    <div class="col l5 s5 m5">OBSERVACIONES</div>
                    <div class="col l2 s2 m2"></div>
                </div>
                <hr class="row" />
                <?php
                $sql1 = "select * from talleres_climaterio 
                            where rut='$rut'  
                            order by id_taller desc";
                $res1 = mysql_query($sql1);
                while($row1 = mysql_fetch_array($res1)){
                    $profesional = new profesional($row1['id_profesional']);
                    ?>
                    <div class="row tooltipped rowInfoSis"
                         data-position="bottom" data-delay="50"
                         data-tooltip="PROFESIONAL: <?php echo $profesional->nombre; ?>" >
                        <div class="col l2 s2 m2"><?PHP echo fechaNormal($row1['fecha_taller']); ?></div>
                        <div class="col l8 s8 m8"><?PHP echo $row1['obs_taller']; ?></div>
                        <div class="col l2 s2 m2">
                            <?PHP
                            IF($row1['fecha_taller']==date('Y-m-d')){
                                ?>
                                <a href="#" onclick="delte_taller('<?php echo $row1['id_taller']; ?> ')">ELIMINAR</a>
                                <?php
                            }
                            ?>
                        </div>
                    </div>
                    <?php
                }
                ?>
            </div>
            <!-- PAUTA MRS -->
            <div class="card-panel green lighten-2">
                <div class="row">
                    <div class="col l8 m8 s8">
                        <strong style="line-height: 2em;font-size: 1.5em;">PAUTA MRS <strong class="tooltipped"
                                                                                       style="cursor: help" data-position="bottom" data-delay="50" data-tooltip="EL REGISTRO SERÁ GUARDADO AUTOMATICAMENTE">(?)</strong></strong>
                    </div>
                    <div class="col l4 m4 s4">
                        <div class="btn blue" onclick="boxNewMRS()"> + AGREGAR </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col l2 s2 m2">FECHA</div>
                    <div class="col l3 s3 m3"></div>
                    <div class="col l5 s5 m5">RESULTADO</div>
                    <div class="col l2 s2 m2"></div>
                </div>
                <hr class="row" />
                <?php
                $sql1 = "select * from pauta_mrs 
                            where rut='$rut'  
                            order by id_pauta desc";
                $res1 = mysql_query($sql1);
                while($row1 = mysql_fetch_array($res1)){
                    ?>
                    <div class="row tooltipped rowInfoSis"
                         data-position="bottom" data-delay="50"
                         data-tooltip="OBS: <?php echo $row1['obs_pauta']; ?>" >
                        <div class="col l2 s4 m2"><?PHP echo fechaNormal($row1['fecha_pauta']); ?></div>
                        <div class="col l3 s3 m3"></div>
                        <div class="col l5 s5 m5"><?PHP echo $row1['estado_pauta']; ?></div>
                        <div class="col l2 s2 m2">
                            <?PHP
                            IF($row1['fecha_pauta']==date('Y-m-d')){
                                ?>
                                <a href="#" onclick="delte_pauta_mrs('<?php echo $row1['id_pauta']; ?> ')">ELIMINAR</a>
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
            <div class="card-panel green lighten-2">
                <div class="row">
                    <div class="col l10 m10 s10">
                        <strong style="line-height: 2em;font-size: 1em;">EN TERAPIA HORMONAL DE REEMPLAZO SEGUN MRS</strong>
                    </div>
                    <div class="col l2 m2 s2">
                        <input type="checkbox" id="reemplazo_hormonal"
                               onchange="updateParametro_M('reemplazo_hormonal')"
                            <?php echo $terapia_reemplazo=='SI'?'checked="checked"':'' ?>
                               name="reemplazo_hormonal"  />
                        <label class="white-text" for="reemplazo_hormonal">SI</label>
                    </div>
                </div>
            </div>
            <div class="card-panel green lighten-2" id="div_reemplazo_hormonal" style="display: none;">
                <div class="row">
                    <div class="col l8 m8 s8">
                        <strong style="line-height: 2em;font-size: 1.5em;">REEMPLAZO HORMONAL <strong class="tooltipped"
                                                                                                            style="cursor: help" data-position="bottom" data-delay="50" data-tooltip="EL REGISTRO SERÁ GUARDADO AUTOMATICAMENTE">(?)</strong></strong>
                    </div>
                    <div class="col l4 m4 s4">
                        <div class="btn blue" onclick="boxNewReemplazoHormonal()"> + AGREGAR </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col l2 s1 m2">DESDE</div>
                    <div class="col l4 s4 m8">TIPO</div>
                    <div class="col l2 s1 m2"></div>
                    <div class="col l4 s1 m4"></div>
                </div>
                <hr class="row" />
                <?php
                $sql1 = "select * from hormona_reemplazo_m 
                            where rut='$rut' 
                            order by id_hormona desc";
                $res1 = mysql_query($sql1);
                while($row1 = mysql_fetch_array($res1)){
                    ?>
                    <div class="row tooltipped rowInfoSis"
                         data-position="bottom" data-delay="50" data-tooltip="OBS: <?php echo $row1['obs_hormona']; ?>" >
                        <div class="col l2 s4 m2"><?PHP echo fechaNormal($row1['fecha_desde']); ?></div>
                        <div class="col l4 s4 m8"><?PHP echo $row1['tipo_hormona']; ?></div>
                        <div class="col l2 s4 m2"><?PHP echo fechaNormal($row1['vencimiento']); ?></div>
                        <div class="col l4 s4 m4">
                            <?PHP
                            IF($row1['fecha_desde']==date('Y-m-d')){
                                ?>
                                <a href="#" onclick="deleteHormonaReemplazo('<?php echo $row1['id_hormona']; ?> ')">ELIMINAR</a>
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
        <?php
        if($terapia_reemplazo=='SI'){
            echo '$("#div_reemplazo_hormonal").show();';
        }else{
            echo '$("#div_reemplazo_hormonal").hide();';
        }
        ?>
    });
    function delte_taller(id){
        if(confirm("Desea Eliminar este Taller")){
            $.post('db/delete/taller_climaterio.php',{
                id:id,
                rut:'<?php echo $rut ?>'
            },function(data){
                if(data !== 'ERROR_SQL'){
                    load_m_climaterio('<?php echo $rut; ?>');
                }
            });
        }
    }
    function delte_pauta_mrs(id){
        if(confirm("Desea Eliminar esta Pauta MRS")){
            $.post('db/delete/pauta_mrs.php',{
                id:id,
                rut:'<?php echo $rut ?>'
            },function(data){
                if(data !== 'ERROR_SQL'){
                    load_m_climaterio('<?php echo $rut; ?>');
                }
            });
        }
    }
    function deleteHormonaReemplazo(id){
        if(confirm("Desea Eliminar esta Hormona de Reemplazo")){
            $.post('db/delete/hormona_reemplazo.php',{
                id:id,
                rut:'<?php echo $rut ?>'
            },function(data){
                if(data !== 'ERROR_SQL'){
                    load_m_climaterio('<?php echo $rut; ?>');
                }
            });
        }
    }
    function boxNewTallerClimaterio(){
        $.post('formulario/new_taller_climaterio.php',{
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
    function boxNewMRS(){
        $.post('formulario/new_pauta_mrs.php',{
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
    function boxNewReemplazoHormonal(){
        $.post('formulario/new_reemplazo_hormonal.php',{
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
    function updateParametro_M(column){
        var value = '';
        var fecha = $("#fecha_registro").val();
        if($('#'+column).prop('checked')){
            value = 'SI';
        }else{
            value = 'NO';
        }
        $.post('db/update/m_indicador.php',{
            rut:'<?php echo $rut; ?>',
            column:column,
            value:value,
            fecha_registro:fecha,
        },function (data) {
            if(value==='SI'){
                $("#div_reemplazo_hormonal").show();
            }else{
                $("#div_reemplazo_hormonal").hide();
            }
            alertaLateral(data);
        });
    }

</script>
