<?php

include("../../php/conex.php");
include("../../php/objetos/functionario.php");
require_once('../config/lang/cat.php');
require_once('../tcpdf.php');
error_reporting(0);
//echo $completo."-".$mediodia." -- >".$jornada."<br />";
//Fecha

$firma1 = $_POST['firma2'];
$sqlF1 = "select * from directivo where id_directivo='$firma1' limit 1";
$rowF1 = mysql_fetch_array(mysql_query($sqlF1));
$nombreF1 = $rowF1['nombre_directivo'];
$cargoF1 = $rowF1['cargo_directivo'];


$item = $_POST['item'];
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

function dameFecha($fecha, $dia) {
    list($year, $mon, $day) = explode('-', $fecha);
    return date('d-m-Y', mktime(0, 0, 0, $mon, $day + $dia, $year));
}

// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'iso-8859-1', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('I. Municipalidad de Carahue');
$pdf->SetTitle('Registro de Licencia Medica');
$pdf->SetSubject('Decreto');
$pdf->SetKeywords('Decreto, PDF, Licencia Medica, Documento');

$title_pdf = "Dirección de Administración y Finanzas                                            ";
$sub_title_pdf = "Oficina de Personal\nDiego Portales #295";
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


//Proceso
$isapre_array = array("COMPIN");
foreach ($item as $fila => $value) {//Value es id_licencia
    $sql1 = "select * from licencias inner join funcionario on reloj=id_empleado "
            . "where id_licencia='$value' "
            . "limit 1";
    $row1 = mysql_fetch_array(mysql_query($sql1));
    if ($row1['tabla_salud'] == 'isapres') {
        $sql2 = "select * from isapres "
                . "where id_isapre='" . $row1['value_salud'] . "' "
                . "limit 1";
        $row2 = mysql_fetch_array(mysql_query($sql2));
        $isapre = $row2['nombre_isapre'];
        array_push($isapre_array, $isapre);
    } else {
        array_push($isapre_array, 'COMPIN');
    }
}
$isapre_array = array_unique($isapre_array); //LIMPIAMOS DATOS REPETIDOS
$isapre_array = array_values($isapre_array);
for ($i = 0; $i < sizeof($isapre_array); $i++) {
    if (    $isapre_array[$i] != '' && 
            $isapre_array[$i] != 'Fonasa' && 
            $isapre_array[$i] != 'COMPIN') {
        $html = '';
        $pdf->AddPage();
        $institucion = $isapre_array[$i];
        $tabla = "<table>";
        $tabla .= ''
                . '<tr style="font-weight: bold;background-color: #66ccff;padding: 2px;">'
                . '<td style="widht:100px;">Nª Licencia</td>'
                . '<td style="widht:300px;">Nombre del Trabajador</td>'
                . '<td style="widht:80px;">Fecha de Inicio</td>'
                . '<td style="widht:80px;">Fecha de Termino</td>'
                . '<td style="widht:100px;text-align:center;">Nª de Días</td>'
                . '</tr>';
        foreach ($item as $fila => $value) {
            $sql1 = "select * from licencias inner join isapres on value_salud=id_isapre "
                    . "where id_licencia='$value' limit 1";
            mysql_query("update licencias set estado_licencia='EN COBRANZA' where id_licencia='$value'");
            $row1 = mysql_fetch_array(mysql_query($sql1));
            if ($row1['nombre_isapre'] == $isapre_array[$i]) {
                $f = new functionario($row1['id_empleado']);
                list($y, $m, $d) = explode("-", $row1['fecha_inicio']);
                $numero_licencia = $row1['numero_licencia'];
                $fecha_inicio = $d . "-" . $m . "-" . $y;
                $dias_licencia = $row1['dias_licencia'];
                $fecha_termino = dameFecha($row1['fecha_inicio'], $dias_licencia);
                $tabla .= '<tr style="font-size:10pt;">'
                        . '<td style="widht:100px;">' . $numero_licencia . '</td>'
                        . '<td style="widht:300px;">' . $f->nombre . '</td>'
                        . '<td style="widht:80px;">' . $fecha_inicio . '</td>'
                        . '<td style="widht:80px;">' . $fecha_termino . '</td>'
                        . '<td style="widht:100px;text-align:center;">' . $dias_licencia . '</td>'
                        . '</tr>';
            }
        }

        $tabla .= '</table>';


        $html = ''
                . '<style type="text/css">
            h2{
            text-align:center;
            }
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
        text-align: right;
        }
    li{
    font-size:10pt;
    }
</style>
<h2>SOLICITUD DE COBRO</h2>
<h5>SEÑORES<br />ISAPRE ' . $institucion . '<br />PRESENTE</h5>
<p>Cumplimos con las instrucciones impartidas por la superintendencia de seguridad 
social, con respecto al reembolso del pago de subsidios por incapacidad laboral,
a entidades empleadoras del sector privado que tengan un convenio para el pago directo
de dicho beneficio a sus trabajadores, solicitamos a ustedes reembolso de los
montos correspondientes a las siguientes licencias medicas:</p>';
        $html .= $tabla;
        $html .= "<p></p><h6>Atentamente</h6>";
        $html .= '<table>
            <tr><td></td><td></td></tr><tr><td></td><td></td></tr>
            <tr><td></td><td></td></tr><tr><td></td><td></td></tr>
            <tr><td></td><td></td></tr><tr><td></td><td></td></tr>
            
                    <tr>
                        <td style="text-align: center;font-size:14pt;">
                            <strong>'.$nombreF1.'</strong><br />
                            <span style="font-size:12pt;">'.$cargoF1.'</span>
                            <br />Nombre del Empleador
                        </td>
                        <td style="text-align: center;font-size:14pt;">
                            <strong>69.190.500-4</strong><br />
                            Rut Empleador
                        </td>
                    </tr>
                    <tr><td></td><td></td></tr><tr><td></td><td></td></tr>
                    <tr><td></td><td></td></tr><tr><td></td><td></td></tr>
                    <tr><td></td>
                        <td><strong>Firma y timbre del Empleador</strong></td>
                    </tr>
                  </table>';
        $pdf->setTextShadow(array('enabled' => true, 'depth_w' => 0.2, 'depth_h' => 0.2, 'color' => array(0, 0, 0), 'opacity' => 0, 'blend_mode' => 'Normal'));
// Print text using writeHTMLCell()
        $pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', $html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);
        $pdf->Cell(0, 10, '', 0, 1, 'L');
    } else {
        if ($isapre_array[$i] == 'COMPIN') {
            $html = '';
            $pdf->AddPage();
            $institucion = 'COMPIN';
            $tabla = "<table>";
            $tabla .= ''
                    . '<tr style="font-weight: bold;background-color: #66ccff;padding: 2px;">'
                    . '<td style="widht:100px;">Nª Licencia</td>'
                    . '<td style="widht:300px;">Nombre del Trabajador</td>'
                    . '<td style="widht:80px;">Fecha de Inicio</td>'
                    . '<td style="widht:80px;">Fecha de Termino</td>'
                    . '<td style="widht:100px;text-align:center;">Nª de Días</td>'
                    . '</tr>';
            foreach ($item as $fila => $value) {
                $sql1 = "select * from licencias  "
                        . "where id_licencia='$value' limit 1";
                mysql_query("update licencias set estado_licencia='EN COBRANZA' where id_licencia='$value'");
                $row1 = mysql_fetch_array(mysql_query($sql1));
                if ($row1['tabla_salud'] == 'fonasa') {
                    $f = new functionario($row1['id_empleado']);
                    list($y, $m, $d) = explode("-", $row1['fecha_inicio']);
                    $numero_licencia = $row1['numero_licencia'];
                    $fecha_inicio = $d . "-" . $m . "-" . $y;
                    $dias_licencia = $row1['dias_licencia'];
                    $fecha_termino = dameFecha($row1['fecha_inicio'], $dias_licencia);
                    $tabla .= '<tr style="font-size:10pt;">'
                            . '<td style="widht:100px;">' . $numero_licencia . '</td>'
                            . '<td style="widht:300px;">' . $f->nombre . '</td>'
                            . '<td style="widht:80px;">' . $fecha_inicio . '</td>'
                            . '<td style="widht:80px;">' . $fecha_termino . '</td>'
                            . '<td style="widht:100px;text-align:center;">' . $dias_licencia . '</td>'
                            . '</tr>';
                }
            }

            $tabla .= '</table>';


            $html = ''
                    . '<style type="text/css">
                        h2{
                            text-align:center;
                        }
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
                            text-align: right;
                            }
                        li{
                        font-size:10pt;
                        }
                    </style>
                    <h2>SOLICITUD DE COBRO</h2>
                    <h5>SEÑORES<br /> ' . $institucion . '<br />PRESENTE</h5>
                    <p>Cumplimos con las instrucciones impartidas por la superintendencia de seguridad 
                    social, con respecto al reembolso del pago de subsidios por incapacidad laboral,
                    a entidades empleadoras del sector privado que tengan un convenio para el pago directo
                    de dicho beneficio a sus trabajadores, solicitamos a ustedes reembolso de los
                    montos correspondientes a las siguientes licencias medicas:</p>';
            $html .= $tabla;
            $html .= "<p></p><h6>Atentamente</h6>";
            $html .= '<table>
            <tr><td></td><td></td></tr><tr><td></td><td></td></tr>
            <tr><td></td><td></td></tr><tr><td></td><td></td></tr>
            <tr><td></td><td></td></tr><tr><td></td><td></td></tr>
            
                    <tr>
                        <td style="text-align: center;font-size:14pt;">
                            <strong>'.$nombreF1.'</strong><br />
                            <span style="font-size:12pt;">'.$cargoF1.'</span>
                            <br /><span style="text-align: center;font-size:10pt;">Nombre del Empleador</span>
                        </td>
                        <td style="text-align: center;font-size:14pt;">
                            <strong>69.190.500-4</strong><br />
                            Rut Empleador
                        </td>
                    </tr>
                    <tr><td></td><td></td></tr><tr><td></td><td></td></tr>
                    <tr><td></td><td></td></tr><tr><td></td><td></td></tr>
                    <tr><td></td>
                        <td><strong>Firma y timbre del Empleador</strong></td>
                    </tr>
                  </table>';
            $pdf->setTextShadow(array('enabled' => true, 'depth_w' => 0.2, 'depth_h' => 0.2, 'color' => array(0, 0, 0), 'opacity' => 0, 'blend_mode' => 'Normal'));
// Print text using writeHTMLCell()
            $pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', $html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);
            $pdf->Cell(0, 10, '', 0, 1, 'L');
        }
    }
}

// Close and output PDF document
// This method has several options, check the source code documentation for more information.
$pdf->Output('Permiso Administrativo.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+
