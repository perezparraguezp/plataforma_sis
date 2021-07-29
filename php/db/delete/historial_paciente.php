<?php
include '../../config.php';
include '../../objetos/mysql.php';

$id = $_POST['id'];
$id_profesional = $_SESSION['id_usuario'];


$sql = "delete from historial_paciente where id_historial='$id' and id_profesional='$id_profesional'";
mysql_query($sql)or die('ERROR_SQL');


