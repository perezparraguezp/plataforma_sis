<?php
include 'config.php';
include 'objetos/persona.php';

$table = 'historial_psicomotor';
$sql1 = "select * from  $table";
$res1 = mysql_query($sql1);
while($row1 = mysql_fetch_array($res1)){
    $p = new persona($row1['rut']);
    $fecha_registro = $row1['fecha_registro'];
    $id_historial = $row1['id_historial'];
    $sql = "SELECT TIMESTAMPDIFF(DAY,'$p->fecha_nacimiento' , '$fecha_registro') AS dias;";
    $row = mysql_fetch_array(mysql_query($sql));
    if($row){
        $dias = $row['dias'];
        $sql2 = "update $table set edad_dias='$dias' where id_historial='$id_historial' ";
        mysql_query($sql2);
    }
}