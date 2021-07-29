<?php

include("../../php/conex.php");
require_once('../config/lang/eng.php');
require_once('../tcpdf.php');
include("../../php/objetos/functionario.php");
$meses = Array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
$diasMes = Array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);

function diaSemana($ano, $mes, $dia) {
    // 0->domingo	 | 6->sabado
    $dia = date("w", mktime(0, 0, 0, $mes, $dia, $ano));
    return $dia;
}

//Datos de Certificado
$mes = $_POST['mes'];
if ($_POST['anio']) {
    $anio = $_POST['anio'];
} else {
    $anio = date('Y');
}
$array = $_POST['funcionario'];
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

foreach ($array as $key => $rut) {

    $html = '';
    $pdf->AddPage();
    $sql1 = "select * from empleado where id_empleado='$rut' limit 1";
    $row1 = mysql_fetch_array(mysql_query($sql1));
    $id_empleado = $row1['id_empleado'];
    $funcionario = new functionario(($id_empleado));
    $feriado = 0;
// Set some content to print
    $html = '<div style="text-align:center;">
    <h1>Registro de Asistencia</h1>
    </div>';
    $html.= '<p style="text-align:left;">
        Este Certificado representa un registro de asistencia para el funcionario
        <strong>' . $funcionario->nombre . '</strong> correspondiente al mes de <strong>' . $meses[$mes - 1] . '</strong> 
        del presente aÃ±o
    </p>';
    $html.='
        <div style="position:relative;font-size:8pt;text-align:center;">
        <table>
        <tr>
            <td>
                <table border="0    px"  style="text-align:center;">
                    <tr style="background-color: antiquewhite;">
                        <td style="width:30px;">Dia</td>
                        <td style="width:60px;">Entrada</td>
                        <td style="width:60px;">Salida</td>
                        <td style="width:80px;">Jstificacion</td>
                    </tr>
            ';
    $diasFaltantes = 0;
    $fila_html = '';
    $dias_feriado = 0;
    $dias_ausentes = 0;
    $codigoJustificaciones = array();
    for ($i = 1; $i <= $diasMes[$mes - 1]; $i++) {
        $td_html = '<td style="width:30px;">' . $i . '</td>';
        $total_marcas = 0; //Numero de Marcas Hoy
        $fila = 0;
        $justificacion_hoy = false;
        $id_justificacion = 0;
        $feriado = false;
        $diaGestion = $anio . "-" . $mes . "-" . $i; //Dia de Gestion
        $semana = diaSemana($anio, $mes, $i); // Dia de la Semana [Lun,Mar,Mier,...,Sab,Dom]
        //Preguntamos que dia de la semana es
        if ($semana != 0 && $semana != 6) {
            $dia_habil = true;
            //Averiguamos si es feriado
            $sqlFeriado = "select * from feriado where dia='$i' and mes='" . (int) $mes . "' limit 1";
            $rowFeriado = mysql_fetch_array(mysql_query($sqlFeriado));
            if ($rowFeriado) {
                $feriado = true;
            } else {
                $feriado = false;
            }
        } else {
            $dia_habil = false;
            //Averiguamos si es feriado
            $sqlFeriado = "select * from feriado where dia='$i' and mes='" . (int) $mes . "' limit 1";
            $rowFeriado = mysql_fetch_array(mysql_query($sqlFeriado));
            if ($rowFeriado) {
                $feriado = true;
            } else {
                $feriado = false;
            }
        }
        //Preguntamos si el dia actual tiene marcas
        $sql2 = "select count(*) as total from asistencia 
                                where fecha='$diaGestion' and id_empleado='$id_empleado'
                                group by fecha
                                order by fecha asc";
        $row2 = mysql_fetch_array(mysql_query($sql2));
        if ($row2) {
            $total_marcas = $row2['total'];
        } else {
            $total_marcas = 0;
        }
        //Preguntamos si existe una justificacion para este dia 
        $sql3 = "select * from ausencia "
                . "where dia='$diaGestion' and id_empleado='$id_empleado' "
                . "limit 1";
        $row3 = mysql_fetch_array(mysql_query($sql3));
        if ($row3) {
            $justificacion_hoy = true;
            $id_justificacion = $row3['id_justificacion'];
        } else {
            $justificacion_hoy = false;
            $id_justificacion = 0;
        }
        //verificamos las marcas de hoy
        if ($total_marcas > 0) {
            //Recorremos las marcas efectuadas en 
            $marcas = Array();
            $m = 0;
            $sql4 = "select * from asistencia 
                              where fecha='$diaGestion' and id_empleado='$id_empleado' ";
            $res4 = mysql_query($sql4);
            while ($row4 = mysql_fetch_array($res4)) {
                $marcas[$m] = $row4['hora'];
                $m++;
            }
            $entrada = $marcas[0];
            if ($marcas[$m - 1] != $entrada) {
                $salida = $marcas[$m - 1];
            } else {
                $salida = 'N/M';
            }
            $td_html .= '<td>' . $entrada . '</td>';
            $td_html .= '<td>' . $salida . '</td>';
            //Existe alguna justificacion para esta fecha
            $sql5 = "select * from ausencia inner join justificaciones using(id_justificacion)
                                        where dia='$diaGestion' and id_empleado='$id_empleado' limit 1";
            $row5 = mysql_fetch_array(mysql_query($sql5));
            if ($row5) {
                $justificacion = $row5['codigo_justificacion'];
                $codigoJustificaciones[$fila] = $row5['codigo_justificacion'] . ":" . $row5['nombre_justificacion'];
                $fila++;
                $td_html .= '<td>' . $justificacion . '</td>';
            } else {
                $td_html .= '<td></td>';
            }
        } else {
            if ($dia_habil == true && $feriado == false) {
                $td_html .= '<td></td>';
                $td_html .= '<td></td>';
                //Existe alguna justificacion para esta fecha
                $sql5 = "select * from ausencia inner join justificaciones using(id_justificacion)
                                        where dia='$diaGestion' and id_empleado='$id_empleado' limit 1";
                $row5 = mysql_fetch_array(mysql_query($sql5));
                if ($row5) {
                    $justificacion = $row5['codigo_justificacion'];
                    $codigoJustificaciones[$fila] = $row5['codigo_justificacion'] . ":" . $row5['nombre_justificacion'];
                    $fila++;
                    $td_html .= '<td>' . $justificacion . '</td>';
                } else {
                    $td_html .= '<td>Ausente</td>';
                    $dias_ausentes++;
                }
            } else {
                if ($dia_habil == true && $feriado == true) {
                    $td_html .= '<td></td>';
                    $td_html .= '<td></td>';
                    $td_html .= '<td>Feriado</td>';
                } else {
                     $td_html .= '<td></td>';
                    $td_html .= '<td></td>';
                    $td_html .= '<td></td>';
                }
            }
        }

        if ($feriado == true) {
            $tr_html = '<tr style="background-color: #EEFCBB;">';
            $dias_feriado++;
        } else {
            if ($dia_habil == false) {
                $tr_html = '<tr style="background-color: #EEFCBB;">';
            } else {
                $tr_html = '<tr>';
            }
        }

        $fila_html .= $tr_html . $td_html . '</tr>';
    }
    $html .= $fila_html;


    //echo "dias ".$diasFaltantes;
    $html.='</table>
        </td>
            <td>
                <table border="1px">
                            <tr>
                               <td style="width:100px;">Feriados</td>
                               <td style="width:150px;">' . $dias_feriado . '</td>
                            </tr>
                            <tr>
                               <td style="width:100px;">Dias Faltantes</td>
                               <td style="width:150px;">' . $dias_ausentes . '</td>
                            </tr>
                                   ';
    $sql5 = "select * from ausencia inner join justificaciones using(id_justificacion)
                                 where id_empleado='$id_empleado' and month(dia)='$mes'
                                 group by ausencia.id_justificacion";
    $res5 = mysql_query($sql5);
    while ($row5 = mysql_fetch_array($res5)) {
        $html.='
                        <tr>
                           <td style="width:100px;">' . $row5['codigo_justificacion'] . '</td>
                           <td style="width:150px;">' . $row5['nombre_justificacion'] . '</td>
                        </tr>';
    }

    $html.='
                        </table>
            </td>
        </tr>
        </table>
            
        </div>
        ';
    $html.='
        
        ';
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
