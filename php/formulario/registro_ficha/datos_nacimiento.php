<?php
include "../../config.php";
include '../../objetos/persona.php';

$rut = str_replace('.','',$_POST['rut']);
$fecha_registro = $_POST['fecha_registro'];

$paciente = new persona($rut);
$paciente->load_DatosNacimiento();


?>
<style type="text/css">
    .letra_datos_nacimiento{
        font-size: 1em;
    }
</style>
<div class="col l12 s12 m12" id="datos_nacimiento_form">
    <div class="card-panel yellow darken-4">
        <div class="row">
            <div class="col l3">
                <span class="white-text letra_datos_nacimiento">EOA <strong class="tooltipped" style="cursor: help" data-position="bottom" data-delay="50" data-tooltip="PESO EDAD">(?)</strong></span>
            </div>
            <div class="col l8">
                <select name="eoa" id="eoa">
                    <option><?php echo $paciente->eoa;  ?></option>
                    <option>NORMAL</option>
                    <option>ALTERADO</option>
                </select>
                <script type="text/javascript">
                    $(function(){
                        $('#eoa').jqxDropDownList({
                            width: '100%',
                            height: '25px'
                        });
                        $("#eoa").jqxDropDownList('selectItem','<?php echo $paciente->eoa;  ?>');
                        $("#eoa").on('change',function(){
                            var val = $("#eoa").val();
                            $.post('php/db/update/paciente_datos_nacimiento.php',{
                                rut:'<?php echo $rut; ?>',
                                val:val,
                                column:'EOA',
                                fecha_registro:'<?php echo $fecha_registro; ?>'

                            },function(data){
                                alertaLateral(data);
                                $('.tooltipped').tooltip({delay: 50});
                            });

                        });
                        $('.tooltipped').tooltip({delay: 50});
                    });
                </script>
            </div>
        </div>
    </div>
</div>

<div class="col l12 s12 m12">
    <div class="card-panel yellow darken-4">
        <div class="row">
            <div class="col l3">
                <span class="white-text letra_datos_nacimiento">PKU <strong class="tooltipped" style="cursor: help" data-position="bottom" data-delay="50" data-tooltip="PESO EDAD">(?)</strong></span>
            </div>
            <div class="col l8">
                <select name="pku" id="pku">
                    <option><?php echo $paciente->pku;  ?></option>
                    <option>SI</option>
                    <option>NO</option>
                </select>
                <script type="text/javascript">
                    $(function(){
                        $('#pku').jqxDropDownList({
                            width: '100%',
                            height: '25px'
                        });
                        $("#pku").jqxDropDownList('selectItem','<?php echo $paciente->pku;  ?>');
                        $("#pku").on('change',function(){
                            var val = $("#pku").val();
                            $.post('php/db/update/paciente_datos_nacimiento.php',{
                                rut:'<?php echo $rut; ?>',
                                val:val,
                                column:'PKU',
                                fecha_registro:'<?php echo $fecha_registro; ?>'

                            },function(data){
                                alertaLateral(data);
                                $('.tooltipped').tooltip({delay: 50});
                            });

                        });
                        $('.tooltipped').tooltip({delay: 50});
                    });
                </script>
            </div>
        </div>
    </div>
</div>

<div class="col l12 s12 m12">
    <div class="card-panel yellow darken-4">
        <div class="row">
            <div class="col l3">
                <span class="white-text letra_datos_nacimiento">HC <strong class="tooltipped" style="cursor: help" data-position="bottom" data-delay="50" data-tooltip="PESO EDAD">(?)</strong></span>
            </div>
            <div class="col l8">
                <select name="hc" id="hc">
                    <option><?php echo $paciente->hc;  ?></option>
                    <option>SI</option>
                    <option>NO</option>
                </select>
                <script type="text/javascript">
                    $(function(){
                        $('#hc').jqxDropDownList({
                            width: '100%',
                            height: '25px'
                        });
                        $("#hc").jqxDropDownList('selectItem','<?php echo $paciente->hc;  ?>');
                        $("#hc").on('change',function(){
                            var val = $("#hc").val();
                            $.post('php/db/update/paciente_datos_nacimiento.php',{
                                rut:'<?php echo $rut; ?>',
                                val:val,
                                column:'HC',
                                fecha_registro:'<?php echo $fecha_registro; ?>'

                            },function(data){
                                alertaLateral(data);
                                $('.tooltipped').tooltip({delay: 50});
                            });

                        });
                        $('.tooltipped').tooltip({delay: 50});
                    });
                </script>
            </div>
        </div>
    </div>
</div>

