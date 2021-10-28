<?php
include '../../../../php/config.php';
include '../../../../php/objetos/persona.php';

$rut = $_POST['rut'];
$column = $_POST['column'];
$value = $_POST['value'];
$fecha = $_POST['fecha_registro'];
$id_gestacion = $_POST['id_gestacion'];
$obs = $_POST['obs_psicosocial'];

$p = new persona($rut);

$p->update_mgestacion($id_gestacion,$column,$value,$fecha);
$p->insert_historial_m_obs($column,$value,$fecha,$id_gestacion,$obs);

echo 'ACTUALIZADO';