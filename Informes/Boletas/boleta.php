<?php

include("../../php/conex.php");
require_once('../config/lang/cat.php');
require_once('../tcpdf.php');
session_start();
//error_reporting(0);
$rut = $_POST['rut'];
$sql0 = "insert into boletas(fecha_creacion,hora_creacion,rut_persona,estado) values(current_date(),current_time(),'$rut','GENERADA')";
mysql_query($sql0);
$row0 = mysql_fetch_array(mysql_query("select * from boletas where rut_persona='$rut' order by folio desc limit 1"));
$folio = $row0['folio'];

// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'iso-8859-1', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('I. Municipalidad de Carahue');
$pdf->SetTitle('Orden de Ingreso');
$pdf->SetSubject('Farmac');
$pdf->SetKeywords('Decreto, PDF, Boleta de Pago, Documento');


//Generacion de Folio
$title_pdf = "Orden de Ingreso                                         ";
$sub_title_pdf = "Farmacia Comunitaria Florencia Concha\n Villagrán 240, Carahue";
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
$sql_1 = "select * from persona where rut='$rut' limit 1";
$row_1 = mysql_fetch_array(mysql_query($sql_1));
$tabla = ''
    . '<table border="1px" style="font-size:.8em;">'
    . '<tr style="background-color: #ccccff;font-weight: bold;font-size: 10pt;">
				<td colspan="3">Depto. Girador</td>
				<td colspan="2">Fecha</td>'
    .'</tr>'
    .'<tr>
            <td colspan="3">FINANZAS - SALUD </td><td colspan="2">'.date('d-m-Y').'</td>
     </tr>'
    . '<tr style="background-color: #ccccff;font-weight: bold;font-size: 10pt;">
				<td colspan="3">CONTRIBUYENTE</td>
				<td colspan="2">RUT</td>'
    .'</tr>'
    .'<tr>
            <td colspan="3">'.$row_1['nombres'].", ".$row_1['ap_paterno']." ".$row_1['ap_materno'].'</td>
            <td colspan="2">'.$rut.'</td>
     </tr>'
    . '<tr style="background-color: #ccccff;font-weight: bold;font-size: 10pt;">
				<td colspan="5">DIRECCION</td>'
    .'</tr>'
    . '<tr>
				<td colspan="5">'.$row_1['direccion'].". Tel. ".$row_1['telefono'].'</td>'
    .'</tr>'
    . '<tr><td colspan="5"></td></tr>'
    . '<tr style="background-color: #ccccff;font-weight: bold;font-size: 10pt;">'
    . '<td style="text-align:center;">Medicamento</td>'
    . '<td style="text-align:center;">Laboratorio</td>'
    . '<td style="text-align:center;">Cantidad</td>'
    . '<td style="text-align:center;">Valor Unitario</td>'
    . '<td style="text-align:center;">Total</td>'
    . '</tr>';
//Datos recibidos
$m = $_POST['m'];
$total_general = 0;
$lista = '';
foreach ($m as $i => $value) {
    $cantidad = $_POST['cantidad_' . $i];
    if ($cantidad != 0) {
        $sql1 = "select * from medicamento inner join medicamento_farmacia using(codigo) "
            . "where codigo='$value' limit 1";
        $row1 = mysql_fetch_array(mysql_query($sql1));
        $codigo = $row1['codigo'];
        $unitario = $row1['valor_unitario']*1.06;
        $total = $cantidad*$unitario;
        $tabla = $tabla . '<tr>'
            . '<td>' . $row1['nombre'] . '</td>'
            . '<td>' . $row1['marca'] . '</td>'
            . '<td style="text-align:center;">' . $cantidad . '</td>'
            . '<td style="text-align:right;">$ '.  number_format($unitario,0,",",".") .'</td>'
            . '<td style="text-align:right;">$ '.  number_format($total,0,",",".").'</td>'
            . '</tr>';
        $total_general += $total;
        $lista .= "&".$codigo."=".$cantidad."=".$unitario."=".$total;
    }
}
$sql2 = "update boletas set listado='$lista',total_pagado='".$total_general."' where folio='$folio'";
mysql_query($sql2);

$tabla = $tabla . '<tr style="background-color: #ccccff;font-weight: bold;font-size: 10pt;">'
    . '<td colspan="4" style="text-align:right;">Total General</td>'
    . '<td style="text-align:right;">$ '.  number_format($total_general,0,",",".").'</td>'
    . '</tr>'
    . '<tr><td colspan="5"></td></tr>'
    . '<tr>
						<td colspan="2">Codigo Presupuestario</td>
						<td> <strong>115.08.99.999</strong></td>
						<td colspan="2">OTROS DERECHOS</td>
					</tr>';;
$tabla = $tabla . '</table>';

$sql_1 = "select * from persona where rut='$rut' limit 1";
$row_1 = mysql_fetch_array(mysql_query($sql_1));
$tabla_persona = '';
$head = '<h1>Orden de Ingreso Nº '.$folio.'</h1>'
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
$pdf->Output('Permiso Administrativo.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+


