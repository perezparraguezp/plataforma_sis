<?php

include("../../php/conex.php");
include("../../php/objetos/functionario.php");
require_once('../config/lang/cat.php');
require_once('../tcpdf.php');
session_start();
error_reporting(0);
$id_mio = $_SESSION['id_empleado'];
$f_mio = new functionario($id_mio);

$patente = $_POST['patente'];
$mes = $_POST['mes'];
$anio = $_POST['anio'];


//echo $completo."-".$mediodia." -- >".$jornada."<br />";
//Fecha 
$dias = Array("Domingo", "Lunes", "Martes", "Miercoles", "Jueves", "Viernes", "Sabado");
$meses = Array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
$a = date('Y');
$m = date('m');
$d = date('d');
$dia = diaSemana($a, $m, $d);
$fecha = $dias[$dia] . " " . $d . " de " . $meses[$m - 1] . " del " . $a;

function diaSemana($ano, $mes, $dia) {
    // 0->domingo	 | 6->sabado
    $diaX = date("w", mktime(0, 0, 0, $mes, $dia, $ano));
    return $diaX;
}
$texto = '';

$depto_doc = "Departamento de Obras";
$oficina_personal = 'Portales #295, 1er Piso';
// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'iso-8859-1', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('I. Municipalidad de Carahue');
$pdf->SetTitle('Registro de Viajes');
$pdf->SetSubject('Decreto');
$pdf->SetKeywords('Decreto, PDF, Permiso Administrativo, Documento');


//Generacion de Folio








$title_pdf = "$depto_doc                                            ";
$sub_title_pdf = "Registro de Camiones\n$oficina_personal";
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
// set text shadow effect
$pdf->setTextShadow(array('enabled' => true, 'depth_w' => 0.2, 'depth_h' => 0.2, 'color' => array(196, 196, 196), 'opacity' => 1, 'blend_mode' => 'Normal'));
// Set some content to print
$obs = "";
if ($row1['texto_estado'] != '') {
    $obs = '<br /><span>Obs: <strong>' . $row1['texto_estado'] . '</strong></span>';
}



$html = '
<style type="text/css">
    p{
        text-align:left;
        text-indent: 280px;
        font-size:12pt;
        margin-top: 0px;
    }
    BLOCKQUOTE{
        font-size:10pt;
    }
    table{
        font-size:8pt;
    }
    span{
        font-size:10pt;
        }
    li{
    font-size:10pt;
    }
</style>
<p>Carahue, '.$fecha.'<br />
<h3 style="text-align:center;position:relative;">Reporte de Viajes: '.$patente.'</h3>
<table border="1px">
<tr style="background-color: #91fd9c;font-size: 6pt;font-weight: bold;">
            <td>###</td>
            <td>Ficha</td>
            <td>Fecha</td>
            <td>Hora</td>
            <td>Patente</td>
            <td>Tipo</td>
            <td>Chofer</td>
            <td>Capacidad</td>
            <td>Transportado</td>
            <td>Destino</td>
        </tr>';
$fila = 1;
$total_general = 0;
$sql2 = "select * from ficha_viaje inner join vehiculo using(patente)
        where year(fecha) = '$anio' and month(fecha)='$mes' and patente='$patente'
        order by fecha asc";
$res2 = mysql_query($sql2);
while($row2 = mysql_fetch_array($res2)){
    $id_ficha = $row2['id_ficha'];
    $sql3 = "select * from viajes_camiones
            where id_ficha='$id_ficha'
            order by hora_salida asc";
    $res3 = mysql_query($sql3);
    while($row3 = mysql_fetch_array($res3)){
        for($v = 0 ; $v < $row3['vueltas'];$v++){
            $fecha = fechaNormal($row2['fecha']);
            if($row3['id_conductor']==''){
                $f = new functionario($row2['id_empleado']);
            }else{
                $f = new functionario($row3['id_conductor']);
            }
            $capacidad = $row2['capacidad']." [".$row2['medida']."]";
            $cantidad = $row3['cantidad']." [".$row3['medida']."]";
            if($capacidad == $cantidad){
                $color = 'rgba(145, 253, 156, 0.55)';
            }else{
                $color = 'rgba(253, 164, 147, 0.67)';
            }
            $total_general += $row3['cantidad'];
            $html .= '<tr style="font-size: 5pt;">
                <td>'.$fila.'</td>
                <td>'.$id_ficha.'</td>
                <td>'.$fecha.'</td>
                <td>'.$row3['hora_salida'].'</td>
                <td>'.$row2['patente'].'</td>
                <td>'.$row2['tipo_vehiculo']." - ".$row2['especificacion_vehiculo'].'</td>
                <td>'.$f->nombre.'</td>
                <td>'.$capacidad.'</td>
                <td>'.$cantidad.'</td>
                <td>'.$row3['hasta'].'</td>
            </tr>';
            $fila++;
        }



    }

}

$html .= '</table>';
//echo $html;
//argamos los datos de este PDF
mysql_query("update solicitudausencia set pdf='$html' 
    where id_solicitud='$id_solicitud'");
// Print text using writeHTMLCell()
$pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', $html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);

// ---------------------------------------------------------
// Close and output PDF document
// This method has several options, check the source code documentation for more information.
$pdf->Output('Registro de Camiones.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+
