<?php
#Include the connect.php file
include("../../config.php");

$id_establecimiento = $_SESSION['id_establecimiento'];

$sql = "SELECT * from centros_internos
          where id_establecimiento='$id_establecimiento'
          order by nombre_centro_interno asc";

$res = mysql_query($sql);
$i = 0;
while($row = mysql_fetch_array($res)){
    $customers[] = array(
        'centro' => $row['nombre_centro_interno'],
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
