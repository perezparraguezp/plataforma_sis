<?php
include("../../php/config.php");
include("../../php/objetos/persona.php");
require_once '../reader/Classes/PHPExcel/IOFactory.php';
//Funciones extras
$offset = $_POST['offset'];
$batch = $_POST['batch'];


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
for ($v = $start_v; $v <= $end_v; $v++) {

    $fila_excel++;
    //empieza lectura horizontal
    $fechas = "";
    $coma = 0;
    if($v > 2){
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

                    $pcint = strtoupper($fila['M']);
                    $imce = strtoupper($fila['H']);
                    $pe = strtoupper($fila['E']);
                    $pt = strtoupper($fila['F']);
                    $te = strtoupper($fila['G']);
                    $dni = strtoupper($fila['I']);
                    $ira = strtoupper($fila['K']);
                    $lme = strtoupper($fila['J']);

                    $perimetro_creaneal = strtoupper($fila['L']);
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

                    //dental
                    $paciente->update_dental_cero($cero,date('Y-m-d',$fecha_antropometria));
                    $paciente->update_dental_ges6($ges6,date('Y-m-d',$fecha_antropometria));
                    //vacunas

                    $sql1 = "update vacunas_paciente set 
                                          2m=upper('$m2'), 
                                          4m=upper('$m4'), 
                                          6m=upper('$m6'), 
                                          12m=upper('$m12'), 
                                          18m=upper('$m18'), 
                                          5anios=upper('$m5anios') 
                                          WHERE rut='$paciente->rut' ";

                    mysql_query($sql1);

                    //psicomotor

                    $paciente->update_eedp($eedp,$fecha_psicomotor);

                    $sql2 = "update paciente_psicomotor set 
                                              eedp='$eedp',
                                              tepsi='$tepsi',
                                              ev_neurosensorial='$ev_neurosensorial',
                                              rx_pelvis='$rx_pelvis',
                                              tepsi_lenguaje='$tepsi_lenguaje',
                                              tepsi_coordinacion='$tepsi_coordinacion',
                                              tepsi_motrocidad='$tepsi_motricidad',
                                              eedp_social='$eedp_social',
                                              eedp_lenguaje='$eedp_lenguaje',
                                              eedp_coordinacion='$eedp_coordinacion',
                                              eedp_motrocidad='$eedp_motricidad'
                                              where rut='$paciente->rut' limit 1";
                    mysql_query($sql2);


                    //antropometria
                    $paciente->update_Antropometria('PE',$pe,$fecha_antropometria);

                    $sql2 = "update antropometria set 
                                            PE='$pe',
                                            PT='$pt',
                                            TE='$te',
                                            DNI='$dni',
                                            IMCE='$imce',
                                            PCINT='$pcint',
                                            LME='$lme',
                                            RIMANL='$rimanl',
                                            SCORE_IRA='$ira',
                                            evaluacion_auditiva='$evaluacion_auditiva',
                                            perimetro_craneal='$perimetro_creaneal',
                                            agudeza_visual='$agudeza_vidual',
                                            presion_arterial='$presion_arterial',
                                            fecha_sql=now(),
                                            fecha_registro='$fecha' 
                                            where rut='$paciente->rut' limit 1";
                    mysql_query($sql2);


                    $id_establecimiento = $_SESSION['id_establecimiento'];

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
        $porcentaje = $fila_excel*100/100;
        $row = array(
        'executed' => $fila_excel,
        'total' => 100,
        'percentage' => round($porcentaje, 0),
        'execute_time' => 1
    );
    die(json_encode($row));
    }//fin if cabecera de excel
}

echo $error;
?>
<?php
//
//
//$result = $connexion->query(
//    'SELECT * FROM comments ORDER BY date_added DESC LIMIT '.$offset.', '.$batch
//);
//if ($result->num_rows > 0) {
//    while ($row_comments = $result->fetch_assoc()) {
//        //proceso
//        $update_comment = "UPDATE comments SET approved = 1 WHERE comment_id = ".$row_comments['comment_id'];
//        $connexion->query($update_comment);
//
//        $update_process = 'UPDATE process SET executed = executed + 1 WHERE id_process = 1';
//        $connexion->query($update_process);
//        //sleep(3);
//    }
//
//    $result_process = $connexion->query('SELECT * FROM process WHERE id_process = 1');
//    $row_process = $result_process->fetch_assoc();
//
//    $percentage = round(($row_process['executed'] * 100) / $row_process['total'], 2);
//
//    $date_add = new DateTime($row_process['date_add']);
//    $date_upd = new DateTime($row_process['date_upd']);
//    $diff = $date_add->diff($date_upd);
//
//    $execute_time = '';
//
//    if ($diff->days > 0) {
//        $execute_time .= $diff->days.' dias';
//    }
//    if ($diff->h > 0) {
//        $execute_time .= ' '.$diff->h.' horas';
//    }
//    if ($diff->i > 0) {
//        $execute_time .= ' '.$diff->i.' minutos';
//    }
//
//    if ($diff->s > 1) {
//        $execute_time .= ' '.$diff->s.' segundos';
//    } else {
//        $execute_time .= ' 1 segundo';
//    }
//
//    $update_process = 'UPDATE process SET percentage = '.$percentage.', execute_time = "'.(string)$execute_time.'" WHERE id_process = 1';
//    $connexion->query($update_process);
//
//    $row = array(
//        'executed' => $row_process['executed'],
//        'total' => $row_process['total'],
//        'percentage' => round($percentage, 0),
//        'execute_time' => $execute_time
//    );
//    die(json_encode($row));
//}
//?>
