<?php
include '../../../../php/config.php';
include '../../../../php/objetos/persona.php';

$rut = $_POST['rut'];//rut paciente
$id_gestacion = $_POST['id_gestacion'];
$fecha_registro = $_POST['fecha_registro'];

$inicio = $_POST['inicio'];
$termino = $_POST['termino'];
$proyectada = $_POST['proyectada'];
$bebes = $_POST['cantidad_bebes'];
$obs_generales = $_POST['obs_generales'];

$paciente = new persona($rut);

$sql1 = "update gestacion_mujer 
                            set 
                                fecha_inicio='$inicio',
                                fecha_termino='$termino',
                                fecha_proyectada='$proyectada',
                                candidad_bebes='$bebes',
                                obs_gestacion=upper('$obs_generales')
                            where id_gestacion='$id_gestacion' 
                            and rut='$rut' ";
mysql_query($sql1)or die('ERROR_SQL');
echo 'ACTUALIZADO';