<?php

include("../../php/config.php");
include("../../php/objetos/persona.php");
include("../../php/objetos/contrato_honorario.php");
include("../../php/objetos/documento.php");
include("../../php/objetos/functionario.php");
include("../../php/class/decreto.php");
require_once('../config/lang/cat.php');
require_once('../tcpdf.php');
session_start();
//error_reporting(0);
//variables mysql

//VARIABLES POST
$id_responsable = $_POST['id_responsable'];
$id_secretaria = $_POST['id_secretaria'];
$nombres = $_POST['nombres'];
$paterno = $_POST['paterno'];
$materno = $_POST['materno'];
$certificado = $_POST['imputacion'];
$profesion = $_POST['profesion'];
$domicilio = $_POST['direccion'];
$discapacidad = $_POST['discapacidad'];
$rut = str_replace(".","",$_POST['rut']);
$rut_mysql = $rut;
$firma1 = $_POST['firma1'];
$firma2 = $_POST['firma2'];
$firma3 = $_POST['firma3'];
$cuotas = $_POST['cuotas'];
$unidad = $_POST['unidad'];
$id_centro_costo = $_POST['centro_de_costo'];

$cabecera = trim($_POST['cabecera']);
$direccion = trim($_POST['direccion']);
$profesion = trim($_POST['profesion']);
$fecha_inicio = $_POST['fecha_inicio'];
$fecha_termino = $_POST['fecha_termino'];
$cuenta_cip = $_POST['cuenta_cip'];

$tercer_decreto = $_POST['tercer_decreto'];


