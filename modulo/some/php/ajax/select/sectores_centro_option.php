
<option>TODOS</option>
        <?php
        include '../../../../php/config.php';

        $centro = explode(",",$_POST['id_centro']);


        foreach ($centro as $i => $value){
            echo $value;
            $value = trim($value);
            if($value!=''){
                if($value!='TODOS'){
                    $sql = "select * from sectores_centros_internos
                                      inner join centros_internos using(id_centro_interno) 
                                      where id_centro_interno='$value' 
                                      order by nombre_sector_interno";
                    echo $sql;
                    $res = mysql_query($sql);
                    while($row = mysql_fetch_array($res)){
                        ?>
                        <option value="<?php echo $row['id_sector_centro_interno']; ?>"><?php echo $row['nombre_sector_interno']." [".$row['nombre_centro_interno']."]"; ?></option>
                        <?php
                    }
                }
            }

        }


        ?>
