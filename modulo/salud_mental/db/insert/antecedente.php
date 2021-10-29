<?php
include '../../../../php/config.php';
include '../../../../php/objetos/mysql.php';

$rut = $_POST['rut'];//rut paciente
$fecha_registro = $_POST['fecha_registro'];
$fecha_inicio = $_POST['fecha_ingreso'];
$id_tipo = $_POST['tipo'];
$valor = $_POST['evaluacion'];
$obs = $_POST['obs'];
$myID = $_SESSION['id_usuario'];


$sql = "insert into paciente_antecedentes_sm(rut,fecha_ingreso,nombre_antecedente,obs_ingreso,id_profesional) 
              values('$rut','$fecha_inicio','$id_tipo',upper('$obs'),'$myID')";
mysql_query($sql);

