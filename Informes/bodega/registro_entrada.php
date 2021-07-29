<?php

include('../../php/conex.php');
require_once('../config/lang/eng.php');
require_once('../tcpdf.php');
include('../../php/objetos/functionario.php');
include('../../php/objetos/funciones.php');
session_start();

$id_empleado = $_SESSION['id_empleado'];
$factura = $_POST['factura'];
$fecha_factura = $_POST['fecha_factura'];
$rut = str_replace(".","",$_POST['rut']);
$nombre_prov = $_POST['nombre_prov'];
$bodega = $_POST['bodega'];
$inventariable = $_POST['inventariable'];


$id_compra = $_POST['id_compra'];

$sql_1 = "SELECT * FROM compras inner join funcionario on id_comprador=reloj where id_compra='$id_compra' limit 1";
$row_1 = mysql_fetch_array(mysql_query($sql_1));

mysql_query("update compras set estado_compra='RECEPCIONADA' WHERE id_compra='$id_compra' limit 1");

$id_certificado = $row_1['id_certificado'];

$oc = $row_1['orden_compra'];
$licitacion = $row_1['licitacion'];
$comprador = $row_1['paterno']." ".$row_1['materno'].", ".$row_1['nombres'];


$sql0= "insert into bdg_factura(numero_factura, fecha_fact, rut_fact, nombre_fact,id_empleado,id_bodega,id_compra,estado_pago,id_certificado)
          values ('$factura','$fecha_factura','$rut','$nombre_prov','$id_empleado','$bodega','$id_compra','PENDIENTE','$id_certificado')";
mysql_query($sql0);
$row0 = mysql_fetch_array(mysql_query("select * from bdg_factura order by id_factura desc limit 1"));
$id_factura = $row0['id_factura'];

//$lid = mysql_query("SELECT MAX(id_factura) AS id FROM bdg_factura");
//if ($row = mysql_fetch_row($lid)) {
//    $id = trim($row[0]);
//}

$tipo = $_POST['tipo'];
$nombre = $_POST['nombre'];
$valor = $_POST['valor'];
$cantidad = $_POST['cantidad'];


foreach ($tipo as $i => $value) {
    $sql = "insert into bdg_objeto(id_factura,id_categoria,marca,precio,cantidad,stock,inventariable)
        values('$id_factura','".$tipo[$i]."','".$nombre[$i]."','".$valor[$i]."','".$cantidad[$i]."','".$cantidad[$i]."','".$inventariable[$i]."');";
    mysql_query($sql);
    $row = mysql_fetch_array(mysql_query("select * from bdg_objeto order by id_objeto desc limit 1"));
    $id_objeto = $row['id_objeto'];
    $codigo = $tipo[$i]."-".$id_objeto;
    mysql_query(("update bdg_objeto set codigo='$codigo' where id_objeto='".$id_objeto."' limit 1"));

}


$meses = Array('', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo',
    'Junio', 'Julio', 'Agosto', 'Septiembre',
    'Octubre', 'Noviembre', 'Diciembre');
$diasMes = Array(0, 31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
error_reporting(0);

//Variables
$bodega = $_POST['bodega'];
$sql1 = "select * from bdg_bodega inner join bdg_lugar using(id_lugar) "
        . "where id_bodega='$bodega' limit 1";
$row1 = mysql_fetch_array(mysql_query($sql1));
$nombre_bodega = $row1['nombre_bodega'].", ".$row1['direccion_lugar'];

$factura = $_POST['factura'];
$fecha_factura = $_POST['fecha_factura'];
$rut = str_replace(".","",$_POST['rut']);
$nombre_prov = $_POST['nombre_prov'];
$inventariable = $_POST['inventariable'];
$ruta = $_POST['ruta_compra'];
$receptor = $_POST['id_empleado'];


//Datos de Certificado
$id_objeto = $_POST['id_objeto'];
$logo = 'images/logo.png';
$title_pdf = 'Certificado de Ingreso';
$sub_title_pdf = "Municipalidad de Carahue\n ".$nombre_bodega;
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


$tipo = $_POST['tipo'];
$nombre = $_POST['nombre'];
$valor = $_POST['valor'];
$cantidad = $_POST['cantidad'];
$descripcion = $_POST['descripcion'];
$fecha = $_POST['fecha'];

$table_head = '<hr />
        <table widht="100%" style="font-size:0.7em;">'
        . '<tr>'
            . '<td>Rut Proveedor</td>'
            . '<td>'.$rut.'</td>'
            . '<td>Nombre Proveedor</td>'
            . '<td>'.$nombre_prov.'</td>'
        . '</tr>'
    . '<tr>'
    . '</tr>'
    . '<tr>'
        . '<td>Nº Documento</td>'
        . '<td>'.$factura.'</td>'
        . '<td>Fecha Documento</td>'
        . '<td>'.fechaNormal($fecha_factura).'</td>'
    . '</tr>'
    . '<tr>'
    . '<td>Comprador</td>'
    . '<td colspan="3">'.$comprador.'</td>'
    . '</tr>'
    . '<tr>'
    . '<td>Orde de Compra</td>'
    . '<td>'.$oc.'</td>'
    . '<td>Licitacion</td>'
    . '<td>'.$licitacion.'</td>'
    . '</tr>'
        . '</table>';

$table = '<br style="width: 100%;clear: both;" /><hr /><table border="1px" widht="100%" style="font-size:0.7em;"> '
        . '<tr style="background-color: rgba(204,253,255,0.99);font-weight: bold;padding: 3px;">'
        . '<td>Cantidad</td>'
        . '<td>Objeto</td>'
        . '<td>Valor</td>'
        . '<td>Total</td>'
        . '</tr>';
$total_factura = 0;
foreach ($tipo as $i => $value) {
    ;
    $sql = "select * from bdg_categoria where id_categoria='".$tipo[$i]."' limit 1";
    $row = mysql_fetch_array(mysql_query($sql));
    $total = (int) $cantidad[$i] * (int) str_replace(",", "", str_replace("$", "", str_replace(".", "", $valor[$i])));
    $total_factura = $total;
    $table .= '<tr>'
                . '<td>'.$cantidad[$i].'</td>'
                . '<td>'.$row['nombre_categoria'].'</td>'
                . '<td>'.$valor[$i].'</td>'
                . '<td> $ '. number_format($total,0,'','.').'</td>'
                . '</tr>';
}
$table .= '</table>';

mysql_query("update bdg_factura set monto_factura='$total_factura' where id_factura='$id_factura'");

$sql = "select * from bdg_factura where numero_factura='$factura' and rut_fact='$rut' order by id_factura desc limit 1";
$row = mysql_fetch_array(mysql_query($sql));
$folio = $row['id_factura'];

$pdf->Cell(0, 0, 'Registro de Ingreso Nº'.$folio." ", 1, 1, 'C');

//Datos de la firma

list($orden,$firma2) = explode("|", $ruta);
$f1 = new functionario($receptor);

$firma2 = "<strong>".$firma2."</strong><br />Adquicisiones";
$firma1 = "<strong>".$f1->nombre."</strong><br />Encargado de Bodega";

$html .= "<p>El Encargado </p>";
$html = $table_head.$table;
$html .= '<table style="width:100%;">'
    . '<tr><td></td><td></td><td></td></tr>'
    . '<tr><td></td><td></td><td></td></tr>'
    . '<tr>'
    . '<td style="text-align:center;font-size:11pt;">'.$firma1.'</td>'
    . '<td></td>'
    . '<td style="text-align:center;font-size:11pt;">'.$firma2.'</td>'
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
