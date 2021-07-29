<?php

include("../../php/conex.php");
require_once('../config/lang/cat.php');
require_once('../tcpdf.php');
session_start();
error_reporting(0);

$rut = $_POST['rut_contribuyente'];
$sql0 = "insert into boletas(fecha_creacion,hora_creacion,rut_persona,estado) 
            values(current_date(),current_time(),'$rut','GENERADA')";
mysql_query($sql0);
$row0 = mysql_fetch_array(mysql_query("select * from boletas where rut_persona='$rut' order by folio desc limit 1"));
$folio = $row0['folio'];

// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'iso-8859-1', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('I. Municipalidad de Carahue');
$pdf->SetTitle('Orden de Ingreso');
$pdf->SetSubject('Cementerio Municipal');
$pdf->SetKeywords('Decreto, PDF, Boleta de Pago, Documento');


//Generacion de Folio
$title_pdf = "Orden de Ingreso                                         ";
$sub_title_pdf = "Cementerio Municipal\nCalle Cementerio s/n, Carahue";
// set default header data
$pdf->SetHeaderData('logo.png', PDF_HEADER_LOGO_WIDTH, $title_pdf, $sub_title_pdf, array(0, 0, 0), array(0, 0, 0));
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

$tabla = ''
    . '<table border="1px" style="font-size:.8em;">'
    . '<tr style="background-color: #ccccff;font-weight: bold;font-size: 10pt;">
				<td colspan="3">Depto. Girador</td>
				<td colspan="2">Fecha</td>'
    .'</tr>'
    .'<tr>
            <td colspan="3">Obras - Cementerio</td><td colspan="2">'.date('d-m-Y').'</td>
     </tr>'
    . '<tr style="background-color: #ccccff;font-weight: bold;font-size: 10pt;">
				<td colspan="3">CONTRIBUYENTE</td>
				<td colspan="2">RUT</td>'
    .'</tr>'
    .'<tr>
            <td colspan="3">'.$_POST['nombres_contribuyente'].'</td>
            <td colspan="2">'.$_POST['rut_contribuyente'].'</td>
     </tr>'
    . '<tr style="background-color: #ccccff;font-weight: bold;font-size: 10pt;">
				<td colspan="3">DIRECCION</td>
                <td colspan="2">TELEFONO</td>'
    .'</tr>'
    . '<tr>
				<td colspan="3">'.$_POST['direccion_contribuyente'].'</td>
				<td colspan="2">'.$_POST['telefono_contribuyente'].'</td>'
    .'</tr>'
    . '<tr><td colspan="5"></td></tr>'
    . '<tr><td colspan="5"></td></tr>'
    . '<tr><td colspan="3"></td><td style="background-color: #ccccff;font-weight: bold;font-size: 10pt;text-align:right;">U.T.M.</td><td style="text-align: right;">'.$_POST['utm'].'</td></tr>'
    . '<tr><td colspan="5"></td></tr>'
    . '<tr style="background-color: #ccccff;font-weight: bold;font-size: 10pt;">'
    . '<td style="text-align:center;" colspan="3">Detalle Pago</td>'
    . '<td style="text-align:center;">Valor UTM</td>'
    . '<td style="text-align:center;">Total</td>'
    . '</tr>';

$tabla.='<tr>
            <td colspan="3">Derecho por Terreno</td>
            <td style="text-align: center;">'.$_POST['utm_m2'].'</td>
            <td style="text-align:right;">'.$_POST['subtotal'].'</td>
        </tr>';
$tabla.='<tr>
            <td colspan="3">Derecho a Sepultura</td>
            <td style="text-align:center;">'.$_POST['utm_sepultura'].'</td>
            <td style="text-align:right;">'.$_POST['sepultura'].'</td>
        </tr>';

$tabla.='<tr>
            <td style="background-color: #ccccff;font-weight: bold;font-size: 10pt;text-align:right;" colspan="4">Total a Pagar</td>
            <td style="text-align:right;">'.$_POST['total_a_pagar'].'</td>
        </tr>';

$tabla.='</table>';
//Datos recibidos
$tabla_persona = '';
$head = '<h1>Orden de Ingreso Nº XXX</h1>'
    . '<p>'.$tabla_persona.'</p>'
    . '<br />';
$footer = '<br /><br />'
    . '<table style="width:100%;text-align:center">'
    . '<tr>'
    . '<td>Firma Caja</td>'
    . '<td></td>'
    . '<td>Firma Receptor</td>'
    . '</tr>'
    . '</table>';

$html = $head.$tabla.$footer;
// Print text using writeHTMLCell()
$pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', $html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);

// ---------------------------------------------------------
// Close and output PDF document
// This method has several options, check the source code documentation for more information.
$pdf->Output('Orden de Ingreso.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+


