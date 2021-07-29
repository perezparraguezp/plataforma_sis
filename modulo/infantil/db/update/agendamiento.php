<?php
include "../../../../php/config.php";
include "../../../../php/objetos/persona.php";

$id = $_POST['id'];
$estado = 'REALIZADA';
$historial = 'CITA FINALIZADA A LAS '.date('d-m-Y h:m');
$sql = "update agendamiento set
                        estado_control='$estado',
                        historial=concat(historial,'$historial') 
                where id_agendamiento='$id' 
                limit 1";
$row = mysql_fetch_array(mysql_query($sql));
echo "ACTUALIZADO";