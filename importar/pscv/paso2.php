<?php
include("../../php/config.php");
include("../../php/objetos/persona.php");
require_once '../reader/Classes/PHPExcel/IOFactory.php';
session_start();
$id_establecimiento = $_SESSION['id_establecimiento'];
$offset =$_POST['offset'];
$batch = $_POST['batch'];
$html_error = $_POST['html_error'];
$error = '';
$total_filas = 0;


//Funciones extras

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
//    $error .='<div style="padding: 5px;background-color: #1C97EA;margin-bottom: 2px;border: solid 2px blue;color: white;;">EL ARCHIVO ES UN EXCEL DE FORMATOS ANTERIORES A 2007 (.xls)</div>';

} else if ($type == 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet') {
    // Extension excel 2007 y 2010
    $ext = 'xlsx';
    //$error .='<div style="padding: 5px;background-color: #1C97EA;margin-bottom: 2px;border: solid 2px blue;color: white;">EL ARCHIVO ES UN EXCEL DE FORMATOS POSTERIOR A 2007 (.xlsx)</div>';
} else {
    // Extension no valida
    echo -1;
    exit();
}

//$timestamp = PHPExcel_Shared_Date::ExcelToPHP($fecha);

$xlsx = 'Excel2007';
$xls = 'Excel5';

//creando el lector
$objReader = PHPExcel_IOFactory::createReader($$ext);

//cargamos el archivo

$fila_excel = 0;

