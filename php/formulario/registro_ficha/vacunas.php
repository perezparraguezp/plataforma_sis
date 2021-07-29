<?php
include "../../config.php";
include '../../objetos/persona.php';
$rut = str_replace('.','',$_POST['rut']);
$fecha_registro = $_POST['fecha_registro'];

$paciente = new persona($rut);
$paciente->calcularEdadFecha($fecha_registro);


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
<div class="col l12">
    <strong style="color: red;">(*)</strong> Los cambios son guardados automaticamente
</div>
<div class="col l4">
    <div class="col l12 s12 m12">
        <div class="card-panel cyan lighten-2">
            <div class="row">
                <div class="col l12">
                    INDIQUE LAS VACUNAS QUE VA A SUMINISTRAR DE ACUERDO A LA EDAD DEL PACIENTE.
                </div>
                <div class="col l12">
                    <?php
                    $vacunas = array(2,4,6,12,18,(5*12));

                    //vacuna 2 meses
                    if($paciente->total_meses>=2){
                        if($paciente->vacuna2M()=='NO' || $paciente->vacuna2M()==''){
                            ?>
                            <div class="settings-section">
                                <div class="settings-label">VACUNA 2 MESES</div>
                                <div class="settings-setter">
                                    <div id="vacuna2m"></div>
                                </div>
                            </div>
                            <script type="text/javascript">
                                $(function(){
                                    $('#vacuna2m').jqxSwitchButton({
                                        height: 27, width: 81,
                                        checked: false,
                                        onLabel:'SI',
                                        offLabel:'NO',
                                    });
                                    $('#vacuna2m').on('change',function(){
                                        $.post('php/db/update/vacuna_2m.php',{
                                            vacuna:$('#vacuna2m').val(),
                                            rut:'<?php echo $paciente->rut; ?>'
                                        },function(data){
                                            loadHistorialVacunas();
                                        });
                                    })
                                });
                            </script>
                            <?php
                        }
                    }

                    //vacuna 4 meses
                    if($paciente->total_meses>=4){
                        if($paciente->vacuna4M()=='NO' || $paciente->vacuna4M()==''){
                            ?>
                            <div class="settings-section">
                                <div class="settings-label">VACUNA 4 MESES</div>
                                <div class="settings-setter">
                                    <div id="vacuna4m"></div>
                                </div>
                            </div>
                            <script type="text/javascript">
                                $(function(){
                                    $('#vacuna4m').jqxSwitchButton({
                                        height: 27, width: 81,
                                        checked: false,
                                        onLabel:'SI',
                                        offLabel:'NO',
                                    });
                                    $('#vacuna4m').on('change',function(){
                                        $.post('php/db/update/vacuna_4m.php',{
                                            vacuna:$('#vacuna4m').val(),
                                            rut:'<?php echo $rut; ?>'
                                        },function(data){
                                            loadHistorialVacunas();
                                        });
                                    });
                                });
                            </script>
                            <?php
                        }
                    }

                    //vacuna 6 meses
                    if($paciente->total_meses>=6){
                        if($paciente->vacuna6M()=='NO' || $paciente->vacuna6M()==''){
                            ?>
                            <div class="settings-section">
                                <div class="settings-label">VACUNA 6 MESES</div>
                                <div class="settings-setter">
                                    <div id="vacuna6m"></div>
                                </div>
                            </div>
                            <script type="text/javascript">
                                $(function(){
                                    $('#vacuna6m').jqxSwitchButton({
                                        height: 27, width: 81,
                                        checked: false,
                                        onLabel:'SI',
                                        offLabel:'NO',
                                    });
                                    $('#vacuna6m').on('change',function(){
                                        $.post('php/db/update/vacuna_6m.php',{
                                            vacuna:$('#vacuna6m').val(),
                                            rut:'<?php echo $rut; ?>'
                                        },function(data){
                                            loadHistorialVacunas();
                                        });
                                    });
                                });
                            </script>
                            <?php
                        }
                    }

                    //vacuna 12 meses
                    if($paciente->total_meses>=12){
                        if($paciente->vacuna12M()=='NO' || $paciente->vacuna12M()==''){
                            ?>
                            <div class="settings-section">
                                <div class="settings-label">VACUNA <?php echo $vacunas[$v]; ?> MESES</div>
                                <div class="settings-setter">
                                    <div id="vacuna12m"></div>
                                </div>
                            </div>
                            <script type="text/javascript">
                                $(function(){
                                    $('#vacuna12m').jqxSwitchButton({
                                        height: 27, width: 81,
                                        checked: false,
                                        onLabel:'SI',
                                        offLabel:'NO',
                                    });
                                    $('#vacuna12m').on('change',function(){
                                        $.post('php/db/update/vacuna_12m.php',{
                                            vacuna:$('#vacuna12m').val(),
                                            rut:'<?php echo $rut; ?>'
                                        },function(data){
                                            loadHistorialVacunas();
                                        });
                                    });
                                });
                            </script>
                            <?php
                        }
                    }



                    for ($v = 0 ; $v < count($vacunas) ; $v++){

                        if($vacunas[$v] <= $paciente->total_meses){




                            if($vacunas[$v] == 18 && $paciente->vacuna18M()=='NO'){
                                ?>
                                <div class="settings-section">
                                    <div class="settings-label">VACUNA <?php echo $vacunas[$v]; ?> MESES</div>
                                    <div class="settings-setter">
                                        <div id="vacuna18m"></div>
                                    </div>
                                </div>
                            <script type="text/javascript">
                                $(function(){
                                    $('#vacuna18m').jqxSwitchButton({
                                        height: 27, width: 81,
                                        checked: false,
                                        onLabel:'SI',
                                        offLabel:'NO',
                                    });
                                    $('#vacuna18m').on('change',function(){
                                        $.post('php/db/update/vacuna_18m.php',{
                                            vacuna:$('#vacuna18m').val(),
                                            rut:'<?php echo $rut; ?>'
                                        },function(data){
                                            loadHistorialVacunas();
                                        });
                                    });
                                });
                            </script>
                                <?php
                            }

                            //
                            if($vacunas[$v] == (5*12) && $paciente->vacuna5Anios()=='NO'){
                                ?>
                            <div class="settings-section">
                                <div class="settings-label">VACUNA 5 AÑOS</div>
                                <div class="settings-setter">
                                    <div id="vacuna5anios"></div>
                                </div>
                            </div>
                            <script type="text/javascript">
                                $(function(){
                                    $('#vacuna5anios').jqxSwitchButton({
                                        height: 27, width: 81,
                                        checked: false,
                                        onLabel:'SI',
                                        offLabel:'NO',
                                    });
                                    $('#vacuna5anios').on('change',function(){
                                        $.post('php/db/update/vacuna_5anios.php',{
                                            vacuna:$('#vacuna5anios').val(),
                                            rut:'<?php echo $rut; ?>'
                                        },function(data){
                                            loadHistorialVacunas();
                                        });
                                    });
                                });
                            </script>
                            <?php
                            }
                        }
                    }
                    ?>
                    <script type="text/javascript">
                        $(function(){
                            loadHistorialVacunas();
                        });
                        function loadHistorialVacunas(){
                            $.post('php/info/registro_ficha/historial_vacuna.php',{
                                rut:'<?php echo $rut; ?>'
                            },function(data){
                                $("#div_historialVacunas").html(data);
                            });
                        }
                    </script>
                </div>

            </div>
        </div>
    </div>
</div>
<div class="col l4">.</div>
<div class="col l4">
    <div class="card-panel cyan lighten-2">
        <div class="row">
            <header>HISTORIAL DE VACUNAS</header>
            <div class="col l12" id="div_historialVacunas">

            </div>

        </div>
    </div>
</div>
