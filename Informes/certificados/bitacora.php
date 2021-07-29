<?php

include "../../php/config.php";
include "../../php/objetos/documento.php";
include "../../php/objetos/functionario.php";
include "../../php/objetos/persona.php";

session_start();
error_reporting(0);
//Eliminamos los textos del documento

$patente = $_POST['patente'];

$dias = Array("Domingo", "Lunes", "Martes", "Miercoles", "Jueves", "Viernes", "Sabado");
$meses = Array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");


list($a,$m,$d) = explode("-",date('Y-m-d'));

$dia = diaSemana($a, $m, $d);

$fecha = $dias[$dia] . " " . $d . " de " . $meses[$m - 1] . " del " . $a;

$titulo_superior = "Bitacora de Trabajo                       ";
$titulo_inferior = $f->nombre_depto."\nMunicipalidad de Carahue";

$documento = new documento($titulo_superior,$titulo_inferior,'Bitacora');


$html = '
<style type="text/css">
    
    BLOCKQUOTE{
        font-size:10pt;
    }
    table{
        font-size:8pt;
        width: 100%;
    }
    table tr{
    height: 30px;;
    }
    span{
        font-size:12pt;
        text-align: left;
        
        }
    li{
    font-size:10pt;
    }
    h5{
    text-align: center;;
    }
    h6{
    font-size: 1em;
    text-align: center;
    bottom: 10px;
    position: absolute;
    
    }
</style>
<h6>BITACORA ['.$patente.']</h6>
<p style="text-align: right;">FECHA <strong>'.$fecha.'</strong></p>
<p><strong>Nombre del Conductor:__________________________________________________________________</strong></p>
<p><strong>Kilometraje Inicio:___________________</strong> | <strong>Kilometraje Inicio:___________________</strong></p>
<table border="1" style="width:100%;">
    <tr style="background-color: #d7efff;font-weight: bold;">
        <td style="width:5%;">ID</td>
        <td style="width:10%;">PRIORIDAD</td>
        <td style="width:35%;">ACTIVIDAD</td>
        <td style="width:30%;">PERSONA</td>
        <td style="width:10%;">FIRMA RECEPCION</td>
        <td style="width:10%;">CANTIDAD</td>
    </tr>
    ';
$sql1 = "select * from camion_tareas 
        where patente like '%$patente%' 
        and estado like 'ASIGNADA%' 
        ";
//echo $sql1;
$res1 = mysql_query($sql1);

while($row1 = mysql_fetch_array($res1)){
    $p = new persona($row1['rut_destinatario']);
$html.=' <tr>
 <td style="text-align: center;">'.$row1['id_tarea'].'</td>
            <td style="text-align: center;">'.$row1['prioridad'].'</td>
            <td>'.$row1['detalle_tarea'].'</td>
            <td>Usuario:<br />'.$p->nombre_completo.'<br />Telefono:<br />'.$p->telefono.'</td>
            <td></td>
            <td></td>
        </tr>';
}
$html.='
</table>
<br />
<br />
<br />
<h5>FIRMA CONDUCTOR</h5>
';

//echo $html;

$documento->CrearPDF_Horizontal(trim(($html)));
