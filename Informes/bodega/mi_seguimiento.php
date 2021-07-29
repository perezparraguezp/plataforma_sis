<?php

include('../../php/conex.php');
require_once('../config/lang/eng.php');
require_once('../tcpdf.php');
include('../../php/objetos/functionario.php');
session_start();
$id_empleado = $_SESSION['id_empleado'];

$meses = Array('', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo',
    'Junio', 'Julio', 'Agosto', 'Septiembre',
    'Octubre', 'Noviembre', 'Diciembre');



$diasMes = Array(0, 31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
error_reporting(0);
//Datos de Certificado

$logo = 'images/logo.png';
$title_pdf = 'Orden se Salida';
$sub_title_pdf = 'Municipalidad de Carahue';
// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor($title_pdf);
$pdf->SetTitle('Orden de Salida');
$pdf->SetSubject('Municipalidad de Carahue');
$pdf->SetKeywords('TCPDF, PDF, , test, guide');

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
$pdf->SetFont('times', '', 14, '', true);
$pdf->AddPage('I', 'A4');


$emisor = $_SESSION['id_empleado'];
$lista = explode("#", $_POST['lista']);
$accion = $_POST['accion'];

$sql1 = "insert into bdg_seguimiento(id_emisor,lista,fecha,hora,estado) "
        . "values('$emisor','".$_POST['lista']."',current_date(),current_time(),'TEMPORAL')";
mysql_query($sql1);
$row = mysql_fetch_array(mysql_query("select * from bdg_seguimiento where id_emisor='$emisor' order by folio desc limit 1"));
$folio = $row['folio'];

$fila = '';
foreach ($lista as $i => $value) {
    if ($value != '') {
        list($objeto,$cantidad) = explode("&", $value);
        $sql2 = "select * from bdg_objeto inner join bdg_categoria using(id_categoria) where id_objeto='$objeto' limit 1";
        $row2 = mysql_fetch_array(mysql_query($sql2));
        $fila .= '<tr>'
                . '<td>' . $row2['nombre_categoria']. " | " . $row2['marca']  . '</td>'
                . '<td style="text-align: center;">' . $cantidad . '</td>'
                . '</tr>';
    }
}



$sql0 = "select * from bdg_objeto";
$res0 = mysql_query($sql0);
$row0 = mysql_fetch_array(mysql_query($sql0));

//$sql1 = 'select * from inventario inner join oficina using(id_oficina)'
//. 'inner join departamento using(id_depto) '
//. 'inner join bodega_tipo_inventario on id_tipo=id_tipo_inventario '
//. 'where id_oficina="'.$id_oficina.'" ';
$html .= "<br /><p>Este documento sera un comprobante de inventario que será empleado como evidencia "
        . "que los productos descritos a continuación estaran sujetos a la siguiente operación:"
        . "<br /> <ul><li><strong>".$accion."</strong></li></ul></p>";
$html .= '<table border="1px" style="font-size: 8pt;" width="100%">';
$html .= '<tr style="background-color: #EEFCBB;">'
        . '<td>Objeto</td>'
        . '<td style="text-align: center;">CANTIDAD</td>'
        . '</tr>' . $fila;


$html .= '</table>';
$pdf->Cell(0, 0, 'ORDEN DE SALIDA Nº' . $folio . " ", 1, 1, 'C');
$pdf->Cell(0, 0, $oficina, 1, 1, 'C');
//Datos de la firma
$f1 = new functionario($id_empleado);
$firma2 = "<strong>" . $f1->nombre . "</strong><br />Emisor de Objetos";
$firma1 = "<strong>Nombre Receptor</strong><br />Receptor de Objetos";
$html .= '<table style="width:100%;">'
        . '<tr><td></td><td></td><td></td></tr>'
        . '<tr><td></td><td></td><td></td></tr>'
        . '<tr>'
        . '<td style="text-align:center;font-size:11pt;">' . $firma1 . '</td>'
        . '<td></td>'
        . '<td style="text-align:center;font-size:11pt;">' . $firma2 . '</td>'
        . '</tr>'
        . '</table>';

$pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', $html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);
// ---------------------------------------------------------
// Close and output PDF document
// This method has several options, check the source code documentation for more information.
$pdf->Output('Inventario.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+
?>
