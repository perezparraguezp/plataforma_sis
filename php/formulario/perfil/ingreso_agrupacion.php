<!--
formulario tipo crear agrupacion

sirve para crear los tipos de agrupaciones    que se cargaran posteriormente

variables
nombre_agrupacion = indica el tipo agrpacion  como centro  de apederados, centro de alumnos
tipo_agrupacion = tipo  de agrupaciones

-->
<?php
include '../../config.php';
include '../../objetos/tipo_agrupacion.php';

?>
<div class=" card-panel">
    <form class="col l12" id="form_newAgrupacion">
        <div class="row">
            <h4 class="header">Crear agrupación  </h4>
            <p class="left"> Se crear los tipos  de agrupacion como centro de padres, centro de alumnos y otros </p>
        </div>
        <hr />
        <div class="row" style="padding-left: 10px;">
            <label>Tipo de Agrupación </label>
            <select name="tipo_agrupacion" id="tipo_agrupacion">
                <?php
                $sql1 = "select * from tipo_agrupacion order by nombre_tipo_agrupacion";
                $res = mysql_query($sql1);
                while($row = mysql_fetch_array($res)){
                    $a = new tipo_agrupacion($row['id_tipo_agrupacion']);
                    ?>
                    <option value="<?php echo $a->id; ?>"><?php echo $a->nombre_tipo;  ?></option>
                    <?php
                }
                ?>
            </select>
        </div>
        <div class="row">
            <div class="col l6">
                <label>Desde</label>
                <div class="fecha" id="desde" name="desde"></div>
            </div>
            <div class="col l6">
                <label>Hasta</label>
                <div class="fecha" id="hasta" name="hasta"></div>
            </div>
        </div>
        <hr />
        <div class="row">
            <fieldset>
                <legend>Datos del Presidente de la Agupación</legend>
                <div class="row">
                    <div class="col l3">RUT</div>
                    <div class="col l9">
                        <input type="text" name="rut_presidente" id="rut_presidente"
                               onchange="getDatosPersona('nombre_presidente','rut_presidente','telefono_presidente')" />
                    </div>
                </div>
                <div class="row">
                    <div class="col l3">Nombre Completo</div>
                    <div class="col l9">
                        <input type="text" name="nombre_presidente" id="nombre_presidente" />
                    </div>
                </div>
                <div class="row">
                    <div class="col l3">Telefono</div>
                    <div class="col l9">
                        <input type="text" name="telefono_presidente" id="telefono_presidente" />
                    </div>
                </div>
            </fieldset>
        </div>
        <div class="row">
            <fieldset>
                <legend>Datos del Tesorero de la Agupación</legend>
                <div class="row">
                    <div class="col l3">RUT</div>
                    <div class="col l9">
                        <input type="text" name="rut_tesorero" id="rut_tesorero"
                               onchange="getDatosPersona('nombre_tesorero','rut_tesorero','telefono_tesorero')" />
                    </div>
                </div>
                <div class="row">
                    <div class="col l3">Nombre Completo</div>
                    <div class="col l9">
                        <input type="text" name="nombre_tesorero" id="nombre_tesorero" />
                    </div>
                </div>
                <div class="row">
                    <div class="col l3">Telefono</div>
                    <div class="col l9">
                        <input type="text" name="telefono_tesorero" id="telefono_tesorero" />
                    </div>
                </div>
            </fieldset>
        </div>
        <div class="row">
            <fieldset>
                <legend>Datos del Secretario de la Agupación</legend>
                <div class="row">
                    <div class="col l3">RUT</div>
                    <div class="col l9">
                        <input type="text" name="rut_secretario" id="rut_secretario"
                               onchange="getDatosPersona('nombre_secretario','rut_secretario','telefono_secretario')" />
                    </div>
                </div>
                <div class="row">
                    <div class="col l3">Nombre Completo</div>
                    <div class="col l9">
                        <input type="text" name="nombre_secretario" id="nombre_secretario" class="jqx-validator-error-element" />
                    </div>
                </div>
                <div class="row">
                    <div class="col l3">Telefono</div>
                    <div class="col l9">
                        <input type="text" name="telefono_secretario" id="telefono_secretario" />
                    </div>
                </div>
            </fieldset>
        </div>

        <div class="row">
            <div class="input-field col s12">
                <a href="#!" onclick="insertAgrupacionEscolar()" class="btn waves-effect waves-light  col s12"> Crear agrupacion</a>
            </div>
        </div>

    </form>
</div>
<script type="text/javascript">

    function insertAgrupacionEscolar() {
        $.post('php/db/insert/agrupacion_escolar.php',$("#form_newAgrupacion").serialize(),
            function (data) {
                if(data !== 'ERROR_SQL'){
                    loadListaAgrupaciones();
                }
            });
    }
    function getDatosPersona(input,nombre,telefono){
        $.post('php/db/buscar/persona.php',{
            rut:$("#"+nombre).val()
        },function(data){
            var datos = data.split(";");
            $("#"+input).val(datos[0]);
            $("#"+telefono).val(datos[1]);
        });
    }
</script> 