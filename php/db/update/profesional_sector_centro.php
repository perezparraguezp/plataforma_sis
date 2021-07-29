<?php
include "../../config.php";

$vacuna = $_POST['vacuna'];
if($vacuna == 'true'){
    $vacuna = 1;
}else{
    $vacuna = 0;
}
$rut = $_POST['rut'];
$sql = "select * from vacunas_paciente where rut='$rut' limit 1";
$row = mysql_fetch_array(mysql_query($sql));
if($row){
    $sql1 = "update vacunas_paciente set 12m='$vacuna' where rut='$rut' limit 1";
}else{
    $sql1 = "insert into vacunas_paciente(rut,12m) values(upper('$row'),'$vacuna')";
}
mysql_query($sql1);