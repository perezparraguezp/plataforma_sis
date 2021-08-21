<?php
include '../../../../php/config.php';
include '../../../../php/objetos/persona.php';
$rut = $_POST['rut'];
$column = $_POST['column'];
$value = $_POST['value'];
$fecha = $_POST['fecha_registro'];
$amigable = $_POST['amigable'];
$p = new persona($rut);
$p->update_conserjeria_ad_amigable($column,$value,$amigable,$fecha);
echo 'ACTUALIZADO';