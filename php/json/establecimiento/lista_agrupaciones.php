<?php
#Include the connect.php file
include("../../config.php");
include("../../objetos/establecimiento.php");

$id_establecimiento = $_SESSION['id_establecimiento'];

$sql = "select * from agrupacion_escolar
          INNER JOIN tipo_agrupacion USING(id_tipo_agrupacion) 
          order by fecha_hasta asc";

$res = mysql_query($sql);
$i = 0;
while($row = mysql_fetch_array($res)){
    $e = new establecimiento($row['id_establecimiento']);

    $customers[] = array(
        'comuna' => $e->comuna,
        'establecimiento' => $e->nombre,
        'tipo' => $row['nombre_tipo_agrupacion'],
        'hasta' => $row['fecha_hasta'],
        'link' => 'X'
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
