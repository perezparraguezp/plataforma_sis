<?php
include '../../../../php/config.php';
include '../../../../php/objetos/mysql.php';

$rut = $_POST['rut'];//rut paciente
$tipo = $_POST['tipo_hormona'];
$vencimiento = $_POST['vencimiento'];


$mysq = new mysql($_SESSION['id_usuario']);
$mysq->insert_hormona($rut,$tipo,$vencimiento);