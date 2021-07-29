
<div class="col l12">
    <label>SECTORES INTERNO</label>
    <select name="id_sector_centro" id="id_sector_centro">
        <option>TODOS</option>
        <?php
        include "../../config.php";

        $centro = $_POST['id_centro'];

        $sql = "select * from sectores_centros_internos where id_centro_interno='$centro' order by nombre_sector_interno";
        $res = mysql_query($sql);
        while($row = mysql_fetch_array($res)){
            ?>
            <option value="<?php echo $row['id_sector_centro_interno']; ?>"><?php echo $row['nombre_sector_interno']; ?></option>
            <?php
        }
        ?>
    </select>

</div>
