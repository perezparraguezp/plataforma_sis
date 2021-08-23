<?php
include '../../../../php/config.php';
include '../../../../php/objetos/mysql.php';

$rut = $_POST['rut'];//rut paciente
$id_historial = $_POST['id_historial'];

$mysq = new mysql($_SESSION['id_usuario']);
$mysq->deleteHormona($rut,$id_historial);