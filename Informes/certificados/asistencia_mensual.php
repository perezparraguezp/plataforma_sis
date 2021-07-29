<?php


/*
 *
 *
 * certificado 265
 */

include("../../php/config.php");
include("../../php/objetos/functionario.php"); //se realizan llamadas de datos de los funcionarios y se pasa $f que es una funcion
include("../../php/objetos/persona.php");
require_once('../config/lang/cat.php');
require_once('../tcpdf.php');
session_start();
$myId = $_SESSION['id_empleado'];
//error_reporting(0);
//Eliminamos los textos del documento

$dias = Array("Domingo", "Lunes", "Martes", "Miercoles", "Jueves", "Viernes", "Sabado");
$meses = Array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");

$tipo = "Ordinaria";
$anio = $_POST['anio'];
$mes = $_POST['status'];
$mesnormal = $meses[$_POST['status']-1];
$texto1 = trim($_POST['solicitante']);
$id_secretario = $_POST['secretario'];
$f = new functionario($id_secretario);
$firmante = $f->nombre_completo;


$sql12 = "SELECT MAX(n_cert)as n_cert
FROM certificado_secretaria where anio=$anio";
$rows = mysql_fetch_array(mysql_query($sql12));
$n_cert = 1;
if($rows['n_cert']>=1){
    $n_cert = $rows['n_cert']+1;
}

$tabla = '<table border="1px">';
$sql0 = "select id_reunion, fecha, numero_reunion from concejo_reunion where month(fecha)='$mes' and year(fecha)='$anio' and tipo_reunion = 'ORDINARIA'";
$res0 = mysql_query($sql0);
$tabla.='<tr style="background-color: #d7efff;font-weight:bold;"> <td>Concejales/Reuniones</td>';
while ($row0=mysql_fetch_array($res0)){
    $fecha = fechaNormal($row0['fecha']);
    $tabla.='<td style="text-align:center;">Reunion Ordinarias '.$row0['numero_reunion'].'<br />'.$fecha.'</td>';
}
$tabla.="</tr>";
$sql="select id_concejal, nombre_concejal from concejal";
$res=mysql_query($sql);
while ($row=mysql_fetch_array($res)){
    $id_concejal=$row['id_concejal'];
    $nombre_concejal=$row['nombre_concejal'];
    $sql2="select id_reunion from concejo_reunion where month(fecha)='$mes' and year(fecha)='$anio' and tipo_reunion='ORDINARIA' order by numero_reunion asc";
    $tabla.="<tr><td>".strtoupper($nombre_concejal)."</td>";
    $res2=mysql_query($sql2);
    while ($row2=mysql_fetch_array($res2)){
        $id_reunion=$row2['id_reunion'];
        $sql3="select estado_asistencia from concejo_asistencia where id_reunion='$id_reunion' and id_concejal='$id_concejal'";
        $res3 = mysql_fetch_array(mysql_query($sql3));
        $tabla.="<td>".$res3['estado_asistencia']."</td>";
    }
    $tabla.="</tr>";
}
$tabla.="</table>";



$tabla1 = '<table border="1px">';
$sql01 = "select id_reunion, fecha, numero_reunion from concejo_reunion where month(fecha)='$mes' and year(fecha)='$anio' and tipo_reunion = 'EXTRAORDINARIA'";
$res01 = mysql_query($sql01);
$tabla1.='<tr style="background-color: #d7efff;font-weight: bold;"> <td>Consejal/Reunion</td>';
while ($row01=mysql_fetch_array($res01)){
    $fecha = fechaNormal($row01['fecha']);
    $tabla1.='<td style="text-align:center;">Reunion Extraordinaria '.$row01['numero_reunion'].'<br />'.$fecha.'</td>';
}
$tabla1.="</tr>";
$ordi = 0;
$sql4="select id_concejal, nombre_concejal from concejal";
$res4=mysql_query($sql4);
while ($row=mysql_fetch_array($res4)){

    $id_concejal=$row['id_concejal'];

    $nombre_concejal=$row['nombre_concejal'];

    $sql21="select id_reunion from concejo_reunion where month(fecha)='$mes' and year(fecha)='$anio' and tipo_reunion='EXTRAORDINARIA' order by numero_reunion asc";
    $tabla1.="<tr><td>".strtoupper($nombre_concejal)."</td>";
    $res21=mysql_query($sql21);
    while ($row21=mysql_fetch_array($res21)){
        $ordi++;
        $id_reunion=$row21['id_reunion'];
        $sql31="select estado_asistencia from concejo_asistencia where id_reunion='$id_reunion' and id_concejal='$id_concejal'";
        $res31 = mysql_fetch_array(mysql_query($sql31));
        $tabla1.="<td>".$res31['estado_asistencia']."</td>";
    }
    $tabla1.="</tr>";
}
$tabla1.="</table>";


if($ordi==0){
    $tabla1 = '';
}


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

$title_pdf = "Certificado de Asistencia                                          ";
$sub_title_pdf = "Secretaría Municipal\nI. Municipalidad de Carahue, Portales #295, Carahue";

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

$pdf->SetFont('dejavusans', '', 14, '', true);

// Add a page
// This method has several options, check the source code documentation for more information.
$pdf->AddPage();
// Set some content to print

$html = '
<style type="text/css">
h1{
text-align: center;
font-size: 0.8em;
}
h2{
text-align: center;
font-size: 0.7em;;
}
p{
text-indent: 100px;
font-size: 0.8em;
font-family: Roboto, HelveticaNeue, sans-serif;
text-align: justify;
}
table{
       font-size:0.6em;
   }
</style>
<p></p>
<h1>CERTIFICADO Nº'.$n_cert.'</h1>
<p>'.$firmante.', Secretario Municipal de la Municipalidad de Carahue, quien suscribe certifica que:</p>
<p>La asistencia de los señores concejales de la comuna a las sesiones que celebró el Concejo Municipal, durante el mes de '.$mesnormal.' de '.$anio.', es como sigue:</p>
<h1>REUNIONES :</h1>
'.$tabla.'
<p></p>
'.$tabla1.'
<p></p>
<p>Se extiende el presente certificado para ser presentado en el '.$texto1.' de la Municipalidad de Carahue</p>
<p></p>
<p></p>
<p></p>
<p></p>
<p></p>
<p></p>
<p></p>
<p></p>
<p></p>
<table style="width: 100%; border: none;font-size: 1em;">
<tr>
<td></td>
<td style="text-align: center">'.$firmante.'<br />Secretario Municipal</td>
</tr>
</table>
<p></p>
<p></p>
<p></p>
<p></p>
<p>En Carahue, '.$fecha_letras.'</p>

';

$sql = "INSERT INTO certificado_secretaria ( anio, html,n_cert) values ('$anio','$html','$n_cert')";
mysql_query($sql);
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
