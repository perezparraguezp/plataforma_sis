<?php
#Include the connect.php file
include("../../../php/config.php");

$id_establecimiento = $_SESSION['id_establecimiento'];

$sql = "SELECT * from centros_internos
          inner join sector_comunal on centros_internos.id_sector_comunal=sector_comunal.id_sector_comunal 
          where centros_internos.id_establecimiento='$id_establecimiento'
          order by nombre_centro_interno asc";


$res = mysql_query($sql);
$i = 0;
while($row = mysql_fetch_array($res)){
    $customers[] = array(
        'centro' => $row['nombre_centro_interno'],
        'sector_comunal' => $row['nombre_sector_comunal'],
        'direccion' => $row['direccion_centro_interno'],
        'borrar' => $row['id_centro_interno'],
        'link' => $row['id_centro_interno'],
        'codigo' => $row['id_centro_interno'],
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
