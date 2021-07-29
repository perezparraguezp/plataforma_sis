<?php
#Include the connect.php file
include("../../config.php");
include("../../objetos/establecimiento.php");

$sql = "select * from establecimiento 
        order by nombre_establecimiento";
$res = mysql_query($sql);
$i = 0;
while($row = mysql_fetch_array($res)){
    $e = new establecimiento($row['id_establecimiento']);
    $customers[] = array(
        'comuna' => $e->comuna,
        'nombre' => $e->nombre,
        'tipo' => $e->tipo,
        'accesibilidad' => $e->acceso_universal,
        'estacionamiento' => $e->estacionamiento,
        'patio_techado' => $e->patio_techado,
        'gym' => $e->gym,
        'humanista' => $e->humanista,
        'tecnico_profesional' => $e->tecnico_profesional,
        'biblioteca' => $e->biblioteca,
        'internado' => $e->internado,
        'jardin_infantil' => $e->jardin_infantil,
        'sala_cuna' => $e->sala_cuna,
        'lab_computacion' => $e->lab_computacion,
        'lab_quimica' => $e->lab_quimica,
        'info' => '<i onclick="boxInfoEstablecimiento(\''.$e->id.'\')" class="mdi-action-info" style="font-size: 0.9em;cursor: pointer;"></i>'
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
