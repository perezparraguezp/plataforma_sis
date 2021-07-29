<?php

include("../../php/config.php");
include("../../php/objetos/functionario.php");
include("../../php/objetos/documento.php");
require_once('../config/lang/cat.php');
require_once('../tcpdf.php');
session_start();
error_reporting(0);

$listado = $_POST['lista'];





$listado = explode("#",$listado);
foreach($listado as $i => $value) {
    if ($value != '') {
        $listado = "#" . $value;
        $sql3 = "select * from viaticos where folio_documento='$value' limit 1";
        $row3 = mysql_fetch_array(mysql_query($sql3));
        $f = new functionario(($row3['id_empleado']));

    }

}

$documento = new documento('I. Municipalidad de Carahue','Pago de Viaticos','decreto_pago');

$html = '
<style type="text/css">
    p{
       text-align:justify;
        text-indent: 280px;
        font-size:0.7em;
        margin-top: -10px;
        
 
    }
    BLOCKQUOTE{
        font-size:1em;
    }
    table{
        font-size:0.7em;
    }
    span{
        font-size:10pt;
        text-align: right;
        }
    li{
    font-size:1.2em;
    margin-top: 5px;
    font-weight: bold;
    }
     strong{
    font-size: 0.6em;
    }
</style>
<p style="text-indent: 280px;">DECRETO DE PAGO Nº: <strong>'.$numero_dp.' / '.$anio.'</strong></p>
<p style="text-indent: 280px;">Carahue, '.date('d/m/Y').'</p>        


<p><strong>Vistos</strong></p>
<p>1.- El Decreto Alcaldicio N° xxxxx de fecha xxxxx de xxxxx que aprueba el presupuesto para el año xxx
<br/>2.- La Resolucion N° 18 Art. N°18 tramitacion en linia de derechos y resoluciones relativos a materia de personal.
<br>3.- La planilla de pago de cometidos de funcionarios de plata y contrata realizado durante el mes de (mes) xxx a cancelar en (mes ) xxx
 <br>4.- Las facultades que de confiere el texto refundido de la ley N°18.695,"Organica Constitucional de Municipalidades".</p>
<p style="font-size:1em;text-align:center;"><strong>DECRETO</strong></p>
<p>1.- Apruebese el pago de cometidos de los funcionarios municipales de palnta y contrata segun la siguente tabla.<br />
</p>    

<table width="100%" border="1px" style="font-size: 0.6em;">
     <tr style="background-color: antiquewhite; font-weight: bold;">
        <td style="width: 50px;">Folio</td>
        <td style="width: 60px;">fecha viatico</td>
        <td style="width: 200px;">Departamento</td>
        <td style="width: 60px;">hora salida</td>
        <td style="width: 60px;">hora llegada</td>
          <td style="width: 60px;">Monto</td>
            <td style="width: 60px;">Adicional</td>
        <td style="width: 70px;">Lugar</td>
     
    </tr>
       <td style="width: 50px;">'.$value.'</td>
                            <td style="width: 60px;">'.$row3['fecha_viatico'].'</td>
                            <td style="width: 200px;">'.$f->nombre_depto.' ['.$f->nombre.']</td>
                            <td style="width: 60px;">'.$row3['hora_salida'].'</td>
                            <td style="width: 3px;">'.$row3['hora_llegada'].'</td>
                              <td style="width: 3px;">'.$row3['monto_viatico'].'</td>
                                <td style="width: 3px;">'.$row3['monto_adicional'].'</td>
                            <td style="width: 3px;">'.$row3['lugar_viatico'].'</td>
               
                        </tr><tr><td colspan="8"></td></tr>
                        
                        
                       
        </table>
        
   



<p>2.- Para todos los afectos legales la planilla de pago pasa a formar parte integrante 
del presente decreto de pago de viaticos y pasajes del personal municipal.
<br/>3.-Los gastos ocasionados por el presente decreto seran imputados al item xxxxxxxx y xxxxxx.
</p>
        
        
        <blockquote>ANOTESE, REGISTRESE,COMUNIQUESE Y ARCHIVE </blockquote> 

<p></p>
<p></p>




';


$documento->CrearPDF($html);