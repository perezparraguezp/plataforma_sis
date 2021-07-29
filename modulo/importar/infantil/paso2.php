<?php

include("../../php/config.php");
include("../../php/objetos/persona.php");
require_once '../reader/Classes/PHPExcel/IOFactory.php';
//Funciones extras
$offset =$_POST['offset'];
$batch = $_POST['batch'];

$id_establecimiento = $_SESSION['id_establecimiento'];

function get_cell($cell, $objPHPExcel) {
    //select one cell
    $objCell = ($objPHPExcel->getActiveSheet()->getCell($cell));
    //get cell value
    return $objCell->getvalue();
}

function pp(&$var) {
    $var = chr(ord($var) + 1);
    return true;
}

$name = $_FILES['file']['name'];
$tname = $_FILES['file']['tmp_name'];
$type = $_FILES['file']['type'];

if ($type == 'application/vnd.ms-excel') {
    // Extension excel 97
    $ext = 'xls';
} else if ($type == 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet') {
    // Extension excel 2007 y 2010
    $ext = 'xlsx';
} else {
    // Extension no valida
    echo -1;
    echo 'parsererror';
    exit();
}

//$timestamp = PHPExcel_Shared_Date::ExcelToPHP($fecha);

$xlsx = 'Excel2007';
$xls = 'Excel5';

//creando el lector
$objReader = PHPExcel_IOFactory::createReader($$ext);

//cargamos el archivo
$objPHPExcel = $objReader->load($tname);

$dim = $objPHPExcel->getActiveSheet()->calculateWorksheetDimension();

// list coloca en array $start y $end
list($start, $end) = explode(':', $dim);


if (!preg_match('#([A-Z]+)([0-9]+)#', $start, $rslt)) {
    return false;
}
list($start, $start_h, $start_v) = $rslt;
if (!preg_match('#([A-Z]+)([0-9]+)#', $end, $rslt)) {
    return false;
}
list($end, $end_h, $end_v) = $rslt;

