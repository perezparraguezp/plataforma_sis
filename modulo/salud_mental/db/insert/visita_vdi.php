<?php
include '../../../../php/config.php';
include '../../../../php/objetos/mysql.php';
session_start();
$rut = $_POST['rut'];//rut paciente

$fecha = $_POST['fecha_vdi'];
$obs = $_POST['obs_vdi'];
$id_gestacion = $_POST['id_gestacion'];


$mysq = new mysql($_SESSION['id_usuario']);
$mysq->insert_VDI($id_gestacion,$fecha,$obs,$rut);