<?php

include "../../php/config.php";
include "../../php/objetos/documento.php";
include "../../php/objetos/functionario.php";
include "../../php/objetos/persona.php";

session_start();
error_reporting(0);
//Eliminamos los textos del documento

$id_tarea = $_POST['id_tarea'];

$sql = "select * from camion_tareas where id_tarea='$id_tarea' limit 1";
$row = mysql_fetch_array(mysql_query($sql));


$f = new functionario($row['id_creador']);
$p = new persona($row['rut_destinatario']);


$dias = Array("Domingo", "Lunes", "Martes", "Miercoles", "Jueves", "Viernes", "Sabado");
$meses = Array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");


list($a,$m,$d) = explode("-",$row['fecha_modificacion']);

$dia = diaSemana($a, $m, $d);

$fecha_atencion = $dias[$dia] . " " . $d . " de " . $meses[$m - 1] . " del " . $a;

$titulo_superior = "Certificado de Atención                       ";
$titulo_inferior = $f->nombre_depto."\nMunicipalidad de Carahue";

$documento = new documento($titulo_superior,$titulo_inferior,'Certificado');


$sql1 = "select * from sectores_carahue where id_carahue='".$row['id_sector']."' limit 1";
$row1 = mysql_fetch_array(mysql_query($sql1));
if($row1){
    $sector = $row1['nombre_sector'];
}else{
    $sector = 'NO DEFINIDO';
}



$html = '
<style type="text/css">
h3{
font-size: 1.3em;
text-align: center;

}
table tr td{
font-family: Roboto, HelveticaNeue, sans-serif;
font-size: .9em;;
text-indent: 20px;
line-height: 1.2em;;
height: 25px;;
}
p{

}
</style>
<h3>REGISTRO DE ATENCIÓN</h3>
<table width="100%" border="1px">
<tr>
    <td style="width: 30%;">Fecha Registro</td>
    <td style="width: 70%;">Atendido Por</td>
</tr>
<tr>
    <td>'.fechaNormal($row['fecha_modificacion']).'</td>
    <td>'.$f->nombre_completo.'</td>
</tr>
<tr>
    <td></td>
    <td></td>
</tr>
<tr style="background-color: #d7efff;font-weight: bold;">
    <td colspan="2">DATOS DE CONTACTO</td>
</tr>
<tr>
    <td>RUT</td>
    <td>'.$row['rut_destinatario'].'</td>
</tr>
<tr>
    <td>NOMBRE USUARIO</td>
    <td>'.$p->nombre_completo.'</td>
</tr>
<tr>
    <td>TELEFONO</td>
    <td>'.$p->telefono.'</td>
</tr>
<tr>
    <td>SECTOR</td>
    <td>'.$p->sector.'</td>
</tr>
<tr style="background-color: #d7efff;font-weight: bold;">
    <td colspan="2">MOTIVO SOLICITUD</td>
</tr>
<tr>
    <td colspan="2" style="height: auto;">'.$row['detalle_tarea'].'<br /></td>
</tr>
</table>
<p></p>
<hr width="100%" />
<p></p>
<p></p>
<p></p>
<table width="100%" style="font-size: 0.7em;">
<tr>
    <td style="text-align: center;">FIRMA DEL SOLICITANTE</td>
    <td style="text-align: center;">'.$f->nombre_completo.'</td>
</tr>
</table>
<p></p>
<p></p>
<p></p>
<p></p>
<p></p>
<p></p>
<p></p>
<p></p>
<p></p>
<p></p>
<p></p>
<p></p>
<p></p>
<p></p>
<p></p>
<p></p>
<p></p>
<p></p>
<p></p>
<p></p>
<p></p>
<p></p>
<p></p>
<hr width="100%" />
<p style="font-size: .5em;">La Municipalidad de Carahue almacenara los datos suministrados para ser empleados
de manera estadistica y de contacto, los cuales podrán ser eliminados de nuestros registros en caso de que la persona
lo solicite.</p>
';

//echo $html;

$documento->CrearPDF(trim(($html)));
