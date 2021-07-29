<?php
include "../../config.php";
include '../../objetos/persona.php';
$rut = str_replace('.','',$_POST['rut']);

$fecha_registro = $_POST['fecha_registro'];

$paciente = new persona($rut);
$paciente->loadAntropometria();


?>
<input type="hidden" name="rut" value="<?php echo $rut; ?>" />
<input type="hidden" name="fecha_registro" id="fecha_registro" value="<?php echo $fecha_registro; ?>" />
<?php
if($paciente->validaNutricionista()==true){
    ?>
    <div class="row">
        <div class="col l12 card-panel light-green lighten-4" style="padding-top: 10px;padding-bottom: 10px;">
            <div class="col l4">EVALUACIÓN NUTRICIONISTA</div>
            <div class="col l8">
                <select name="eval_nutricionista" id="eval_nutricionista">
                    <option></option>
                    <option>5 MESES</option>
                    <option>3 AÑOS 6 MESES</option>
                </select>
                <script type="text/javascript">
                    $(function(){
                        $('#eval_nutricionista').jqxDropDownList({
                            width: '100%',
                            height: '25px'
                        });

                        $("#peval_nutricionistae").on('change',function(){
                            var val = $("#eval_nutricionista").val();
                            $.post('php/db/update/paciente_antropometria.php',{
                                rut:'<?php echo $rut; ?>',
                                val:val,
                                column:'EVAL_NUTRICIONISTA',
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
<?php
}
?>
<div class="row">
    <div class="col l12 m12 s12">
        <?php
        if($paciente->validaPE()){
            ?>
            <div class="col l4 s12 m6">
                <div class="card-panel yellow darken-4">
                    <div class="row">
                        <div class="col l3">
                            <span class="white-text">PE <strong class="tooltipped" style="cursor: help" data-position="bottom" data-delay="50" data-tooltip="PESO EDAD">(?)</strong></span>
                        </div>
                        <div class="col l8">
                            <select name="pe" id="pe">
                                <option></option>
                                <option value="2">2</option>
                                <option value="1">1</option>
                                <option value="N">N</option>
                                <option value="-1">-1</option>
                                <option value="-2">-2</option>
                            </select>
                            <script type="text/javascript">
                                $(function(){
                                    $('#pe').jqxDropDownList({
                                        width: '100%',
                                        height: '25px'
                                    });

                                    $("#pe").on('change',function(){
                                        var val = $("#pe").val();
                                        $.post('php/db/update/paciente_antropometria.php',{
                                            rut:'<?php echo $rut; ?>',
                                            val:val,
                                            column:'PE',
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
                        <div class="col l1">
                            <i class="mdi-editor-insert-chart"
                               onclick="loadModalGraficoAntropometria('<?php echo $rut ?>','PE')"></i>
                        </div>

                    </div>
                </div>
            </div>
            <?php
        }
        ?>
        <?php
        if($paciente->validaPT()){
            ?>
            <div class="col l4 s12 m6">
                <div class="card-panel yellow darken-4">
                    <div class="row">
                        <div class="col l3">
                            <span class="white-text">PT <strong class="tooltipped" style="cursor: help" data-position="bottom" data-delay="50" data-tooltip="PESO TALLA">(?)</strong></span>
                        </div>
                        <div class="col l8">
                            <select name="pt" id="pt">
                                <option></option>
                                <option value="3">3</option>
                                <option value="2">2</option>
                                <option value="1">1</option>
                                <option value="N">N</option>
                                <option value="-1">-1</option>
                                <option value="-2">-2</option>
                            </select>
                            <script type="text/javascript">
                                $(function(){
                                    $('#pt').jqxDropDownList({
                                        width: '100%',
                                        height: '25px'
                                    });

                                    $("#pt").on('change',function(){
                                        var val = $("#pt").val();
                                        $.post('php/db/update/paciente_antropometria.php',{
                                            rut:'<?php echo $rut; ?>',
                                            val:val,
                                            column:'PT',
                                            fecha_registro:'<?php echo $fecha_registro; ?>'

                                        },function(data){
                                            alertaLateral(data);
                                            $('.tooltipped').tooltip({delay: 50});
                                        });

                                    });
                                    $('.tooltipped').tooltip({delay: 50});
                                })
                            </script>
                        </div>
                        <div class="col l1">
                            <i class="mdi-editor-insert-chart"
                               onclick="loadModalGraficoAntropometria('<?php echo $rut ?>','PT')"></i>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        }
        ?>
        <?php
        if($paciente->validaIMCE()){
            ?>
            <div class="col l4 s12 m6">
                <div class="card-panel yellow darken-4">
                    <div class="row">
                        <div class="col l4">
                            <span class="white-text">IMC/E <strong class="tooltipped" style="cursor: help" data-position="bottom" data-delay="50" data-tooltip="INDICE DE MASA CORPORAL / EDAD">(?)</strong></span>
                        </div>
                        <div class="col l7">
                            <select name="imce" id="imce">
                                <option></option>
                                <option value="3">3</option>
                                <option value="2">2</option>
                                <option value="1">1</option>
                                <option value="N">N</option>
                                <option value="-1">-1</option>
                                <option value="-2">-2</option>
                            </select>
                            <script type="text/javascript">
                                $(function(){
                                    $('#imce').jqxDropDownList({
                                        width: '100%',
                                        height: '25px'
                                    });

                                    $("#imce").on('change',function(){
                                        var val = $("#imce").val();
                                        $.post('php/db/update/paciente_antropometria.php',{
                                            rut:'<?php echo $rut; ?>',
                                            val:val,
                                            column:'IMCE',
                                            fecha_registro:'<?php echo $fecha_registro; ?>'

                                        },function(data){
                                            alertaLateral(data);
                                            $('.tooltipped').tooltip({delay: 50});
                                        });

                                    });
                                    $('.tooltipped').tooltip({delay: 50});
                                })
                            </script>
                        </div>
                        <div class="col l1">
                            <i class="mdi-editor-insert-chart"
                               onclick="loadModalGraficoAntropometria('<?php echo $rut ?>','IMCE')"></i>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        }
        ?>
        <?php
        if($paciente->validaTE()){
            ?>
            <div class="col l4 s12 m6">
                <div class="card-panel yellow darken-4">
                    <div class="row">
                        <div class="col l3">
                            <span class="white-text">TE <strong class="tooltipped" style="cursor: help" data-position="bottom" data-delay="50" data-tooltip="TALLA EDAD">(?)</strong></span>
                        </div>
                        <div class="col l8">
                            <select name="te" id="te">
                                <option></option>
                                <option value="2">2</option>
                                <option value="1">1</option>
                                <option  value="N">N</option>
                                <option value="-1">-1</option>
                                <option value="-2">-2</option>
                            </select>
                            <script type="text/javascript">
                                $(function(){
                                    $('#te').jqxDropDownList({
                                        width: '100%',
                                        height: '25px'
                                    });

                                    $("#te").on('change',function(){
                                        var val = $("#te").val();
                                        $.post('php/db/update/paciente_antropometria.php',{
                                            rut:'<?php echo $rut; ?>',
                                            val:val,
                                            column:'TE',
                                            fecha_registro:'<?php echo $fecha_registro; ?>'

                                        },function(data){
                                            alertaLateral(data);
                                            $('.tooltipped').tooltip({delay: 50});
                                        });

                                    });
                                    $('.tooltipped').tooltip({delay: 50});
                                })
                            </script>
                        </div>
                        <div class="col l1">
                            <i class="mdi-editor-insert-chart"
                               onclick="loadModalGraficoAntropometria('<?php echo $rut ?>','TE')"></i>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        }
        ?>
        <?php
        if($paciente->validaDNI()){
            ?>
            <div class="col l4 s12 m6">
                <div class="card-panel yellow darken-4">
                    <div class="row">
                        <div class="col l3">
                            <span class="white-text">DNI <strong class="tooltipped" style="cursor: help" data-position="bottom" data-delay="50" data-tooltip="">(?)</strong></span>
                        </div>
                        <div class="col l8">
                            <select name="dni" id="dni">
                                <option></option>
                                <option>NORMAL</option>
                                <option>SOBREPESO</option>
                                <option>OBESIDAD</option>
                                <option>OB SEVERA</option>
                                <option>Ri DESNUTRICION</option>
                                <option>DESNUTRICION</option>
                            </select>
                            <script type="text/javascript">
                                $(function(){
                                    $('#dni').jqxDropDownList({
                                        width: '100%',
                                        height: '25px'
                                    });

                                    $("#dni").on('change',function(){
                                        var val = $("#dni").val();
                                        $.post('php/db/update/paciente_antropometria.php',{
                                            rut:'<?php echo $rut; ?>',
                                            val:val,
                                            column:'DNI',
                                            fecha_registro:'<?php echo $fecha_registro; ?>'

                                        },function(data){
                                            alertaLateral(data);
                                            $('.tooltipped').tooltip({delay: 50});
                                        });

                                    });
                                    $('.tooltipped').tooltip({delay: 50});
                                })
                            </script>
                        </div>
                        <div class="col l1">
                            <i class="mdi-editor-insert-chart"
                               onclick="loadModalGraficoAntropometria('<?php echo $rut ?>','DNI')"></i>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        }
        ?>
    </div>
</div>
<div class="row">
    <div class="col l12 m12 s12">
        <?php
        if($paciente->validaLME()){
            ?>
            <div class="col l4 s12 m6">
                <div class="card-panel yellow darken-4">
                    <div class="row">
                        <div class="col l3">
                            <span class="white-text">TIPO LACTANCIA <strong class="tooltipped" style="cursor: help" data-position="bottom" data-delay="50" data-tooltip="LME">(?)</strong></span>
                        </div>
                        <div class="col l8">
                            <select name="lme" id="lme">
                                <option></option>
                                <option value="LME">LME</option>
                                <option>SIN LME</option>
                            </select>
                            <script type="text/javascript">
                                $(function(){
                                    $('#lme').jqxDropDownList({
                                        width: '100%',
                                        height: '25px'
                                    });
                                    $("#lme").on('change',function(){
                                        var val = $("#lme").val();
                                        $.post('php/db/update/paciente_antropometria.php',{
                                            rut:'<?php echo $rut; ?>',
                                            val:val,
                                            column:'LME',
                                            fecha_registro:'<?php echo $fecha_registro; ?>'

                                        },function(data){
                                            alertaLateral(data);
                                            $('.tooltipped').tooltip({delay: 50});
                                        });

                                    });
                                    $('.tooltipped').tooltip({delay: 50});
                                })
                            </script>
                        </div>
                        <div class="col l1">
                            <i class="mdi-editor-insert-chart"
                               onclick="loadModalGraficoAntropometria('<?php echo $rut ?>','LME')"></i>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        }
        ?>
        <?php
        if($paciente->validaPCINT()){
            ?>
            <div class="col l4 m6 s12">
                <div class="card-panel yellow darken-4">
                    <div class="row">
                        <div class="col l4">
                            <span class="white-text">PCint/ed <strong class="tooltipped" style="cursor: help" data-position="bottom" data-delay="50" data-tooltip="PERIMETRO CINTURA">(?)</strong></span>
                        </div>
                        <div class="col l7">
                            <select name="pcint" id="pcint">
                                <option></option>
                                <option value="NORMAL">NORMAL</option>
                                <option value="RIESGO OBESIDAD">RIESGO OBESIDAD</option>
                                <option value="OBESIDAD ABDOMINAL">OBESIDAD ABDOMINAL</option>
                            </select>
                            <script type="text/javascript">
                                $(function(){
                                    $('#pcint').jqxDropDownList({
                                        width: '100%',
                                        height: '25px'
                                    });

                                    $("#pcint").on('change',function(){
                                        var val = $("#pcint").val();
                                        $.post('php/db/update/paciente_antropometria.php',{
                                            rut:'<?php echo $rut; ?>',
                                            val:val,
                                            column:'PCINT',
                                            fecha_registro:'<?php echo $fecha_registro; ?>'

                                        },function(data){
                                            alertaLateral(data);
                                            $('.tooltipped').tooltip({delay: 50});
                                        });

                                    });
                                    $('.tooltipped').tooltip({delay: 50});
                                })
                            </script>
                        </div>
                        <div class="col l1">
                            <i class="mdi-editor-insert-chart"
                               onclick="loadModalGraficoAntropometria('<?php echo $rut ?>','PCINT')"></i>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        }
        ?>
        <?php
        if($paciente->validaRIMALNEXCESO()){
            ?>
            <div class="col l4 m6 s12">
                <div class="card-panel yellow darken-4">
                    <div class="row">
                        <div class="col l5">
                            <span class="white-text">Ri MALN EXCESO <strong class="tooltipped" style="cursor: help" data-position="bottom" data-delay="50" data-tooltip="(?)">(?)</strong></span>
                        </div>
                        <div class="col l6">
                            <select name="rimaln" id="rimaln">
                                <option></option>
                                <option >SIN RIESGO</option>
                                <option>CON RIESGO</option>
                            </select>
                            <script type="text/javascript">
                                $(function(){
                                    $('#rimaln').jqxDropDownList({
                                        width: '100%',
                                        height: '25px'
                                    });
                                    $("#rimaln").on('change',function(){
                                        var val = $("#rimaln").val();
                                        $.post('php/db/update/paciente_antropometria.php',{
                                            rut:'<?php echo $rut; ?>',
                                            val:val,
                                            column:'RIMALN',
                                            fecha_registro:'<?php echo $fecha_registro; ?>'

                                        },function(data){
                                            alertaLateral(data);
                                            $('.tooltipped').tooltip({delay: 50});
                                        });

                                    });
                                    $('.tooltipped').tooltip({delay: 50});
                                })
                            </script>
                        </div>
                        <div class="col l1">
                            <i class="mdi-editor-insert-chart"
                               onclick="loadModalGraficoAntropometria('<?php echo $rut ?>','RIMALN')"></i>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        }
        ?>
        <?php
        if($paciente->validaPRESIONARTERIAL()){
            ?>
            <div class="col l4 m6 s12">
                <div class="card-panel yellow darken-4">
                    <div class="row">
                        <div class="col l4">
                            <span class="white-text" style="font-size: 0.8em;">PRESION ARTERIAL <strong class="tooltipped" style="cursor: help" data-position="bottom" data-delay="50" data-tooltip="PRESION ARTERIAL">(?)</strong></span>
                        </div>
                        <div class="col l7">
                            <select name="presion_arterial" id="presion_arterial">
                                <option></option>
                                <option >NORMAL</option>
                                <option>PRE-HIPERTENSION</option>
                                <option>ETAPA 1</option>
                                <option>ETAPA 2</option>
                            </select>
                            <script type="text/javascript">

                                $(function(){
                                    $('#presion_arterial').jqxDropDownList({
                                        width: '100%',
                                        height: '25px'
                                    });

                                    $("#presion_arterial").on('change',function(){
                                        var val = $("#presion_arterial").val();
                                        $.post('php/db/update/paciente_antropometria.php',{
                                            rut:'<?php echo $rut; ?>',
                                            val:val,
                                            column:'presion_arterial',
                                            fecha_registro:'<?php echo $fecha_registro; ?>'

                                        },function(data){
                                            alertaLateral(data);
                                            $('.tooltipped').tooltip({delay: 50});
                                        });

                                    });
                                    $('.tooltipped').tooltip({delay: 50});
                                })
                            </script>
                        </div>
                        <div class="col l1">
                            <i class="mdi-editor-insert-chart"
                               onclick="loadModalGraficoAntropometria('<?php echo $rut ?>','presion_arterial')"></i>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        }
        ?>
    </div>
</div>
<div class="row">
    <div class="col l12 m12 s12">
        <?php
        if($paciente->validaPerimetroCraneal()){
            ?>
            <div class="col l4 m6 s12">
                <div class="card-panel yellow darken-4">
                    <div class="row">
                        <div class="col l3">
                            <span class="white-text">PERIMETRO CRANEAL <strong class="tooltipped" style="cursor: help" data-position="bottom" data-delay="50" data-tooltip="RIESGO IRA PARA MENORES DE 3 AÑOS">(?)</strong></span>
                        </div>
                        <div class="col l8">
                            <select name="perimetro_craneal" id="perimetro_craneal">
                                <option></option>
                                <option>2</option>
                                <option>1</option>
                                <option>N</option>
                                <option>-1</option>
                                <option>-2</option>
                            </select>
                            <script type="text/javascript">
                                $(function(){
                                    $('#perimetro_craneal').jqxDropDownList({
                                        width: '100%',
                                        height: '25px'
                                    });


                                    $("#perimetro_craneal").on('change',function(){
                                        var val = $("#perimetro_craneal").val();
                                        $.post('php/db/update/paciente_antropometria.php',{
                                            rut:'<?php echo $rut; ?>',
                                            val:val,
                                            column:'perimetro_craneal',
                                            fecha_registro:'<?php echo $fecha_registro; ?>'

                                        },function(data){
                                            alertaLateral(data);
                                            $('.tooltipped').tooltip({delay: 50});
                                        });

                                    });
                                    $('.tooltipped').tooltip({delay: 50});
                                })
                            </script>
                        </div>
                        <div class="col l1">
                            <i class="mdi-editor-insert-chart"
                               onclick="loadModalGraficoAntropometria('<?php echo $rut ?>','SCORE_IRA')"></i>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        }
        ?>
        <?php

        if($paciente->valida_AgudezaVisual()){
            ?>
            <div class="col l4 m6 s12">
                <div class="card-panel yellow darken-4">
                    <div class="row">
                        <div class="col l3">
                            <span class="white-text">AGUDEZA VIDUAL <strong class="tooltipped" style="cursor: help" data-position="bottom" data-delay="50" data-tooltip="RIESGO IRA PARA MENORES DE 3 AÑOS">(?)</strong></span>
                        </div>
                        <div class="col l8">
                            <select name="agudeza_visual" id="agudeza_visual">
                                <option></option>
                                <option disabled>---------</option>
                                <option>NORMAL</option>
                                <option>ALTERADA</option>
                            </select>
                            <script type="text/javascript">
                                $(function(){
                                    $('#agudeza_visual').jqxDropDownList({
                                        width: '100%',
                                        height: '25px'
                                    });


                                    $("#agudeza_visual").on('change',function(){
                                        var val = $("#agudeza_visual").val();
                                        $.post('php/db/update/paciente_antropometria.php',{
                                            rut:'<?php echo $rut; ?>',
                                            val:val,
                                            column:'agudeza_visual',
                                            fecha_registro:'<?php echo $fecha_registro; ?>'

                                        },function(data){
                                            alertaLateral(data);
                                            $('.tooltipped').tooltip({delay: 50});
                                        });

                                    });
                                    $('.tooltipped').tooltip({delay: 50});
                                })
                            </script>
                        </div>
                        <div class="col l1">
                            <i class="mdi-editor-insert-chart"
                               onclick="loadModalGraficoAntropometria('<?php echo $rut ?>','SCORE_IRA')"></i>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        }
        ?>
        <?php
        if($paciente->valida_AgudezaVisual()){
            ?>
            <div class="col l4 m6 s12">
                <div class="card-panel yellow darken-4">
                    <div class="row">
                        <div class="col l3">
                            <span class="white-text">EV. AUDITIVA <strong class="tooltipped" style="cursor: help" data-position="bottom" data-delay="50" data-tooltip="RIESGO IRA PARA MENORES DE 3 AÑOS">(?)</strong></span>
                        </div>
                        <div class="col l8">
                            <select name="evaluacion_auditiva" id="evaluacion_auditiva">
                                <option></option>
                                <option disabled>---------</option>
                                <option>NORMAL</option>
                                <option>ALTERADA</option>
                            </select>
                            <script type="text/javascript">
                                $(function(){
                                    $('#evaluacion_auditiva').jqxDropDownList({
                                        width: '100%',
                                        height: '25px'
                                    });


                                    $("#evaluacion_auditiva").on('change',function(){
                                        var val = $("#evaluacion_auditiva").val();
                                        $.post('php/db/update/paciente_antropometria.php',{
                                            rut:'<?php echo $rut; ?>',
                                            val:val,
                                            column:'evaluacion_auditiva',
                                            fecha_registro:'<?php echo $fecha_registro; ?>'

                                        },function(data){
                                            alertaLateral(data);
                                            $('.tooltipped').tooltip({delay: 50});
                                        });

                                    });
                                    $('.tooltipped').tooltip({delay: 50});
                                })
                            </script>
                        </div>
                        <div class="col l1">
                            <i class="mdi-editor-insert-chart"
                               onclick="loadModalGraficoAntropometria('<?php echo $rut ?>','SCORE_IRA')"></i>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        }
        ?>
    </div>
</div>
<div class="row">
    <div class="col l12 m12 s12">
        <?php
        if($paciente->validaIRA()){
        ?>
        <div class="col l12 s12 m12">
            <div class="card-panel red darken-4">
                <div class="row">
                    <div class="col l3">
                        <span class="white-text">SCORE IRA <strong class="tooltipped" style="cursor: help" data-position="bottom" data-delay="50" data-tooltip="RIESGO IRA PARA MENORES DE 7 MESES">(?)</strong></span>
                    </div>
                    <div class="col l8">
                        <select name="ira" id="ira">
                            <option></option>
                            <option>LEVE</option>
                            <option>MODERADO</option>
                            <option>GRAVE</option>
                        </select>
                        <script type="text/javascript">
                            $(function(){
                                $('#ira').jqxDropDownList({
                                    width: '100%',
                                    height: '25px'
                                });

                                $("#ira").on('change',function(){
                                    var val = $("#ira").val();
                                    $.post('php/db/update/paciente_antropometria.php',{
                                        rut:'<?php echo $rut; ?>',
                                        val:val,
                                        column:'SCORE_IRA',
                                        fecha_registro:'<?php echo $fecha_registro; ?>'

                                    },function(data){
                                        alertaLateral(data);
                                        $('.tooltipped').tooltip({delay: 50});
                                    });
                                    if(val!=='LEVE'){
                                        $('#IRA_ATENCION').show("swing");
                                    }else{
                                        $('#IRA_ATENCION').hide("swing");
                                    }

                                });
                                $('.tooltipped').tooltip({delay: 50});
                            })
                        </script>
                    </div>
                    <div class="col l1">
                        <i class="mdi-editor-insert-chart"
                           onclick="loadModalGraficoAntropometria('<?php echo $rut ?>','SCORE_IRA')"></i>
                    </div>
                </div>
                <div class="row" id="IRA_ATENCION" style="display: none">
                    <p class="white-text">INDICAR SI TIENE VISITA POR PROFESIONAL KINESIOLOGO</p>
                    <div class="col l3">
                        <span class="white-text">TIENE VISITA <strong class="tooltipped" style="cursor: help" data-position="bottom" data-delay="50" data-tooltip="RIESGO IRA PARA MENORES DE 7 MESES">(?)</strong></span>
                    </div>
                    <div class="col l8">
                        <select name="ira_visita" id="ira_visita">
                            <option></option>
                            <option>SI</option>
                            <option>NO</option>
                        </select>
                        <script type="text/javascript">
                            $(function(){
                                $('#ira_visita').jqxDropDownList({
                                    width: '100%',
                                    height: '25px'
                                });

                                $("#ira_visita").on('change',function(){
                                    var val = $("#ira_visita").val();
                                    $.post('php/db/update/paciente_antropometria.php',{
                                        rut:'<?php echo $rut; ?>',
                                        val:val,
                                        column:'VISITA_SCORE',
                                        fecha_registro:'<?php echo $fecha_registro; ?>'

                                    },function(data){
                                        alertaLateral(data);
                                        $('.tooltipped').tooltip({delay: 50});
                                    });

                                });
                                $('.tooltipped').tooltip({delay: 50});
                            })
                        </script>
                    </div>
                </div>
            </div>
            <?php
            }
            ?>
        </div>
    </div>
</div>
<style type="text/css">
    .btn:hover{
        background-color: #3fff7f;
    }
</style>
<script type="text/javascript">
    function loadModalGraficoAntropometria(rut,indicador){
        $.post('php/modal/graficos/antropometria.php',{
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