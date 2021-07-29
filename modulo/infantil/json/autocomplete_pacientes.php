<?php
#Include the connect.php file
include("../../../php/config.php");
include("../../../php/objetos/persona.php");
error_reporting(0);
session_start();
$search = $_GET['query'];

$sql = "select * from persona 
        inner join paciente_establecimiento using(rut)
        where m_infancia='SI' AND (upper(persona.nombre_completo)
        like upper('%$search%') 
        or upper(persona.rut) like upper('$search%'))
        group by rut";
$res = mysql_query($sql);
$i = 0;
while($row = mysql_fetch_array($res)){
    $customers[] = array(
        'rut' => $row['rut'],
        'nombre' => $row['nombre_completo'],
    );
    $i++;
}

echo json_encode($customers);
?>