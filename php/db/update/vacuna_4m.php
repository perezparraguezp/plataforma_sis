<?php
include "../../config.php";
include "../../objetos/persona.php";

$vacuna = $_POST['vacuna'];
$rut = $_POST['rut'];

if($vacuna == 'true'){
    $vacuna = 1;
}else{
    $vacuna = 0;
}

$sql = "select * from vacunas_paciente where rut='$rut' limit 1";
$row = mysql_fetch_array(mysql_query($sql));
if($row){
    $sql1 = "update vacunas_paciente set 4m='$vacuna' where rut='$rut' limit 1";
}else{
    $sql1 = "insert into vacunas_paciente(rut,4m) values(upper('$rut'),'$vacuna')";
}
mysql_query($sql1);
$paciente = new persona($rut);

$paciente->addHistorial('SE REGISTRO MODIFICACION EN LA VACUNA DE 4 MESES','VACUNAS');