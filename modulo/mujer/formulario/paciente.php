<?php
include '../../../php/config.php';
$id_establecimiento = $_SESSION['id_establecimiento'];
?>
<style type="text/css">
    #form_paciente input{
        text-align: left;
    }
</style>
<form name="form_paciente" id="form_paciente" class="card-panel left-align">
    <script type="text/javascript">
        $(document).ready(function () {
            // Create jqxNavigationBar
            $("#jqxNavigationBar").jqxNavigationBar({ width: '100%',theme: 'eh-open', height: 460});
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
                            <option>TODOS</option>
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
                        <select name="id_sector_centro" id="id_sector_centro">

                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col l12 m12 s12">
                    <div class="col l4">RUT PACIENTE</div>
                    <div class="col l8">
                        <input type="text" name="rut"
                               id="rut"
                               placeholder="11222333-4" />
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col l12 m12 s12">
                    <div class="col l4">NOMBRE COMPLETO</div>
                    <div class="col l8">
                        <input type="text" name="nombre"  />
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col l12 m12 s12">
                    <div class="col l4">FECHA DE NACIMIENTO</div>
                    <div class="col l8">
                        <input type="date" name="nacimiento" value="<?php echo date('Y-m-d'); ?>"  />
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col l12 m12 s12">
                    <div class="col l4">SEXO</div>
                    <div class="col l4">
                        <input type="radio" id="sexo_1" name="sexo" value="M" checked />
                        <label for="sexo_1">MASCULINO</label>
                    </div>
                    <div class="col l4">
                        <input type="radio" id="sexo_2" name="sexo" value="F" />
                        <label for="sexo_2">FEMENINO</label>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col l12 m12 s12">
                    <div class="col l4">PUEBLO ORIGINARIO</div>
                    <div class="col l4">
                        <input type="radio" id="pueblo_2" name="pueblo" value="SI" />
                        <label for="pueblo_2">SI</label>
                    </div>
                    <div class="col l4">
                        <input type="radio" id="pueblo_1" name="pueblo" value="NO" checked />
                        <label for="pueblo_1">NO</label>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col l12 m12 s12">
                    <div class="col l4">POBLACIÓN MIGRANTE</div>
                    <div class="col l4">
                        <input type="radio" id="migrante_1" name="migrante" value="SI"  />
                        <label for="migrante_1">SI</label>
                    </div>
                    <div class="col l4">
                        <input type="radio" id="migrante_2" name="migrante" value="NO" checked />
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
                        <input type="text" name="telefono" />
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col l12 m12 s12">
                    <div class="col l4">e-Mail</div>
                    <div class="col l8">
                        <input type="text" name="email" />
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col l12 m12 s12">
                    <div class="col l4">Región</div>
                    <div class="col l8">
                        <select  name="region" id="region">
                            <option disabled selected>Seleccionar</option>
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
                            width: '100%',theme: 'eh-open', height: 30});

                        $("#region").on('change',function(){
                            var region = $("#region").val();
                            $.post('../../php/ajax/select/provincias.php',{
                                region:region
                            },function(data){

                                $("#div_provincia").html('');
                                $("#div_comuna").html('');
                                $("#div_provincia").html('<select  name="provincia" id="provincia"></select>');
                                $("#provincia").html(data);
                                $("#provincia").jqxDropDownList({
                                    width: '100%', height: 30});

                                $("#provincia").on('change',function(){
                                    var region = $("#region").val();
                                    var provincia = $("#provincia").val();

                                    $.post('../../php/ajax/select/comunas.php',{
                                        region:region,
                                        provincia:provincia
                                    },function(data){
                                        $("#div_comuna").html('');
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
                    <div class="col l8" id="div_provincia">
                        <select  name="provincia" id="provincia"></select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col l12 m12 s12">
                    <div class="col l4">Comuna</div>
                    <div class="col l8" id="div_comuna">
                        <select  name="comuna" id="comuna"></select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col l12 m12 s12">
                    <div class="col l4">DIRECCIÓN</div>
                    <div class="col l8">
                        <input type="text" name="direccion" />
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
                        <input type="text" name="ficha" />
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col l12 m12 s12">
                    <div class="col l4">Nº CARPETA FAMILIA</div>
                    <div class="col l8">
                        <input type="text" name="carpeta_familiar" />
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">

        <a href="#" onclick="insertPaciente_final()" class="btn waves-effect modal-trigger waves-light col s12 "> INGRESAR NUEVO PACIENTE</a>
    </div>
</form>
<script type="text/javascript">
    $(document).ready(function () {
        $("#id_centro").jqxDropDownList({
            width: '100%',theme: 'eh-open', height: 30});


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


    });

    var select = 0;
    $("#id_centro").on('change',function(){
        var centro = $("#id_centro").val();

        $.post('../../php/ajax/select/sectores_centro_option.php',{
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

    function insertPaciente_final(){
        if(confirm('Esta seguro que desea registrar este paciente en nuestros registros')){
            $.post('db/insert/paciente.php',
                $("#form_paciente").serialize(),function (data){
                    alertaLateral('PACIENTE REGISTRADO!');
                    load_form_nuevo_pcvp();
                });
        }
    }
</script>
