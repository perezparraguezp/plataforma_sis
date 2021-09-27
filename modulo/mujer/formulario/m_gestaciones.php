<?php

include "../../../php/config.php";
include "../../../php/objetos/persona.php";
include "../../../php/objetos/profesional.php";

$rut = $_POST['rut'];
$fecha_registro = $_POST['fecha_registro'];

$paciente = new persona($rut);
$id_gestacion = $paciente->getIdGestacion();
if($id_gestacion==0){
    $paciente->crearGestacion();;
    $id_gestacion = $paciente->getIdGestacion();
}

$riesgo_biopsicosocial = $paciente->getParametroGestacion_M('riesgo_biopsicosocial',$id_gestacion);

$estado_gestacion = $paciente->getEstadoGestacion($id_gestacion);

?>

<form name="m_gestaciones_form" class="row">
    <input type="hidden" name="id_gestacion" id="id_gestacion" value="<?php echo $id_gestacion; ?>" />
    <div class="col l4 m6 s12">
        <div class="row">
            <div class="col l12 m12 s12">
                <div class="card-panel green lighten-2">
                    <div class="row">
                        <div class="col l12 s12 m12"><strong>HISTORIAL DE GESTACIONES</strong></div>
                    </div>
                    <hr class="row" />
                    <div class="row">
                        <div class="col l12 s12 m12" style="cursor: pointer;font-size: 2em;">
                            <div class="card-panel blue darken-3 white-text" onclick="boxGestionGestacion_Finalizada('EXITOSA')">
                                EXITOSAS <?php echo $paciente->getTotalGestaciones('EXITOSA'); ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col l12 s12 m12" style="cursor: pointer;font-size: 2em;">
                            <div class="card-panel  red darken-3 white-text" onclick="boxGestionGestacion_Finalizada('INTERRUMPIDA')">
                                INTERRUMPIDAS <?php echo $paciente->getTotalGestaciones('INTERRUMPIDA'); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col l8 m6 s12">
        <div class="card-panel deep-purple lighten-4">
            <div class="row">
                <div class="col l8 s8 m8">
                    <div class="row">
                        <div class="col l12">
                            <input type="checkbox" id="gestacion_<?php echo $id_gestacion; ?>"
                                   onchange="updateActivarGestacion('<?php echo $id_gestacion; ?>')"
                                <?php echo $estado_gestacion=='ACTIVA'?'checked="checked"':'' ?>
                                   name="gestacion_<?php echo $id_gestacion; ?>"  />
                            <label class="white-text" for="gestacion_<?php echo $id_gestacion; ?>">ACTIVAR GESTACIÓN</label>
                        </div>
                    </div>
                </div>
            </div>
            <hr class="row" />
            <div id="formulario_gestacion_<?php echo $id_gestacion; ?>" style="display: none;">

                <div class="row">
                    <div class="col l4 s4 m4 right right-align"
                         onclick="boxGestionGestacion()"
                         style="cursor: pointer;">
                        <i class="mdi-file-folder-shared blue-text"></i>
                        <label style="color: blue;">CONFIGURAR GESTACIÓN</label>
                    </div>
                </div>
                <div class="">
                    <div class="row">
                        <div class="col l6 card-panel lime lighten-2" style="padding: 10px;">
                            <div class="row">
                                <div class="col l12">
                                    <div class="row">
                                        <div class="col l0 s0 m10">RIESGO BIOPSICOSOCIAL</div>
                                        <div class="col l2 s2 m2 right-align">
                                            <i class="mdi-editor-insert-chart"
                                               onclick="loadHistorialGestacion('<?php echo $rut ?>','riesgo_biopsicosocial','<?php echo $id_gestacion; ?>')"></i>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col l12 s12 m12">
                                            <select name="riesgo_biopsicosocial" id="riesgo_biopsicosocial">
                                                <option><?php echo $riesgo_biopsicosocial; ?></option>
                                                <option disabled="disabled">-------------------------------------</option>
                                                <option>SIN RIESGO</option>
                                                <option>CON RIESGO BIOPSICOSOCIAL</option>
                                                <option>PRESENTA VIOLENCIA DE GENERO</option>
                                                <option>PRESENTA ARO (alto riesgo obstétrico)</option>
                                            </select>
                                            <script type="text/javascript">
                                                $(function(){
                                                    $('.tooltipped').tooltip({delay: 50});
                                                    $('#riesgo_biopsicosocial').jqxDropDownList({
                                                        width: '100%',
                                                        theme: 'eh-open',
                                                        height: '25px'
                                                    });
                                                    if($("#riesgo_biopsicosocial").val() !=='SIN RIESGO'){
                                                        $('#div_vdi').show();
                                                    }else{
                                                        $('#div_vdi').hide();
                                                    }
                                                    $("#riesgo_biopsicosocial").on('change',function(){
                                                        boxRegistrarBiopsicosocial();
                                                    });
                                                })
                                            </script>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col l6 card-panel lime lighten-2" style="padding: 10px;">
                            <div class="row">
                                <div class="col l12">
                                    <div class="row">
                                        <div class="col l0 s0 m10">IMC GESTACIONAL</div>
                                        <div class="col l2 s2 m2 right-align">
                                            <i class="mdi-editor-insert-chart"
                                               onclick="loadHistorialGestacion('<?php echo $rut ?>','imc_gestacional','<?php echo $id_gestacion; ?>')"></i>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col l12 s12 m12">
                                            <select name="imc_gestacional" id="imc_gestacional">
                                                <option></option>
                                                <option>OBESA</option>
                                                <option>SOBREPESO</option>
                                                <option>NORMAL</option>
                                                <option>BAJO PESO</option>
                                            </select>
                                            <script type="text/javascript">
                                                $(function(){
                                                    $('.tooltipped').tooltip({delay: 50});
                                                    $('#imc_gestacional').jqxDropDownList({
                                                        width: '100%',
                                                        theme: 'eh-open',
                                                        height: '25px'
                                                    });

                                                    $("#imc_gestacional").on('change',function(){
                                                        $.post('db/update/m_gestante.php',{
                                                            id_gestacion:'<?php echo $id_gestacion; ?>',
                                                            value:$('#imc_gestacional').val(),
                                                            column:'imc_gestacional',
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
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">

                    </div>
                </div>
                <!-- otro riesgo -->
                <div class="card-panel lime lighten-2" id="div_vdi" style="display:none;">
                    <div class="row">
                        <div class="col l8 s8 m8">VDI CON RIESGO</div>
                        <div class="col l4 s4 m4">
                            <div class="btn blue" onclick="boxNewVDI()"> + AGREGAR </div>
                        </div>
                    </div>
                    <hr />
                    <div class="row" >
                        <div class="col l10 s10 m10">OBSERVACIONES</div>
                        <div class="col l2 s2 m2">FECHA</div>
                    </div>
                    <?php
                    $sql1 = "select * from visita_vdi 
                                where rut='$rut' 
                                  and id_gestacion='$id_gestacion' 
                                  order by id_gestacion desc";

                    $res1 = mysql_query($sql1);
                    while($row1 = mysql_fetch_array($res1)){
                        $profesional = new profesional($row1['id_profesional']);
                        $obs = "Profesional: ".$profesional->nombre.'<br />'.$row1['obs_vdi'];
                        ?>
                        <div class="row" style="border: dotted 1px black;padding: 2px;">
                            <div class="col l10 s10 m10"><?php echo $obs; ?></div>
                            <div class="col l2 s2 m2"><?php echo fechaNormal($row1['fecha_vdi']); ?></div>
                        </div>
                        <?php
                    }
                    ?>
                </div>
                <!--CONTROLES NUTRICIONALES -->
                <div class="card-panel lime lighten-2">
                    <div class="row">
                        <div class="col l8 s8 m8">CONTROL NUTRICIONAL</div>
                        <div class="col l4 s4 m4">
                            <div class="btn blue" onclick="boxNewNutri()"> + AGREGAR </div>
                        </div>
                    </div>
                    <hr />
                    <div class="row"  >
                        <div class="col l4 s4 m4">TIPO CONTROL</div>
                        <div class="col l4 s4 m4">IMC</div>
                        <div class="col l4 s4 m4">FECHA</div>
                    </div>
                    <hr />
                    <?php
                    $sql1 = "select * from control_nutricional_gestacion 
                                    where id_gestacion='$id_gestacion' 
                                        order by id_control desc";

                    $res1 = mysql_query($sql1);
                    while($row1 = mysql_fetch_array($res1)){
                        ?>
                        <div class="row tooltipped"  style="border: dotted 1px black;padding: 2px;" style="cursor: help"
                             data-position="bottom" data-delay="50"
                             data-tooltip="<?php echo $row1['obs_control']; ?>">
                            <div class="col l4 s4 m4"><?php echo $row1['tipo_control']; ?></div>
                            <div class="col l4 s4 m4"><?php echo $row1['imc']; ?></div>
                            <div class="col l4 s4 m4"><?php echo fechaNormal($row1['fecha_control']); ?></div>
                        </div>
                        <?php
                    }
                    ?>
                </div>
                <!--ECOGRAFIAS DE CONTROL -->
                <div class="card-panel lime lighten-2">
                    <div class="row">
                        <div class="col l8 s8 m8">ECOGRAFIAS DE CONTROL</div>
                        <div class="col l4 s4 m4">
                            <div class="btn blue" onclick="boxNewEco()"> + AGREGAR </div>
                        </div>
                    </div>
                    <hr />
                    <?php
                    $sql1 = "select * from ecografias_mujer where rut='$rut' and id_gestacion='$id_gestacion' order by id_ecografia desc";
                    $res1 = mysql_query($sql1);
                    while($row1 = mysql_fetch_array($res1)){
                        ?>
                        <div class="row"  style="border: dotted 1px black;padding: 2px;">
                            <div class="col l4 s4 m4"><?php echo $row1['trimestre'].' TRIMESTRE'; ?></div>
                            <div class="col l4 s4 m4"><?php echo $row1['tipo_eco']; ?></div>
                            <div class="col l4 s4 m4"><?php echo fechaNormal($row1['fecha_eco']); ?></div>
                        </div>
                        <?php
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</form>
<script type="text/javascript">
    $(function () {
        $('.tooltipped').tooltip({delay: 50});
        //$("#imc_<?php //echo strtolower($imc); ?>//").attr('checked','checked');
        <?php
        if($estado_gestacion=='ACTIVA'){
            echo '$("#gestacion_'.$id_gestacion.'").attr("checked","checked");';
            echo '$("#form_gestacion_'.$id_gestacion.'").show();';
            echo '$("#formulario_gestacion_'.$id_gestacion.'").show();';
        }else{
            echo '$("#form_gestacion_'.$id_gestacion.'").hide();';
            echo '$("#formulario_gestacion_'.$id_gestacion.'").hide();';
        }
        ?>

    });
    function updateActivarGestacion(id){
        var value = '';
        if($('#gestacion_'+id).prop('checked')){
            value = 'ACTIVA';
            $("#form_gestacion_"+id).show();
            $("#formulario_gestacion_"+id).show();
        }else{
            value = 'NO ACTIVA';
            $("#form_gestacion_"+id).hide();
            $("#formulario_gestacion_"+id).hide();
        }
        $.post('db/update/update_gestacion.php',{
            id_gestacion:id,
            value:value,
            fecha_registro:'<?php echo $fecha_registro; ?>',
            rut:'<?php echo $rut ?>'
        },function (data) {
            alertaLateral(data);
        });
    }

    function boxNewEco(){
        $.post('formulario/new_ecografia.php',{
            rut:'<?php echo $rut ?>',
            id_gestacion:$("#id_gestacion").val(),
            fecha_registro:'<?php echo $fecha_registro ?>',
        },function(data){
            if(data !== 'ERROR_SQL'){
                $("#modal").html(data);
                $("#modal").css({'width':'800px'});
                document.getElementById("btn-modal").click();
            }
        });
    }
    function boxRegistrarBiopsicosocial(){
        var val = $("#riesgo_biopsicosocial").val();
        $.post('formulario/new_biopsicosocial.php',{
            rut:'<?php echo $rut ?>',
            val:val,
            id_gestacion:$("#id_gestacion").val(),
            fecha_registro:'<?php echo $fecha_registro ?>',
            column:'riesgo_biopsicosocial',
        },function(data){
            if(data !== 'ERROR_SQL'){
                $("#modal").html(data);
                $("#modal").css({'width':'800px'});
                document.getElementById("btn-modal").click();
            }
        });
    }
    function boxNewNutri(){
        $.post('formulario/new_nutri_gestacion.php',{
            rut:'<?php echo $rut ?>',
            id_gestacion:$("#id_gestacion").val(),
            fecha_registro:'<?php echo $fecha_registro ?>',
        },function(data){
            if(data !== 'ERROR_SQL'){
                $("#modal").html(data);
                $("#modal").css({'width':'800px'});
                document.getElementById("btn-modal").click();
            }
        });
    }
    function boxNewVDI(){
        $.post('formulario/new_vdi.php',{
            rut:'<?php echo $rut ?>',
            id_gestacion:$("#id_gestacion").val(),
            fecha_registro:'<?php echo $fecha_registro ?>',
        },function(data){
            if(data !== 'ERROR_SQL'){
                $("#modal").html(data);
                $("#modal").css({'width':'800px'});
                document.getElementById("btn-modal").click();
            }
        });
    }
    function boxGestionGestacion(){
        $.post('formulario/gestacion.php',{
            rut:'<?php echo $rut ?>',
            id_gestacion:$("#id_gestacion").val(),
            fecha_registro:'<?php echo $fecha_registro ?>',
        },function(data){
            if(data !== 'ERROR_SQL'){
                $("#modal").html(data);
                $("#modal").css({'width':'800px'});
                document.getElementById("btn-modal").click();
            }
        });
    }
    function boxGestionGestacion_Finalizada(tipo){
        $.post('formulario/ver_gestaciones.php',{
            rut:'<?php echo $rut ?>',
            tipo:tipo,
            fecha_registro:'<?php echo $fecha_registro ?>',
        },function(data){
            if(data !== 'ERROR_SQL'){
                $("#modal").html(data);
                $("#modal").css({'width':'800px'});
                document.getElementById("btn-modal").click();
            }
        });
    }
    function loadHistorialGestacion(rut,indicador,id) {
        $.post('grid/historial_gestacion.php',{
            rut:rut,
            indicador:indicador,
            id_gestacion:id
        },function(data){
            if(data !== 'ERROR_SQL'){
                $("#modal").html(data);
                $("#modal").css({'width':'800px'});
                document.getElementById("btn-modal").click();
            }
        });
    }
</script>