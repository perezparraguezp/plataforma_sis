<?php
#Include the connect.php file
include("../../config.php");

$id_establecimiento = $_SESSION['id_establecimiento'];

$sql = "select * from sector_comunal  
          where id_establecimiento='$id_establecimiento' order by nombre_sector_comunal ";

$res = mysql_query($sql);
$i = 0;
while($row = mysql_fetch_array($res)){
    $customers[] = array(
        'codigo' => $row['id_sector_comunal'],
        'borrar' => $row['id_sector_comunal'],
        'nombre' => $row['nombre_sector_comunal']
    );
    $i++;
}

if($i>0){
    $data[] = array(
        'TotalRows' => ''.$i,
        'Rows' => $customers
    );
    echo json_encode($data);
}

?>
