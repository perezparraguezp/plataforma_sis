<?php

include("../../php/conex.php");
include("../../php/objetos/funciones.php");
include("../../php/objetos/persona.php");
include("../../php/objetos/documento.php");
include("../../php/objetos/plantilla_pago.php");
require_once('../config/lang/cat.php');
require_once('../tcpdf.php');
session_start();
error_reporting(0);


$plantilla_pago = new plantilla_pago();

$plantilla_pago->crearPlantillaPago('DECRETOS');
$numero_plantilla = $plantilla_pago->id_plantilla;


$listado = $_POST['list_pagos'];

$titulo_superior = "Plantilla de Pago                    ";
$titulo_inferior = "Pago de Otros Documentos  \nMunicipalidad de Carahue";
$documento = new documento($titulo_superior,$titulo_inferior,'Plantilla de Pago');
$item = explode("#",$listado);
$html='<style type="text/css">
        p{
            text-align:left;
            text-indent: 280px;
            font-size:1em;;
            margin-top: -10px;
        }
        blockquote,strong{
            font-size:10pt;
        }
        table{
            font-size:8pt;
        }
        table tr td{
        padding-top: 3px;;
        line-break: initial;
        
        }
        span{
            font-size:10pt;
            text-align: right;
            }
        ol li{
        font-size:10pt;
        }
        h4{
        text-align: center;
        }
    </style>
    
    <h4>PLANTILLA DE PAGO<br />Nº '.$numero_plantilla.'/'.date('Y').'</h4>
    <p>A continuación se detalla el listado de los documentos a los cuales se realizara el proceso de pago:</p>

    <table  width="100%" border="1px">
            <tr style="background-color: #fdff8b;font-weight: bold;padding: 5px;">
                <td style="width: 10%;">N° Decreto</td>
                <td style="width: 10%;">Fecha Decreto</td>
                <td style="width: 10%;">TIPO Decreto</td>
                <td style="width: 20%;">Tipo Documento a Pagar</td>
                <td style="width: 10%;">Numero Documento</td>
                <td style="width: 10%;">Monto Total</td>
                <td style="width: 30%;">Detalle Pago</td>
            </tr>
            
';
$sumatorianeto = 0;
$sumatoriaimpuesto = 0;
$sumatoriabruto = 0;



foreach ($item as $i => $id_documento) {
    if ($id_documento != '') {
        $sql1 = "select * from otros_documentos_pago where id_numero_decreto='$id_documento' limit 1";

        $ds = mysql_fetch_array(mysql_query($sql1));
        $sumatoriabruto = $sumatoriabruto + $ds['monto'];

        $tipo_decreto = $ds['tipo_decreto'];
        $numero_decreto = $ds['decreto'];
        $fecha_decreto = $ds['fecha'];

        $sql2 = "select * from decretos 
                    where upper(tipo_decreto)=upper('$tipo_decreto')  
                    and fecha_decreto='$fecha_decreto' 
                    and numero_decreto='$numero_decreto'  
                    limit 1";
        $row2 = mysql_fetch_array(mysql_query($sql2));
        if($row2){
            $detalle_decreto = limpiaCadena($row2['texto_afectado']);

        }else{
            $detalle_decreto = '';
        }

        $html .= '
                <tr >
                    <td style="text-align: right;">' . $ds['decreto'] . '</td>
                    <td style="text-align: center;">' . fechaNormal($ds['fecha']) . '</td>
                    <td >' . $ds['tipo_decreto'] . '</td>
                    <td >' . $ds['tipo_documento'] . '</td>
                    <td style="text-align: right;">' . $ds['numero_documento'] . '</td>
                    <td style="text-align: right;" >$ '. number_format($ds['monto'],0,'','.') . '</td>
                    <td >' . $detalle_decreto . '</td> 
                </tr>
                
        ';
        $plantilla_pago->insertDocumento($id_documento);
    }
}
$plantilla_pago->calcularMontoPlantilla();
$html.='<tr>
            <td colspan="5" style="background-color: #fdff8b;text-align: right;font-weight: bold">TOTAL GENERAL</td>
            <td style="text-align: right">$ '.number_format($sumatoriabruto,0,'','.').'</td>
            
        </tr>
</table>';

$documento -> CrearPDF_Horizontal($html);


?>