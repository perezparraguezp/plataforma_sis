<?php

include("../../php/conex.php");
require_once('../config/lang/eng.php');
require_once('../tcpdf.php');
include("../../php/objetos/functionario.php");
$meses = Array("Enero", "Febrero", "Marzo", "Abril", "Mayo",
                "Junio", "Julio", "Agosto", "Septiembre", 
                "Octubre", "Noviembre", "Diciembre");
$diasMes = Array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
error_reporting(0);
$contrato = $_POST['contrato'];
$planta = $_POST['planta'];
$tipo = "";
if($contrato == "Planta/Contrata"){
    $tipo = "(planta_municipal='PLANTA' OR planta_municipal='CONTRATA') ";
}else{
    if($contrato == "Honorario"){
        $tipo = "planta_municipal='HONORARIO' ";
    }else{
        $tipo = "planta_municipal='COD. TRABAJO' ";
    }
}

function diaSemana($ano, $mes, $dia) {
    // 0->domingo	 | 6->sabado
    $dia = date("w", mktime(0, 0, 0, $mes, $dia, $ano));
    return $dia;
}
//Datos de Certificado
if (@$_POST['mes']) {
    $mes = @$_POST['mes'];
} else {
    $mes = date('m');
}
if (@$_POST['anio']) {
    $anio = @$_POST['anio'];
} else {
    $anio = date('Y');
}
$logo = "images/logo.png";
$title_pdf = "Registro de Asistencia";
$sub_title_pdf = "Oficina de Personal\nDiego Portales #295";
// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor($title_pdf);
$pdf->SetTitle('Registro de Asistencia');
$pdf->SetSubject("Municipalidad de Carahue");
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

// This method has several options, check the source code documentation for more information.
$sql1 = "select * from funcionario inner join departamento using(id_depto) "
        . "where id_escalafon=1 AND tipo='$planta' and activo='SI' "
        . "group by id_depto";
