<?php
include "../../php/config.php";
include "../../php/objetos/documento.php";
include "../../php/objetos/functionario.php";
include "../../php/objetos/persona.php";
session_start();
$myId = $_SESSION['id_empleado'];
$f = new functionario($myId);

$titulo_superior = "Unidad de Vialidad Comunal                       ";
$titulo_inferior = $f->nombre_depto."\nMunicipalidad de Carahue";

$documento = new documento($titulo_superior,$titulo_inferior,'Unidad de Vialidad');

$html = '<style type="text/css">
    p,strong{
        text-align:left;
        font-size:.7em;
        padding: 0px;
        margin: 0px;
        clear: top;
    }
    ul,li{
        font-size:.7em;
        padding: 0px;
        margin: 0px;
        clear: top;
        text-align: left;
    }
    blockquote,strong{
        font-size:10pt;
    }
    table{
        font-size:8pt;
    }
    h3{
        font-size:1.2em;;
        text-align: center;
        }
</style>
<h3>REGISTRO DE SOLICITUDES PENDITES SIN ASIGNAR</h3>
<table style="border: solid 1px black;" width="100%;" border="1px">
<tr style="background-color: #d7efff">
    <td style="width: 5%;">DIAS</td>
    <td style="width: 15%">SECTOR</td>
    <td style="width: 20%">USUARIO</td>
    <td style="width: 20%">SOCIAL</td>
    <td style="width: 20%">DETALLE</td>
    <td style="width: 20%">ASIGNACION</td>
</tr>';
$sql0 = "select * from camion_tareas 
          where estado!='FINALIZADA' AND estado!='ASIGNADA' 
          order by fecha_creacion desc";
$res0 = mysql_query($sql0);
while($row0 = mysql_fetch_array($res0)){
    $id_sector = $row0['id_sector'];
    $p = new persona($row0['rut_destinatario']);

    $sql1 = "SELECT DATEDIFF (sysdate(),'".$row0['fecha_modificacion']."') as pendiente;";
    $row1 = mysql_fetch_array(mysql_query($sql1));

    $sql2 = "select * from sectores_carahue where id_sector='$id_sector' limit 1";
    $row2 = mysql_fetch_array(mysql_query($sql2));
    if($row2){
        $sector = $row2['nombre_sector'];
    }else{
        $sector = 'NO DEFINIDO';
    }
    if($p->existe){
        $html .= '<tr>
        <td>'.$row1['pendiente'].'</td>
        <td>TIPO:<br /><strong>'.$row0['tipo_tarea'].'</strong><br />
        SECTOR:<br /><strong>'.$sector.'</strong></td>
        <td>RUT:'.$p->rut.'<br />
            Nombre:'.$p->nombre_completo.'<br />
            Telefono:'.$p->telefono.'<br />
        </td>
        <td>'.$row0['social'].'</td>
        <td>'.$row0['detalle_tarea'].'</td>
        <td></td>
    </tr>';
    }



}
$html .='
</table>
';




//echo $html;
$documento->CrearPDF_Horizontal($html);