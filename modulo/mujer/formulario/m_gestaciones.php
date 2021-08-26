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
                        <div class="col l12 s12 m12">GESTACIONES EXITOSAS</div>
                    </div>
                    <div class="row">
                        <div class="col l12 s12 m12">GESTACIONES INTERRUMPIDAS</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col l8 m6 s12">
        <div class="card-panel deep-purple lighten-4">
            <div class="row">
                <div class="col l8 s8 m8"><strong>GESTACIÓN ACTIVA</strong></div>
                <div class="col l4 s4 m4"
                     onclick="boxGestionGestacion()"
                     style="cursor: pointer;">
                    <i class="mdi-file-folder-shared blue-text"></i>
                    <label style="color: blue;">CONFIGURAR GESTACIÓN</label>
                </div>
            </div>
            <hr class="row" />
            <div class="row">
                <div class="card-panel lime lighten-2">
                    <div class="row">
                        <div class="col l0 s0 m10">RIESGO BIOPSICOSOCIAL</div>
                        <div class="col l2 s2 m2 center-align">
                            <i class="mdi-editor-insert-chart"
                               onclick="loadHistorialGestacion('<?php echo $rut ?>','riesgo_biopsicosocial')"></i>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col l12 s12 m12">
                            <select name="riesgo_biopsicosocial" id="riesgo_biopsicosocial">
                                <option></option>
                                <option>CON RIESGO BIOPSICOSOCIAL</option>
                                <option>PRESENTA VIOLENCIA DE GENERO</option>
                                <option>PRESENTA ARO (alto riesgo obstétrico)</option>
                            </select>
                            <script type="text/javascript">
                                $(function(){
                                    $('#riesgo_biopsicosocial').jqxDropDownList({
                                        width: '100%',
                                        height: '25px'
                                    });
                                    $("#riesgo_biopsicosocial").on('change',function(){
                                        var val = $("#riesgo_biopsicosocial").val();
                                        $.post('db/update/m_gestante.php',{
                                            rut:'<?php echo $rut; ?>',
                                            val:val,
                                            id_gestacion:$("#id_gestacion").val(),
                                            column:'riesgo_biopsicosocial',
                                            fecha_registro:'<?php echo $fecha_registro; ?>'
                                        },function(data){
                                            alertaLateral(data);
                                            $('.tooltipped').tooltip({delay: 50});
                                        });

                                    });
                                })
                            </script>
                        </div>
                    </div>
                </div>
                <!-- otro riesgo -->
                <div class="card-panel lime lighten-2">
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
    function loadHistorialGestacion(rut,indicador) {
        $.post('grid/historial_gestacion.php',{
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
</script>