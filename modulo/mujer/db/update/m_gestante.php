<?php
include '../../../../php/config.php';
include '../../../../php/objetos/persona.php';

$rut = $_POST['rut'];
$column = $_POST['column'];
$value = $_POST['val'];
$fecha = $_POST['fecha_registro'];
$id_gestacion = $_POST['id_gestacion'];

$p = new persona($rut);

$p->update_mgestacion($id_gestacion,$column,$value,$fecha);
echo 'ACTUALIZADO';