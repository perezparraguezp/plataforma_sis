<?php

include('../../php/config.php');
require_once('../config/lang/eng.php');
require_once('../tcpdf.php');
include '../../php/objetos/functionario.php';

session_start();
$myId = $_SESSION['id_empleado'];

$f = new functionario($myId);
$inventario = $f->nombre_completo;


$meses = Array('', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo',
    'Junio', 'Julio', 'Agosto', 'Septiembre',
    'Octubre', 'Noviembre', 'Diciembre');
$diasMes = Array(0, 31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
//error_reporting(0);
//Datos de Certificado

$id_lugar = $_POST['puntos_control'];
$sql = "select * from punto_control_inventario where id_punto_control='$id_lugar' limit 1";
$row = mysql_fetch_array(mysql_query($sql));
$nombre_lugar = $row['nombre_punto_control']." [".$row['codigo_unico']."]";

$directivo = $_POST['directivo'];

$responsable = $_POST['responsable'];

$sql1 = "select * from firmantes where id_empleado='$directivo' limit 1";
$row1 = mysql_fetch_array(mysql_query($sql1));

$nombre_directivo = strtoupper($row1['nombre_firma']);
$cargo_directivo = strtoupper($row1['cargo']);




$logo = 'images/logo.png';
$title_pdf = 'Registro de Inventario';
$sub_title_pdf = '';
// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor($title_pdf);
$pdf->SetTitle('Registro de Inventario');
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
$pdf->AddPage('L', 'A4');

$html .= '
<table border="1px" style="font-size: 8pt;width: 100%;" width="100%">';
$html .= '<tr style="background-color: #EEFCBB;">'
    . '<td>CODIGO</td>'
    . '<td>CATEGORIA</td>'
    . '<td>DETALLE</td>'
    . '<td>ESTADO</td>'
    . '<td>PROPIEDAD</td>'
    . '</tr>';
$i = 1;

$sql1 = "select * from bdg_producto 
            inner join bdg_objeto using(id_objeto)
            INNER JOIN bdg_categoria on bdg_objeto.id_categoria=bdg_categoria.id_categoria 
        where id_punto_control='$id_lugar' and bdg_objeto.tipo_objeto='INVENTARIABLE'
        order by fecha_desde";

$res1 = mysql_query($sql1);
while ($row1 = mysql_fetch_array($res1)) {

    $sql2 = "select * from inventario 
            where id_inventario='".$row1['codigo_producto']."' limit 1";
    $row2 = mysql_fetch_array(mysql_query($sql2));
    if($row2){
        $objeto = str_replace('"','',$row2['nombre_inventario']." " .$row1['marca']);
    }else{
        $objeto = str_replace('"','',$row1['marca']);
    }

    $objeto = limpiaCadena($objeto);
    $html .= '<tr>'
            . '<td style="text-align:center;font-size: 10pt;">'.$row1['codigo_producto'].'</td>'
            . '<td>'.$row1['nombre_categoria'].'</td>'
            . '<td>'.$objeto.'</td>'
            . '<td style="text-align:center;">'.$row1['estado_producto'].'</td>'
            . '<td style="text-align:center;">'.$row1['propiedad'].'</td>'
        . '</tr>';
}
$html .='</table>

<p></p>
<p></p>
<table width="100%" style="text-align: center;font-size: 0.7em;font-weight: bold;">
    <tr>
            <td>'.$nombre_directivo.'<br />'.$cargo_directivo.'</td>
            <td>'.$responsable.'<br />RESPONSABLE</td>
            <td>'.$inventario.'<br />ENCARGADO DE INVENTARIO</td>
            
    </tr>';

$html .= '</table>';
$pdf->Cell(0, 0, 'INVENTARIO DE BIENES ' . date('Y')." ", 1, 1, 'C');
$pdf->Cell(0, 0, strtoupper($nombre_lugar) , 1, 1, 'C');
$pdf->Cell(0, 0, '', 1, 1, 'C');

//Datos de la firma

$pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', $html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);
// ---------------------------------------------------------
// Close and output PDF document
// This method has several options, check the source code documentation for more information.
$pdf->Output('Inventario.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+
?>
