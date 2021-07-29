<?php

include("../../php/conex.php");
include("../../php/objetos/funciones.php");
include("../../php/objetos/functionario.php");
include("../../php/objetos/persona.php");
require_once('../config/lang/cat.php');
require_once('../tcpdf.php');
session_start();
error_reporting(0);

$id_reunion = $_POST['id_reunion'];
$id_tema = $_POST['id_tema'];

$acuerdo_texto = $_POST['acuerdo_texto'];


$sql1 = "SELECT * from concejo_reunion 
      where id_reunion='$id_reunion' limit 1";
$row1 = mysql_fetch_array(mysql_query($sql1));



$lugar = $row1['lugar'];
$tipo_reunion=$row1['tipo_reunion'];
$numero_reunion=$row1['numero_reunion'];

$f = new functionario($row1['id_secretario']);


$firmante = $f->nombre_completo;

$sql2 = "select * from concejo_temas where id_tema='$id_tema' limit 1";
$row2 = mysql_fetch_array(mysql_query($sql2));
$anio_tema = date('Y');
$numero_acuerdo = $row2['numero_acuerdo'];

if($numero_acuerdo=='' || $numero_acuerdo=='0'){
    $sql1_2 = "SELECT *
              FROM concejo_temas 
              where anio_acuerdo='$anio_tema' 
              order by numero_acuerdo 
              desc limit 1 ";
    $row_2 = mysql_fetch_array(mysql_query($sql1_2));

    $numero_acuerdo = $row_2['numero_acuerdo']+1;

    mysql_query("update concejo_temas 
                        set numero_acuerdo='$numero_acuerdo',
                        anio_acuerdo='$anio_tema',
                        acuerdo_texto='$acuerdo_texto' 
                        where id_tema='$id_tema' ");


}else{
    $numero_acuerdo = $row2['numero_acuerdo'];

    mysql_query("update concejo_temas 
                        set numero_acuerdo='$numero_acuerdo',
                        anio_acuerdo='$anio_tema',
                        acuerdo_texto='$acuerdo_texto' 
                        where id_tema='$id_tema' ");
}


$sql3 = "SELECT * from concejo_reunion
  INNER JOIN concejo_temas_reunion using(id_reunion)
  INNER JOIN concejo_temas using(id_tema) 
  where id_reunion='$id_reunion' and id_tema='$id_tema' limit 1";
$row3 = mysql_fetch_array(mysql_query($sql3));

$sql2 = "select * from concejal
              where desde<=current_date() and hasta>=CURRENT_DATE()";
$res2 = mysql_query($sql2);
mysql_query("delete from votos_tema_concejo where id_tema='$id_tema'");
while ($row2 = mysql_fetch_array($res2)){
    $id_concejal = $row2['id_concejal'];
    $voto = $_POST['voto-'.$row2['id_concejal']];
    $sql1 = "insert into votos_tema_concejo(id_tema,id_reunion,voto,id_concejal) 
            values('$id_tema','$id_reunion','$voto','$id_concejal');";

    mysql_query($sql1);
}

// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'iso-8859-1', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('I. Municipalidad de Carahue');
$pdf->SetTitle('Seguimiento');
$pdf->SetSubject('Decreto');
$pdf->SetKeywords('Decreto, PDF, Seguimiento, Documento');


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
$html = '<style type="text/css">
p{
font-size: 0.8em;
}
</style>';
$html .= '<h2 style="text-align: center;">REGISTRO DE VOTACIÓN</h2>';
$html .= '<p>Con Fecha '.fechaNormal($row3['fecha']).' se realiza la 
votación para la toma de acuerdo Nº '.$numero_acuerdo.' de la reunión '.$tipo_reunion.' N°'.$numero_reunion.', la cual se Realizo en '.$lugar.'.</p>';

$html .= '<p><strong>TEMA:</strong> '.strtoupper($row3['tema_texto']).'</p>';
$html .= '<p><strong>EXPOSITOR:</strong> '.strtoupper($row3['expositor']).'</p>';

$html .= '<p>Esta Votación fue realizada por los Concejales y el Alcalde de la Municipalidad de Carahue, los cuales indicaron su decisión de la siguiente forma:</p>';
$html .= '<table border="1">
                <tr style="background-color: #00a2e8;font-weight: bold">
                    <td style="width: 80%;">Nombre Concejal</td>
                    <td style="width: 20%;text-align: center;">VOTACION</td>
                </tr>';
$sql2 = "select * from concejal
        where desde<=current_date() and hasta>=CURRENT_DATE()";
$res2 = mysql_query($sql2);
$votos_NO = 0;
$votos_SI = 0;
$votos_ABSTENENCIA = 0;
$votos_INHABILIDAD = 0;
while($row2 = mysql_fetch_array($res2)){
    $html .= '<tr>
        <td>'.strtoupper($row2['nombre_concejal']).'</td>
        <td  style="width: 20%;text-align: center;">'.$_POST['voto-'.$row2['id_concejal']].'</td>
    </tr>';
    if($_POST['voto-'.$row2['id_concejal']] == 'SI'){
        $votos_SI++;
    }
    if($_POST['voto-'.$row2['id_concejal']] == 'NO'){
        $votos_NO++;
    }
    if($_POST['voto-'.$row2['id_concejal']] == 'ABSTENENCIA'){
        $votos_ABSTENENCIA++;
    }
    if($_POST['voto-'.$row2['id_concejal']] == 'INHABILIDAD'){
        $votos_INHABILIDAD++;
    }

}

$html .= '</table>';
$html .= '<p><strong><u>Resumen de Votación:</u></strong></p>';
$html .='<table>
    <tr><td>Votos a Favor</td><td>'.$votos_SI.'</td></tr>
    <tr><td>Votos en Contra</td><td>'.$votos_NO.'</td></tr>
     <tr><td>Votos en ABSTENENCIA</td><td>'.$votos_ABSTENENCIA.'</td></tr>
      <tr><td>Votos en INHABILIDAD</td><td>'.$votos_INHABILIDAD.'</td></tr>
</table>';
$html .='<p></p>';
$html .='<p>SE TOMA POR <strong>ACUERDO Nº '.$numero_acuerdo.'</strong>, LA VOTACIÓN REALIZADA ANTERIORMENTE AL TEMA <strong>'.strtoupper($row3['tema_texto']).'</strong>, EXPUESTO POR <strong>'.strtoupper($row3['expositor']).'</strong></p>';
$html .='<p></p>';
$html .='<p></p>';
$html .='<p></p>';
$html .='<p></p>';
$html .='<p></p>';
$html .='<h2 style="text-align: center;font-size: 1em;">'.$firmante.'<br />Secretario Municipal</h2>';


$pdf->AddPage();
$pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', $html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);

// ---------------------------------------------------------
// Close and output PDF document
// This method has several options, check the source code documentation for more information.
$pdf->Output('Votacion.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+
