<?php
include '../../../../php/config.php';
include '../../../../php/objetos/persona.php';
$rut = $_POST['rut'];
$column = $_POST['column'];
$value = $_POST['value'];
$fecha = $_POST['fecha_registro'];
$p = new persona($rut);
$p->update_parametro_am($column,$value,$fecha);
echo 'ACTUALIZADO';