if($objPHPExcel = $objReader->load($tname)){

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

    $columnas = ['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','AA','AB','AC','AD','AE','AF','AG','AH','AJ','AK','AL','AM','AN','AO','AP','AQ','AR','AS','AT','AU','AV'];


    $error_filas = '';
    $cantidad_errores = 0;
//    for ($v = $offset; $v <= $end_v; $v++) {
    if ($v = $offset) {

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
                            $fecha_php = date("Y-m-d",$timestamp);
                            $dato = $fecha_php;
                            $fecha_registro = $dato;
                        }else{
                            if($h == "U"){
                                //COLUMNAS CON FECHAS
                                $timestamp = PHPExcel_Shared_Date::ExcelToPHP($cellValue);
                                //$timestamp = strtotime("+1 day",$timestamp);
                                $fecha_php = date("Y-m-d",$timestamp);
                                $dato = $fecha_php;
                                $fecha_pa = $dato;

                            }else{
                                if($h == "W"){
                                    //COLUMNAS CON FECHAS
                                    $timestamp = PHPExcel_Shared_Date::ExcelToPHP($cellValue);
                                    //$timestamp = strtotime("+1 day",$timestamp);
                                    $fecha_php = date("Y-m-d",$timestamp);
                                    $dato = $fecha_php;
                                    $fecha_glicemia = $dato;

                                }
                                if($h == "Y"){
                                    //COLUMNAS CON FECHAS
                                    $timestamp = PHPExcel_Shared_Date::ExcelToPHP($cellValue);
                                    //$timestamp = strtotime("+1 day",$timestamp);
                                    $fecha_php = date("Y-m-d",$timestamp);
                                    $dato = $fecha_php;
                                    $fecha_ptgo = $dato;

                                }else{
                                    if($h == "AA"){
                                        //COLUMNAS CON FECHAS
                                        $timestamp = PHPExcel_Shared_Date::ExcelToPHP($cellValue);
                                        //$timestamp = strtotime("+1 day",$timestamp);
                                        $fecha_php = date("Y-m-d",$timestamp);
                                        $dato = $fecha_php;
                                        $fecha_colt = $dato;

                                    }else{
                                        if($h == "AC"){
                                            //COLUMNAS CON FECHAS
                                            $timestamp = PHPExcel_Shared_Date::ExcelToPHP($cellValue);
                                            //$timestamp = strtotime("+1 day",$timestamp);
                                            $fecha_php = date("Y-m-d",$timestamp);
                                            $dato = $fecha_php;
                                            $fecha_ldl = $dato;

                                        }else{
                                            if($h == "AD"){
                                                //COLUMNAS CON FECHAS
                                                $timestamp = PHPExcel_Shared_Date::ExcelToPHP($cellValue);
                                                //$timestamp = strtotime("+1 day",$timestamp);
                                                $fecha_php = date("Y-m-d",$timestamp);
                                                $dato = $fecha_php;
                                                $fecha_ekg = $dato;

                                            }else{
                                                if($h == "AF"){
                                                    //COLUMNAS CON FECHAS
                                                    $timestamp = PHPExcel_Shared_Date::ExcelToPHP($cellValue);
                                                    //$timestamp = strtotime("+1 day",$timestamp);
                                                    $fecha_php = date("Y-m-d",$timestamp);
                                                    $dato = $fecha_php;
                                                    $fecha_erc = $dato;

                                                }else{
                                                    if($h == "AH"){
                                                        //COLUMNAS CON FECHAS
                                                        $timestamp = PHPExcel_Shared_Date::ExcelToPHP($cellValue);
                                                        //$timestamp = strtotime("+1 day",$timestamp);
                                                        $fecha_php = date("Y-m-d",$timestamp);
                                                        $dato = $fecha_php;
                                                        $fecha_rac = $dato;

                                                    }else{
                                                        if($h == "AK"){
                                                            //COLUMNAS CON FECHAS
                                                            $timestamp = PHPExcel_Shared_Date::ExcelToPHP($cellValue);
                                                            //$timestamp = strtotime("+1 day",$timestamp);
                                                            $fecha_php = date("Y-m-d",$timestamp);
                                                            $dato = $fecha_php;
                                                            $fecha_hba1c = $dato;

                                                        }
                                                        if($h == "AM"){
                                                            //COLUMNAS CON FECHAS
                                                            $timestamp = PHPExcel_Shared_Date::ExcelToPHP($cellValue);
                                                            //$timestamp = strtotime("+1 day",$timestamp);
                                                            $fecha_php = date("Y-m-d",$timestamp);
                                                            $dato = $fecha_php;
                                                            $fecha_fondo_ojo = $dato;

                                                        }
                                                        if($h == "AN"){
                                                            //COLUMNAS CON FECHAS
                                                            $timestamp = PHPExcel_Shared_Date::ExcelToPHP($cellValue);
                                                            //$timestamp = strtotime("+1 day",$timestamp);
                                                            $fecha_php = date("Y-m-d",$timestamp);
                                                            $dato = $fecha_php;
                                                            $podologia = $dato;

                                                        }if($h == "AT"){
                                                            //COLUMNAS CON FECHAS
                                                            $timestamp = PHPExcel_Shared_Date::ExcelToPHP($cellValue);
                                                            //$timestamp = strtotime("+1 day",$timestamp);
                                                            $fecha_php = date("Y-m-d",$timestamp);
                                                            $dato = $fecha_php;
                                                            $fecha_ev_pie = $dato;

                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }

                    }

                }else{
                    $dato = '';
                }
                //ASIGNAMOS EL VALOR AL ARRAY FILA

                $fila[$h] = $dato;
            }

            if($fecha_registro!=''){
                if($rut!=''){
                    $total_filas++;
                    if(valida_rut($rut)==true){
                        if(rut_establecimiento($rut)==true){
                            $centro = strtoupper($fila['A']);
                            $profesional = strtoupper($fila['B']);
                            if($profesional!=''){
                                $riesgo_cv = strtoupper($fila['E']);
                                $postrado = strtoupper($fila['F']);
                                $hemodialisis = strtoupper($fila['G']);
                                $hta = strtoupper($fila['H']);
                                $hta_sigges = strtoupper($fila['I']);
                                $dm = strtoupper($fila['J']);
                                $dm_sigges = strtoupper($fila['K']);
                                $dlp = strtoupper($fila['L']);
                                $tabaquismo = strtoupper($fila['M']);
                                $iam = strtoupper($fila['N']);
                                $enf_cv = strtoupper($fila['O']);
                                $aas= strtoupper($fila['P']);
                                $ieeca = strtoupper($fila['Q']);
                                $estatina = strtoupper($fila['R']);
                                $araii = strtoupper($fila['S']);
                                $pa = strtoupper($fila['T']);
                                //fecha_pa U
                                $glicemia = strtoupper($fila['V']);
                                //fecha_glicemia W
                                $ptgo = strtoupper($fila['X']);
                                //fecha_ptgo Y
                                $colt=strtoupper($fila['Z']);
                                //fecha_colt AA
                                $ldl=strtoupper($fila['AB']);
                                //$fecha_ldl AC
                                //$fecha_ekg AD
                                $ercvfg=strtoupper($fila['AE']);
                                //$fecha_erc AF
                                $rac=strtoupper($fila['AG']);
                                //$fecha_rac AH
                                $imc=strtoupper($fila['AI']);
                                //diabetes
                                $hba1c=strtoupper($fila['AJ']);
                                //$fecha_hba1c AK
                                $fondo_ojo =strtoupper($fila['AL']);
                                //$fecha_fondo_ojo AM
                                //$podologia AN
                                $ultra_lenta =strtoupper($fila['AO']);
                                $nph =strtoupper($fila['AP']);
                                $rapida =strtoupper($fila['AQ']);
                                $urapida =strtoupper($fila['AR']);
                                $ev_pie =strtoupper($fila['AS']);
                                //$fecha_ev_pie AT
                                $ulceras =strtoupper($fila['AU']);
                                $amputacion =strtoupper($fila['AV']);


                                $paciente = new persona($rut);
                                //PSCV ANTECEDENTES
                                $sql_0 = "select * from paciente_pscv where rut='$rut' limit 1";
                                $row_1 = mysql_fetch_array(mysql_query($sql_0));
                                if(!$row_1){
                                    mysql_query("insert into paciente_pscv(rut) values('$rut')");
                                }
                                $paciente->update_pscv('riesgo_cv',$riesgo_cv,$fecha_registro);
                                $paciente->update_pscv('postrado',$postrado,$fecha_registro);
                                $paciente->update_pscv('hemodialisis',$hemodialisis,$fecha_registro);
                                $paciente->update_pscv('patologia_hta',$hta,$fecha_registro);
                                $paciente->update_pscv('patologia_hta_sigges',$hta_sigges,$fecha_registro);
                                $paciente->update_pscv('patologia_dm',$dm,$fecha_registro);
                                $paciente->update_pscv('patologia_dm_sigges',$dm_sigges,$fecha_registro);
                                $paciente->update_pscv('patologia_dlp',$dlp,$fecha_registro);
                                $paciente->update_pscv('factor_riesgo_tabaquismo',$tabaquismo,$fecha_registro);
                                $paciente->update_pscv('factor_riesgo_iam',$iam,$fecha_registro);
                                $paciente->update_pscv('factor_riesgo_enf_cv',$enf_cv,$fecha_registro);
                                $paciente->update_pscv('tratamiento_aas',$aas,$fecha_registro);
                                $paciente->update_pscv('tratamiento_ieeca',$ieeca,$fecha_registro);
                                $paciente->update_pscv('tratamiento_estatina',$estatina,$fecha_registro);
                                $paciente->update_pscv('tratamiento_araii',$araii,$fecha_registro);


                                $sql_3 = "delete from historial_pscv where valor=''";
                                mysql_query($sql_3);

                                //PARAMETROS
                                $sql_1 = "select * from parametros_pscv where rut='$rut' limit 1";
                                $row_1 = mysql_fetch_array(mysql_query($sql_1));
                                if(!$row_1){
                                    mysql_query("insert into parametros_pscv(rut) values('$rut')");
                                }
                                if($fecha_pa!=''){
                                    $paciente->update_parametro_pscv('pa', $pa, $fecha_pa);
                                }

                                $paciente->update_parametro_pscv('glicemia',$glicemia,$fecha_registro);
                                $paciente->update_parametro_pscv('ptgo',$ptgo,$fecha_registro);
                                $paciente->update_parametro_pscv('colt',$colt,$fecha_registro);
                                if($fecha_ldl!=''){
                                    $paciente->update_parametro_pscv('ldl',$ldl,$fecha_ldl);
                                }
                                if($fecha_ekg!=''){
                                    $paciente->update_parametro_pscv('ekg',$fecha_ekg,$fecha_ekg);
                                }
                                if($fecha_erc!=''){
                                    $paciente->update_parametro_pscv('erc_vfg',$ercvfg,$fecha_erc);
                                }
                                if($fecha_rac!=''){
                                    $paciente->update_parametro_pscv('rac',$rac,$fecha_rac);
                                }




                                $paciente->update_parametro_pscv('imc',$imc,$fecha_registro);



                                $sql_3 = "delete from historial_parametros_pscv where valor=''";
                                mysql_query($sql_3);


                                //DIABETES
                                $sql_1 = "select * from pscv_diabetes_mellitus where rut='$rut' limit 1";
                                $row_1 = mysql_fetch_array(mysql_query($sql_1));
                                if(!$row_1){
                                    mysql_query("insert into pscv_diabetes_mellitus(rut) values('$rut')");
                                }

                                $paciente->update_diabetes_pscv('hba1c',$hba1c,$fecha_hba1c);
                                $paciente->update_diabetes_pscv('fondo_ojo',$fondo_ojo,$fecha_fondo_ojo);
                                $paciente->update_diabetes_pscv('podologia',$podologia,$fecha_registro);
                                $paciente->update_diabetes_pscv('nph',$nph,$fecha_registro);
                                $paciente->update_diabetes_pscv('ultra_lenta',$ultra_lenta,$fecha_registro);
                                $paciente->update_diabetes_pscv('rapida',$rapida,$fecha_registro);
                                $paciente->update_diabetes_pscv('urapida',$urapida,$fecha_registro);
                                $paciente->update_diabetes_pscv('ev_pie',$ev_pie,$fecha_ev_pie);
                                $paciente->update_diabetes_pscv('ulceras',$ulceras,$fecha_registro);
                                $paciente->update_diabetes_pscv('amputacion',$amputacion,$fecha_registro);
                                //delete
                                $sql_3 = "delete from historial_diabetes_mellitus where valor=''";
                                mysql_query($sql_3);
                                $error_filas .='<div class="filaHide" style="padding: 5px;background-color: #d7efff;margin-bottom: 2px;border: solid 2px blue;">REGISTRO ACTUALIZADO DEL PACIENTE  '.$rut.', FILA '.$v.'</div>';
                            }else{
                                //NO EXISTE PROFESIONAL
                                $error_filas .='<div class="filaHide" style="padding: 5px;background-color: #ff898b;margin-bottom: 2px;border: solid 2px red;">NO SE ENCUENTRA REGISTRO DEL PROFESIONAL EN LA FILA:'.$v.', NO SE PUEDE REGISTRAR</div>';
                                $cantidad_errores++;
                            }

                        }else{
                            $error_filas .='<div class="filaHide" style="padding: 5px;background-color: #ff898b;margin-bottom: 2px;border: solid 2px red;">EL RUT '.$rut.' NO PERTENECE A NUESTROS REGISTROS  FILA:'.$v.', NO SE PUEDE REGISTRAR</div>';
                            $cantidad_errores++;
                        }
                    }else{
                        $error_filas .='<div class="filaHide" style="padding: 5px;background-color: #ff898b;margin-bottom: 2px;border: solid 2px red;">EL RUT '.$rut.' NO ES VALIDO EN LA FILA '.$v.', NO SE PUEDE REGISTRAR</div>';
                        $cantidad_errores++;
                    }

                }else{
                    $error_filas .='<div class="filaHide" style="padding: 5px;background-color: #ff898b;margin-bottom: 2px;border: solid 2px red;">NO EXISTE RUT EN LA FILA '.$v.', NO SE PUEDE REGISTRAR'.$rut.'</div>';
                    $cantidad_errores++;
                }
            }else{
                //error fecha registro
                $error_filas .='<div class="filaHide" style="padding: 5px;background-color: #ff898b;margin-bottom: 2px;border: solid 2px red;">NO EXISTE FECHA DE REGISTRO VALIDA EN LA FILA '.$v.', NO SE PUEDE REGISTRAR'.$rut.'</div>';
                $cantidad_errores++;
            }//fin error fecha registro vacia

        }//fin if cabecera de excel
    }
    if($total_filas>0){
        //$error .='<div style="padding: 5px;background-color: #b6ff95;margin-bottom: 2px;border: solid 2px green;">SE LEYERON '.$total_filas.' REGISTROS DEL EXCEL CARGADO.</div>';
        $error .= $error_filas;
    }
    $error .= $error_filas;
    $porcentaje = $fila_excel*100/$batch;
    $error = $html_error.$error;
    $row = array(
        'executed' => $offset,
        'total' => trim($batch),
        'html' => $error,
        'errores' => $cantidad_errores,
        'percentage' => round($porcentaje, 0),
        'execute_time' => 1
    );
    die(json_encode($row));

}else{
    //no se puede cargar el archivo
    $error .='<div style="padding: 5px;background-color: #ff898b;margin-bottom: 2px;border: solid 2px red;">EL ARCHIVO NO PUDO SER LEIDO POR EL SISTEMA, VERIFICAR SEGUN LA PLANTILLA</div>';

}
?>
    <style type="text/css">
        .filaHide{
            display: none;
        }
    </style>
    <script type="text/javascript">
        $(function(){
            $(".filaHide").on('click',function(){
                showFiles();
            });
        })
        function showFiles() {
            $(".filaHide").show("slow");
        }
    </script>
<?php
