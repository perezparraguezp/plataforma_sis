<!--
formulario tipo crear agrupacion

sirve para crear los tipos de agrupaciones    que se cargaran posteriormente

variables
nombre_agrupacion = indica el tipo agrpacion  como centro  de apederados, centro de alumnos
tipo_agrupacion = tipo  de agrupaciones

-->
<?php
include '../../../php/config.php';
$id_establecimiento = $_SESSION['id_establecimiento'];

?>
<div class=" card-panel">
    <form class="col l12" id="form_newPersonal">
        <div class="row">
            <h4 class="header">Registrar Personal</h4>
            <p class="left">
                Formulario creado para registrar personal de salud.
            </p>
        </div>
        <hr />
        <div class="row" style="padding-left: 10px;">
            <label>Tipo de Contrato </label>
            <select name="tipo_contrato" id="tipo_contrato">
                <?php

                $sql = "select * from mis_atributos_establecimientos 
                        inner join atributo_establecimiento using (id_atributo) 
                        where id_establecimiento='$id_establecimiento' and valor='SI' order by nombre_atributo";
                //echo $sql;
                $res = mysql_query($sql);
                while ($row = mysql_fetch_array($res)){
                    ?>
                    <option><?php echo $row['nombre_atributo']; ?></option>
                    <?php
                }
                ?>
            </select>
        </div>
        <div class="row">
            <div class="col l4">
                <label>Desde</label>
                <div class="fecha" id="desde" name="desde"></div>
            </div>
            <div class="col l4">
                <label>Hasta</label>
                <div class="fecha" id="hasta" name="hasta"></div>
            </div>
            <div class="col l4">
                <label>Indefinido</label>
                <div class="switch">
                    <label>
                        NO
                        <input type="checkbox" name="indefinido" id="indefinido"  />
                        <span class="lever"></span> SI
                    </label>
                </div>
            </div>
        </div>
        <hr />
        <div class="row">
            <fieldset>
                <legend>Datos del Presidente de la Agupación</legend>
                <div class="row">
                    <div class="col l3">RUT</div>
                    <div class="col l9">
                        <input type="text" name="rut" id="rut"
                               onchange="getDatosPersona('nombre','rut','telefono')" />
                    </div>
                </div>
                <div class="row">
                    <div class="col l3">Nombre Completo</div>
                    <div class="col l9">
                        <input type="text" name="nombre" id="nombre" />
                    </div>
                </div>
                <div class="row">
                    <div class="col l3">Telefono</div>
                    <div class="col l9">
                        <input type="text" name="telefono" id="telefono" />
                    </div>
                </div>
                <div class="row">
                    <div class="col l3">e-Mail</div>
                    <div class="col l9">
                        <input type="text" name="email" id="email" />
                    </div>
                </div>
                <div class="row">
                    <div class="col l3">Horas de Trabajo</div>
                    <div class="col l9">
                        <input type="number" name="horas" id="horas" />
                    </div>
                </div>
            </fieldset>
        </div>

        <div class="row">
            <div class="input-field col s12">
                <a href="#!" onclick="insertPersonalEstablecimiento()" class="btn waves-effect waves-light  col s12"> REGISTRAR</a>
            </div>
        </div>

    </form>
</div>
<script type="text/javascript">

    function insertPersonalEstablecimiento() {
        $.post('db/insert/personal.php',$("#form_newPersonal").serialize(),
            function (data) {
                if(data !== 'ERROR_SQL'){
                    if(confirm("Desea Agregar otro Profesional")){
                        $("#form_newPersonal").reset();
                    }else{
                        loadGrid_profesionales();
                    }

                }
            });
    }
    function getDatosPersona(input,nombre,telefono){
        $.post('db/buscar/persona.php',{
            rut:$("#"+nombre).val()
        },function(data){
            var datos = data.split(";");
            $("#"+input).val(datos[0]);
            $("#"+telefono).val(datos[1]);
            $("#email").val(datos[3]);
            $("#rut").val(datos[4]);
        });
    }
</script>