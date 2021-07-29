<?php
include '../../config.php';
include '../../objetos/persona.php';

$rut = $_POST['rut'];
$fecha_registro = $_POST['fecha_registro'];
$pe = $_POST['pe'];
$pt = $_POST['pt'];
$te = $_POST['te'];
$dni = $_POST['dni'];
$imce = $_POST['imce'];
$pcint = $_POST['pcint'];
$rimaln = $_POST['rimaln'];
$ira = $_POST['ira'];
$lme = $_POST['lme'];
$presion_arterial = $_POST['presion_arterial'];

$paciente = new persona($rut);
$paciente->insertAntropometria($pe,$pt,$te,$dni,$imce,$pcint,$lme,$rimaln,$ira,$fecha_registro);

