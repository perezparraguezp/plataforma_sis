<?php
include "../../../php/config.php";
include '../../../php/objetos/persona.php';
$rut = str_replace('.','',$_POST['rut']);
$fecha_registro = $_POST['fecha_registro'];

$paciente = new persona($rut);


?>
<style type="text/css">
    .settings-section
    {

        height: 45px;
        width: 100%;

    }
    .settings-label
    {
        font-weight: bold;
        font-family: Sans-Serif;
        font-size: 14px;
        margin-left: 14px;
        margin-top: 15px;
        float: left;
    }

    .settings-setter
    {
        float: right;
        margin-right: 14px;
        margin-top: 8px;
    }
</style>
<div class="col l4">
    <?php
    //CERO DENTAL
    if($paciente->validaCERO()){
        ?>
        <div class="col l12 s12 m12">
            <div class="card-panel yellow darken-4">
                <div class="row">
                    <div class="col l12">
                        <div class="settings-section">
                            <div class="settings-label">
                                <span class="white-text">CERO <strong class="tooltipped" style="cursor: help" data-position="bottom" data-delay="50" data-tooltip="PARA NIÑOS DESDE LOS 8 MESES">(?)</strong></span>
                            </div>
                            <div class="settings-setter">
                                <div id="cero_dental"></div>
                            </div>
                        </div>
                        <script type="text/javascript">
                            $(function(){
                                $('#cero_dental').jqxSwitchButton({
                                    height: 27, width: 81,
                                    checked: <?php echo $paciente->dental_cero == 'SI' ? 'true':'false'; ?>,
                                    disabled:<?php echo $paciente->dental_cero == 'SI' ? 'true':'false'; ?>,
                                    onLabel:'SI',
                                    offLabel:'NO',
                                });
                                $('#cero_dental').on('change',function(){
                                    $.post('db/update/cero_dental.php',{
                                        val:$('#cero_dental').val(),
                                        rut:'<?php echo $paciente->rut; ?>',
                                        fecha_registro:'<?php echo $fecha_registro; ?>'
                                    },function(data){
                                        //loadHistorialVacunas();
                                        historial_dental('<?php echo $rut; ?>');
                                    });
                                })
                            });
                        </script>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
    ?>
    <?php
    //GES6 DENTAL
    if($paciente->validaGES6()){
        ?>
        <div class="col l12 s12 m12">
            <div class="card-panel yellow darken-4">
                <div class="row">
                    <div class="col l12">
                        <div class="settings-section">
                            <div class="settings-label">
                                <span class="white-text">GES 6 <strong class="tooltipped" style="cursor: help" data-position="bottom" data-delay="50" data-tooltip="PARA NIÑOS DESDE LOS 6 AÑOS 1 MES EN ADELANTE">(?)</strong></span>
                            </div>
                            <div class="settings-setter">
                                <div id="ges6_dental"></div>
                            </div>
                        </div>
                        <script type="text/javascript">
                            $(function(){
                                $('#ges6_dental').jqxSwitchButton({
                                    height: 27, width: 81,
                                    checked: <?php echo $paciente->dental_ges6 == 'SI' ? 'true':'false'; ?>,
                                    disabled:<?php echo $paciente->dental_ges6 == 'SI' ? 'true':'false'; ?>,
                                    onLabel:'SI',
                                    offLabel:'NO',
                                });

                                $('#ges6_dental').on('change',function(){
                                    $.post('db/update/ges6_dental.php',{
                                        val:$('#ges6_dental').val(),
                                        rut:'<?php echo $paciente->rut; ?>',
                                        fecha_registro:'<?php echo $fecha_registro; ?>'
                                    },function(data){
                                        historial_dental('<?php echo $rut; ?>');
                                    });
                                })
                            });
                        </script>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
    ?>
    <?php
    //GES6 DENTAL
//    echo $paciente->edad_meses;
    if($paciente->edad_total>=6){
        ?>
        <div class="col l12 s12 m12">
            <div class="card-panel  blue lighten-1">
                <div class="row">
                    <div class="col l3">
                        <span class="white-text">INDICE <strong class="tooltipped" style="cursor: help" data-position="bottom" data-delay="50" data-tooltip="INDICE">(?)</strong></span>
                    </div>
                    <div class="col l8">
                        <select name="dental_indice" id="dental_indice">
                            <option></option>
                            <option>0</option>
                            <option>1 a 2</option>
                            <option>3 a 4</option>
                            <option>5 a 6</option>
                            <option>7 a 8</option>
                            <option>9 ó más</option>
                        </select>

                        <script type="text/javascript">
                            $(function(){
                                $('#dental_indice').jqxDropDownList({
                                    width: '100%',
                                    theme: 'eh-open',
                                    height: '25px'
                                });

                                $("#dental_indice").on('change',function(){
                                    var val = $("#dental_indice").val();
                                    $.post('db/update/paciente_dental.php',{
                                        rut:'<?php echo $rut; ?>',
                                        val:val,
                                        column:'indice',
                                        fecha_registro:'<?php echo $fecha_registro; ?>'

                                    },function(data){
                                        alertaLateral(data);
                                        $('.tooltipped').tooltip({delay: 50});
                                        historial_dental('<?php echo $rut; ?>');
                                    });

                                });
                                $('.tooltipped').tooltip({delay: 50});
                            });
                        </script>
                    </div>
                    <div class="col l1">
                        <i class="mdi-editor-insert-chart"
                           onclick="loadModalGraficoDental('<?php echo $rut ?>','indice')"></i>
                    </div>

                </div>
            </div>
        </div>
        <div class="col l12 s12 m12">
            <div class="card-panel yellow darken-4">
                <div class="row">
                    <div class="col l3">
                        <span class="white-text">C <strong class="tooltipped" style="cursor: help" data-position="bottom" data-delay="50" data-tooltip="C">(?)</strong></span>
                    </div>
                    <div class="col l8">
                        <select name="dental_c" id="dental_c">
                            <option></option>
                            <?php
                            for($i=0;$i<=20;$i++){
                                ?><option><?php echo $i; ?></option><?php
                            }
                            ?>
                        </select>


                        <script type="text/javascript">
                            $(function(){
                                $('#dental_c').jqxDropDownList({
                                    width: '100%',
                                    theme: 'eh-open',
                                    height: '25px'
                                });

                                $("#dental_c").on('change',function(){
                                    var val = $("#dental_c").val();
                                    $.post('db/update/paciente_dental.php',{
                                        rut:'<?php echo $rut; ?>',
                                        val:val,
                                        column:'c',
                                        fecha_registro:'<?php echo $fecha_registro; ?>'

                                    },function(data){
                                        alertaLateral(data);
                                        $('.tooltipped').tooltip({delay: 50});
                                        historial_dental('<?php echo $rut; ?>');
                                    });

                                });
                                $('.tooltipped').tooltip({delay: 50});
                            });
                        </script>
                    </div>
                    <div class="col l1">
                        <i class="mdi-editor-insert-chart"
                           onclick="loadModalGraficoDental('<?php echo $rut ?>','c')"></i>
                    </div>

                </div>
                <div class="row">
                    <div class="col l3">
                        <span class="white-text">E <strong class="tooltipped" style="cursor: help" data-position="bottom" data-delay="50" data-tooltip="E">(?)</strong></span>
                    </div>
                    <div class="col l8">
                        <select name="dental_e" id="dental_e">
                            <option></option>
                            <?php
                            for($i=0;$i<=20;$i++){
                                ?><option><?php echo $i; ?></option><?php
                            }
                            ?>
                        </select>

                        <script type="text/javascript">
                            $(function(){
                                $('#dental_e').jqxDropDownList({
                                    width: '100%',
                                    theme: 'eh-open',
                                    height: '25px'
                                });

                                $("#dental_e").on('change',function(){
                                    var val = $("#dental_e").val();
                                    $.post('db/update/paciente_dental.php',{
                                        rut:'<?php echo $rut; ?>',
                                        val:val,
                                        column:'e',
                                        fecha_registro:'<?php echo $fecha_registro; ?>'

                                    },function(data){
                                        alertaLateral(data);
                                        $('.tooltipped').tooltip({delay: 50});
                                        historial_dental('<?php echo $rut; ?>');
                                    });

                                });
                                $('.tooltipped').tooltip({delay: 50});
                            });
                        </script>
                    </div>
                    <div class="col l1">
                        <i class="mdi-editor-insert-chart"
                           onclick="loadModalGraficoDental('<?php echo $rut ?>','e')"></i>
                    </div>

                </div>
                <div class="row">
                    <div class="col l3">
                        <span class="white-text">O <strong class="tooltipped" style="cursor: help" data-position="bottom" data-delay="50" data-tooltip="O">(?)</strong></span>
                    </div>
                    <div class="col l8">
                        <select name="dental_o" id="dental_o">
                            <option></option>
                            <?php
                            for($i=0;$i<=20;$i++){
                                ?><option><?php echo $i; ?></option><?php
                            }
                            ?>
                        </select>

                        <script type="text/javascript">
                            $(function(){
                                $('#dental_o').jqxDropDownList({
                                    width: '100%',
                                    theme: 'eh-open',
                                    height: '25px'
                                });

                                $("#dental_o").on('change',function(){
                                    var val = $("#dental_o").val();
                                    $.post('db/update/paciente_dental.php',{
                                        rut:'<?php echo $rut; ?>',
                                        val:val,
                                        column:'o',
                                        fecha_registro:'<?php echo $fecha_registro; ?>'

                                    },function(data){
                                        alertaLateral(data);
                                        $('.tooltipped').tooltip({delay: 50});
                                        historial_dental('<?php echo $rut; ?>');
                                    });

                                });
                                $('.tooltipped').tooltip({delay: 50});
                            });
                        </script>
                    </div>
                    <div class="col l1">
                        <i class="mdi-editor-insert-chart"
                           onclick="loadModalGraficoDental('<?php echo $rut ?>','o')"></i>
                    </div>

                </div>

            </div>
        </div>
        <div class="col l12 s12 m12">
            <div class="card-panel  blue lighten-1">
                <div class="row">
                    <div class="col l3">
                        <span class="white-text">Nº Dientes <strong class="tooltipped" style="cursor: help" data-position="bottom" data-delay="50" data-tooltip="Cantidad de Dientes">(?)</strong></span>
                    </div>
                    <div class="col l8">
                        <select name="dental_dientes" id="dental_dientes">
                            <option></option>
                            <option>0</option>
                            <option>1 a 9</option>
                            <option>10 a 19</option>
                            <option>20 a 27</option>
                            <option>28 ó más</option>
                        </select>

                        <script type="text/javascript">
                            $(function(){
                                $('#dental_dientes').jqxDropDownList({
                                    width: '100%',
                                    theme: 'eh-open',
                                    height: '25px'
                                });

                                $("#dental_dientes").on('change',function(){
                                    var val = $("#dental_dientes").val();
                                    $.post('db/update/paciente_dental.php',{
                                        rut:'<?php echo $rut; ?>',
                                        val:val,
                                        column:'dientes',
                                        fecha_registro:'<?php echo $fecha_registro; ?>'

                                    },function(data){
                                        alertaLateral(data);
                                        $('.tooltipped').tooltip({delay: 50});
                                        historial_dental('<?php echo $rut; ?>');
                                    });

                                });
                                $('.tooltipped').tooltip({delay: 50});
                            });
                        </script>
                    </div>
                    <div class="col l1">
                        <i class="mdi-editor-insert-chart"
                           onclick="loadModalGraficoDental('<?php echo $rut ?>','dientes')"></i>
                    </div>

                </div>
            </div>
        </div>

        <div class="col l12 s12 m12">
            <div class="card-panel red accent-2">
                <div class="row">
                    <div class="col l3">
                        <span class="white-text">Riesgo <strong class="tooltipped" style="cursor: help" data-position="bottom" data-delay="50" data-tooltip="Cantidad de Dientes">(?)</strong></span>
                    </div>
                    <div class="col l8">
                        <select name="dental_riesgo" id="dental_riesgo">
                            <option></option>
                            <option>BAJO</option>
                            <option>ALTO</option>
                        </select>

                        <script type="text/javascript">
                            $(function(){
                                $('#dental_riesgo').jqxDropDownList({
                                    width: '100%',
                                    theme: 'eh-open',
                                    height: '25px'
                                });

                                $("#dental_riesgo").on('change',function(){
                                    var val = $("#dental_riesgo").val();
                                    $.post('db/update/paciente_dental.php',{
                                        rut:'<?php echo $rut; ?>',
                                        val:val,
                                        column:'riesgo',
                                        fecha_registro:'<?php echo $fecha_registro; ?>'

                                    },function(data){
                                        alertaLateral(data);
                                        $('.tooltipped').tooltip({delay: 50});
                                        historial_dental('<?php echo $rut; ?>');
                                    });

                                });
                                $('.tooltipped').tooltip({delay: 50});
                            });
                        </script>
                    </div>
                    <div class="col l1">
                        <i class="mdi-editor-insert-chart"
                           onclick="loadModalGraficoDental('<?php echo $rut ?>','riesgo')"></i>
                    </div>

                </div>
            </div>
        </div>
        <?php
    }
    ?>
</div>
<div class="col l2 center-align center">
    <img src="../../images/odontologa.png" width="100" />
</div>
<div class="col l6" style="background-color: #cffcff;">
    <div id="historial_dental" style="font-size: 1.2em;"></div>
</div>
<div class="col l12">
    <strong style="color: red;">(*)</strong> Los cambios son guardados automaticamente
</div>
<script type="text/javascript">
    function loadModalGraficoDental(rut,indicador){
        $.post('grid/historial_dental.php',{
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
    function updateInfoAntropometria(){

    }
</script>