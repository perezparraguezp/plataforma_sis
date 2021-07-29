<?php
include "../../config.php";
include "../../objetos/establecimiento.php";
$id_establecimiento = $_POST['id'];

$e = new establecimiento($id_establecimiento);
?>
<div class="modal-content">
    <div class="card-panel" style="padding: 10px;margin: 0px;">
        <div class="row col l12" style="margin-bottom: 0px;">
            <div class="col l8"><?php echo $e->nombre; ?></div>
            <div class="col l2 center">
                <i class="mdi-editor-vertical-align-bottom"></i><br />
                <?php echo $e->tipo; ?>
            </div>
            <div class="col l2 center">
                <i class="mdi-maps-place"></i><br />
                <?php echo $e->comuna; ?>
            </div>

        </div>
        <div class="row col l12" style="font-size: 0.8em;margin-bottom: 0px;">
            <div class="row">
                <i class="mdi-maps-directions tiny"></i>Dirección: <?php echo $e->direccion; ?> |
                <i class="mdi-maps-local-phone tiny"></i>Telefono: <?php echo $e->telefono; ?> |
                <i class="mdi-maps-local-post-office tiny"></i>E-mail: <?php echo $e->email; ?>
            </div>
        </div>
    </div>
    <div class="card-panel">
        <p class="header">Atributos del Establecimiento</p>
        <div class="row center">
            <div class="col l4 teal lighten-3">
                PROFESIONALES
            </div>
            <div class="col l4 light-blue lighten-2">
                SECTORES INTERNOS
            </div>
            <div class="col l4 red lighten-4">
                TOTAL USUARIOS
            </div>
        </div>
        <div class="row">
            <div class="col l4">
                <?php
                $sql = "SELECT * from mis_atributos_establecimientos 
                    INNER JOIN atributo_establecimiento USING(id_atributo) 
                    where id_establecimiento='$id_establecimiento' and tipo_atributo='ESTADO' ";
                $res = mysql_query($sql);
                while($row = mysql_fetch_array($res)){
                    $color = 'teal lighten-3';
                    ?>
                    <div class="card-panel <?php echo $color; ?> col l12 center" style="margin-left: 5px;">
                        <strong><?php echo $row['nombre_atributo']; ?></strong>
                        <p>
                            <label class="black-text">VALOR: <strong><?php echo $row['valor']; ?></strong></label>
                        </p>
                    </div>
                    <?php
                }
                ?>
            </div>
            <div class="col l4">
                <?php
                $sql = "SELECT * from mis_atributos_establecimientos 
                    INNER JOIN atributo_establecimiento USING(id_atributo) 
                    where id_establecimiento='$id_establecimiento' and tipo_atributo='NUMERICO' ";
                $res = mysql_query($sql);
                while($row = mysql_fetch_array($res)){
                    $color = 'light-blue lighten-2';
                    ?>
                    <div class="card-panel <?php echo $color; ?> col l12 center" style="margin-left: 5px;">
                        <strong><?php echo $row['nombre_atributo']; ?></strong>
                        <p>
                            <label class="black-text">VALOR: <strong><?php echo $row['valor']; ?></strong></label>
                        </p>
                    </div>
                    <?php
                }
                ?>
            </div>
            <div class="col l4">
                <?php
                $sql = "SELECT * from mis_atributos_establecimientos 
                    INNER JOIN atributo_establecimiento USING(id_atributo) 
                    where id_establecimiento='$id_establecimiento' and tipo_atributo='TEXTO' ";
                $res = mysql_query($sql);
                while($row = mysql_fetch_array($res)){
                    $color = 'red lighten-4';
                    ?>
                    <div class="card-panel <?php echo $color; ?> col l12 center" style="margin-left: 5px;">
                        <strong><?php echo $row['nombre_atributo']; ?></strong>
                        <p>
                            <label class="black-text"><strong><?php echo $row['valor']; ?></strong></label>
                        </p>
                    </div>
                    <?php
                }
                ?>
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <a href="#" class="waves-effect waves-red btn-flat modal-action modal-close">CERRAR</a>
</div>
