<?php
header ('Content-type: text/html; charset=utf-8');
include("../../php/config.php");
include("../../php/objetos/functionario.php"); //se realizan llamadas de datos de los funcionarios y se pasa $f que es una funcion
include("../../php/objetos/persona.php");
require_once('../config/lang/cat.php');
require_once('../tcpdf.php');
session_start();
$myId = $_SESSION['id_empleado'];
//error_reporting(0);
//Eliminamos los textos del documento
$texto1 = ($_POST['acuedo']);
$texto2 = str_replace('​','', $_POST['textarea_acuerdo']);
//echo $_POST['textarea_acuerdo'];

$texto2 = str_replace('?','',$texto2);
$texto2 = str_replace('&nbsp;','',$texto2);
$texto2 = trim($texto2);

$texto_acuerdo_2 = $_POST['textarea_acuerdo'];

//echo $_POST['textarea_acuerdo'];

$solicitante = trim($_POST['solicitante']);
$id_secretario = $_POST['secretario'];
$votacion = trim($_POST['votacion']);

$id_tema = $_POST['tema'];//id tema
$sql1 = "select year(concejo_reunion.fecha) as anio,numero_acuerdo from concejo_temas inner join concejo_temas_reunion using(id_tema)
      inner join concejo_reunion using(id_reunion)  
          where id_tema='$id_tema' limit 1";
$row1 = mysql_fetch_array(mysql_query($sql1));
$anio_tema = $row1['anio'];//anio tema
$tema_acuerdo = $row1['numero_acuerdo']; //numero acuerdo
$numero_acuerdo = $tema_acuerdo;


$f = new functionario($id_secretario);


$firmante = $f->nombre_completo;


$a = date('Y');
$m = date('m');
$d = date('d');
$dia = diaSemana($a, $m, $d);






$dias = Array("Domingo", "Lunes", "Martes", "Miercoles", "Jueves", "Viernes", "Sabado");
$meses = Array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");



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


$title_pdf = "I. Municipalidad de Carahue                                            ";
$sub_title_pdf = "Secretaria Municipal\nConcejo Municipal";

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
font-size: 1em;;
}
h2{
text-align: center;
font-size: 0.8em;;
}
p{
font-size: 0.9em;
font-family: Roboto, HelveticaNeue, sans-serif;
text-align: justify;
}
</style>
<P></P>
<h1><u>CERTIFICADO</u></h1>
<p>'.$firmante.', '.$texto1.' por  '.$votacion.' de sus miembros ha tomado el siguiente acuerdo:</p>
<p></p>
<h1>ACUERDO Nº '.$numero_acuerdo.'/'.$anio_tema.'</h1>
<p>'.$texto2.'</p>
<p>Se extiende el presente certificado para ser presentado en: '.$solicitante.'</p>
<p>En Carahue, '.$fecha_letras.'</p>
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
';

$acuerdo_texto = $texto_acuerdo_2;

$sql = "update concejo_temas set 
        numero_acuerdo='$numero_acuerdo',
        anio_acuerdo='$anio_tema',
        acuerdo_texto='$acuerdo_texto'  
        where id_tema='$id_tema' ";

mysql_query($sql);


$sql3 = "insert into acuerdos_concejo(numero_certificado,numero_acuerdo,anio_acuerdo,fecha_acuerdo,html_acuerdo) 
            values('$numero_certificado','$numero_acuerdo','$anio_tema',current_date(),'00')";

mysql_query($sql3);
// Print text using writeHTMLCell()
$pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', $html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);
// Close and output PDF document
// This method has several options, check the source code documentation for more information.
$pdf->Output('Documento.pdf', 'I');

