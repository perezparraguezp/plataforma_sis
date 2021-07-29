<?php
include "../../../php/config.php";

include "../../../php/objetos/persona.php";
include "../../../php/objetos/establecimiento.php";
include "../../../php/objetos/profesional.php";

session_start();
$id = $_POST['id'];

$id_establecimiento = $_SESSION['id_establecimiento'];


$profesional = new profesional($id);
$persona = new persona($profesional->rut);

?>
<script type="text/javascript">
    function asignarModulo(modulo){
        if($('#modulo-'+modulo).prop('checked')){
            var activo = 'SI';
        }else{
            var activo = 'NO';
        }

        $.post('db/update/modulos_perfil.php',{
            id_modulo:modulo,
            id_profesional:'<?php echo $id ?>',
            activo:activo
        },function(data){
            alertaLateral(data);

        });
    }
    function resetPassword(){
        $.post('db/update/passwords.php',{
            id_profesional:'<?php echo $id ?>',
        },function(data){
            alertaLateral(data);

        });
    }
</script>
<div class="modal-content">
    <div class="card-panel" style="padding: 10px;margin: 0px;">
        <div class="row col l12" style="margin-bottom: 0px;">
            <div class="col l8">NOMBRE PROFESIONAL: <?php echo $persona->nombre; ?></div>

        </div>
        <div class="row col l12" style="font-size: 0.8em;margin-bottom: 0px;">
            <script type="text/javascript">
                $(document).ready(function () {
                    // Create jqxTabs.
                    $('#tabs').jqxTabs({ width: '100%', height: 450, position: 'top'});

                });
            </script>
            <div id='tabs'>
                <ul>
                    <li>MODULOS PERMITIDOS</li>
                    <li>EDITAR DATOS</li>
                    <li>RESETEAR CONTRASEÑA</li>
                </ul>
                <div STYLE="padding: 10px;">
                    <div checked="row">
                        <div class="col l5">
                            <header>MODULOS DISPONIBLES</header>
                            <div class="container">
                                <?php

                                $sql = "select * from modulos_ehopen 
                                                inner join modulos_establecimiento using(id_modulo)
                                                where id_establecimiento='$id_establecimiento' 
                                                and estado_modulo='ACTIVO' 
                                                order by id_modulo";
                                $res = mysql_query($sql);
                                $i = 0;
                                while($row = mysql_fetch_array($res)){
                                    $id_modulo = $row['id_modulo'];
                                    $rut = $profesional->rut;
                                    $sql1 = "select * from menu_usuario where id_modulo='$id_modulo' and rut='$rut' limit 1";
                                    $row1 = mysql_fetch_array(mysql_query($sql1));
                                    if($row1){
                                        $check = 'checked="checked"';
                                    }else{
                                        $check = '';
                                    }

                                    ?>
                                    <div class="row">
                                        <div class="col l8">
                                            <div class="switch">
                                                <label>
                                                    INACTIVO
                                                    <input type="checkbox"
                                                           name="modulo[<?php echo $row['id_modulo'] ?>]"
                                                           id="modulo-<?php echo $row['id_modulo']; ?>"
                                                            <?php echo $check; ?>
                                                           onchange="asignarModulo('<?php echo $row['id_modulo']; ?>')"  />
                                                    <span class="lever"></span>
                                                    ACTIVO
                                                </label>
                                            </div>
                                        </div>
                                        <div checked="col l4">
                                            <label for="modulo-<?php echo $i ?>"><?php echo $row['nombre_modulo']; ?></label>
                                        </div>
                                    </div>
                                    <?php
                                    $i++;
                                }
                                ?>
                            </div>
                        </div>
                    </div>

                </div>
                <div>
                    <form id="formDatosContrato" class="container">
                        <div class="row">
                            <div class="col l4">
                                <label>Tipo de Contrato </label>
                                <select name="tipo_contrato" id="tipo_contrato">
                                    <option><?php echo $profesional->tipo_profesional; ?></option>
                                    <option disabled="disabled">--------------------</option>
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
                        </div>
                        <div class="row">
                            <div class="col l4">
                                <label>FECHA TERMINO CONTRATO</label>
                                <input type="date" name="fecha_termino" value="<?php echo $profesional->fecha_termino; ?>" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col l4">
                                <label>CORREO ELECTRONICO</label>
                                <input type="text" name="email" value="<?php echo $profesional->email; ?>" />
                            </div>
                        </div>
                    </form>
                </div>
                <div>
                    <form class="container" id="formResetPasswords" >
                        <input type="hidden" name="id_profesional" value="<?php echo $id; ?>" />
                        <div class="row">
                            <div class="col l12">
                                <div class="card-panel">
                                    <header>PARA RESTABLECER LA CONTRASEÑA DEL USUARIO DEBERA PRESIONAR EL BOTON RESET, Y SE ENVIARA UN CORREO ELECTRONICO PARA QUE EL USUARIO PUEDA INGRESAR A SU CUENTA</header>
                                    <p>SE ENVIARA UN CORREO ELECTRONICO AL SIGUIENTE E-MAIL: <?PHP echo $persona->email; ?></p>
                                    <input type="button" value="RESTABLECIER CONTRASEÑA" onclick="resetPassword()" />
                                    <hr />
                                    <?php
                                    if(!isset($_GET['text']) or !isset($_GET['phone'])){ die('Not enough data');}

                                    $apiURL = 'https://api.chat-api.com/instanceYYYYY/';
                                    $token = 'abcdefgh12345678';

                                    $message = 'hola';
                                    $phone = '56982181007';

                                    $data = json_encode(
                                        array(
                                            'chatId'=>$phone.'@c.us',
                                            'body'=>$message
                                        )
                                    );
                                    $url = $apiURL.'message?token='.$token;
                                    $options = stream_context_create(
                                        array('http' =>
                                            array(
                                                'method'  => 'POST',
                                                'header'  => 'Content-type: application/json',
                                                'content' => $data
                                            )
                                        )
                                    );
                                    $response = file_get_contents($url,false,$options);
                                    echo $response; exit;
                                    ?>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
    </div>
</div>
<div class="modal-footer">
    <a href="#" class="waves-effect waves-red btn-flat modal-action modal-close">CERRAR</a>
</div>