$res1 = mysql_query($sql1);
while ($row1 = mysql_fetch_array($res1)) {
    $html = '';
    $pdf->AddPage();
    $id_empleado = $row1['id_empleado'];
    $funcionario = new functionario(($row1['reloj']));

// Set some content to print
    $html = '<div style="text-align:center;">
    <h1>Registro de Asistencia</h1>
    <h2>' . $row1['nombre_depto'] . '</h2>
    </div>';
    $html.= '<p style="text-align:left;">
        Este Certificado representa un registro de <strong>NO MARCAS</strong>, del departamento a cargo de 
        <strong>' . $funcionario->nombre . '</strong> correspondiente al mes de <strong>' . $meses[$mes - 1] . '</strong> 
        del <strong>' . $anio . '</strong>.
            A continuaci&oacute;n presentaremos un listado de los funcionarios que registra 
            una o ninguna marca en los dias habiles 
            y que no cuenta con ningun tipo de justificaci&oacute;n
    </p>';

    $html.= '<p style="text-align:left;">
        <table style="font-size:9pt;">
        <tr style="font-size:14pt;background-color: antiquewhite;font-weight: bold;">
            <td style="font-size:14pt;">Funcionario</td>
            <td style="font-size:14pt;text-align:center">Detalle</td>
        </tr>
        ';
    $sql2 = "select * from funcionario "
            . "where id_depto='" . $row1['id_depto'] . "' and reloj!='" . $row1['reloj'] . "' "
            . "and $tipo and tipo='$planta' "
            . "and id_escalafon!=8 and activo='SI' "
            . "order by paterno,materno,nombres";
    $res2 = mysql_query($sql2);
    while ($row2 = mysql_fetch_array($res2)) {
        $f = new functionario(($row2['reloj']));
        $f_id = $row2['reloj'];
        $sql3 = "select id_empleado,day(fecha) as dia,count(*)as marcas from asistencia 
                    where month(fecha)='$mes' and year(fecha)='$anio' 
                    and id_empleado='$f_id' 
                    group by fecha,id_empleado 
                    order by id_empleado,fecha ";

        $res3 = mysql_query($sql3);
        $tiene = false;
        $dias_una_marca = '';
        $primero = 0;
        while ($row3 = mysql_fetch_array($res3)) {
            if ($row3['marcas'] == 1) {
                $tiene = true;
                if ($primero == 0) {
                    $primero++;
                    $dias_una_marca .= $row3['dia'];
                } else {
                    $dias_una_marca .= " - " . $row3['dia'];
                }
            }
        }
        $detalle_ausencias = '';
        $primero = 0;
        for ($d = 1; $d <= $diasMes[$mes - 1]; $d++) {
            $fecha_consulta = $anio . "-" . $mes . "-" . $d;
            $posicion = diaSemana($anio, $mes, $d);
            if ($posicion != 0 && $posicion != 6) {
                $sql4 = "select * from asistencia where fecha='$fecha_consulta' and id_empleado='$f_id' limit 1";
                $row4 = mysql_fetch_array(mysql_query($sql4));
                if (!$row4) {
                    $sql5 = "select * from ausencia where dia='$fecha_consulta' and id_empleado='$f_id' limit 1";
                    $row5 = mysql_fetch_array(mysql_query($sql5));
                    if (!$row5) {
                        $tiene = true;
                        if ($primero == 0) {
                            $primero++;
                            $detalle_ausencias .= $d;
                        } else {
                            $detalle_ausencias .= " - " . $d;
                        }
                    }
                }
            }
        }

        $detalle = '<table style="font-size:9pt;border:solid 1px;padding-left:5px;padding-top:2px;padding-bottom:3px;">
              <tr>
                 <td>No Registra Marca</td>
              </tr>
              <tr>
                 <td><strong style="color:blue;font-weight: bold;">' . $detalle_ausencias . '</strong></td>
              </tr>
              <tr>
                 <td>Solo Tiene una marca</td>
              </tr>
              <tr>
                 <td><strong style="color:blue;font-weight: bold;">' . $dias_una_marca . '</strong></td>
              </tr>
            </table>';
        if ($tiene == true) {
            $html .='<tr>'
                    . '<td><strong style="font-size:12pt">' . $f->nombre . '</strong></td>'
                    . '<td>' . $detalle . '</td>'
                    . '</tr>';
        }
    }
    //Codigo Gestion de Personal
    $encargada_personal = new functionario(59);
    $html .= '
        <tr>
        <td colspan="2" style="text-align:center;position:fixed;bottom:5px;">
            <h4>' . $encargada_personal->nombre . '</h4><br />
            Encargada de Personal    
        </td>
        </tr>
        </table>
    </p>';
// set text shadow effect
    $pdf->setTextShadow(array('enabled' => true, 'depth_w' => 0.2, 'depth_h' => 0.2, 'color' => array(0, 0, 0), 'opacity' => 0, 'blend_mode' => 'Normal'));
// Print text using writeHTMLCell()
    $pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', $html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);
    $pdf->Cell(0, 10, '', 0, 1, 'L');
}

//Directivos

//Alcalde
$sql1 = "select * from funcionario "
        . "inner join departamento using(id_depto) "
        . "where id_escalafon=8 AND  tipo='$planta' and activo='SI' ";
