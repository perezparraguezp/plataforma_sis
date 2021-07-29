<?php
session_start();
include('../../php/config.php');
include('../../php/objetos/proveedor.php');
include('../../php/objetos/certificado.php');

include('../../php/objetos/functionario.php');

require_once('../config/lang/eng.php');
require_once('../tcpdf.php');

session_start();
$certificado = new certificado($_SESSION['id_empleado']);
$certificado->insertCertificado('LEVANTAMIENTO DE INVENTARIO');
$id_certificado = $certificado->id_certificado;


$logo = 'images/logo.png';
$title_pdf = 'Registro de Levantamiento';
$sub_title_pdf = "Unidad de Inventario \nMunicipalidad de Carahue";

// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor($title_pdf);
$pdf->SetTitle('Certificado de Ingreso');
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

$cantidad = $_POST['cantidad'];
$detalle = $_POST['objeto'];
$categoria = $_POST['categoria'];
$estado = $_POST['estado'];
$propiedad = $_POST['propiedad'];

$ubicacion = $_POST['puntos_control'];

$sql1 = "select * from punto_control_inventario where id_punto_control='$ubicacion' limit 1";
$row1 = mysql_fetch_array(mysql_query($sql1));
if($row1){
    $nombre_lugar = $row1['nombre_punto_control'];
}
$fila = '';
foreach ($cantidad as $i => $total){
    //creo objeto
    $cat = $categoria[$i];
    $info = strtoupper($detalle[$i]);
    $est = strtoupper($estado[$i]);
    $propiedad_obj = $propiedad[$i];

    $sql2 = "insert into bdg_objeto(id_factura,id_categoria,marca,cantidad,stock,tipo_objeto,propiedad) 
            values('1','$cat','$info','$total','$total','INVENTARIABLE','$propiedad_obj')";

    mysql_query($sql2);
    $row2 = mysql_fetch_array(mysql_query("select * from bdg_objeto where id_factura=1 and id_categoria='$cat' order by id_objeto desc limit 1"));
    $id_objeto = $row2['id_objeto'];
    $codigo = $cat."-".$id_objeto;
    mysql_query("update bdg_objeto set codigo='$codigo' where id_objeto='$id_objeto'");

    for($p = 0 ; $p < $total ; $p++){
        $codigo_producto = $codigo."-".$p;
        $sql3 = "insert into bdg_producto(id_objeto,codigo_producto,id_lugar,estado_producto,inventariable,id_bodega,tipo_inventario,id_punto_control,fecha_codificado) 
          values('$id_objeto','$codigo_producto','$ubicacion','$est','SI','0','INVENTARIABLE','$ubicacion',now()) ";
        mysql_query($sql3);
        $fila .= '<tr>
                    <td>'.$codigo_producto.'</td>
                    <td>'.$info.'</td>
                    <td>'.$est.'</td>
                    <td>'.$propiedad_obj.'</td>
                </tr>';
    }
}

$html = '
<style type="text/css">
h2{
text-align: center;
}
table{
font-size: 0.6em;;
}
</style>
<h2>Levantamiento de Inventario<br />Nº '.$id_certificado.'</h2>
<p>A continuación realizaremos el levantamiento de inventario correspondiente a la ubicación denominada <strong>'.$nombre_lugar.'</strong>, 
este levantamiento fue realizado según lo especifica la toma de inventario basado en el reglamento municipal</p>
<table width="100%" border="1">
<tr style="background-color: #fdff8b;font-weight: bold;">
    <td style="width: 10%;">CODIGO</td>
    <td style="width: 60%;">DETALLE</td>
    <td style="width: 15%;">ESTADO</td>
    <td style="width: 15%;">PROPIEDAD</td>
</tr>
'.$fila.'
</table>
';


$pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', $html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);
// ---------------------------------------------------------
// Close and output PDF document
// This method has several options, check the source code documentation for more information.
$pdf->Output('Levantamiento Inventario.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+




?>
