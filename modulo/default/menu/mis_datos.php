<?php
include '../../../php/config.php';
include '../../../php/objetos/persona.php';
include '../../../php/objetos/profesional.php';
include '../../../php/objetos/establecimiento.php';
session_start();

$id_establecimiento = $_SESSION['id_establecimiento'];

$profesional = new profesional($_SESSION['id_usuario']);
$persona = new persona($profesional->rut);
$establecimiento = new establecimiento($id_establecimiento);


?>
<style type="text/css">
    #form_datosProfesionales .row{
        margin: 10px;
    }
</style>
<div class="container">
    <div class="row">
        <div class="col l8 z-depth-4">
            <form class="card-panel" id="form_datosProfesionales">
                <header class="blue-text">DATOS PROFESIONALES</header>
                <div class="row">
                    <div class="col l3">ID PROFESIONAL</div>
                    <div class="col l9">
                        <input type="text"  value="<?php echo $profesional->id_profesional; ?>" disabled="disabled" />
                        <input type="hidden" name="id_profesional" id="id_profesional" value="<?php echo $profesional->id_profesional; ?>"  />
                    </div>
                </div>
                <div class="row">
                    <div class="col l3">TIPO DE USUARIO</div>
                    <div class="col l9">
                        <input type="text" name="tipo_usuario" id="tipo_usuario" value="<?php echo $profesional->tipo_profesional; ?>" disabled="disabled" />
                    </div>
                </div>
                <div class="row">
                    <div class="col l3">VIGENTE HASTA</div>
                    <div class="col l9">
                        <input type="text" name="vigencia" id="vigencia" value="<?php echo $profesional->vigencia; ?>" disabled="disabled" />
                    </div>
                </div>
                <header class="blue-text">DATOS PERSONALES</header>
                <div class="row">
                    <div class="col l3">RUN</div>
                    <div class="col l9">
                        <input type="text"  value="<?php echo $persona->rut ?>" disabled="disabled" />
                        <input type="hidden" name="rut" id="rut" value="<?php echo $persona->rut ?>"  />
                    </div>
                </div>
                <div class="row">
                    <div class="col l3">Nombre</div>
                    <div class="col l9">
                        <input type="text" name="nombre" id="nombre" value="<?php echo $persona->nombre ?>"  />
                    </div>
                </div>
                <div class="row">
                    <div class="col l3">e-Mail</div>
                    <div class="col l9">
                        <input type="text" name="email" id="email" value="<?php echo $persona->email ?>"  />
                    </div>
                </div>
                <div class="row">
                    <div class="col l3">Teléfono</div>
                    <div class="col l9">
                        <input type="text" name="telefono" id="telefono" value="<?php echo $persona->telefono ?>"  />
                    </div>
                </div>
                <div class="row">
                    <div class="col l3">Dirección</div>
                    <div class="col l9">
                        <input type="text" name="direccion" id="direccion" value="<?php echo $persona->direccion ?>"  />
                    </div>
                </div>
                <header class="blue-text">DATOS DE SEGURIDAD</header>
                <div class="row">
                    <div class="col l3">Contraseña</div>
                    <div class="col l9">
                        <input type="text" name="clave" id="clave" value="<?php echo $profesional->clave ?>"  />
                    </div>
                </div>
                <div class="row">
                    <div class="col l12">
                        <input type="button"
                               style="width: 100%;"
                               onclick="updateDatosPersonales()"
                               class="btn-large light-green darken-2" value="ACTUALIZAR DATOS"  />
                    </div>
                </div>
            </form>
        </div>
        <div class="col l4">
            <div class="container">
                <div class="card-panel  light-blue darken-4" style="color: white;">
                    <div class="row">
                        <div class="col l12">
                            <header class="white-text">DATOS DEL ESTABLECIMIENTO</header>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col l12">
                            <label class="flow-text" style="font-size: 0.8em;color: white;">CODIGO ESTABLECIMIENTO</label>
                            <br /><strong class=""><?php echo $establecimiento->id ?></strong>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col l12">
                            <label class="flow-text" style="font-size: 0.8em;color: white;">NOMBRE ESTABLECIMIENTO</label>
                            <br /><strong class=""><?php echo $establecimiento->nombre ?></strong>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col l12">
                            <label class="flow-text" style="font-size: 0.8em;color: white;">E-MAIL</label>
                            <br /><strong class=""><?php echo $establecimiento->email; ?></strong>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col l12">
                            <label class="flow-text" style="font-size: 0.8em;color: white;">TELÉFONO</label>
                            <br /><strong class=""><?php echo $establecimiento->telefono; ?></strong>
                        </div>
                    </div>
                </div>
                <div class="card-panel red lighten-2">
                    <header>PARA MODIFICAR OTROS DATOS, CONTACTESE CON EL ADMINISTRADOR DEL SITIO</header>
                </div>
                <div class="card-panel light-green">
                    <header>PARA OBTENER AYUDA FAVOR ESCRIBIRNOS A <a href="mailto:soporte@eh-open.com">SOPORTE@EH-OPEN.COM</a> </header>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    function updateDatosPersonales(){
        $.post('db/update/profesional.php',
            $("#form_datosProfesionales").serialize(),function (data){
                alertaLateral('DATOS ACTUALIZADOS!');

            });
    }
</script>