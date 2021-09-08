<?php
include '../../../../php/config.php';
include '../../../../php/objetos/persona.php';
$table = $_POST['table'];
$id = $_POST['id'];
$rut = $_POST['rut'];
$p = new persona($rut);
$p->delete_registro_historial($table,$id);
