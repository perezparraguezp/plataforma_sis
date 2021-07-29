<?php
include "../../config.php";
include '../../objetos/persona.php';
$rut = str_replace('.','',$_POST['rut']);
$fecha_registro = $_POST['fecha_registro'];

$paciente = new persona($rut);


?>
<style type="text/css">
    .letra_datos_psicomotor{
        font-size: 1.6em;
    }
</style>
<div class="col l12" style="font-size: 0.8em;">
    <div class="col l6">
        <?php
        if($paciente->validaDNI()){
            ?>
            <div class="col l12 s12 m12">
                <div class="card-panel yellow darken-4">
                    <div class="row">
                        <div class="col l4">
                            <span class="white-text letra_datos_psicomotor">EV NEUROSENSORIAL <strong class="tooltipped" style="cursor: help" data-position="bottom" data-delay="50" data-tooltip="EVALUACIÓN NEUROSENSORIAL">(?)</strong></span>
                        </div>
                        <div class="col l8">
                            <select name="ev_neurosensorial" id="ev_neurosensorial">
                                <option></option>
                                <option>NORMAL</option>
                                <option>ALTERADO</option>
                            </select>
                            <script type="text/javascript">
                                $(function(){
                                    $('#ev_neurosensorial').jqxDropDownList({
                                        width: '100%',
                                        height: '25px'
                                    });
                                    $("#ev_neurosensorial").on('change',function(){
                                        var val = $("#ev_neurosensorial").val();
                                        $.post('php/db/update/ev_neurosensorial.php',{
                                            rut:'<?php echo $rut; ?>',
                                            val:val,
                                            fecha_registro:'<?php echo $fecha_registro; ?>'

                                        },function(data){
                                            alertaLateral(data);
                                            historial_psicomotor('<?php echo $rut; ?>');
                                            $('.tooltipped').tooltip({delay: 50});
                                        });

                                    });
                                    $('.tooltipped').tooltip({delay: 50});
                                })
                            </script>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        }
        ?>
        <?php
        //RX PELVIS
        if($paciente->validaDNI()){
            ?>
            <div class="col l12 s12 m12">
                <div class="card-panel yellow darken-4">
                    <div class="row">
                        <div class="col l4">
                            <span class="white-text letra_datos_psicomotor">RX PELVIS <strong class="tooltipped" style="cursor: help" data-position="bottom" data-delay="50" data-tooltip="RADIOGRAFIA DE PELVIS">(?)</strong></span>
                        </div>
                        <div class="col l8">
                            <select name="rx_pelvis" id="rx_pelvis">
                                <option></option>
                                <option >NORMAL</option>
                                <option>ALTERADO</option>
                            </select>
                            <script type="text/javascript">
                                $(function(){
                                    $('#rx_pelvis').jqxDropDownList({
                                        width: '100%',
                                        height: '25px'
                                    });
                                    $("#rx_pelvis").on('change',function(){
                                        var val = $("#rx_pelvis").val();
                                        $.post('php/db/update/rx_pelvis.php',{
                                            rut:'<?php echo $rut; ?>',
                                            val:val,
                                            fecha_registro:'<?php echo $fecha_registro; ?>'

                                        },function(data){
                                            alertaLateral(data);
                                            historial_psicomotor('<?php echo $rut; ?>');
                                            $('.tooltipped').tooltip({delay: 50});
                                        });

                                    });
                                    $('.tooltipped').tooltip({delay: 50});
                                })
                            </script>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        }
        ?>
        <?php
        //EEDP
        if($paciente->total_meses < 24){
            ?>
            <div class="col l12 s12 m12">
                <div class="card-panel yellow darken-4">
                    <div class="row">
                        <div class="col l4">
                            <span class="white-text letra_datos_psicomotor">PAUTA BREVE <strong class="tooltipped" style="cursor: help" data-position="bottom" data-delay="50" data-tooltip="">(?)</strong></span>
                        </div>
                        <div class="col l8">
                            <select name="pauta_breve" id="pauta_breve">
                                <option></option>
                                <option >NORMAL</option>
                                <option>ALTERADO</option>
                            </select>
                            <script type="text/javascript">
                                $(function(){
                                    $('#pauta_breve').jqxDropDownList({
                                        width: '100%',
                                        height: '25px'
                                    });
                                    $("#pauta_breve").on('change',function(){
                                        var val = $("#pauta_breve").val();
                                        $.post('php/db/update/pauta_breve.php',{
                                            rut:'<?php echo $rut; ?>',
                                            val:val,
                                            fecha_registro:'<?php echo $fecha_registro; ?>'

                                        },function(data){
                                            alertaLateral(data);
                                            historial_psicomotor('<?php echo $rut; ?>');
                                            $('.tooltipped').tooltip({delay: 50});
                                        });

                                    });
                                    $('.tooltipped').tooltip({delay: 50});
                                })
                            </script>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col l12 s12 m12">
                <div class="card-panel yellow darken-4">
                    <div class="row">
                        <div class="col l4">
                            <span class="white-text letra_datos_psicomotor">EEDP <strong class="tooltipped" style="cursor: help" data-position="bottom" data-delay="50" data-tooltip="SOLO MENORES DE 12 MESES">(?)</strong></span>
                        </div>
                        <div class="col l8">
                            <select name="eedp" id="eedp">
                                <option selected="selected"></option>
                                <option >NORMAL</option>
                                <option>NORMAL C/REZAGO</option>
                                <option>RIESGO</option>
                                <option>RETRASO</option>
                            </select>
                            <script type="text/javascript">
                                $(function(){
                                    $('#eedp').jqxDropDownList({
                                        width: '100%',
                                        height: '25px'
                                    });
                                    $('#eedp').on('change',function(){
                                        var value = $('#eedp').val();
                                        if(value!=='NORMAL' && value!==''){
                                            $('#EEDP_DETALLE').show("swing");
                                        }else{
                                            $('#EEDP_DETALLE').hide("swing");
                                        }
                                    });
                                    $("#eedp").on('change',function(){
                                        var val = $("#eedp").val();
                                        $.post('php/db/update/paciente_psicomotor.php',{
                                            rut:'<?php echo $rut; ?>',
                                            val:val,
                                            column:'eedp',
                                            fecha_registro:'<?php echo $fecha_registro; ?>'

                                        },function(data){
                                            alertaLateral(data);
                                            historial_psicomotor('<?php echo $rut; ?>');
                                            $('.tooltipped').tooltip({delay: 50});
                                        });

                                    });
                                    $('.tooltipped').tooltip({delay: 50});
                                })
                            </script>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col l12 s12 m12" id="EEDP_DETALLE" style="display: none;">
                <div class="card-panel yellow darken-4">
                    <div class="row">
                        <div class="col l12">
                            <header>DEBE INDICAR EL DETALLE DEL EDDP</header>
                        </div>
                    </div>
                    <div class="row">
                        <div class="settings-section">
                            <div class="settings-label">LENGUAJE </div>
                            <div class="settings-setter">
                                <div id="lenguaje"></div>
                            </div>
                        </div>
                        <div class="settings-section">
                            <div class="settings-label">MOTROCIDAD </div>
                            <div class="settings-setter">
                                <div id="motrocidad"></div>
                            </div>
                        </div>
                        <div class="settings-section">
                            <div class="settings-label">COORDINACIÓN </div>
                            <div class="settings-setter">
                                <div id="coordinacion"></div>
                            </div>
                        </div>
                        <div class="settings-section">
                            <div class="settings-label">SOCIAL </div>
                            <div class="settings-setter">
                                <div id="social"></div>
                            </div>
                        </div>
                        <script type="text/javascript">
                            $(function(){
                                $('#lenguaje').jqxSwitchButton({
                                    height: 27, width: 200,
                                    checked: <?php echo $paciente->eedp_lenguaje == 'NORMAL' ? 'true' : 'false'; ?>,
                                    onLabel:'NORMAL',
                                    offLabel:'ALTERADO',
                                });
                                $('#lenguaje').on('change',function(){
                                    $.post('php/db/update/lenguaje_eedp.php',{
                                        val:$('#lenguaje').val(),
                                        rut:'<?php echo $rut; ?>',
                                        fecha_registro:'<?php echo $fecha_registro; ?>'

                                    },function(data){
                                        alertaLateral(data);
                                        historial_psicomotor('<?php echo $rut; ?>');
                                    });
                                });
                                $('#motrocidad').jqxSwitchButton({
                                    height: 27, width: 200,
                                    checked: <?php echo $paciente->eedp_motrocidad == 'NORMAL' ? 'true' : 'false'; ?>,
                                    onLabel:'NORMAL',
                                    offLabel:'ALTERADO',
                                });
                                $('#motrocidad').on('change',function(){
                                    $.post('php/db/update/motrocidad_eedp.php',{
                                        val:$('#motrocidad').val(),
                                        rut:'<?php echo $rut; ?>',
                                        fecha_registro:'<?php echo $fecha_registro; ?>'

                                    },function(data){
                                        alertaLateral(data);
                                        historial_psicomotor('<?php echo $rut; ?>');
                                    });
                                });
                                $('#coordinacion').jqxSwitchButton({
                                    height: 27, width: 200,
                                    checked: <?php echo $paciente->eedp_coordinacion == 'NORMAL' ? 'true' : 'false'; ?>,
                                    onLabel:'NORMAL',
                                    offLabel:'ALTERADO',
                                });
                                $('#coordinacion').on('change',function(){
                                    $.post('php/db/update/coordinacion_eedp.php',{
                                        val:$('#coordinacion').val(),
                                        rut:'<?php echo $rut; ?>',
                                        fecha_registro:'<?php echo $fecha_registro; ?>'

                                    },function(data){
                                        alertaLateral(data);
                                        historial_psicomotor('<?php echo $rut; ?>');
                                    });
                                });
                                $('#social').jqxSwitchButton({
                                    height: 27, width: 200,
                                    checked: <?php echo $paciente->eedp_social == 'NORMAL' ? 'true' : 'false'; ?>,
                                    onLabel:'NORMAL',
                                    offLabel:'ALTERADO',
                                });
                                $('#social').on('change',function(){
                                    $.post('php/db/update/social_eedp.php',{
                                        val:$('#social').val(),
                                        rut:'<?php echo $rut; ?>',
                                        fecha_registro:'<?php echo $fecha_registro; ?>'

                                    },function(data){
                                        alertaLateral(data);
                                        historial_psicomotor('<?php echo $rut; ?>');
                                    });
                                });
                            });
                        </script>
                    </div>
                </div>
            </div>
            <?php
        }else{
            //TEPSI
            if($paciente->validaTEPSI()==true){
                ?>
                <div class="col l12 s12 m12">
                    <div class="card-panel yellow darken-4">
                        <div class="row">
                            <div class="col l4">
                                <span class="white-text letra_datos_psicomotor">TEPSI <strong class="tooltipped" style="cursor: help" data-position="bottom" data-delay="50" data-tooltip="SOLO MAYORES DE 12 MESES">(?)</strong></span>
                            </div>
                            <div class="col l8">
                                <select name="tepsi" id="tepsi">
                                    <option selected="selected"></option>
                                    <option>NORMAL</option>
                                    <option>NORMAL C/REZAGO</option>
                                    <option>RIESGO</option>
                                    <option>RETRASO</option>
                                </select>
                                <script type="text/javascript">
                                    $(function(){
                                        $('#tepsi').jqxDropDownList({
                                            width: '100%',
                                            height: '25px'
                                        });
                                        $('#tepsi').on('change',function(){
                                            var value = $('#tepsi').val();
                                            if(value!=='NORMAL' && value!==''){
                                                $('#TEPSI_DETALLE').show("swing");
                                                $('#lenguaje_tepsi').jqxSwitchButton({ checked:true })
                                                $('#coordinacion_tepsi').jqxSwitchButton({ checked:true })
                                                $('#motrocidad_tepsi').jqxSwitchButton({ checked:true })
                                            }else{
                                                $('#TEPSI_DETALLE').hide("swing");
                                            }
                                        });

                                        $("#tepsi").on('change',function(){
                                            var val = $("#tepsi").val();
                                            $.post('php/db/update/tepsi.php',{
                                                rut:'<?php echo $rut; ?>',
                                                val:val,
                                                fecha_registro:'<?php echo $fecha_registro; ?>'

                                            },function(data){
                                                alertaLateral(data);
                                                historial_psicomotor('<?php echo $rut; ?>');
                                                $('.tooltipped').tooltip({delay: 50});
                                            });

                                        });
                                        $('.tooltipped').tooltip({delay: 50});
                                    })
                                </script>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col l12 s12 m12" id="TEPSI_DETALLE" style="display:none;">
                    <div class="card-panel yellow darken-4">
                        <div class="row">
                            <div class="col l12">
                                <header>DEBE INDICAR EL DETALLE DEL TEPSI</header>
                            </div>
                        </div>
                        <div class="row">
                            <div class="settings-section">
                                <div class="settings-label">LENGUAJE </div>
                                <div class="settings-setter">
                                    <div id="lenguaje_tepsi"></div>
                                </div>
                            </div>
                            <div class="settings-section">
                                <div class="settings-label">MOTROCIDAD </div>
                                <div class="settings-setter">
                                    <div id="motrocidad_tepsi"></div>
                                </div>
                            </div>
                            <div class="settings-section">
                                <div class="settings-label">COORDINACIÓN </div>
                                <div class="settings-setter">
                                    <div id="coordinacion_tepsi"></div>
                                </div>
                            </div>
                            <script type="text/javascript">
                                $(function(){
                                    $('#lenguaje_tepsi').jqxSwitchButton({
                                        height: 27, width: 200,
                                        checked: <?php echo $paciente->tepsi_lenguaje == 'NORMAL' ? 'true' : 'false'; ?>,
                                        onLabel:'NORMAL',
                                        offLabel:'ALTERADO',
                                    });
                                    $('#lenguaje_tepsi').on('change',function(){
                                        $.post('php/db/update/lenguaje_tepsi.php',{
                                            val:$('#lenguaje_tepsi').val(),
                                            rut:'<?php echo $rut; ?>',
                                            fecha_registro:'<?php echo $fecha_registro; ?>'

                                        },function(data){
                                            alertaLateral(data);
                                            historial_psicomotor('<?php echo $rut; ?>');
                                        });
                                    });
                                    $('#motrocidad_tepsi').jqxSwitchButton({
                                        height: 27, width: 200,
                                        checked: <?php echo $paciente->tepsi_motrocidad == 'NORMAL' ? 'true' : 'false'; ?>,
                                        onLabel:'NORMAL',
                                        offLabel:'ALTERADO',
                                    });
                                    $('#motrocidad_tepsi').on('change',function(){
                                        $.post('php/db/update/motrocidad_tepsi.php',{
                                            val:$('#motrocidad_tepsi').val(),
                                            rut:'<?php echo $rut; ?>',
                                            fecha_registro:'<?php echo $fecha_registro; ?>'

                                        },function(data){
                                            alertaLateral(data);
                                            historial_psicomotor('<?php echo $rut; ?>');
                                        });
                                    });

                                    $('#coordinacion_tepsi').jqxSwitchButton({
                                        height: 27, width: 200,
                                        checked: <?php echo $paciente->tepsi_coordinacion == 'NORMAL' ? 'true' : 'false'; ?>,
                                        onLabel:'NORMAL',
                                        offLabel:'ALTERADO',
                                    });
                                    $('#coordinacion_tepsi').on('change',function(){
                                        $.post('php/db/update/coordinacion_tepsi.php',{
                                            val:$('#coordinacion_tepsi').val(),
                                            rut:'<?php echo $rut; ?>',
                                            fecha_registro:'<?php echo $fecha_registro; ?>'

                                        },function(data){
                                            alertaLateral(data);
                                            historial_psicomotor('<?php echo $rut; ?>');
                                        });
                                    });
                                });
                            </script>
                        </div>
                    </div>
                </div>
                <?php
            }
        }
        ?>
    </div>
    <div class="col l6">
        <div class="col l12 s12 m12" >
            <div class="card-panel grey lighten-1" style="font-size: 1.2em;padding-top: 5px;">
                <div class="row">
                    <header style="font-weight: bold;">HISTORIAL DE ATENCIONES</header>
                </div>
                <div id="historial_psicomotor" style="font-size: 1.2em;"></div>
            </div>
        </div>
    </div>
</div>