$columnas = ['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ'];
$error = '';
$fila_excel = 0;
for ($v = $offset; $v <= $end_v; $v++) {
    //empieza lectura horizontal
    $fechas = "";
    $coma = 0;
    if($v > 2){

        $fila_excel++;
        //desde la fila 3 en adelante
        $fila = array();
        $rut = '';

        for ($c = 0; $c < count($columnas); $c++) {
            $h = $columnas[$c];

            $cellValue = get_cell($h . $v, $objPHPExcel);//ASIGNAMOS EL CONTENIDO DE LA CELDA A LA VARIABLE

            if($cellValue !== null){
                //tiene datos
                $dato = $cellValue;

                if($h == "C"){
                    //columna con RUT
                    $rut = $cellValue;
                    $rut = str_replace(".","",$rut);//limpiamos el rut
                }else{
                    if($h == "D" ){
                        //COLUMNAS CON FECHAS
                        $timestamp = PHPExcel_Shared_Date::ExcelToPHP($cellValue);
                        //$timestamp = strtotime("+1 day",$timestamp);
                        $fecha_php = date("Y-m-d H:i:s",$timestamp);
                        $dato = $fecha_php;
                        $fecha_antropometria = $dato;
                    }else{
                        if($h == "W"){
                            //COLUMNAS CON FECHAS
                            $timestamp = PHPExcel_Shared_Date::ExcelToPHP($cellValue);
                            //$timestamp = strtotime("+1 day",$timestamp);
                            $fecha_php = date("Y-m-d H:i:s",$timestamp);
                            $dato = $fecha_php;
                            $fecha_psicomotor = $dato;

                        }
                    }

                }

            }else{
                $dato = '';
            }
            //ASIGNAMOS EL VALOR AL ARRAY FILA

            $fila[$h] = $dato;
        }


        if($rut!=''){

            if(valida_rut($rut)==true){
                if(rut_establecimiento($rut)==true){
                    $centro = strtoupper($fila['A']);
                    $profesional = strtoupper($fila['B']);
                    $id_empleado = $profesional;

                    $pcint = strtoupper($fila['M']);
                    $imce = strtoupper($fila['H']);
                    $pe = strtoupper($fila['E']);
                    $pt = strtoupper($fila['F']);
                    $te = strtoupper($fila['G']);
                    $dni = strtoupper($fila['I']);
                    $ira = strtoupper($fila['K']);
                    $lme = strtoupper($fila['J']);

                    $perimetro_craneal = strtoupper($fila['L']);
                    $presion_arterial = strtoupper($fila['N']);
                    $agudeza_vidual = strtoupper($fila['O']);
                    $evaluacion_auditiva= strtoupper($fila['P']);

                    //vacunas
                    $m2 = strtoupper($fila['Q']);
                    $m4 = strtoupper($fila['R']);
                    $m6 = strtoupper($fila['S']);
                    $m12 = strtoupper($fila['T']);
                    $m18 = strtoupper($fila['U']);
                    $m5anios = strtoupper($fila['V']);

                    $ev_neurosensorial=strtoupper($fila['X']);
                    $rx_pelvis=strtoupper($fila['Y']);
                    $eedp=strtoupper($fila['Z']);
                    $eedp_lenguaje=strtoupper($fila['AA']);
                    $eedp_motricidad=strtoupper($fila['AB']);
                    $eedp_coordinacion=strtoupper($fila['AC']);
                    $eedp_social=strtoupper($fila['AD']);

                    $tepsi=strtoupper($fila['AE']);
                    $tepsi_lenguaje=strtoupper($fila['AF']);
                    $tepsi_motricidad=strtoupper($fila['AG']);
                    $tepsi_coordinacion=strtoupper($fila['AH']);

                    $cero=strtoupper($fila['AI']);
                    $ges6=strtoupper($fila['AJ']);


                    $paciente = new persona($rut);
                    //antropometria
                    $paciente->update_Antropometria('PE',$pe,$fecha_antropometria);
                    $sql2 = "update antropometria set ";
                    $coma = 0;
                    if($pe!='')     { if($coma>0){ $sql2.= ","; } $sql2.= "PE=upper('$pe') ";$coma++; mysql_query("insert into historial_antropometria(rut,id_empleado,indicador,valor,fecha_registro) values('$rut','$id_empleado','PE','$pe','$fecha_antropometria');");}
                    if($pt!='')     { if($coma>0){ $sql2.= ","; } $sql2.= "PT=upper('$pt') ";$coma++; mysql_query("insert into historial_antropometria(rut,id_empleado,indicador,valor,fecha_registro) values('$rut','$id_empleado','PT','$pt','$fecha_antropometria');");}
                    if($te!='')     { if($coma>0){ $sql2.= ","; } $sql2.= "TE=upper('$te') ";$coma++; mysql_query("insert into historial_antropometria(rut,id_empleado,indicador,valor,fecha_registro) values('$rut','$id_empleado','TE','$te','$fecha_antropometria');");}
                    if($dni!='')    { if($coma>0){ $sql2.= ","; } $sql2.= "DNI=upper('$dni') ";$coma++; mysql_query("insert into historial_antropometria(rut,id_empleado,indicador,valor,fecha_registro) values('$rut','$id_empleado','DNI','$dni','$fecha_antropometria');"); }
                    if($pcint!='')  { if($coma>0){ $sql2.= ","; } $sql2.= "PCINT=upper('$pcint') ";$coma++; mysql_query("insert into historial_antropometria(rut,id_empleado,indicador,valor,fecha_registro) values('$rut','$id_empleado','PCINT','$pcint','$fecha_antropometria');");}
                    if($imce!='')   { if($coma>0){ $sql2.= ","; } $sql2.= "IMCE=upper('$imce') ";$coma++; mysql_query("insert into historial_antropometria(rut,id_empleado,indicador,valor,fecha_registro) values('$rut','$id_empleado','IMCE','$imce','$fecha_antropometria');");}
                    if($lme!='')    { if($coma>0){ $sql2.= ","; } $sql2.= "LME=upper('$lme') ";$coma++; mysql_query("insert into historial_antropometria(rut,id_empleado,indicador,valor,fecha_registro) values('$rut','$id_empleado','LME','$lme','$fecha_antropometria');");}
                    if($ira!='')    { if($coma>0){ $sql2.= ","; } $sql2.= "SCORE_IRA=upper('$ira') ";$coma++; mysql_query("insert into historial_antropometria(rut,id_empleado,indicador,valor,fecha_registro) values('$rut','$id_empleado','SCORE_IRA','$ira','$fecha_antropometria');");}
                    if($evaluacion_auditiva!='')    { if($coma>0){ $sql2.= ","; } $sql2.= "evaluacion_auditiva=upper('$evaluacion_auditiva') ";$coma++; mysql_query("insert into historial_antropometria(rut,id_empleado,indicador,valor,fecha_registro) values('$rut','$id_empleado','evaluacion_auditiva','$evaluacion_auditiva','$fecha_antropometria');");}
                    if($perimetro_craneal!='')      { if($coma>0){ $sql2.= ","; } $sql2.= "perimetro_craneal=upper('$perimetro_craneal') ";$coma++; mysql_query("insert into historial_antropometria(rut,id_empleado,indicador,valor,fecha_registro) values('$rut','$id_empleado','perimetro_craneal','$perimetro_craneal','$fecha_antropometria');");}
                    if($agudeza_vidual!='')         { if($coma>0){ $sql2.= ","; } $sql2.= "agudeza_visual=upper('$agudeza_vidual') ";$coma++; mysql_query("insert into historial_antropometria(rut,id_empleado,indicador,valor,fecha_registro) values('$rut','$id_empleado','agudeza_visual','$agudeza_vidual','$fecha_antropometria');");}
                    if($presion_arterial!='')       { if($coma>0){ $sql2.= ","; } $sql2.= "presion_arterial=upper('$presion_arterial') ";$coma++; mysql_query("insert into historial_antropometria(rut,id_empleado,indicador,valor,fecha_registro) values('$rut','$id_empleado','presion_arterial','$presion_arterial','$fecha_antropometria');");}
                    if($fecha_psicomotor!='')                  { if($coma>0){ $sql2.= ","; } $sql2.= "fecha_registro=upper('$fecha_psicomotor') ";$coma++; }
                    $sql2 .= " where rut='$paciente->rut' limit 1";

                    mysql_query($sql2);


                    //dental
                    $paciente->update_dental_cero($cero,date('Y-m-d',$fecha_antropometria));
                    $paciente->update_dental_ges6($ges6,date('Y-m-d',$fecha_antropometria));

                    //vacunas
                    $paciente->validaVacunas();

                    $sql1 = "update vacunas_paciente set ";
                    $coma = 0;
                    if($m2!=''){ if($coma>0){ $sql1.= ","; } $sql1.= "2m=upper('$m2') ";$coma++; }
                    if($m4!=''){ if($coma>0){ $sql1.= ","; } $sql1.= "4m=upper('$m4') ";$coma++; }
                    if($m6!=''){ if($coma>0){ $sql1.= ","; } $sql1.= "6m=upper('$m6') ";$coma++; }
                    if($m12!=''){ if($coma>0){ $sql1.= ","; } $sql1.= "12m=upper('$m12') ";$coma++; }
                    if($m18!=''){ if($coma>0){ $sql1.= ","; } $sql1.= "18m=upper('$m18') ";$coma++; }
                    if($m5anios!=''){ if($coma>0){ $sql1.= ","; } $sql1.= "5anios=upper('$m5anios') ";$coma++; }
                    $sql1 .= "WHERE rut='$paciente->rut' ";
                    mysql_query($sql1);



                    //psicomotor
                    $paciente->validarPsicomotor();
                    $sql3 = "update paciente_psicomotor set ";
                    $coma = 0;
                    if($eedp!=''){ if($coma>0){ $sql3.= ","; } $sql3.= "eedp=upper('$eedp') ";$coma++; }
                    if($tepsi!=''){ if($coma>0){ $sql3.= ","; } $sql3.= "tepsi=upper('$tepsi') ";$coma++; }
                    if($ev_neurosensorial!=''){ if($coma>0){ $sql3.= ","; } $sql3.= "ev_neurosensorial=upper('$ev_neurosensorial') ";$coma++; }
                    if($rx_pelvis!=''){ if($coma>0){ $sql3.= ","; } $sql3.= "rx_pelvis=upper('$rx_pelvis') ";$coma++; }
                    if($tepsi_lenguaje!=''){ if($coma>0){ $sql3.= ","; } $sql3.= "tepsi_lenguaje=upper('$tepsi_lenguaje') ";$coma++; }
                    if($tepsi_coordinacion!=''){ if($coma>0){ $sql3.= ","; } $sql3.= "tepsi_coordinacion=upper('$tepsi_coordinacion') ";$coma++; }
                    if($tepsi_motricidad!=''){ if($coma>0){ $sql3.= ","; } $sql3.= "tepsi_motrocidad=upper('$tepsi_motricidad') ";$coma++; }
                    if($eedp_social!=''){ if($coma>0){ $sql3.= ","; } $sql3.= "eedp_social=upper('$eedp_social') ";$coma++; }
                    if($eedp_lenguaje!=''){ if($coma>0){ $sql3.= ","; } $sql3.= "eedp_lenguaje=upper('$eedp_lenguaje') ";$coma++; }
                    if($eedp_coordinacion!=''){ if($coma>0){ $sql3.= ","; } $sql3.= "eedp_coordinacion=upper('$eedp_coordinacion') ";$coma++; }
                    if($eedp_motricidad!=''){ if($coma>0){ $sql3.= ","; } $sql3.= "eedp_motrocidad=upper('$eedp_motricidad') ";$coma++; }
                    $sql3 .= "where rut='$paciente->rut' limit 1";


                    mysql_query($sql3);

                    $error .='<div style="padding: 5px;background-color: #d7efff;margin-bottom: 2px;border: solid 2px blue;">REGISTRO ACTUALIZADO DEL PACIENTE  '.$rut.', FILA '.$v.'</div>';
                }else{
                    $error .='<div style="padding: 5px;background-color: #ff898b;margin-bottom: 2px;border: solid 2px red;">EL RUT '.$rut.' NO PERTENECE A NUESTROS REGISTROS  FILA:'.$v.', NO SE PUEDE REGISTRAR</div>';
                }
            }else{
                $error .='<div style="padding: 5px;background-color: #ff898b;margin-bottom: 2px;border: solid 2px red;">EL RUT '.$rut.' NO ES VALIDO EN LA FILA '.$v.', NO SE PUEDE REGISTRAR</div>';
            }

        }else{
            $error .='<div style="padding: 5px;background-color: #ff898b;margin-bottom: 2px;border: solid 2px red;">NO EXISTE RUT EN LA FILA '.$v.', NO SE PUEDE REGISTRAR'.$rut.'</div>';
        }


        $porcentaje = $fila_excel*100/$batch;
        $row = array(
            'executed' => $offset,
            'total' => trim($batch),
            'percentage' => round($porcentaje, 0),
            'execute_time' => 1
        );
        die(json_encode($row));
    }//fin if cabecera de excel

}
