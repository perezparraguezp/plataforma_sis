<?php
include '../../../../php/config.php';
include '../../../../php/objetos/mysql.php';

$rut = $_POST['rut'];//rut paciente
$tipo = $_POST['tipo_eco'];
$fecha = $_POST['fecha_eco'];
$trimestre = $_POST['trimestre'];
$id_gestacion = $_POST['id_gestacion'];
$obs = $_POST['obs'];


$mysq = new mysql($_SESSION['id_usuario']);
$mysq->insert_ecografia($id_gestacion,$rut,$tipo,$fecha,$trimestre,$obs);