<?php
include '../../../../php/config.php';
include '../../../../php/objetos/persona.php';

$rut = $_POST['rut'];//rut paciente
$id_gestacion = $_POST['id_gestacion'];
$fecha_registro = $_POST['fecha_registro'];

$estado = $_POST['estado'];

$paciente = new persona($rut);

$sql1 = "update gestacion_mujer 
                            set 
                                estado_gestacion='$estado'
                            where id_gestacion='$id_gestacion' 
                            and rut='$rut' ";
mysql_query($sql1)or die('ERROR_SQL');
echo 'ACTUALIZADO';