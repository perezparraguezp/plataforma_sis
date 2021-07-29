<?php
include "../../config.php";
include '../../objetos/persona.php';
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
                                    $.post('php/db/update/cero_dental.php',{
                                        val:$('#cero_dental').val(),
                                        rut:'<?php echo $paciente->rut; ?>',
                                        fecha_registro:'<?php echo $fecha_registro; ?>'
                                    },function(data){
                                        //loadHistorialVacunas();
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
                                    $.post('php/db/update/ges6_dental.php',{
                                        val:$('#ges6_dental').val(),
                                        rut:'<?php echo $paciente->rut; ?>',
                                        fecha_registro:'<?php echo $fecha_registro; ?>'
                                    },function(data){
                                        //loadHistorialVacunas();
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
</div>
<div class="col l4">.</div>
<div class="col l4">
    <img src="images/odontologa.png" width="200" />
</div>
<div class="col l12">
    <strong style="color: red;">(*)</strong> Los cambios son guardados automaticamente
</div>