$datos_cip = mysql_fetch_array(mysql_query("select nombre_centro, cic.monto_certificado, cic.cuenta_general from compras_certificado_imputacion cci
    inner join certificado_imputacion_cuentas cic using (id_certificado)
    inner join pc_centro_costo pcc using (id_centro_costo)
    where cic.id_dato = '$cuenta_cip'"));



$nombre_centro_costo = mysql_fetch_array(mysql_query("select nombre_centro from pc_centro_costo where id_ntro_costo ='$id_cent_centro_costo'"));
$nombre_centro_costo = $nombre_centro_costo['nombre_centro_costo'];




$tipo_contrato = $_POST['extrapresupuestario'];
if ($tipo_contrato){
    $tipo_contrato="EXTRAPRESUPUESTARIO";
}else{
    $tipo_contrato="MUNICIPAL";
}



//Creacion de objetos


$responsable = new functionario($id_responsable);

$contrato_honorario = new contrato_honorario($_SESSION['id_empleado']);

$persona = new persona($rut);


if($_POST['registro']){
    $REGISTRO = ",".$_POST['registro'];
}else{
    $REGISTRO = '';
}



//Agregar o actualizar persona
if($persona->existe){
    $persona->updateDatos($nombres,$paterno,$materno,$profesion);
    $persona->updateDatosContacto($persona->telefono,$domicilio,$persona->correo);
}else{
    $persona->crearPersona($nombres,$paterno,$materno,$domicilio,$persona->telefono,$persona->correo);
}
//if($profesion!=''){
//    //actualizamos la profesion de la persona, solo si se ingresa una nueva profesion
//    $persona->updateProfesion($profesion);
//}



//if($diferencial!=''){
//    if($diferencial != '0'){
//        $monto_texto = str_replace("$ ","",str_replace(".","",$_POST['monto']));
//        $monto_texto = "$ ".number_format($monto_texto + $diferencial,0,'','.')." (".convertir_numero_a_letra(($monto_texto + $diferencial)).")";
//        $diferencial = "y una cuota diferencial de $ ".number_format($diferencial,0,'','.')." (".convertir_numero_a_letra($diferencial).").";
//    }else{
//        $monto_texto = $_POST['monto'];
//        $diferencial = '.';
//    }
//
//}else{
//    $monto_texto = $_POST['monto'];
//    $diferencial = '.';
//}
$monto_formato = $_POST['monto'];
$monto_texto = str_replace("$ ","",str_replace(".","",$_POST['monto']));
//Variables recibidas

$sqlF1 = "select * from directivo where id_directivo='$firma1' limit 1";
$rowF1 = mysql_fetch_array(mysql_query($sqlF1));
$sqlF2 = "select * from directivo where id_directivo='$firma2' limit 1";
$rowF2 = mysql_fetch_array(mysql_query($sqlF2));
$sqlF3 = "select * from directivo where id_directivo='$firma3' limit 1";
$rowF3 = mysql_fetch_array(mysql_query($sqlF3));
$nombreF1 = $rowF1['nombre_directivo'];
$nombreF2 = $rowF2['nombre_directivo'];
$mandante = $rowF3['nombre_directivo'];
$encargados = $_POST['pie_firmas'];

$control = $_POST['control'];
$rrhh = $_POST['rrhh'];

$sqlCONTROl = "select * from directivo where id_directivo='$control' limit 1";
$rowCONTROL = mysql_fetch_array(mysql_query($sqlCONTROl));
$nombre_control = $rowCONTROL['nombre_directivo'];
$cargoControl = trim("Dir. Control ".$_POST['control_s']);

$sqlRRHH = "select * from directivo where id_directivo='$rrhh' limit 1";
$rowRRHH = mysql_fetch_array(mysql_query($sqlRRHH));
$nombre_rrhh = trim($rowRRHH['nombre_directivo']);

$cargo_rrhh = trim($rowRRHH['cargo_directivo']." ".$_POST['rrhh_s']);



$sql_update = "update decreto set 
                firma1='$firma1',firma2='$firma2',firma3='$firma3',
                encargados='$encargados'
                where id_decreto=6 ";
mysql_query($sql_update);


if($_POST['secretario_s']){
    $cargoF1 = "Secretario Municipal (s)";
}else{
    $cargoF1 = "Secretario Municipal";
}

if($_POST['alcalde_s']){
    $cargoF2 = "Alcalde (s)";
}else{
    $cargoF2 = $rowF2['cargo_directivo'];
}



//Variables de dias y meses
$dias = Array("Domingo", "Lunes", "Martes", "Miercoles", "Jueves", "Viernes", "Sabado");
$meses = Array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");




//variables PDF

//list($anio,$mes,$dia) = explode("-",$fecha_inicio);
list($anio,$mes,$dia) = explode("-",$_POST['fecha_contrato']);
$fecha_contrato = $_POST['fecha_contrato'];

$f = $_POST['f'];//funciones del contrato
$lista_funciones = '<ul>';
foreach ($f as $i => $valor){
    if(trim($valor)!=''){
        $lista_funciones .= '<li>'.(trim(strtoupper($valor))).'</li>';
    }

}
$lista_funciones .= '</ul>';


list($anio,$mes,$dia) = explode("-",$_POST['fecha_contrato']);
list($simbolo,$monto) = explode("$ ",$_POST['monto']);
$monto = str_replace(".","",$monto);

$monto_numero_letra = convertir_numero_a_letra($monto);


list($cuenta,$nombre_cuenta) = explode(" | ",$_POST['cuenta']);

$sql_cuenta = "select * from pc_cuenta where codigo_general='".trim($cuenta)."' limit 1";
//echo $sql_cuenta;
$row_cuenta = mysql_fetch_array(mysql_query($sql_cuenta));



$id_programa = $_POST['programa'];


$titulo_sup = "I. Municipalidad de Carahue                                           ";
$titulo_inf = "Departamento de Recursos Humanos\nPortales #295, Carahue";
$nombre_documento = "Contrato_Honorario_".$nombres."_".$paterno."_$anio";
$contrato = new documento($titulo_sup,'OFICINA DE PERSONAL',$nombre_documento);


//----------------------------------------------------------------------------------------------------------------------
//Actualizaciones BD
if($_POST['id_contrato']){ //Si existe el contrato
    $id_contrato = $_POST['id_contrato'];
    $contrato_honorario->updateInfoContrato($id_contrato);
    $contrato_honorario->updateFuncionesContrato($lista_funciones);
    $contrato_honorario->updateMontoContrato($monto_formato);
//    $contrato_honorario->updateDatosPrograma($id_programa);

    $contrato_honorario->updateCentroCosto($id_centro_costo);
    $contrato_honorario->updateResponsableContrato($id_responsable);
    $contrato_honorario->updateSecretaria($id_secretaria);
    $contrato_honorario->updateCuotas($cuotas);
    $contrato_honorario->updateCertificadoImputacion($certificado);
    $contrato->cargarFolio($contrato_honorario->folio_contrato);
    $folio = $contrato_honorario->folio_contrato;

    $contrato_honorario ->updateExtrapresupuestario($tipo_contrato);
    $contrato_honorario ->updateAfectado($rut);
    $contrato_honorario ->updateCuentaCip($cuenta_cip);

}else{ //Si no esxiste el contrato
    $fecha_contrato = $_POST['fecha_contrato'];
    $contrato_honorario->nuevoContratoHonorario(date('Y'),$fecha_contrato);
    $decreto = new decreto();
    $folio = $decreto->folio;
    $decreto->tipo_decreto("Personal con Registro","CONTRATO DE TRABAJO");
    $texto_decreto_folio = "Apruebese el Contrato entre ".$persona->nombre_completo." y la Municipalidad de Carahue, entre las fechas $fecha_inicio al $fecha_termino, por un Monto de $monto_texto";
    $decreto->datos_afectado($rut_mysql,$persona->nombre_completo,' ',$texto_decreto_folio);
    $contrato -> asignarFolioExterno($folio);
    $contrato_honorario->updateFolioContrato($folio);
    $contrato_honorario->updateMontoContrato($monto_formato);
    $contrato_honorario->updateFuncionesContrato($lista_funciones);
    $contrato_honorario->updateFechasContrato($fecha_inicio,$fecha_termino,$fecha_contrato);
    $contrato_honorario->updateContratoPersonaPrograma($rut,0);
    $contrato_honorario->updateCentroCosto($id_centro_costo);
    $contrato_honorario->updateEstadoContrato("EN TRAMITE");
    $contrato_honorario->updateResponsableContrato($id_responsable);
    $contrato_honorario->updateSecretaria($id_secretaria);
    $contrato_honorario->updateCertificadoImputacion($certificado);
    $contrato_honorario->updateCuotas($cuotas);
    $contrato_honorario->modificarCuenta($cuenta);
    $contrato_honorario->updateExtrapresupuestario($tipo_contrato);
    $contrato->updateTipoDocumento('Personal con Registro','Contrato');
    $id_contrato = mysql_fetch_array(mysql_query("select id_contrato from contrato_honorario where folio='$folio'"));
    $id_contrato = $id_contrato['id_contrato'];
    $contrato_honorario ->updateCuentaCip($cuenta_cip);
}

$nombre_funcionario = $persona->nombre_completo;
//----------------------------------------------------------------------------------------------------------------------

//----------------------------------------------------------------------------------------------------------------------
//Actualizamos el monto sugerido de las cuotas a pagar
mysql_query("delete from sugerencias_montos_honorarios where id_contrato='$id_contrato'");
if($cuotas >1){
    for ($i = 1; $i <= $cuotas; $i++) {
        $montoEstimadoMensual= $_POST['estimado'.$i];
        mysql_query("insert into sugerencias_montos_honorarios (rut,id_contrato,monto_bruto,nro_cuota) values ('$rut','$id_contrato','$montoEstimadoMensual','$i')");
    }
}else{
    $montoEstimadoMensual= $monto;
    mysql_query("insert into sugerencias_montos_honorarios (rut,id_contrato,monto_bruto,nro_cuota) values ('$rut','$id_contrato','$montoEstimadoMensual','1')");
}
//----------------------------------------------------------------------------------------------------------------------

$sql_1 = "select * from programas_municipales where id_programa='$id_programa' limit 1";
$row_1 = mysql_fetch_array(mysql_query($sql_1));
$nombre_programa = $row_1['nombre_programa'];




if($_POST['t1']){
    $post_natal = $_POST['texto1'];
}else{
    $post_natal = '';
}

$i = 3;
$vistos_extra = '';
if($_POST['d1']){
    $d1 = $_POST['texto_d1'];
    $vistos_extra .= '3.- '.trim($d1).'<br />';
    $i = 4;
}
if($_POST['d2']){
    $d2 = $_POST['texto_d2'];
    if($i==3){
        $i = 4;
    }else{
        $i = 5;
    }
    $vistos_extra .= ($i-1).'.- '.trim($d2).'<br />';

}

//------------------------------------------------------------------------------------------------------------------------
//TEXTOS VARIABLES

$vistos = $_POST['vistos'];
$vistos_decretos = '<p>';
$numero_visto = 1;
foreach ($vistos as $i => $value){
    $vistos_decretos .= $numero_visto.'.-'.$_POST['texto_visto'.$value].'<br />';
    $numero_visto++;
}
$vistos_decretos .= '</p>';
//$texto_decreto_municipal_parte1 = '<p>1.- El Decreto Alcaldicio Numero 3767 de fecha 27 de Diciembre de 2019, que aprueba el Presupuesto de Ingreso y Gastos para el año 2020.<br />
//        2.- El Decreto 3768 del 27 De Diciembre de 2019, que Aprueba las Actividades y programas para el año 2020.<br />
//        3.- El certificado de imputacion presupuestaria Nº <strong>'.$certificado.'.</strong><br />
//        4.- El Programa o Actividad: <strong>'.$nombre_programa.'.</strong><br />
//        5.- El contrato de prestacion de servicios a Honorarios suscritos entre la Municipalidad de Carahue y el prestador de servicios
//        que se detalla en la siguiente tabla:</p>';

//$texto_decreto_extrapresupuestario_parte1 = '<p>1.- El Decreto Alcaldicio Numero 3767 de fecha 27 de Diciembre de 2019, que aprueba el Presupuesto de Ingreso y Gastos para el año 2020.<br />
//        2.- El Decreto 3768 del 27 De Diciembre de 2019, que Aprueba las Actividades y programas para el año 2020.<br />
//        '.$vistos_extra.'
//        '.($i).'.- El Programa o Actividad: <strong>'.$datos_cip['nombre_centro'].'.</strong><br />
//        '.($i+1).'.- El contrato de prestacion de servicios a Honorarios suscritos entre la Municipalidad de Carahue y el prestador de servicios
//        que se detalla en la siguiente tabla:</p>.';

//$texto_decreto_extrapresupuestario_parte2 = '3.- Imputese el gasto del presente decreto a la cuenta presupuestaria <strong>'.$cuenta.'</strong> asociada al programa <strong>'.$nombre_programa.'</strong>.<br />';
//$texto_decreto_municipal_parte2 = '3.- Imputese el gasto del presente decreto a la cuenta presupuestaria '.$datos_cip['cuenta_general'].' asociada al programa '.$datos_cip['nombre_centro'].', con el Certificado de Imputacion Nª'.$certificado.'<br />';

//------------------------------------------------------------------------------------------------------------------------




$texto_contrato_honorario = str_replace("Folio: XXXXXXX","Folio: $folio",$_POST['contrato_honorario_html']);









$texto_decreto_honorario = '<style type="text/css">
            p{
                text-align:justify;
                text-indent: 280px;
                font-size:0.7em;
                margin-top: 0px;
            }
            BLOCKQUOTE{
                font-size:0.7em;
            }
            table{
                font-size:0.6em;
            }
            span{
                font-size:0.7em;
                text-align: right;
                }
            li{
            font-size:0.7em;
            }
        </style>
        <p>DECRETO N:</p>
        <p>Carahue, <br />VISTOS: Estos Antecedentes</p>'
        .$vistos_decretos;

        list($nombre_actividad,$codigo_fox) = explode("FOX:",$id_programa);

        $texto_decreto_honorario.='<br />
        <table border="1px" style="font-size: 0.7em;;width: 100%;">
        <tr style="background-color: antiquewhite;font-weight: bold;">
           <td style="font-size: 0.7em;">Prestador de Servicios</td>
           <td style="font-size: 0.7em;">Fecha de Contrato</td>
           <td style="font-size: 0.7em;">Fecha Inicio</td>
           <td style="font-size: 0.7em;">Fecha Termino</td>
        </tr>
        <tr>
           <td style="font-size: 0.7em;">'.$nombre_funcionario.'</td>
           <td style="font-size: 0.7em;">'.fechaNormal($fecha_contrato).'</td>
           <td style="font-size: 0.7em;">'.fechaNormal($fecha_inicio).'</td>
           <td style="font-size: 0.7em;">'.fechaNormal($fecha_termino).'</td>
        </tr>
        </table><p>';

        $texto_decreto_honorario.=$numero_visto.'.- Las facultades que me confiere el texto refundido de la Ley Nº 18.695, Organica Constitucional de Municipalidades.</p>
        <p><strong><u>DECRETO</u></strong></p>
        <p>1.- Apru&eacute;bese el contrato de prestaci&oacute;n de servicios a Honorarios suscritos entre la Municipalidad de Carahue y el 
        prestador de servicios citados en el visto numero 3, conforme al siguiente detalle:</p>
        <table border="1px" style="font-size: 0.6em;">
        <tr style="background-color: antiquewhite;font-weight: bold;">
           <td style="font-size: 0.7em;">BENEFICIARIO</td>
           <td style="font-size: 0.7em;">RUT</td>
           <td style="font-size: 0.7em;">MONTO</td>
           <td style="font-size: 0.7em;">ACTIVIDAD</td>
           
        </tr>
        <tr>
           <td style="font-size: 0.7em;">'.$nombre_funcionario.'</td>
           <td style="font-size: 0.7em;">'.$persona->rut.'</td>
           <td style="font-size: 0.7em;">'.$monto_texto.'</td>
           <td style="font-size: 0.7em;">'.$nombre_actividad.'</td>
        </tr>
        </table> 
        
        <p>2.- El Contrato de Prestaci&oacute;n de Servicios a Honorarios Adjunto para todos los efectos legales pasa a formar
        parte integrante del presente Decreto<br />';

        $texto_decreto_honorario.=$tercer_decreto."<br/>";


        $texto_decreto_honorario.='4.- Des&iacute;gnese, como Responsable de la Supervisi&oacute;n, Control y Ejecuci&oacute;n del presente Contrato a Don(a) '.$responsable->nombre_completo.', Grado '.$responsable->grado.'</p>
        
        <blockquote>ANOTESE'.$REGISTRO.', COMUNIQUESE Y ARCHIVESE<blockquote>' . $porOrden . '</blockquote></blockquote>
        <p></p>
        <table>
        <tr><td></td></tr>
        </table>
        
        <table>
        
        </table>
        
        <table style="font-size: 0.8em;">
        <tr>
            <td style="text-align: center;">
                <strong>' . $nombreF1 . '</strong><br />
                <span>'.$cargoF1.'</span>
                </td>
            <td style="text-align: center;">
                <strong>' . $nombreF2 . '</strong><br />
               <span>' . $cargoF2 . '</span>
            </td>
        </tr>
        <tr><td></td><td></td></tr>
        <tr><td></td><td></td></tr>
        <tr>
            <td style="border: solid 1px black;text-align: center">
                <strong style="text-align: left;font-size: 0.7em;">Observaciones R.R.H.H.</strong><br />
                <p></p>
                <p></p>
           
            </td>
            <td style="border: solid 1px black;text-align: center">
                <strong style="text-align: left;font-size: 0.7em;">Observaciones Dir. Control</strong><br />
                <p></p>
                <p></p>
            </td>
        </tr>
        <tr style="">
            <td style="text-align: center;border: solid 1px black;">
                <p></p><br />
                <strong>' . trim($nombre_rrhh) . '</strong><br />
                <span>'.$cargo_rrhh.'</span>
                </td>
            <td style="text-align: center;border: solid 1px black;">
                <p></p><br />
                <strong>' . $nombre_control . '</strong><br />
               <span>' . $cargoControl . '</span>
            </td>
        </tr>
        <tr><td></td><td></td></tr>
        <tr>
            <td style="text-align: left;font-size:0.8em;"><br /><strong>'. $encargados .'</strong></td>
            <td></td>
        </tr>
        <tr>
            <td style="text-align: left;font-size:8pt;">
                <strong><u>DISTRIBUICION</u></strong><br />
                -Archivo Municipal<br/>
                -Archivo Personal<br/>
                -Interesado<br/>
                -Folio: <strong>'.$folio.'</strong><br/>
            </td>
            <td></td>
        </tr>
        </table>';

        //------------------------------------------------------------------------------------------------------------------------
list($anio,$mes,$dia) = explode("-",$fecha_inicio);

$declaracion_jurada = '
        <style type="text/css">
            p{
                text-align:justify;
                font-size:0.7em;
                margin-top: 0px;
            }
            BLOCKQUOTE{
                font-size:0.7em;
            }
            table{
                font-size:0.7em;
            }
            span{
                font-size:0.7em;
                text-align: right;
                }
            li{
            font-size:0.7em;
            }
        </style>
        
        <h5 style="text-align: center;">DECLARACION JURADA SIMPLE</h5>
                <p></p>
        <span style="text-align: justify;">En Carahue, a '.$dia.' de '.$meses[(int)$mes-1].' de '.$anio.', '.$nombre_funcionario.', de nacionalidad Chilena, cédula de identidad número 
        '.$rut.' domiciliado en '.$domicilio.', declara y certifica mediante este documento, no estar afecto
        a ninguna de las inhabilidades establecidas en los articulos 54, 55 y 56 de la Ley Nº 18.575 y al 
        el artículo 5 incisos 3º y 4º de la Ley Nº 18.896.</span>
        <p></p>
        <p></p>
        <p></p>
        <p></p>
        <p></p>
        <h6 style="text-align: center;">'.$nombre_funcionario.'<br />'.$rut.'</h6>
        ';


//IMPRESIONES DE DOCUMENTO
$contrato -> crearCabeceraPagina();
$contrato -> addPagina_Vertical($texto_contrato_honorario);
$contrato -> addPagina_Vertical($texto_decreto_honorario);
$contrato -> addPagina_Vertical($declaracion_jurada);

$nombre_decreto = "Decreto_Contrato_".$nombres."_".$paterno."_".$anio."_$folio";
$nombre_contrato = "Contrato_Honorario_".$nombres."_".$paterno."_".$anio."_$folio";
$contrato -> respaldaDocumento($texto_decreto_honorario,$nombre_decreto);
$contrato -> respaldaDocumento($texto_contrato_honorario,$nombre_contrato);

$contrato -> imprimeDocumento();






//
//$sql = "insert into pdf_respaldo(nombre_doc,head1,head2,html,id_empleado,folio)
//                values('CONTRATO HONORARIO FORMATO','CONTRATO HONORARIO FORMATO','".$documento->titulo_inf."','".$html_1."','".$documento->id_empleado."','".$folio."')";
//
//mysql_query($sql);
//
//$sql = "insert into pdf_respaldo(nombre_doc,head1,head2,html,id_empleado,folio)
//                values('".$documento->nombre_documento."','".$documento->titulo_sup."','".$documento->titulo_inf."','".$html_2."','".$documento->id_empleado."','".$folio."')";
//
//mysql_query($sql);


//============================================================+
// END OF FILE
//============================================================+
