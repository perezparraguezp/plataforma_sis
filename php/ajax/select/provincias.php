<option>SELECCIONE</option>
        <?php
        include "../../config.php";

        $id = $_POST['region'];

        $sql = "select * from provincias where region_id='$id' order by provincia";
        $res = mysql_query($sql);
        while($row = mysql_fetch_array($res)){
            ?>
            <option value="<?php echo $row['id']; ?>"><?php echo $row['provincia']; ?></option>
            <?php
        }
        ?>
