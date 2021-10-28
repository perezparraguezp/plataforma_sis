<?php
include '../../../../php/config.php';
include '../../../../php/objetos/mysql.php';
session_start();
$myId = $_SESSION['id_usuario'];
$rut = $_POST['rut'];//rut paciente

$fecha = $_POST['fecha_control'];
$obs = $_POST['obs_control'];
$tipo = $_POST['tipo_control'];
$evaluacion = $_POST['evaluacion'];
$id_gestacion = $_POST['id_gestacion'];

$sql = "insert into control_nutricional_gestacion(id_gestacion,imc,tipo_control,fecha_control,obs_control,id_profesional) 
        values('$id_gestacion','$evaluacion','$tipo','$fecha',upper('$obs'),'$myId')";
mysql_query($sql);