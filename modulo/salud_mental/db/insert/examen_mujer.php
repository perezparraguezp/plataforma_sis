<?php
include '../../../../php/config.php';
include '../../../../php/objetos/persona.php';
session_start();
$rut = $_POST['rut'];//rut paciente

$fecha = $_POST['fecha'];
$tipo_examen = $_POST['tipo_examen'];
$origen_examen = $_POST['origen_examen'];
$valor_examen = $_POST['valor_examen'];
$obs = $_POST['obs'];


$paciente = new persona($rut);

$paciente->insertExamen_M($origen_examen,$tipo_examen,$fecha,$valor_examen,$obs);
echo 'REGISTRADO';
