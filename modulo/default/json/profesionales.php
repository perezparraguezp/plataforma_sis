<?php
#Include the connect.php file
include("../../../php/config.php");

$id_establecimiento = $_SESSION['id_establecimiento'];

$sql = "SELECT * from personal_establecimiento 
          inner join persona using(rut) 
          inner join usuarios on persona.rut=usuarios.rut
          where id_establecimiento='$id_establecimiento'
          order by nombre_completo asc";

$res = mysql_query($sql);
$i = 0;
while($row = mysql_fetch_array($res)){
    $vencimiento = $row['fecha_termino'];
    if($row['indefinido']=='SI'){
        $vencimiento = 'INDEFINIDO';
    }
    $customers[] = array(
        'id_profesional' => $row['id_profesional'],
        'codigo' => $row['id_profesional'],
        'rut' => $row['rut'],
        'nombre' => $row['nombre_completo'],
        'tipo' => $row['tipo_contrato'],
        'email' => $row['email'],
        'vencimiento' => $vencimiento,
        'horas' => $vencimiento,
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
