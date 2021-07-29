<?php

include("../../php/conex.php");
require_once('../config/lang/eng.php');
require_once('../tcpdf.php');

include("../../php/objetos/functionario.php");
$meses = Array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
$diasMes = Array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
error_reporting(0);
function diaSemana($ano, $mes, $dia) {
    // 0->domingo	 | 6->sabado
    $dia = date("w", mktime(0, 0, 0, $mes, $dia, $ano));
    return $dia;
}

//Datos de Certificado
$mes = $_GET['mes'];
if ($_GET['anio']) {
    $anio = $_GET['anio'];
} else {
    $anio = date('Y');
}
$rut = $_GET['funcionario'];
$id_empleado = $rut;
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

if($rut) {
    $html = '';
    $no_marca = 0;
    $pdf->AddPage();


    $funcionario = new functionario($id_empleado);
    $feriado = 0;
    $sql5 = "select * from horario inner join horario_funcionario using(id_horario)
                                    where id_empleado='$id_empleado'
                                    limit 1";
    $row5 = mysql_fetch_array(mysql_query($sql5));

    //horario asignado PARA EL FUNCIONARIO
    $entradaLJ = $row5['lj_entrada'];
    $entradaV = $row5['v_entrada'];
    $salidaLJ = $row5['lj_salida'];
    $salidaV = $row5['v_salida'];
    $tope_entrada = $row5['tope_entrada'];
    $base_salida = $row5['base_salida'];


    $total_descuento=0;

    $total_extras = 0;

// Set some content to print
    $html = '<div style="text-align:center;">
    <h1>Registro de Asistencia</h1>
    </div>';
    $html.= '<p style="text-align:left;">
        Este Certificado representa un registro de asistencia para el funcionario
        <strong>' . $funcionario->nombre . '</strong> correspondiente al mes de <strong>' . $meses[$mes - 1] . '</strong> 
        del año '.$anio.'
    </p>';
    $html.='
        <div style="position:relative;font-size:8pt;text-align:center;">
        <table>
        <tr>
            <td>
                <table border="0    px"  style="text-align:center;">
                    <tr style="background-color: antiquewhite;">
                        <td style="width:30px;">Dia</td>
                        <td style="width:30px;">Hrs. <br />Trab.</td>
                        <td style="width:60px;">Entrada</td>
                        <td style="width:20px;">Tipo</td>
                        <td style="width:60px;">Salida</td>
                        <td style="width:20px;">Tipo</td>
                        <td style="width:80px;">Justificacion</td>
                        <td style="width:80px;">Min. <br />Descto.</td>
                        <td style="width:80px;">Hrs. <br />Extras.</td>
                    </tr>
            ';
    $diasFaltantes = 0;

    for ($i = 1; $i <= $diasMes[$mes - 1]; $i++) {
        $minutos_atraso = 0;
        $fila = 0;
        $color_dia='#FFFFFF';

        $diaGestion = $anio . "-" . $mes . "-" . $i;

        $semana = diaSemana($anio, $mes, $i);

        $entrada = "";
        $salida = "";
        $justificacion = "";

        $horas_trabajadas = 0;

        $descuento = 0;
        $extras = 0;

        //preguntamos si es feriado
        $sqlFeriado = "select * from feriado where dia='$i' and mes='" . (int) $mes . "' limit 1";
        $rowFeriado = mysql_fetch_array(mysql_query($sqlFeriado));
        if($rowFeriado){
            //es feriado
            $color_dia = "red";
            $feriado++;
        }else{
            //preguntamos si existen marcas
            $sql2 = "select count(*) as total from asistencia 
                                            where fecha='$diaGestion' and id_empleado='$id_empleado'
                                            group by fecha
                                            order by fecha asc";
            $row2 = mysql_fetch_array(mysql_query($sql2));
            if($row2){
                //ALMACENO LAS MARCAS EN LA VARIABLE MARCAS TIPO ARRAY
                $sql5 = "select * from asistencia 
                         where fecha='$diaGestion' and id_empleado='$id_empleado'";
                $res5 = mysql_query($sql5);
                $marcas = Array();
                $m = 0;
                while ($row5 = mysql_fetch_array($res5)) {
                    $marcas[$m] = $row5['hora'];
                    $m++;
                }
                //capturamos entrada y salida
                if($m>1){//mas de una marca
                    $entrada = $marcas[0];
                    if($entrada>'14:00:00'){
                        $entrada='N/M';
                    }
                    $salida = $marcas[$m-1];
                }else{
                    //NO EXISTE REGISTRO DE ULTIMA MARCA
                    if($marcas[0] < '14:00:00'){//media jornada
                        $entrada = $marcas[0];
                        $salida = 'N/M';
                        $no_marca++;
                    }else{
                        $entrada = 'N/M';
                        $salida  = $marcas[0];
                        $no_marca++;
                    }
                }
                //AVERIGUAMOS EL DIA DE LA SEMANA
                if ($semana != 0 && $semana != 6) {
                    //Lun - Vie dias no feriados
                    $color_dia = "#FFFFFF";
                    if($entrada!='N/M'){
                        //procedemos a calcular descuento por entrada
                        $sql_entrada = "SELECT '$entrada'<'$entradaLJ' as estado,
                                '$entrada'>'$tope_entrada' as mayor_tope,
                                minute(timediff('$entrada', '$entradaLJ')) as mayor_min,
                                hour(timediff('$entrada', '$entradaLJ')) as mayor_hora;";
                        //echo $sql_entrada."<br />";
                        $row_entrada = mysql_fetch_array(mysql_query($sql_entrada));
                        //si la entrada es menor a la hora de entrada no se suman descuentos, si es mayor se suman los minutos
                        // cuando estado es 0, significa es es falso por ende se suman descuentos

                        if($row_entrada['estado'] == 0){//solo si es mayor a la hora de entrada
                            //SI ES MAYOR A LA HORA DE ENTRADA
                            //ES DECIR SOLO MARCO LA SALIDA
                            if($row_entrada['mayor_tope']==1){
                                //que hacemos

                            }else{
                                //se suman descuentos
                                $minutos_atraso += $row_entrada['mayor_min']; //SUMAMOS MINUTOS DE ATRAZO
                                $minutos_atraso += ($row_entrada['mayor_hora'] * 60); //SUMAMOS HORAS CONVERTIDAS A MINUTOS
                            }

                        }
                    }else{
                        //solo registra salida
                        //calcular descuentos por salir antes

                    }
                    if($entrada!='N/M' && $salida!='N/M'){
                        //existen dos marcas para calcular horas trabajadas
                        //CALCULAMOS LAS HORAS TRABAJADAS
                        if($salida>'15:00:00'){
                            $sql_hora = "select hour(timediff('".$salida."' ,'".$entrada."')) as hora_trabajada,
                                             60-minute(timediff('".$salida."' ,'".$entrada."')) as minutos_descuento";
                            $row_horas = mysql_fetch_array(mysql_query($sql_hora));
                            $horas_trabajadas = $row_horas['hora_trabajada'];

                            //SALIDA DE LUNES A JUEVES
                            if ($semana != 5 && $semana != 6 && $semana != 0) {
                                //DEBE TRABAJAR 9 HORAS
                                if($horas_trabajadas < 9){
                                    //sumamos los minutos de salir antes del cumplimiento de horario
                                    $minutos_atraso += $row_horas['minutos_descuento'];
                                    //$minutos_atraso += (9*60)-($row_horas['hora_trabajada']*60);
                                }else{
                                    //horas extra semana lun-jue
                                    //$minutos_atraso += $row_horas['hora_trabajada']*60;

                                }
                            } else {
                                if ($semana == 5) { //Viernes
                                    //valor de la mañana
                                    if($horas_trabajadas < 8){ //TIENE QUE TRABAJAR COMO MINIMO 8 HORAS
                                        //sumamos los minutos de salir antes del cumplimiento de horario
                                        $minutos_atraso += $row_horas['minutos_descuento'];
                                    }else{

                                        //horas extra ?

                                    }
                                }
                            }
                        }
                    }

                }else{
                    $color_dia='#00AAE7';//sabado y domingo

                    if($entrada!='N/M' && $salida!='N/M'){
                        //existen dos marcas para calcular horas trabajadas
                        //CALCULAMOS LAS HORAS TRABAJADAS
                        $sql_hora = "select hour(timediff('".$salida."' ,'".$entrada."')) as hora_trabajada,
                                             60-minute(timediff('".$salida."' ,'".$entrada."')) as minutos_descuento";
                        $row_horas = mysql_fetch_array(mysql_query($sql_hora));

                        $extras = ($row_horas['hora_trabajada'] * 60)  ;

                    }

                }


            }else{//no existen marcas
                if ($semana != 0 && $semana != 6) {
                    $diasFaltantes++;
                }else{
                    $color_dia='#00AAE7';//sabado y domingo
                }
            }
        }


        if($horas_trabajadas==0){
            $horas_trabajadas='';
        }
        if($extras==0){
            $extras='';
        }else{
            $extras= ((int)($extras/60));
            $total_extras += $extras;
        }
        if($descuento==0){
            if($minutos_atraso==0){
                $descuento='';
            }else{
                $descuento=$minutos_atraso;
                $total_descuento+=$descuento;
            }

        }else{
            $descuento = $minutos_atraso + ($descuento*60);
            $total_descuento+=$descuento;
        }
        //existe una justificacion
        $sql3 = "SELECT * from ausencia INNER JOIN justificaciones on ausencia.id_justificacion=justificaciones.id_justificacion 
                  where id_empleado='$id_empleado' and dia='$diaGestion' limit 1";
        $row3 = mysql_fetch_array(mysql_query($sql3));
        if($row3){
            $justificacion = $row3['codigo_justificacion'];
        }




        $html.='
                    <tr style="background-color: '.$color_dia.';">
                        <td style="width:30px;">' . $i . '</td>
                        <td style="width:30px;">' . $horas_trabajadas . '</td>
                        <td style="width:60px;">' . $entrada . '</td>
                        <td style="width:20px;">' . $tipo_entrada . '</td>
                        <td style="width:60px;">' . $salida . '</td>
                        <td style="width:20px;">' . $tipo_salida . '</td>
                        <td style="width:80px;">' . $justificacion . '</td>
                        <td style="width:80px;">'.$descuento.'</td>
                        <td style="width:80px;">'.$extras.'</td>
                    </tr>
                    ';


    }
    $html.='
                    <tr style="background-color: aquamarine;font-weight: bold;">
                        <td style="width:30px;"></td>
                        <td style="width:30px;"></td>
                        <td style="width:60px;"></td>
                        <td style="width:20px;"></td>
                        <td style="width:60px;"></td>
                        <td style="width:20px;"></td>
                        <td style="width:80px;">TOTALES</td>
                        <td style="width:80px;">'.' ['.(int)($total_descuento/60).' Hrs.]</td>
                        <td style="width:80px;"> ['.$total_extras.' Hrs.]</td>
                    </tr>
                    ';
    //echo "dias ".$diasFaltantes;
    $html.='</table>
        </td>
            <td>
            </td>
        </tr>
        </table>
        </div>
        ';
    $html.='
    <table>
        <tr>
            <td>AUSENCIAS</td>
            <td>'.$diasFaltantes.'</td>
            <td>NO MARCA</td>
            <td>'.$no_marca.'</td>
        </tr>
    </table>
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
