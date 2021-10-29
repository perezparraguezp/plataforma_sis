<?php
include '../../../../php/config.php';
include '../../../../php/objetos/persona.php';

$rut = $_POST['rut'];//rut paciente
$id = $_POST['id'];


$sql = "delete from paciente_antecedentes_sm where id='$id' and rut='$rut' limit 1";
mysql_query($sql);