<?php
include '../../../../php/config.php';
include '../../../../php/objetos/persona.php';
$rut = $_POST['rut'];
$id = $_POST['id_gestacion'];
$value = $_POST['value'];
$fecha = $_POST['fecha_registro'];
$p = new persona($rut);

$p->update_mgestacion($id,'estado_gestacion',$value,$fecha);
echo 'ACTUALIZADO';