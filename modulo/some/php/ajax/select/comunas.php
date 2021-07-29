
<option disabled selected>SELECCIONE COMUNA</option>
<?php
include '../../../../../php/config.php';

$id = $_POST['provincia'];

$sql = "select * from comunas where provincia_id='$id' order by comuna";
$res = mysql_query($sql);
while($row = mysql_fetch_array($res)){
    ?>
    <option value="<?php echo $row['id']; ?>"><?php echo $row['comuna']; ?></option>
    <?php
}
?>
