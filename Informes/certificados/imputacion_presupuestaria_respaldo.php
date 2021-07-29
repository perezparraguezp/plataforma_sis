<?php

include("../../php/config.php");
include("../../php/objetos/functionario.php");
include("../../php/objetos/persona.php");

include("../../php/class/decreto.php");
require_once('../config/lang/cat.php');
require_once('../tcpdf.php');
session_start();
error_reporting(0);
$certificado = $_GET['certificado'];

$tipo_doc = 'CERTIFICADO IMPUTACION';

$sql_1 = "select * from firmantes_documentos where nombre_doc='$tipo_doc' limit 1";
$row_1 = mysql_fetch_array(mysql_query($sql_1));

$firmante = $row_1['nombre_firmante'];
$cargo = $row_1['cargo_firmante'];

$sql = "select * from compras_certificado_imputacion where id_certificado='$certificado' limit 1";
$row = mysql_fetch_array(mysql_query($sql));
$numero = $row['numero_certificado'];


$monto = $row['monto_certificado'];
$monto_vigente = $row['monto_vigente'];
$saldo_disponible = $row['saldo_disponible'];
$monto_comprometido = $row['monto_comprometido'];

$cuenta = $row['cuenta_general'];
$centro = $row['id_centro_costo'];
$unidad = $row['unidad'];
$anio = $row['anio'];

$licitacion = $row['cod_licitacion'];
$oc = $row['oc'];



$sql1 = "select * from pc_cuenta WHERE codigo_general='$cuenta' limit 1";
$row1 = mysql_fetch_array(mysql_query($sql1));
$nombre_cuenta = $row1['nombre_cuenta'];


$sql2 = "select * from pc_area_gestion inner join pc_centro_costo using(id_area_gestion) 
            where id_centro_costo='$centro' limit 1";
$row2 = mysql_fetch_array(mysql_query($sql2));
$nombre_area = $row2['nombre_area'];
$nombre_centro = $row2['nombre_centro'];








list($fecha,$hora) = explode(" ",$row['fecha_creacion']);


//Eliminamos los textos del documento
$titulo_documento = "<h4>CERTIFICADO DE IMPUTACION PRESUPUESTARIA<br />Nº $numero</h4>";

$anio = $_POST['anio_cert'];

$licitacion = $row['cod_licitacion'];
$oc = $row['orden_compra'];
if($licitacion!=''){
    $frase = ' indicados en las bases de la licitación: <strong>'.$licitacion.'</strong>';
}else{
    if($oc!=''){
        $frase = ' indicados en las bases de la licitación: <strong>'.$oc.'</strong>';
    }else{
        $frase = '.';
    }
}

$dias = Array("Domingo", "Lunes", "Martes", "Miercoles", "Jueves", "Viernes", "Sabado");
$meses = Array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
$a = date('Y');
$m = date('m');
$d = date('d');
$dia = diaSemana($a, $m, $d);

$fecha = $dias[$dia] . " " . $d . " de " . $meses[$m - 1] . " del " . $a;

function diaSemana($ano, $mes, $dia) {
    // 0->domingo	 | 6->sabado
    $diaX = date("w", mktime(0, 0, 0, $mes, $dia, $ano));
    return $diaX;
}

// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'iso-8859-1', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('I. Municipalidad de Carahue');
$pdf->SetTitle('Certificado');
$pdf->SetSubject('Decreto');
$pdf->SetKeywords('Decreto, PDF, documento, Documento');


$title_pdf = "I. Municipalidad de Carahue                                            ";
$sub_title_pdf = "Dir. de Administracion y Finanzas\nPortales #295, Carahue";
// set default header data
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, $title_pdf, $sub_title_pdf, array(0, 0, 0), array(0, 0, 0));
$pdf->setFooterData($tc = array(0, 0, 0), $lc = array(0, 0, 0));

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

//set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

//set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

//set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

//set some language-dependent strings
$pdf->setLanguageArray($l);

// ---------------------------------------------------------
// set default font subsetting mode
$pdf->setFontSubsetting(true);

// Set font
// dejavusans is a UTF-8 Unicode font, if you only need to
// print standard ASCII chars, you can use core fonts like
// helvetica or times to reduce file size.
$pdf->SetFont('dejavusans', '', 14, '', true);

// Add a page
// This method has several options, check the source code documentation for more information.
$pdf->AddPage();
// set text shadow effect
$pdf->setTextShadow(array('enabled' => true, 'depth_w' => 0.2, 'depth_h' => 0.2, 'color' => array(196, 196, 196), 'opacity' => 1, 'blend_mode' => 'Normal'));
// Set some content to print

$html = '
<style type="text/css">
    p{
        text-align:left;
        text-indent: 280px;
        font-size:12pt;
        margin-top: 0px;
    }
    BLOCKQUOTE{
        font-size:10pt;
    }
    table{
        font-size:8pt;
    }
    span{
        font-size:12pt;
        text-align: left;
        }
    li{
    font-size:10pt;
    }
    h4{
    text-align: center;;
    }
    h6{
    font-size: 1em;;
    text-align: center;;
    bottom: 10px;;
    position: absolute;;
    }
</style>
<p style="text-align: right;">FECHA <strong>'.$fecha.'</strong></p>
'.$titulo_documento.'

<span>De conformidad al presupuesto aprobado para este Municipio por el Concejo Municipal para el año '.$anio.', 
certifico que, a la fecha del presente documento,  esta institución cuenta con el presupuesto para el 
financiamiento de los bienes y/o servicios'.$frase.'</span>
<br />
<div style="font-size:0.9em;"><strong>'.$nombre_area.' - '.$nombre_centro.'</strong></div>
<br />
<table border="1" style="font-size:0.5em;">
    <tr style="background-color:#CCFDFF;font-weight: bold;">
        <td>ITEM</td>
        <td>CUENTA</td>
        <td>Ppto. Vigente</td>
        <td>Monto Comprom.</td>
        <td>Monto Documento</td>
        <td>Saldo Disponible</td>
    </tr>
    <tr>
        <td>'.$cuenta.'</td>
        <td>'.$nombre_cuenta.'</td>
        <td>$ '.number_format($monto_vigente,0,'','.').'</td>
        <td>$ '.number_format($monto_comprometido,0,'','.').'</td>
        <td>$ '.number_format($monto,0,'','.').'</td>
        <td>$ '.number_format($saldo_disponible,0,'','.').'</td>
    </tr>
</table>
<br />
<h5 style="text-align:center">'.$firmante.'<br/>'.$cargo.'</h5>
';
//echo $html;
//argamos los datos de este PDF

// Print text using writeHTMLCell()
$pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', $html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);

// ---------------------------------------------------------
// Close and output PDF document
// This method has several options, check the source code documentation for more information.
$pdf->Output('certificado.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+
