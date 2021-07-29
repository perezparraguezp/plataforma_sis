<?php
include "../../config.php";
include "../../objetos/establecimiento.php";
$id_atributo = $_POST['atributo'];
?>
<input type="hidden" name="id_atributo" value="<?php echo $id_atributo ?>" />
<?php
$sql = "select * from atributo_establecimiento where id_atributo='$id_atributo' limit 1";
$row = mysql_fetch_array(mysql_query($sql));
if($row){

    $e = new establecimiento($_SESSION['id_establecimiento']);

    $atributo = $e->getAtributo($id_atributo);


    if($row['tipo_atributo']=='ESTADO'){
        ?>
        <hr />
        <div class="col l12">
            <?php echo $row['descripcion_atributo']; ?>
        </div>
        <div class="clearfix"></div>
        <hr />
        <div class="col l4">
            <label>Atributo</label><br />
            <?php echo strtoupper($row['nombre_atributo']); ?>
        </div>
        <div class="col l4">
            <label>Valor</label>
            <div class="switch">
                <label>
                    NO
                    <?php
                    if($atributo['VALOR']=='SI'){
                        ?>
                        <input type="checkbox" name="valor" checked  />
                        <?php
                    }else{
                        ?>
                        <input type="checkbox" name="valor"  />
                        <?php
                    }
                    ?>
                    <span class="lever"></span> SI
                </label>
            </div>
        </div>
        <div class="col l4">
            <label>Observaciones</label>
            <textarea name="observaciones"><?php echo strtoupper($atributo['OBS']) ?></textarea>
        </div>
    <?php
    }else{
        if($row['tipo_atributo'] == 'NUMERICO'){
            ?>
            <hr />
            <div class="col l12">
                <?php echo $row['descripcion_atributo']; ?>
            </div>
            <div class="clearfix"></div>
            <hr />
            <div class="col l4">
                <label>Atributo</label><br />
                <?php echo strtoupper($row['nombre_atributo']); ?>
            </div>
            <div class="col l4">
                <label>Valor</label>
                <input type="number" value="<?php echo $atributo['VALOR']; ?>" name="valor" />
            </div>
            <div class="col l4">
                <label>Observaciones</label>
                <textarea name="observaciones"><?php echo strtoupper($atributo['OBS']) ?></textarea>
            </div>
            <?php
        }else{
            if($row['tipo_atributo']=='TEXTO'){
                ?>
                <hr />
                <div class="col l12">
                    <?php echo $row['descripcion_atributo']; ?>
                </div>
                <div class="clearfix"></div>
                <hr />
                <div class="col l4">
                    <label>Atributo</label><br />
                    <?php echo strtoupper($row['nombre_atributo']); ?>
                </div>
                <div class="col l4">
                    <label>Valor</label>
                    <textarea name="valor"><?php echo strtoupper($atributo['VALOR']) ?></textarea>
                </div>
                <div class="col l4">
                    <label>Observaciones</label>
                    <textarea name="observaciones"><?php echo strtoupper($atributo['OBS']) ?></textarea>
                </div>
                <?php
            }
        }
    }
}else{
    echo "ERROR_SQL";
}