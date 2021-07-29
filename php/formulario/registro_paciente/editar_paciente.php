<?php
include '../../config.php';
include '../../objetos/persona.php';
$id_establecimiento = $_SESSION['id_establecimiento'];
$rut = $_POST['rut'];
$p = new persona($rut);
$centro_medico = $p->getArrayCentroMedico();
$info_ciudad = $p->getArrayCiudad();

$p->loadDatosPadres();
$papa = new persona($p->rut_papa);
$mama = new persona($p->rut_mama);
?>
<form name="form_paciente" id="form_paciente" class="card-panel">
    <script type="text/javascript">
        $(document).ready(function () {
            // Create jqxNavigationBar
            $("#jqxNavigationBar").jqxNavigationBar({ width: '100%', height: 460});
        });
    </script>
    <div id='jqxNavigationBar'>
        <div>DATOS PERSONALES</div>
        <div>
            <div class="row">
                <div class="col l12 m12 s12">
                    <div class="col l4">CENTRO MEDICO</div>
                    <div class="col l8" >
                        <select name="id_centro" id="id_centro" >
                            <option value="<?php echo $centro_medico['id_centro_interno']; ?>"><?php echo $centro_medico['nombre_centro_interno']; ?></option>
                            <?php
                            $sql1 = "select * from centros_internos 
                          where id_establecimiento='$id_establecimiento' 
                          order by nombre_centro_interno";
                            $res1 = mysql_query($sql1);
                            while($row1 = mysql_fetch_array($res1)){

                                ?>
                                <option value="<?php echo strtoupper($row1['id_centro_interno']); ?>"><?php echo strtoupper($row1['nombre_centro_interno']); ?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col l12 m12 s12">
                    <div class="col l4">SECTOR CENTRO</div>
                    <div class="col l8" id="div_sector_id">
                        <select name="id_sector_centro" id="id_sector_centro" style="display: block;" class="browser-default">
                            <option value="<?php echo $centro_medico['id_sector_centro_interno']; ?>"><?php echo $centro_medico['nombre_sector_interno']; ?></option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col l12 m12 s12">
                    <div class="col l4">RUT PACIENTE</div>
                    <div class="col l8">
                        <input type="text" name="rut"
                               id="rut" value="<?php echo $p->rut ?>"
                               placeholder="11222333-4" />
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col l12 m12 s12">
                    <div class="col l4">NOMBRE COMPLETO</div>
                    <div class="col l8">
                        <input type="text" name="nombre" value="<?php echo $p->nombre ?>" />
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col l12 m12 s12">
                    <div class="col l4">FECHA DE NACIMIENTO</div>
                    <div class="col l8">
                        <input type="date" name="nacimiento" value="<?php echo $p->fecha_nacimiento; ?>"  />
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col l12 m12 s12">
                    <div class="col l4">SEXO</div>
                    <div class="col l4">
                        <input type="radio" id="sexo_1" name="sexo" value="M" <?php echo $p->sexo=='M'?'checked':''; ?> />
                        <label for="sexo_1">MASCULINO</label>
                    </div>
                    <div class="col l4">
                        <input type="radio" id="sexo_2" name="sexo" value="F" <?php echo $p->sexo=='F'?'checked':''; ?> />
                        <label for="sexo_2">FEMENINO</label>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col l12 m12 s12">
                    <div class="col l4">PUEBLO ORIGINARIO</div>
                    <div class="col l4">
                        <input type="radio" id="pueblo_1" name="pueblo" value="NO" <?php echo $p->pueblo=='NO'?'checked':''; ?> />
                        <label for="pueblo_1">NO</label>
                    </div>
                    <div class="col l4">
                        <input type="radio" id="pueblo_2" name="pueblo" value="SI" <?php echo $p->pueblo=='SI'?'checked':''; ?> />
                        <label for="pueblo_2">SI</label>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col l12 m12 s12">
                    <div class="col l4">POBLACIÓN MIGRANTE</div>
                    <div class="col l4">
                        <input type="radio" id="migrante_1" name="migrante" value="SI" <?php echo $p->pueblo=='SI'?'checked':''; ?>  />
                        <label for="migrante_1">SI</label>
                    </div>
                    <div class="col l4">
                        <input type="radio" id="migrante_2" name="migrante" value="NO" <?php echo $p->pueblo=='NO'?'checked':''; ?> />
                        <label for="migrante_2">NO</label>
                    </div>
                </div>
            </div>
        </div>
        <div>DATOS DE CONTACTO</div>
        <div>
            <div class="row">
                <div class="col l12 m12 s12">
                    <div class="col l4">TELÉFONO</div>
                    <div class="col l8">
                        <input type="text" name="telefono" value="<?php echo $p->telefono; ?>" />
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col l12 m12 s12">
                    <div class="col l4">e-Mail</div>
                    <div class="col l8">
                        <input type="text" name="email" value="<?php echo $p->email; ?>" />
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col l12 m12 s12">
                    <div class="col l4">Región</div>
                    <div class="col l8">
                        <select  name="region" id="region">
                            <option value="<?php echo $info_ciudad['id_region']; ?>"><?php echo $info_ciudad['nombre_region']; ?></option>
                            <?php

                            $sql1 = "select * from regiones order by id asc";
                            $res1 = mysql_query($sql1);
                            while ($row1 = mysql_fetch_array($res1)){
                                ?>
                                <option value="<?php echo $row1['id']; ?>"><?php echo utf8_decode($row1['region']); ?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <script type="text/javascript">
                    $(function(){
                        $("#region").jqxDropDownList({
                            width: '100%', height: 30});

                        $("#region").on('change',function(){
                            var region = $("#region").val();
                            $.post('php/ajax/select/provincias.php',{
                                region:region
                            },function(data){



                                $("#div_provincia").html('<select  name="provincia" id="provincia"></select>');
                                $("#provincia").html(data);
                                $("#provincia").jqxDropDownList({
                                    width: '100%', height: 30});

                                $("#provincia").on('change',function(){
                                    var region = $("#region").val();
                                    var provincia = $("#provincia").val();

                                    $.post('php/ajax/select/comunas.php',{
                                        region:region,
                                        provincia:provincia
                                    },function(data){

                                        $("#div_comuna").html('<select  name="comuna" id="comuna"></select>');
                                        $("#comuna").html(data);
                                        $("#comuna").jqxDropDownList({
                                            width: '100%', height: 30});
                                    });
                                });
                            });
                        });

                    });
                </script>
            </div>
            <div class="row">
                <div class="col l12 m12 s12">
                    <div class="col l4">Provincia</div>
                    <div class="col l8" id="div_provincia" style="display: block;">
                        <select  name="provincia" id="provincia" class="browser-default">
                            <option value="<?php echo $info_ciudad['id_provincia']; ?>"><?php echo $info_ciudad['nombre_provincia']; ?></option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col l12 m12 s12">
                    <div class="col l4">Comuna</div>
                    <div class="col l8" id="div_comuna" style="display:block;">
                        <select  name="comuna" id="comuna" class="browser-default">
                            <option value="<?php echo $info_ciudad['id_comuna']; ?>"><?php echo $info_ciudad['nombre_comuna']; ?></option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col l12 m12 s12">
                    <div class="col l4">DIRECCIÓN</div>
                    <div class="col l8">
                        <input type="text" name="direccion" value="<?php echo $p->direccion; ?>" />
                    </div>
                </div>
            </div>
        </div>
        <div>DATOS DE TARJETA</div>
        <div>
            <div class="row">
                <div class="col l12 m12 s12">
                    <div class="col l4">Nº DE FICHA</div>
                    <div class="col l8">
                        <input type="text" name="ficha" value="<?php echo $p->numero_ficha; ?>" />
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col l12 m12 s12">
                    <div class="col l4">Nº CARPETA FAMILIA</div>
                    <div class="col l8">
                        <input type="text" name="carpeta_familiar" value="<?php echo $p->carpeta_familiar; ?>" />
                    </div>
                </div>
            </div>
        </div>
        <div>DATOS DE LA MADRE</div>
        <div>
            <div class="row">
                <div class="col l12 m12 s12">
                    <div class="col l4">RUT MADRE</div>
                    <div class="col l8">
                        <input type="text" name="rut_mama"  id="rut_mama"
                               value="<?php echo $p->rut_mama; ?>"
                               placeholder="11222333-4" />
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col l12 m12 s12">
                    <div class="col l4">NOMBRE COMPLETO</div>
                    <div class="col l8">
                        <input type="text" name="nombre_mama" value="<?php echo $mama->nombre; ?>"/>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col l12 m12 s12">
                    <div class="col l4">FECHA DE NACIMIENTO</div>
                    <div class="col l8">
                        <input type="date" name="nacimiento_mama" value="<?php echo $mama->fecha_nacimiento; ?>"  />
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col l12 m12 s12">
                    <div class="col l4">TELEFONO DE CONTACTO</div>
                    <div class="col l8">
                        <input type="text" name="telefono_mama" value="<?php echo $mama->telefono; ?>"  />
                    </div>
                </div>
            </div>
        </div>
        <div>DATOS DEL PADRE</div>
        <div>
            <div class="row">
                <div class="col l12 m12 s12">
                    <div class="col l4">RUT PADRE</div>
                    <div class="col l8">
                        <input type="text" name="rut_papa" id="rut_papa"
                               value="<?php echo $p->rut_papa; ?>"
                               placeholder="11222333-4" />
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col l12 m12 s12">
                    <div class="col l4">NOMBRE COMPLETO</div>
                    <div class="col l8">
                        <input type="text" name="nombre_papa" value="<?php echo $papa->nombre; ?>"  />
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col l12 m12 s12">
                    <div class="col l4">FECHA DE NACIMIENTO</div>
                    <div class="col l8">
                        <input type="date" name="nacimiento_papa" value="<?php echo $papa->fecha_nacimiento; ?>" />
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col l12 m12 s12">
                    <div class="col l4">TELEFONO DE CONTACTO</div>
                    <div class="col l8">
                        <input type="text" name="telefono_papa" value="<?php echo $papa->telefono; ?>"  />
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">

        <a href="#" onclick="updateInfoPaciente()" class="btn waves-effect modal-trigger waves-light col s12 "> ACTUALIZAR PACIENTE</a>
    </div>
</form>
<script type="text/javascript">
    $(document).ready(function () {
        $("#id_centro").jqxDropDownList({
            width: '100%', height: 30});


        $("#rut").on('change',function(){
            $('#rut').Rut({
                on_error: function() {
                    $(this).focus();
                    //alert('Rut incorrecto');
                    alertaLateral('RUT INCORRECTO');
                    $('#rut').val('');
                    $('#rut').focus();
                    $('#rut').css({
                        "border": "solid red 1px"
                    });
                }
            });
        });
        $("#rut_mama").on('change',function(){
            $('#rut_mama').Rut({
                on_error: function() {
                    $(this).focus();
                    //alert('Rut incorrecto');
                    alertaLateral('RUT INCORRECTO');
                    $('#rut_mama').val('');
                    $('#rut_mama').focus();
                    $('#rut_mama').css({
                        "border": "solid red 1px"
                    });
                }
            });
        });
        $("#rut_papa").on('change',function(){
            $('#rut_papa').Rut({
                on_error: function() {
                    $(this).focus();
                    //alert('Rut incorrecto');
                    alertaLateral('RUT INCORRECTO');
                    $('#rut_papa').val('');
                    $('#rut_papa').focus();
                    $('#rut_papa').css({
                        "border": "solid red 1px"
                    });
                }
            });
        });

    });


    var select = 0;
    $("#id_centro").on('change',function(){
        var centro = $("#id_centro").val();

        $.post('php/ajax/select/sectores_centro_option.php',{
            id_centro:centro
        },function(data){
            $("#div_sector_id").html('');
            $("#div_sector_id").html('<select id="id_sector_centro" name="id_sector_centro"></select>');
            $("#id_sector_centro").html(data);
            $("#id_sector_centro").jqxDropDownList({width: '100%', height: 30});
        });
    });

    function validar_Rut_paciente(){
        // alert('hola');

    }

    function updateInfoPaciente(){
        if(confirm('Esta seguro que desea registrar este paciente en nuestros registros')){
            $.post('php/db/update/paciente.php',
                $("#form_paciente").serialize(),function (data){
                    alertaLateral('PACIENTE REGISTRADO!');
                    loadForm_newPaciente();
                });
        }
    }
</script>