<div class="col l12 s12 m12">
    <div class="card-panel yellow darken-4">
        <div class="row">
            <div class="col l3">
                <span class="white-text letra_datos_nacimiento">APEGO INMEDIATO <strong class="tooltipped" style="cursor: help" data-position="bottom" data-delay="50" data-tooltip="PESO EDAD">(?)</strong></span>
            </div>
            <div class="col l8">
                <select name="apego_inmediato" id="apego_inmediato">
                    <option><?php echo $paciente->apego_inmediato;  ?></option>
                    <option>SI</option>
                    <option>NO</option>
                </select>
                <script type="text/javascript">
                    $(function(){
                        $('#apego_inmediato').jqxDropDownList({
                            width: '100%',
                            height: '25px'
                        });
                        $("#apego_inmediato").jqxDropDownList('selectItem','<?php echo $paciente->apego_inmediato;  ?>');
                        $("#apego_inmediato").on('change',function(){
                            var val = $("#apego_inmediato").val();
                            $.post('php/db/update/paciente_datos_nacimiento.php',{
                                rut:'<?php echo $rut; ?>',
                                val:val,
                                column:'APEGO_INMEDIATO',
                                fecha_registro:'<?php echo $fecha_registro; ?>'

                            },function(data){
                                alertaLateral(data);
                                $('.tooltipped').tooltip({delay: 50});
                            });

                        });
                        $('.tooltipped').tooltip({delay: 50});
                    });
                </script>
            </div>
        </div>
    </div>
</div>

<div class="col l12 s12 m12">
    <div class="card-panel yellow darken-4">
        <div class="row">
            <div class="col l3">
                <span class="white-text letra_datos_nacimiento">VACUNA BCG <strong class="tooltipped" style="cursor: help" data-position="bottom" data-delay="50" data-tooltip="PESO EDAD">(?)</strong></span>
            </div>
            <div class="col l8">
                <select name="vacuna_bcg" id="vacuna_bcg">
                    <option><?php echo $paciente->vacuna_bcg;  ?></option>
                    <option>SI</option>
                    <option>NO</option>
                </select>
                <script type="text/javascript">
                    $(function(){
                        $('#vacuna_bcg').jqxDropDownList({
                            width: '100%',
                            height: '25px'
                        });
                        $("#vacuna_bcg").jqxDropDownList('selectItem','<?php echo $paciente->vacuna_bcg;  ?>');
                        $("#vacuna_bcg").on('change',function(){
                            var val = $("#vacuna_bcg").val();
                            $.post('php/db/update/paciente_datos_nacimiento.php',{
                                rut:'<?php echo $rut; ?>',
                                val:val,
                                column:'VACUNA_BCG',
                                fecha_registro:'<?php echo $fecha_registro; ?>'

                            },function(data){
                                alertaLateral(data);
                                $('.tooltipped').tooltip({delay: 50});
                            });

                        });
                        $('.tooltipped').tooltip({delay: 50});
                    });
                </script>
            </div>
        </div>
    </div>
</div>
<div class="col l12 s12 m12">
    <div class="card-panel yellow darken-4">
        <div class="row">
            <div class="col l3">
                <span class="white-text letra_datos_nacimiento">VACUNA HEPATITIS B <strong class="tooltipped" style="cursor: help" data-position="bottom" data-delay="50" data-tooltip="INDICAR SI EL PACIENTE RECIBIO LA VACUNA DE HEPATITIS B">(?)</strong></span>
            </div>
            <div class="col l8">
                <select name="vacuna_hp" id="vacuna_hp">
                    <option><?php echo $paciente->vacuna_bcg;  ?></option>
                    <option>SI</option>
                    <option>NO</option>
                </select>
                <script type="text/javascript">
                    $(function(){
                        $('#vacuna_hp').jqxDropDownList({
                            width: '100%',
                            height: '25px'
                        });
                        $("#vacuna_hp").jqxDropDownList('selectItem','<?php echo $paciente->vacuna_bcg;  ?>');
                        $("#vacuna_hp").on('change',function(){
                            var val = $("#vacuna_hp").val();
                            $.post('php/db/update/paciente_datos_nacimiento.php',{
                                rut:'<?php echo $rut; ?>',
                                val:val,
                                column:'VACUNA_HP',
                                fecha_registro:'<?php echo $fecha_registro; ?>'

                            },function(data){
                                alertaLateral(data);
                                $('.tooltipped').tooltip({delay: 50});
                            });

                        });
                        $('.tooltipped').tooltip({delay: 50});
                    });
                </script>
            </div>
        </div>
    </div>
</div>
