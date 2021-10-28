<?php
include '../../../../php/config.php';
include '../../../../php/objetos/persona.php';

$rut = $_POST['rut'];//rut paciente
$id = $_POST['id_examen'];
$tipo = $_POST['tipo'];

$paciente = new persona($rut);
$paciente->deleteExamen_M($tipo,$id);