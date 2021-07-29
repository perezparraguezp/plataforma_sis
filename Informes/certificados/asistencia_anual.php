<?php

include("../../php/config.php");
include("../../php/objetos/functionario.php"); //se realizan llamadas de datos de los funcionarios y se pasa $f que es una funcion
include("../../php/objetos/persona.php");
require_once('../config/lang/cat.php');
require_once('../tcpdf.php');
session_start();
$myId = $_SESSION['id_empleado'];
//error_reporting(0);
//Eliminamos los textos del documento





$texto1 = trim($_POST['solicitante']);
$anio = trim($_POST['anio']);
$id_secretario = $_POST['secretario'];

$f = new functionario($id_secretario);


$firmante = $f->nombre_completo;



$dias = Array("Domingo", "Lunes", "Martes", "Miercoles", "Jueves", "Viernes", "Sabado");
$meses = Array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");

$a = date('Y');
$m = date('m');
$d = date('d');
$dia = diaSemana($a, $m, $d);

$fecha_letras = $dias[$dia] . " " . $d . " de " . $meses[$m - 1] . " del " . $a;


// create new PDF document
define ('PDF_PAGE_FORMAT', 'A4');
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'iso-8859-1', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('I. Municipalidad de Carahue');
$pdf->SetTitle('Certificado');
$pdf->SetSubject('Decreto');
$pdf->SetKeywords('Decreto, PDF, documento, Documento');


$title_pdf = "Certificado Acuerdo de Consejo                                            ";
$sub_title_pdf = "Secretaria Municipal\nI. Municipalidad de Carahue, Portales #295, Carahue";

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
// Set some content to print

$html = '
<style type="text/css">
h1{
text-align: center;
font-size: 1.2em;;
}
h2{
text-align: center;
font-size: 0.8em;;
}
p{
font-size: 1em;
font-family: Roboto, HelveticaNeue, sans-serif;
text-align: justify;

}
</style>
<P></P>
<h1>CERTIFICADO Nº</h1>
<p>'.$firmante.' ,Secretario municipal de la municipalidad de carahue, quien suscribe certifica que:</p>
<p>La asistencia de los señores consejales de la comuna a las sesiones que celebro el consejo municipal, durante el año '.$anio.', es como sigue:</p>
<h1>REUNIONES ORDINARIAS:</h1>
<p></p>
<p>Se extiende el presente certificado para ser presentado en el '.$texto1.' de la municipalidad</p>
<p></p>
<p></p>
<p></p>
<p></p>
<p></p>
<p></p>
<p></p>
<p></p>
<p></p>
<h2>'.$firmante.'<br />Secretario Municipal</h2>
<p></p>
<p></p>
<p></p>
<p></p>
<p></p>
<p></p>
<p>En Carahue, '.$fecha_letras.'</p>
';
// Print text using writeHTMLCell()
$pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', $html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);

// Close and output PDF document
// This method has several options, check the source code documentation for more information.
$pdf->Output('Documento.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+


//============================================================+
// INSERT EN BASE DE DATOS
//============================================================+
/*
 *
$reunion = $_POST['id_reunion'];//OK
$tema = $_POST['tema'];//OK


$ingreso_acuerdo = $_POST['ingreso_acuerdo'];
$solicitante =strtoupper($_POST['solicitante']);

$id_secretario = $_POST['secretario'];//OK

$sql = "insert into certificado_acuerdo(id_secretario,id_reunion,id_tema,id_emisor,solicitante,texto_acuerdo)
        values('$id_secretario','$reunion','$tema','$myID','$solicitante','$ingreso_acuerdo')";
//echo $sql;
mysql_query($sql)or die("ERROR_SQL");

 *
 */