$res1 = mysql_query($sql1);
while ($row1 = mysql_fetch_array($res1)) {
    $html = '';
    $pdf->AddPage();
    $id_empleado = $row1['id_empleado'];
    $funcionario = new functionario(($row1['reloj']));

// Set some content to print
    $html = '<div style="text-align:center;">
    <h1>Registro de Asistencia</h1>
    <h2>' . $row1['nombre_depto'] . '</h2>
    </div>';
    $html.= '<p style="text-align:left;">
        Este Certificado representa un registro de <strong>NO MARCAS</strong>, del departamento a cargo de 
        <strong>' . $funcionario->nombre . '</strong> correspondiente al mes de <strong>' . $meses[$mes - 1] . '</strong> 
        del <strong>' . $anio . '</strong>.
            A continuaci&oacute;n presentaremos un listado de los funcionarios que registra 
            una o ninguna marca en los dias habiles 
            y que no cuenta con ningun tipo de justificaci&oacute;n
    </p>';

    $html.= '<p style="text-align:left;">
        <table style="font-size:9pt;">
        <tr style="font-size:14pt;background-color: antiquewhite;font-weight: bold;">
            <td style="font-size:14pt;">Funcionario</td>
            <td style="font-size:14pt;text-align:center">Detalle</td>
        </tr>
        ';
    $sql2 = "select * from funcionario "
            . "where id_escalafon='1' and activo='SI' "
            . "and tipo='$planta'"
            . "order by paterno,materno,nombres";
    $res2 = mysql_query($sql2);
    while ($row2 = mysql_fetch_array($res2)) {
        $f = new functionario(($row2['reloj']));
        $f_id = $row2['reloj'];
        $sql3 = "select id_empleado,day(fecha) as dia,count(*)as marcas from asistencia 
                    where month(fecha)='$mes' and year(fecha)='$anio' 
                    and id_empleado='".$row2['reloj']."' 
                    group by fecha,id_empleado 
                    order by id_empleado,fecha ";
        $res3 = mysql_query($sql3);
        $tiene = false;
        $dias_una_marca = '';
        $primero = 0;
        while ($row3 = mysql_fetch_array($res3)) {
            if ($row3['marcas'] == 1) {
                $tiene = true;
                if ($primero == 0) {
                    $primero++;
                    $dias_una_marca .= $row3['dia'];
                } else {
                    $dias_una_marca .= " - " . $row3['dia'];
                }
            }
        }
        $detalle_ausencias = '';
        $primero = 0;
        for ($d = 1; $d <= $diasMes[$mes - 1]; $d++) {
            $fecha_consulta = $anio . "-" . $mes . "-" . $d;
            $posicion = diaSemana($anio, $mes, $d);
            if ($posicion != 0 && $posicion != 6) {
                $sql4 = "select * from asistencia where fecha='$fecha_consulta' and id_empleado='$f_id' limit 1";
                $row4 = mysql_fetch_array(mysql_query($sql4));
                if (!$row4) {
                    $sql5 = "select * from ausencia where dia='$fecha_consulta' and id_empleado='$f_id' limit 1";
                    $row5 = mysql_fetch_array(mysql_query($sql5));
                    if (!$row5) {
                        $tiene = true;
                        if ($primero == 0) {
                            $primero++;
                            $detalle_ausencias .= $d;
                        } else {
                            $detalle_ausencias .= " - " . $d;
                        }
                    }
                }
            }
        }

        $detalle = '<table style="font-size:9pt;border:solid 1px;padding-left:5px;padding-top:2px;padding-bottom:3px;">
              <tr>
                 <td>No Registra Marca</td>
              </tr>
              <tr>
                 <td><strong style="color:blue;font-weight: bold;">' . $detalle_ausencias . '</strong></td>
              </tr>
              <tr>
                 <td>Solo Tiene una marca</td>
              </tr>
              <tr>
                 <td><strong style="color:blue;font-weight: bold;">' . $dias_una_marca . '</strong></td>
              </tr>
            </table>';
        if ($tiene == true) {
            $html .='<tr>'
                    . '<td><strong style="font-size:12pt">' . $f->nombre . '</strong></td>'
                    . '<td>' . $detalle . '</td>'
                    . '</tr>';
        }
    }
    //Codigo Gestion de Personal
    $html .= '</table>
    </p>';
    $encargada_personal = new functionario(59);
    $html.= '
        <div style="text-align:center;position:fixed;bottom:5px;">
        <h4>' . $encargada_personal->nombre . '</h4><br />
            Encargada de Personal    
    </div>';
// set text shadow effect
    $pdf->setTextShadow(array('enabled' => true, 'depth_w' => 0.2, 'depth_h' => 0.2, 'color' => array(0, 0, 0), 'opacity' => 0, 'blend_mode' => 'Normal'));
// Print text using writeHTMLCell()
    $pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', $html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);
    $pdf->Cell(0, 10, '', 0, 1, 'L');
}

// ---------------------------------------------------------
// Close and output PDF document
// This method has several options, check the source code documentation for more information.
$pdf->Output('Asistencia.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+
?>
