<?php
#Include the connect.php file
include("../../../php/config.php");

$id_establecimiento = $_SESSION['id_establecimiento'];
$id_centro_interno = $_GET['id_centro_interno'];

$sql = "SELECT * from centros_internos
          inner join sector_comunal on centros_internos.id_sector_comunal=sector_comunal.id_sector_comunal
          inner join sectores_centros_internos on centros_internos.id_centro_interno=sectores_centros_internos.id_centro_interno 
          where centros_internos.id_establecimiento='$id_establecimiento'
          and sectores_centros_internos.id_centro_interno='$id_centro_interno'
          order by nombre_sector_interno asc";


$res = mysql_query($sql);
$i = 0;
while($row = mysql_fetch_array($res)){
    $customers[] = array(
        'codigo' => $row['id_sector_centro_interno'],
        'nombre' => $row['nombre_sector_interno'],
        'borrar' => $row['id_sector_centro_interno']
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
