<?php
#Include the connect.php file
include("../../config.php");

$id_establecimiento = $_SESSION['id_establecimiento'];

$sql = "select * from documento_establecimiento
          INNER JOIN tipo_documento USING(id_tipo_doc) 
          where id_establecimiento='$id_establecimiento'
          order by fecha_subida desc";

$res = mysql_query($sql);
$i = 0;
while($row = mysql_fetch_array($res)){
    $customers[] = array(
        'tipo' => $row['nombre_tipo_doc'],
        'obs' => $row['observaciones'],
        'link' => $row['ruta_documento'],
        'borrar' => $row['id_documento']
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
