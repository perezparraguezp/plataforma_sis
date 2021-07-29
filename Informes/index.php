<?php
include("../php/conex.php");
$sql = "create table mis_descuentos_voluntarios(
id_descto_voluntario int,
id_empleado int,
fecha_asignacion date,
monto varchar(100),
primary key(id_descto_voluntario,id_empleado,fecha_asignacion)
)";
mysql_query($sql);
?>