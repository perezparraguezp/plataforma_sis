<?php
require_once('../config/lang/eng.php');
require_once('../tcpdf.php');
error_reporting(0);

$contrato = $_POST['contrato'];
$division = $_POST['division'];

$tipo = "";
if($contrato == '1'){//PLANTA - CONTRATA
    $tipo = "(planta_municipal='PLANTA' OR planta_municipal='CONTRATA') ";
}else{
    if($contrato == '2'){//HONORARIOS
        $tipo = "planta_municipal='HONORARIO' ";
    }else{//COD. TRABAJO
        $tipo = "planta_municipal='COD. TRABAJO' ";
    }
}

$tipo .= " and reloj!='100' ";
// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('I. Municipalidad de Carahue');
$pdf->SetTitle('Descuentos');
$pdf->SetSubject('Decreto');
$pdf->SetKeywords('Decreto, PDF, Descuentos, Documento');

$title_pdf = "Direccion de Administracion y Finanzas                                            ";
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

// Add a page
// This method has several options, check the source code documentation for more information.
$pdf->AddPage();

// set text shadow effect
$pdf->setTextShadow(array('enabled'=>true, 'depth_w'=>0.2, 'depth_h'=>0.2, 'color'=>array(196,196,196), 'opacity'=>1, 'blend_mode'=>'Normal'));


//============================================================+
// END OF FILE
//============================================================+
include("../../php/config.php");
include("../../php/objetos/functionario.php");

//variables
$meses = Array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
$diasMes = Array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);

$anioGestion = $_POST['anio'];
$mesGestion = $_POST['mes'];

function RestarHoras($horaini, $horafin) {
    $horai = substr($horaini, 0, 2);
    $mini = substr($horaini, 3, 2);
    $segi = substr($horaini, 6, 2);

    $horaf = substr($horafin, 0, 2);
    $minf = substr($horafin, 3, 2);
    $segf = substr($horafin, 6, 2);

    $ini = ((($horai * 60) * 60) + ($mini * 60) + $segi);
    $fin = ((($horaf * 60) * 60) + ($minf * 60) + $segf);

    $dif = $fin - $ini;

    $difh = floor($dif / 3600);
    $difm = floor(($dif - ($difh * 3600)) / 60);
    $difs = $dif - ($difm * 60) - ($difh * 3600);
    return date("H:i:s", mktime($difh, $difm, $difs));
}

function SumarHoras($horaini, $horafin) {
    $horai = substr($horaini, 0, 2);
    $mini = substr($horaini, 3, 2);
    $segi = substr($horaini, 6, 2);

    $horaf = substr($horafin, 0, 2);
    $minf = substr($horafin, 3, 2);
    $segf = substr($horafin, 6, 2);

    $ini = ((($horai * 60) * 60) + ($mini * 60) + $segi);
    $fin = ((($horaf * 60) * 60) + ($minf * 60) + $segf);

    $dif = $fin + $ini;

    $difh = floor($dif / 3600);
    $difm = floor(($dif - ($difh * 3600)) / 60);
    $difs = $dif - ($difm * 60) - ($difh * 3600);
    return date("H:i:s", mktime($difh, $difm, $difs));
}

$sql1 = "select * from funcionario 
           where $tipo and tipo='$division' and activo='SI'"
        . "order by paterno,materno,nombres";
$res1 = mysql_query($sql1);
$input = 0;
//recorremos por empleado
$total1 = 0;
$indice = 0;
$array_empleados = Array();
while ($row1 = mysql_fetch_array($res1)) {
    //echo $row1['paterno']." :";
    //variables
    $anio = $anioGestion;
    $mes = $mesGestion;
    $pivote = "";
    $descuento = "00:00:00";
    $id_empleado = $row1['reloj'];
    $diasFaltantes = 0;
    $funcionario = new functionario(($row1['reloj']));

    $minutos_atraso = 0;

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
    $checkbox = false;

    $horario = "<table style='width: 100%;'>
                    <tr>
                        <td>LUNES a VIERNES</td>    
                        <td>ENTRADA ".$entradaLJ."</td>    
                    </tr>
                    <tr>
                        <td>LUN a JUE</td>    
                        <td>SALIDA ".$salidaLJ."</td>    
                    </tr>
                    <tr>
                        <td>VIERNES</td>    
                        <td>SALIDA ".$salidaV."</td>    
                    </tr>
                </table>";


    for ($i = 1; $i <= $diasMes[$mesGestion - 1]; $i++) {
        $fila = 0;
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

        if($rowFeriado){//es feriado, no hacemos ningun calculo
            //NO SE REGISTRAN ACTIVIDADES
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
                        $entrada = 'N/M';
                    }
                    $salida = $marcas[$m-1];
                }else{
                    //NO EXISTE REGISTRO DE ULTIMA MARCA
                    if($marcas[0] < '14:00:00'){//media jornada
                        $entrada = $marcas[0];
                        $salida = 'N/M';
                    }else{
                        $entrada = 'N/M';
                        $salida  = $marcas[0];
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
                    //no hacemos nada los sabados o domingos

                }

            }else{//no existen marcas
                //no se registran marcas para ser evaluadas
            }

        }

    }//fin for

    if($minutos_atraso > 59){//tiene mas de una hora de atrazo
        $pivote = '<td style="width:80px;font-size:12pt;text-align:center;">' .($indice + 1) . '</td>';
        $pivote .= '<td style="font-size:12pt;width:400px;"><strong>' . $funcionario->nombre . '</strong></td>';
        $pivote .= '<td style="text-align:center;width:100px;font-size:12pt">' . (int)($minutos_atraso/60) . '</td>';
        $array_empleados[$indice] = '<tr>' . $pivote . "</tr>";
        $total1 += (int)($minutos_atraso/60);
        $indice++;
    }
}
//Mostramos los datos capturados
$tableD = '<table style="border:solid 1px;font-size:11pt;">';
$tableD .='<tr style="background-color: antiquewhite;">
    <td style="width:80px;font-size:12pt">##</td>
    <td style="width:400px;font-size:12pt">Funcionario</td>
    <td style="width:100px;font-size:12pt">Descuento</td>
    </tr>';
for ($i = 0; $i < $indice; $i++) {
    $tableD .= $array_empleados[$i];
}
$tableD .='<tr style="background-color: antiquewhite;">
    <td style="width:80px;font-size:12pt">##</td>
    <td style="width:400px;font-size:12pt">Total Descuentos</td>
    <td style="width:100px;font-size:12pt;text-align:center;">'.$total1.'</td>
    </tr>';
$tableD .= "</table>";


// Set some content to print
$texto_mes = $meses[$mesGestion-1];
$html =<<<EOD
<h1 style="text-align:center;">Descuentos: $texto_mes</h1>
<p>A continuaci&oacute;n presentaremos un listado de los funcionarios que recibir&aacute;n
    descuento en horas por motivos de atrasos al momento de marcar su huella
    durante la jornada de trabajo.</p>
    
        $tableD

EOD;
;

// Print text using writeHTMLCell()
$pdf->writeHTMLCell($w=0, $h=0, $x='', $y='', $html, $border=0, $ln=1, $fill=0, $reseth=true, $align='', $autopadding=true);

// ---------------------------------------------------------

// Close and output PDF document
// This method has several options, check the source code documentation for more information.
$pdf->Output('example_001.pdf', 'I');
?>


