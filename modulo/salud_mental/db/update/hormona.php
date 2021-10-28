<?php
include '../../../../php/config.php';
include '../../../../php/objetos/mysql.php';

$rut = $_POST['rut'];//rut paciente
$id_historial = $_POST['id_historial'];
$retiro_anticipado = $_POST['retiro_anticipado'];
$motivo_retiro = $_POST['motivo_retiro'];


$sql = "update mujer_historial_hormonal set 
            fecha_retiro_hormonal='$retiro_anticipado',
            estado_hormona='SUSPENDIDA',                                   
            obs_retiro_hormonal=upper('$motivo_retiro') 
            where id_historial='$id_historial' and rut='$rut'";

mysql_query($sql)or die('ERROR_SQL');