<?php

include("../../php/conex.php");
include("../../php/objetos/funciones.php");
include("../../php/objetos/persona.php");
require_once('../config/lang/cat.php');
require_once('../tcpdf.php');
session_start();
error_reporting(0);

// Extend the TCPDF class to create custom Header and Footer
class MYPDF extends TCPDF {

    //Page header
    /*
     *
     public function Header() {
        // Logo
        $image_file = K_PATH_IMAGES.'logo_example.jpg';
        $this->Image($image_file, 10, 10, 15, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
        // Set font
        $this->SetFont('helvetica', 'B', 20);
        // Title
        $this->Cell(0, 15, '', 0, false, 'C', 0, '', 0, false, 'M', 'M');
    }
     */

    // Page footer
    public function Footer() {
        // Position at 15 mm from bottom
        $this->SetY(-15);
        // Set font
        $this->SetFont('helvetica', 'I', 7);
        // Page number
        $this->Cell(0, 10, "Municipalidad de Carahue", 0, false, 'C', 0, '', 0, false, 'T', 'M');
    }
}






$listado = $_POST['list_decreto'];



$mes = $_POST['mes'];
$anio = $_POST['anio'];

$de = $_POST['de'];
$para = $_POST['para'];


$sql1 = "select * from firmantes where id_empleado='$de' limit 1";
$row1 = mysql_fetch_array(mysql_query($sql1));

$de_nombre = limpiaCadena($row1['nombre_firma']);
$cargo_de = $row1['cargo'];


$sql1 = "select * from firmantes where id_empleado='$para' limit 1";
$row1 = mysql_fetch_array(mysql_query($sql1));

$para_nombre = limpiaCadena($row1['nombre_firma']);
$cargo_para = $row1['cargo'];

// create new PDF document
//$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'iso-8859-1', false);

$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

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

$title_pdf = "Pagos honorarios";
$sub_title_pdf = "Municipalidad de Carahue\nPortales #295, Carahue";
// set default header data
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, $title_pdf, $sub_title_pdf, array(0, 0, 0), array(0, 0, 0));
//$pdf->setFooterData($tc = array(0, 0, 0), $lc = array(0, 0, 0));
$item = explode("#",$listado);




$html='<style type="text/css">
            p{
                text-align:left;
                font-size:8pt;
                margin-top: 0px;
            }
            h5{
                font-size:12pt;
                text-align: center;
            }
            strong{
                font-size:12pt;
            }
            table tr td{
                border: 1px solid black;
            }
            th{
               border: 1px solid black;
               background-color: #e9e9e9;
               font-weight: bold;
            }
    </style>
    <table>
        <thead>
            <tr>
                <th style="width: 45px;"><p>ID</p></th>
                <th style="width: 80px;"><p>Rut</p></th>
                <th style="width: 150px;"><p>Nombre</p></th>
                <th style="width: 45px;"><p>Decreto</p></th>
                <th style="width: 150px;"><p>Programa</p></th>
                <th style="width: 60px;"><p>Nro Boleta</p></th>
                <th style="width: 90px;"><p>Monto</p></th>
                <th style="width: 25px;"><p>Mes</p></th>
            </tr>
        </thead>
';
foreach ($item as $i => $data) {
    if($data != '') {
        $ds = explode("^", $data); //datos persona extraidos

        $html .= '
                <tr>
                    <td style="width: 45px;"><p>0x000</p></td>
                    <td style="width: 80px;"><p>' . $ds[0] . '</p></td>
                    <td style="width: 150px;"><p>' . $ds[1] . '</p></td>
                    <td style="width: 45px;"><p>' . $ds[2] . '</p></td>
                    <td style="width: 150px;"><p>' . $ds[3] . '</p></td>
                    <td style="width: 60px;"><p>' . $ds[4] . '</p></td>
                    <td style="width: 90px;"><p>$' . $ds[5] . '</p></td>
                    <td style="width: 25px;"><p>' . $ds[6] . '</p></td>
                </tr>
        ';





//        $style = array(
//            'position' => '',
//            'align' => 'C',
//            'stretch' => false,
//            'fitwidth' => true,
//            'cellfitalign' => '',
//            'border' => true,
//            'hpadding' => 'auto',
//            'vpadding' => 'auto',
//            'fgcolor' => array(0, 0, 0),
//            'bgcolor' => false, //array(255,255,255),
//            'text' => true,
//            'font' => 'helvetica',
//            'fontsize' => 8,
//            'stretchtext' => 4
//        );
    }



}
$html.='</table>';





$pdf->AddPage();
$pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', $html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);

$pdf->resetHeaderTemplate();
$title_pdf = "Detalle pago transferencias";
$sub_title_pdf = "Municipalidad de Carahue\nI. Municipalidad de Carahue, Portales #295, Carahue";
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, $title_pdf, $sub_title_pdf, array(0, 0, 0), array(0, 0, 0));

$html2='<style type="text/css">
            p{
                text-align:left;
                font-size:8pt;
                margin-top: 0px;
            }
            h5{
                font-size:12pt;
                text-align: center;
            }
            strong{
                font-size:12pt;
            }
            table tr td{
                border: 1px solid black;
            }
            th{
               border: 1px solid black;
               background-color: #e9e9e9;
            }
    </style>
    <table>
        <thead>
            <tr>
                <th ><p>ID</p></th>
                <th ><p>Banco</p></th>
                <th ><p>Tipo de cuenta</p></th>
                <th ><p>Nro. Cuenta</p></th>
                <th ><p>Monto</p></th>
            </tr>
        </thead>';
foreach ($item as $i => $data) {
    if($data != '') {
        $ds = explode("^", $data); //datos persona extraidos

        $html2 .= '
                <tr>
                    <td><p>0x000</p></td>
                    <td><p>' . $ds[0] . '</p></td>
                    <td><p>' . $ds[1] . '</p></td>
                    <td><p>' . $ds[2] . '</p></td>
                    <td><p>' . $ds[3] . '</p></td>
                </tr>
        ';






    }



}

$pdf->AddPage();
$pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', $html2, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);

// ---------------------------------------------------------
// Close and output PDF document
// This method has several options, check the source code documentation for more information.
$pdf->Output('DocumentosPagoHonorarios.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+
