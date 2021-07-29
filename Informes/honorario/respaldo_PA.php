<?php

include("../../php/conex.php");
include("../../php/objetos/functionario.php");
require_once('../config/lang/eng.php');
require_once('../tcpdf.php');
error_reporting(0);

$id_solicitud = $_GET['id_solicitud'];
//Actualizar Estado Solicitud;
mysql_query("update solicitudausencia set estado_solicitud='DECRETADA' 
    where id_solicitud='$id_solicitud'");
$sql1 = "select * from solicitudausencia 
    where id_solicitud='$id_solicitud' limit 1";
$row1 = mysql_fetch_array(mysql_query($sql1));

// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'iso-8859-1', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('I. Municipalidad de Carahue');
$pdf->SetTitle('Permiso Administrativo');
$pdf->SetSubject('Decreto');
$pdf->SetKeywords('Decreto, PDF, Permiso Administrativo, Documento');

$title_pdf = "Direccion de Administracion y Finanzas                                            ";
$sub_title_pdf = "Oficina de Personal\nDiego Portales #295";
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

$html = $row1['pdf'];
//argamos los datos de este PDF
mysql_query("update solicitudausencia set pdf='$html' 
    where id_solicitud='$id_solicitud'");
// Print text using writeHTMLCell()
$pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', $html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);

// ---------------------------------------------------------
// Close and output PDF document
// This method has several options, check the source code documentation for more information.
$pdf->Output('Permiso Administrativo.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+
