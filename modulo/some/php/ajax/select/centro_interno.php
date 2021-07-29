<option>TODOS</option>

<?php
include "../../config.php";

$id = explode(",",$_POST['sector_municipal']);

foreach ($id as $i => $value){
    if($value!='TODOS'){
        $sql = "select * from centros_internos where id_sector_comunal='$value' order by nombre_centro_interno";
        $res = mysql_query($sql);
        while($row = mysql_fetch_array($res)){
            ?>
            <option value="<?php echo $row['id_centro_interno']; ?>"><?php echo $row['nombre_centro_interno']; ?></option>
            <?php
        }
    }
}

?>

