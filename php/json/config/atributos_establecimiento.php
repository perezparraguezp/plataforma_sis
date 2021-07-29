<?php
#Include the connect.php file
include("../../config.php");

$id_establecimiento = $_SESSION['id_establecimiento'];

$sql = "select * from atributo_establecimiento 
          order by nombre_atributo ASC ";

$res = mysql_query($sql);
$i = 0;
while($row = mysql_fetch_array($res)){
    $customers[] = array(
        'tipo' => $row['tipo_atributo'],
        'nombre' => $row['nombre_atributo'],
        'texto' => $row['descripcion_atributo'],
        'estado' => $row['estado_atributo'],
        'borrar' => $row['id_atributo']
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
