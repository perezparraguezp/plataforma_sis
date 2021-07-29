<?php
#Include the connect.php file
include("../../config.php");

$id_establecimiento = $_SESSION['id_establecimiento'];

$sql = "SELECT * from mis_atributos_establecimientos 
          INNER JOIN atributo_establecimiento USING(id_atributo)
          where id_establecimiento='$id_establecimiento'
          order by nombre_atributo asc";

$res = mysql_query($sql);
$i = 0;
while($row = mysql_fetch_array($res)){
    $customers[] = array(
        'tipo' => $row['tipo_atributo'],
        'atributo' => $row['nombre_atributo'],
        'valor' => $row['valor'],
        'link' => $row['ruta_documento'],
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
