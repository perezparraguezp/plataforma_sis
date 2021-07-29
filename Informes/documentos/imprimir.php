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
        $style_barcode = array(
            'position' => '',
            'align' => 'C',
            'stretch' => false,
            'fitwidth' => true,
            'cellfitalign' => '',
            'border' => false,
            'hpadding' => 'auto',
            'vpadding' => 'auto',
            'fgcolor' => array(0,0,0),
            'bgcolor' => false, //array(255,255,255),
            'text' => true,
            'font' => 'helvetica',
            'fontsize' => 6,
            'stretchtext' => 4
        );
        // Position at 15 mm from bottom
        $this->SetY(-15);
        // Set font
        $this->SetFont('helvetica', 'I', 7);
        // Page number
        $this->write1DBarcode('3456234', 'C39', '', '', '', 10, 0.4, $style_barcode, 'N');
        //$this->Cell(0, 10, "Municipalidad de Carahue", 0, false, 'C', 0, '', 0, false, 'T', 'M');
    }
}

// create new PDF document
//$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'iso-8859-1', false);

$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('I. Municipalidad de Carahue');
$pdf->SetTitle('Sistema Digital Municipal');
$pdf->SetSubject('Documento Municipal');
$pdf->SetKeywords('Documento, Carahue, Portales 295, Sistema Digital Municipal');


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




$title_pdf = "Titulo del Documento ";
$sub_title_pdf = "Municipalidad de Carahue - Departamento\nPortales #295, Carahue / Tel. 45-2681500";
// set default header data
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, $title_pdf, $sub_title_pdf, array(0, 0, 0), array(0, 0, 0));
$pdf->setFooterData($tc = array(0, 0, 0), $lc = array(0, 0, 0));


$pdf->AddPage('P', 'A4');



 $style_qr = array(
'border' => 2,
    'vpadding' => 'auto',
    'hpadding' => 'auto',
    'fgcolor' => array(0,0,0),
    'bgcolor' => false, //array(255,255,255)
    'module_width' => 1, // width of a single module in points
    'module_height' => 1 // height of a single module in points
);

$pdf->write2DBarcode('http://www.carahue.cl', 'QRCODE,M', 180, 3, 15, 15, $style_qr, 'N');
$html = '
<style type="text/css">
    #pagina{
        margin-top: 1px;
        font-family: Roboto, HelveticaNeue, sans-serif;
    }
    h1{
        text-align: center;
        font-family: Roboto, HelveticaNeue, sans-serif;
    }
    p{
        text-align:left;
        text-indent: 280px;
        font-size: 0.8em;
        font-family: Roboto, HelveticaNeue, sans-serif;
    }
    table{
        width: 100%;
        text-align: center;
        font-family: Roboto, HelveticaNeue, sans-serif;
        font-size: 0.8em;
    }
    span{
        font-family: Roboto, HelveticaNeue, sans-serif;
        font-size: 0.7em;
    }
</style>
<div id="pagina">
   <p>DECRETO Nº: _______________</p>
   <p>Carahue, </p>
   <p>VISTOS:</p>
   <p>1.-El Decreto Nº 578 de fecha 29 de Diciembre de 2017 que aprueba el presupuesto municipal para el año 2018.<BR />
        2.-KNSEKNSDKJNFKJ SNKJFSKD BFKDSBFK BSDKBFK SDBF KBDSJF BJDBFJDBS JBDJFBJDFBGJDBFGJDB GJDFBJ<BR />
        3.-KNSEKNSDKJNFKJ SNKJFSKD BFKDSBFK BSDKBFK SDBF KBDSJF BJDBFJDBS JBDJFBJDFBGJDBFGJDB GJDFBJ<BR />
        4.-KNSEKNSDKJNFKJ SNKJFSKD BFKDSBFK BSDKBFK SDBF KBDSJF BJDBFJDBS JBDJFBJDFBGJDBFGJDB GJDFBJ<BR />
        5.-KNSEKNSDKJNFKJ SNKJFSKD BFKDSBFK BSDKBFK SDBF KBDSJF BJDBFJDBS JBDJFBJDFBGJDBFGJDB GJDFBJ<BR />
        6.-KNSEKNSDKJNFKJ SNKJFSKD BFKDSBFK BSDKBFK SDBF KBDSJF BJDBFJDBS JBDJFBJDFBGJDBFGJDB GJDFBJ<BR />
   </p>
   <p>DECRETO:</p>
   <p>
        1.-KNSEKNSDKJNFKJ SNKJFSKD BFKDSBFK BSDKBFK SDBF KBDSJF BJDBFJDBS JBDJFBJDFBGJDBFGJDB GJDFBJ<BR />
        2.-KNSEKNSDKJNFKJ SNKJFSKD BFKDSBFK BSDKBFK SDBF KBDSJF BJDBFJDBS JBDJFBJDFBGJDBFGJDB GJDFBJ KNSEKNSDKJNFKJ SNKJFSKD BFKDSBFK BSDKBFK SDBF KBDSJF BJDBFJDBS JBDJFBJDFBGJDBFGJDB GJDFBJ<BR />
        3.-KNSEKNSDKJNFKJ SNKJFSKD BFKDSBFK BSDKBFK SDBF KBDSJF BJDBFJDBS JBDJFBJDFBGJDBFGJDB GJDFBJ KNSEKNSDKJNFKJ SNKJFSKD BFKDSBFK BSDKBFK SDBF KBDSJF BJDBFJDBS JBDJFBJDFBGJDBFGJDB GJDFBJ<BR />
      
        <br />
        <span>ANOTESE, COMUNIQUESE, ARCHIVESE Y REGISTRESE</span>
   </p>
   <p></p>
   <table>
   <tr><td></td><td></td></tr>
   <tr><td></td><td></td></tr>
   <tr>
    <td>
        FIRMANTE 1<BR />
        CARGO FIRMANTE
    </td>
   <td>
         FIRMANTE 1<BR />
        CARGO FIRMANTE
    </td>
    </tr>
</table>
<br />
    <span>HASV/RRL/GRB/pppa</span><br />
    <span><u>DISTRIBUCIÓN</u><br />
    Archivo Municipal <br />
    Interesado
    </span>
</div>

';






$pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', $html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);





// ---------------------------------------------------------
// Close and output PDF document
// This method has several options, check the source code documentation for more information.
$pdf->Output('Documento.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+
