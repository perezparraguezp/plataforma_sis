<?php
include "../../php/config.php";
include "../../php/objetos/documento.php";
include "../../php/objetos/persona.php";
include "../../php/objetos/contrato_honorario.php";
include "../../php/objetos/functionario.php";

session_start();
error_reporting(0);



//VARIABLES DE ENTRADA
$rut = str_replace(".","",$_POST['RUT']);
$anio = $_POST['anio_contrato'];
$id_contrato = $_POST['dynradio'];

$domicilio = $_POST['domicilio'];
$comuna = $_POST['comuna'];
$profesion = $_POST['profesion'];
$servicio = $_POST['servicio'];

$cip = $_POST['cip'];
$cuenta_cip = $_POST['cuenta_cip'];
$monto_cip = $_POST['monto_cip'];
$programa_cip = $_POST['programa_cip'];
$cuotas_totales = $_POST['cuotas_totales'];

$fecha_anexo = $_POST['fecha_anexo'];
$fecha_termino_actualizada = $_POST['fecha_termino_actualizada'];
$funciones = $_POST['funciones'];
$anexo = $_POST['anexo'];

$distribucion = $_POST['distribucion'];
$responsable = $_POST['responsable'];
$firma_mandante = $_POST['firma_mandante'];
$firma_secretario = $_POST['firma_secretario'];

$fecha_decreto_contrato = $_POST['fecha_decreto_contrato'];
$numero_decreto_contrato = $_POST['numero_decreto_contrato'];
//FIN VARIABLES DE ENTRADA




//REEMPLAZOS DE CARACTERES ESPECIALES
$monto_cip = str_replace(" ","",$monto_cip);
$monto_cip = str_replace("$","",$monto_cip);
$monto_cip = str_replace(".","",$monto_cip);
//FIN REEMPLAZO DE CARACTERES


//VARIABLES INTERNAS DEL DOCUMENTO
$meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre", "Octubre","Noviembre","Diciembre");

list($anio_anexo,$mes_anexo,$dia_anexo) = explode("-",$fecha_anexo);
$fecha_anexo_normal = $dia_anexo." de ".($meses[$mes_anexo-1]) ." del ". $anio_anexo;

list($anio_termino,$mes_termino,$dia_termino) = explode("-",$fecha_termino_actualizada);
$fecha_termino_actualizada_normal = $dia_termino." de ".($meses[$mes_termino-1]) ." del ". $anio_termino;

list($anio_decreto_contrato,$mes_decreto_contrato,$dia_decreto_contrato) = explode("-",$fecha_decreto_contrato);
$fecha_decreto_contrato_normal = $dia_decreto_contrato." de ".($meses[$mes_decreto_contrato-1]) ." del ". $anio_decreto_contrato;

$fecha_decreto_contrato = fechaNormal($fecha_decreto_contrato);
$fecha_termino_sin_invertir = $fecha_termino_actualizada;
$fecha_termino_actualizada = fechaNormal($fecha_termino_actualizada);
$fecha_anexo = fechaNormal($fecha_anexo);

$afectado = new persona($rut);
$nombre = $afectado->nombres." ".$afectado->paterno." ".$afectado->materno;


$titulo_sup = "I. Municipalidad de Carahue                                           ";
$titulo_inf = "Departamento de Recursos Humanos\nPortales #295, Carahue";
$nombre_documento = "Anexo_De_contrato_".$nombre."_$anio";
$documento = new documento($titulo_sup,'OFICINA DE PERSONAL',$nombre_documento);
$documento->crearFolio();
$documento-> updateDatosDocumento($rut, $nombre,"ANEXO DE CONTRATO REFERENTE A ".$nombre." RUT: ".$rut. " DE FECHA " .$fecha_anexo_normal);
$documento->updateTipoDocumento("Personal con Registro","");
//FIN VARIABLES INTERNAS DEL DOCUMENTO

$contrato =  new contrato_honorario($_SESSION['id_empleado']);
$secretario = mysql_fetch_array(mysql_query("select nombre_directivo, cargo_directivo from directivo where id_empleado='$firma_secretario'"));
$mandante = mysql_fetch_array(mysql_query("select nombre_directivo, cargo_directivo from directivo where id_empleado='$firma_mandante'"));



$contrato->updateInfoContrato($id_contrato);
if ($cip!=""){
    $contrato->updateCertificadoImputacion($cip);
    $contrato->updateCuentaCip($cuenta_cip);
    $contrato->updateMontoContrato($monto_cip);
    $contrato->updateCuotas($cuotas_totales);
    $contrato->updateCentroCosto($cuotas_totales);
}
if($fecha_termino_sin_invertir!=""){
    $contrato->updateFechasContrato($contrato->fecha_inicio,$fecha_termino_sin_invertir,$contrato->fecha_contrato);
}

$contrato->updateResponsableContrato($responsable);

//MODIFICA FUNCIONES Y ACTUALIZA EL CAMPO MODIFICACIONES
$ban = true;
$sql3 = "select funciones, modificaciones from contrato_honorario where id_contrato='$id_contrato' and rut='$rut'
        and anio='$anio'";
$row3 = mysql_fetch_array(mysql_query($sql3));
$modificaciones = $row3['modificaciones'];
if ($modificaciones != ""){
    $modificaciones = explode(",", $modificaciones);
    for ($i = 0; $i < sizeof($modificaciones); $i++) {
        if ($modificaciones[$i]== $documento->folio){
            $ban = false;
        }
    }
}
if ($ban) {
    $funciones = $row3['funciones'] . $funciones;
    $modificaciones = $row3['modificaciones'] . "," . $documento->folio;
    $sql1 = "update contrato_honorario set
                funciones = '$funciones',
                modificaciones = '$modificaciones'
                where id_contrato='$id_contrato' and rut ='$rut' and anio = '$anio'";
    mysql_query($sql1);
}

$cip2 = $contrato->certificadoImputacion;
$lineacip= $contrato->cuenta_cip;





$secretario_s = "";
$mandante_s = "";

if ($_POST['secretario_s']) {
    $secretario_s = " (s)";
}
if ($_POST['mandante_s']) {
   $mandante_s = " (s)";
}

$datos_centro_costo = mysql_fetch_array(mysql_query("select cic.cuenta_general,nombre_centro, id_centro_costo from compras_certificado_imputacion cci
                                         inner join certificado_imputacion_cuentas cic using (id_certificado)
                                         inner join pc_cuenta pc on cic.cuenta_general = pc.codigo_general
                                         inner join pc_centro_costo pcc using (id_centro_costo)
                                            where cci.numero_certificado = '$cip2' and cci.anio='$anio' and id_dato='$lineacip'"));

$anexo_contrato = '
    <style type="text/css">
        p{
            text-align:justify;
            text-indent: 100px;
            font-size:11pt;
            margin-top: 0px;
        }
        BLOCKQUOTE{
            font-size:10pt;
        }
        table{
            font-size:8pt;
        }
        span{
            font-size:12pt;
            text-align: right;
            }
        li{
        font-size:10pt;
        }
    </style>
    
    <h5 style="text-align: center;">MODIFICACION DE CONTRATO DE PRESTACION DE SERVICIOS POR HONORARIOS</h5>
        
        <p>En CARAHUE, a '.$fecha_anexo_normal.', entre la Municipalidad de CARAHUE, RUT 69.190.500-4, representada por su Alcalde Don
        HÉCTOR ALEJANDRO SAEZ VELIZ, Chileno, Casado, cédula de identidad número 8.294.238-6, ambos domiciliados en calle
        Portales número doscientos noventa y cinco, de la ciudad y comuna de CARAHUE, por su parte, en adelante ‘El Mandante’.
        Por otro lado, Don '.$nombre.', Chileno, Profesión u oficio '.$profesion.', cédula nacional de identidad número '.$rut.' con 
        domicilio en '.$domicilio.' de la comuna de '.$comuna.', se acuerda la siguiente modificación de contrato de prestación
        de servicios profesionales a honorarios.</p>
        
        <p><strong>PRIMERO</strong></p>
        
        <p>De la contratación, Por el presente instrumento, "LA MUNICIPALIDAD", representada en la forma relacionada en el
         encabezado, y conforme a lo autorizado por la ley 18.695, procede a modificar la prestacion de servicios a honorarios de Don
          '.$nombre.' Cédula Nacional de Identidad Nro. '.$rut.' para que preste sus servicios como: '.$servicio.'-</p>
        
        <p><strong>SEGUNDO:</strong></p>  
        <p>'.$anexo.'</p>
        
        <p><strong>TERCERO:</strong></p>
        <p>El presente Anexo pasa a formar parte integrante del contrato de prestación de servicios a honorarios de y Don '.$nombre.', aprobado mediante decreto Alcaldicio Nro. '.$numero_decreto_contrato.' del '.$fecha_decreto_contrato_normal.'.-</p>
        
        <p><strong>CUARTO:</strong></p>
        <p>El prestador, mediante declaración jurada simple, señala no estar afecto a ninguna de las inhabilidades establecidas en
        los artículos 54 y 55 de la ley 18.575, cuya declaración forma parte del presente contrato.-</p>
        
        <p>El profesional o prestador estará sujeto además, a lo establecido en el artículo 56 de la ley 18.575, que pasa a
        formar parte integrante del presente Contrato.-</p>
        
        <p>De conformidad con lo establecido en el artículo 5° incisos 3° y 4° de la ley 19.896, a través de Declaración Jurada
        Simple y que forma parte integrante del Contrato, el profesional o prestador declara que no se encuentra afecto a
        conflicto de intereses actual o eventual con la función que se obliga a desarrollar en virtud de este contrato, lo cual
        ha sido constatado y certificado en este acto por el Alcalde que suscribe.-</p>
        
        <p><strong>QUINTO:</strong></p>
        <p>De la competencia. Para todos los efectos legales que se deriven del presente contrato las partes fijan domicilio
        especial en la Ciudad de Carahue y atendida la naturaleza de este contrato, será competente para conocer y fallará
        las contiendas que se originen con ocasión de su incumplimiento o cualquiera otro que de el pudieren derivarse., los
        tribunales ordinarios de justicia de competencia civil de la ciudad de Carahue.-</p>
        
        <p><strong>SEXTO:</strong></p>
        <p>Personería. La personería de Don Héctor Alejandro Saez Veliz, para actuar en representación de la ILUSTRE MUNICIPALIDAD
        DE CARAHUE, consta con Decreto Alcaldicio N° 5.259 de fecha 06 de diciembre del año 2016.-</p>
        
        <p><strong>SÉPTIMO:</strong></p>
        <p>Firma del contrato y copias. El presente instrumento, consta de 4 páginas, en comprobante y señal de aceptación las
         partes firman se extiende en cinco ejemplares, de igual validez y fecha, quedando cuatro en poder de "LA MUNICIPALIDAD"
          y un último en poder del/la contratado/a.-</p>
        <p></p>
        <p></p>
        <h6><strong>Previa lectura, ratifican y firman.</strong></h6>
        <p></p>
        <p></p>
        <p></p>
        <p></p>
        <p></p>
        <table>
            <tr>
                <td><h3 style="text-align: center"><strong>'.strtoupper($nombre).'<br>PRESTADOR</strong></h3></td>
                <td><h3 style="text-align: center"><strong>'.strtoupper($mandante['nombre_directivo']).'<br>MANDANTE'.$mandante_s.'</strong></h3></td>
            </tr>
        </table>
        <p></p>
        <p></p>
        <h5>Folio: '.$documento->folio.'</h5>
';


$decreto_anexo = '
<style type="text/css">
        p{
            text-align:justify;
            text-indent: 280px;
            font-size:11pt;
            margin-top: 0px;
        }
        BLOCKQUOTE{
            font-size:10pt;
        }
        table{
            font-size:8pt;
        }
        span{
            font-size:12pt;
            text-align: right;
            }
        li{
        font-size:10pt;
        }
        .encab{
            text-indent: 280px;
        }
        </style>

        <h5 class="encab">DECRETO:</h5>
        <h5 class="encab">FECHA:</h5>
        <h5 class="encab">VISTOS:</h5>

        <p>1.- El Decreto Alcaldicio Nº 3767 de fecha 27 de Diciembre de 2019, que aprueba el Presupuesto de Ingreso y
        Gastos para el año 2020.-</p>
        <p>2.- El Decreto Alcaldicio Nº 3768 del 27 De Diciembre de 2019, que Aprueba las Actividades y Programas para el
        año 2020.-</p>
        <p>3.- El Decreto N° '.$numero_decreto_contrato.' del '.$fecha_decreto_contrato_normal.', que aprueba el contrato de prestación de servicios a honorarios
        entre la Municipalidad de Carahue y Don '.$nombre.'.-</p>
        <p>4.- El anexo de contrato de prestación de servicios a Honorarios suscritos entre la Municipalidad de Carahue y el
        prestador de servicios '.$nombre.' de fecha '.$fecha_anexo_normal.'.-</p>
        <p>5.- Los artículos 54, 55 y 56 de la ley 18.575.-</p>
        <p>6.- Las facultades que me confiere el texto refundido de la ley 18.695, "Orgánica Constitucional de Municipalidades".-</p>
        <br/>
        <h5 style="text-align: center;">DECRETO:</h5>
        <p>1.- Apruébase el anexo de contrato de prestación de servicios a Honorarios suscritos entre la Municipalidad de Carahue
        y el prestador de servicios don '.$nombre.' RUT '.$rut.' de fecha '.$fecha_anexo_normal.', para cumplir funciones en el programa
        '.$datos_centro_costo['nombre_centro'].'.-</p>
        <p>2.- El anexo de Contratos de Prestación de Servicios a Honorarios Adjuntos para todos los efectos legales pasan a
        formar parte integrante del presente Decreto.-</p>
        <p>3.- Imputese el gasto del presente decreto al ITEM '.$datos_centro_costo['cuenta_general'].' de los siguientes programas: '.$datos_centro_costo['id_centro_costo'].' 
        denominado '.$datos_centro_costo['nombre_centro'].'.-</p>
        <p></p>
        <blockquote>ANOTESE, COMUNIQUESE Y ARCHIVESE</blockquote>
        <p></p>
        <p></p>
        <table>
            <tr>
                <td style="text-align: center; font-size:11pt;"><strong>'.strtoupper($secretario['nombre_directivo']).'<br/>SECRETARIO MUNICIPAL'.$secretario_s.'</strong></td>
                <td style="text-align: center; font-size:11pt;"><strong>'.strtoupper($mandante['nombre_directivo']).'<br/>'."ALCALDE".$mandante_s.'</strong></td>
            </tr>
            <tr></tr>
            <tr>
                <td><strong>'.$distribucion.'</strong><p>DISTRIBUCION:</p><ul>
                <li>Archivo Municipal</li><li>Oficina de Informaciones</li><li>Depto. Finanzas</li>
                <li>Direccion de Desarrollo Com.</li></ul></td>
                <td></td>
            </tr>

        </table>
        <p></p>
        <p></p>
        <strong><p style="text-indent:0px;">Folio: '.$documento->folio.'</p></strong>';







$documento -> crearCabeceraPagina();
$documento -> addPagina_Vertical($anexo_contrato);
$documento -> addPagina_Vertical($decreto_anexo);
$documento -> imprimeDocumento();
$documento -> respaldaDocumento($anexo_contrato,"ANEXO_CONTRATO_".$nombre);
$documento -> respaldaDocumento($decreto_anexo,"DECRETO_ANEXO_",$nombre);





















//
//
//$rut = str_replace(".","",$_POST['rut']);
//$anio = $_POST['anio'];
//$id_contrato = $_POST['dynradio'];
//$monto = $_POST['monto'];
//$programa = $_POST['programa'];
//$fechai = $_POST['fechai'];
//$fechat = $_POST['fechat'];
//list($anioc,$mesc,$diac) = explode("-",$_POST['fechac']);
//$fechac =  date("d/m/Y", strtotime($_POST['fechac']));
//
//$folio = $_POST['folio'];
//
//
//$documento =  new documento();
//
//$documento->cargarFolio($folio);
//$documento->updateTipoDocumento('Personal con Registro','Modificacion de Contrato');
//
//
//$modifcontra = $_POST['modifcontra'];
//$numerdecre = $_POST['ref'];
//$profesion = strtoupper($_POST['ref3']);
//$fechadecre = date("d/m/Y", strtotime($_POST['ref2']));
//$ref = $_POST['ref'];
//$comuna = strtoupper($_POST['comuna']);
//$domicilio = strtoupper($_POST['domicilio']);
//$rut = str_replace(".","",$rut);
//$codprog = strtoupper($_POST['codprog']);
//$itemprog1 = explode("|", $_POST['itemprog']);
//$itemprog = strtoupper($itemprog1[0]);
//$imprimir = $_POST['imprimir'];
//$service = strtoupper($_POST['service']);
//$firma = $_POST['firma'];
//$firma2 = $_POST['firma2'];
//$distribucion = $_POST['vistos'];
//
//
//
//
//
////CORREGIR FORMATO PARA INGRESAR///
//$funciones = $_POST['funciones'];
//
//$montoe = $_POST['montoe'];
//$montoe = str_replace(" ","",$montoe);
//$montoe = str_replace("$","",$montoe);
//$montoe = str_replace(".","",$montoe);
//
//$fechate = $_POST['fechate'];
////ADEMAS INGRESAR
//$fechaci = $_POST['fechac'];
//
//$meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre", "Octubre","Noviembre","Diciembre");
//$modifcontra = str_replace("\n","<br>",$modifcontra);
//$obj = new persona($rut);
//$name= $obj->nombre_completo;
//
//
//if($profesion==""){
//    $profesion = "_______________";
//}
//if($direccion==""){
//    $direccion = "___________________";
//}
//
//if($_POST['registro']){
//    $REGISTRO = ",".$_POST['registro'];
//}else{
//    $REGISTRO = '';
//}
//$porOrden = "";
//
////actualizar valores del documento segun folio creado.
//$texto_afectado = 'Se modifica Contrato de '.$name." con fecha ".$fechac;
//$documento->updateDatosDocumento($rut,$name,$texto_afectado);
//
//switch ($imprimir){
//    case 1:
//        $html = '<style type="text/css">
//        p{
//            text-align:justify;
//            text-indent: 0px;
//            font-size:11pt;
//            margin-top: 0px;
//        }
//        BLOCKQUOTE{
//            font-size:10pt;
//        }
//        table{
//            font-size:8pt;
//        }
//        span{
//            font-size:12pt;
//            text-align: right;
//            }
//        li{
//        font-size:10pt;
//        }
//        </style>
//        
//        
//        $obj1=new documento("Municipalidad de CARAHUE","ALCALDIA \nPortales #295, Carahue ","ANEXO ".$name);
//
//        $obj1->AsignarFolio($folio);
//        $obj1->CrearPDF(trim($html));
//        break;
//
//    case 2:
//        $query = "SELECT nombre_programa from programas_municipales where id_programa='$codprog'";
//        $fila = mysql_fetch_array(mysql_query($query));
//        $nameprog = $fila['nombre_programa'];
//
//        $query2 = "SELECT nombre_firma, cargo from firmantes where id_empleado='$firma'";
//        $fila2 = mysql_fetch_array(mysql_query($query2));
//        $namefirmante = $fila2['nombre_firma'];
//        $cargofirmante = $fila2['cargo'];
//
//        $query3 = "SELECT nombre_firma, cargo from firmantes where id_empleado='$firma2'";
//        $fila3 = mysql_fetch_array(mysql_query($query3));
//        $namefirmante2 = $fila3['nombre_firma'];
//        $cargofirmante2 = $fila3['cargo'];
//
//
//        $html = '<style type="text/css">
//        p{
//            text-align:justify;
//            text-indent: 280px;
//            font-size:11pt;
//            margin-top: 0px;
//        }
//        BLOCKQUOTE{
//            font-size:10pt;
//        }
//        table{
//            font-size:8pt;
//        }
//        span{
//            font-size:12pt;
//            text-align: right;
//            }
//        li{
//        font-size:10pt;
//        }
//        .encab{
//            text-indent: 280px;
//        }
//        </style>
//
//        <h5 class="encab">DECRETO:</h5>
//        <h5 class="encab">FECHA:</h5>
//        <h5 class="encab">VISTOS:</h5>
//
//        <span style="text-align: justify;">'.($cabecera).'</span>
//
//        <p>1.- El Decreto Alcaldicio Número 7044 de fecha 27 de Diciembre de 2018, que aprueba el Presupuesto de Ingreso y
//        Gastos para el año 2019 y el Decreto 7045 del 27 De Diciembre de 2018, que Aprueba las Actividades y Programas para el
//        año 2019.-<br>2.- El Programa o Actividad Código: '.$codprog.' denominada '.$nameprog.'.-<br>3.- El Decreto N° '.$numerdecre.' del '.$fechadecre.', que aprueba el contrato de prestación de servicios a honorarios
//        entre la Municipalidad de Carahue y Don '.$name.'.-<br>4.- El anexo de contrato de prestación de servicios a Honorarios suscritos entre la Municipalidad de Carahue y el
//        prestador de servicios '.$name.' de fecha '.$fechac.'.-<br>5.- Los artículos 54, 55 y 56 de la ley 18.575.-<br>6.- Las facultades que me confiere el texto refundido de la ley 18.695,"Orgánica Constitucional de Municipalidades".-</p>
//        <br/>
//        <h5 style="text-align: center;">DECRETO:</h5>
//        <p>1.- Apruébase el anexo de contrato de prestación de servicios a Honorarios suscritos entre la Municipalidad de Carahue
//        y el prestador de servicios don '.$name.' RUT '.$rut.' de fecha '.$fechac.', para cumplir funciones en el programa
//        '.$nameprog.'.-<br/>2.- El anexo de Contratos de Prestación de Servicios a Honorarios Adjuntos para todos los efectos legales pasan a
//        formar parte integrante del presente Decreto.-<br/>3.- Imputese el gasto del presente decreto al ITEM '.$itemprog.'de los siguientes programas: '.$codprog.' denominado '.$nameprog.'.-</p>
//        <p></p>
//        <blockquote>ANOTESE'.$REGISTRO.', COMUNIQUESE Y ARCHIVESE<blockquote>' . $porOrden . '</blockquote></blockquote>
//        <p></p>
//        <p></p>
//        <table>
//            <tr>
//                <td style="text-align: center; font-size:11pt;"><strong>'.$namefirmante2.'<br/>Secretario Municipal</strong></td>
//                <td style="text-align: center; font-size:11pt;"><strong>'.$namefirmante.'<br/>'.$cargofirmante.'</strong></td>
//            </tr><p></p>
//            <tr>
//                <td><strong>'.$distribucion.'</strong><p>DISTRIBUCION:</p><ul>
//                <li>Archivo Municipal</li><li>Oficina de Informaciones</li><li>Depto. Finanzas</li>
//                <li>Direccion de Desarrollo Com.</li></ul></td>
//                <td></td>
//            </tr>
//
//        </table>
//        <p></p>
//        <strong><p style="text-indent:0px;">Folio: '.$folio.'</p></strong>';
//
//        $obj1=new documento("Municipalidad de CARAHUE","FINANZAS \nPortales #295, Carahue","DECRETO ANEXO ".$name);
//        $obj1->AsignarFolio($folio);
//        $obj1->CrearPDF(trim($html));
//        break;
//}
//
//$ban = true;
//$sql3 = "select funciones, modificaciones from contrato_honorario where id_contrato='$id_contrato' and rut='$rut'
//        and anio='$anio'";
//$row3 = mysql_fetch_array(mysql_query($sql3));
//$modificaciones = $row3['modificaciones'];
//if ($modificaciones != ""){
//    $modificaciones = explode(",", $modificaciones);
//    for ($i = 0; $i < sizeof($modificaciones); $i++) {
//        if ($modificaciones[$i]== $folio){
//            $ban = false;
//        }
//    }
//}
//if ($ban){
//    $funciones = $row3['funciones'].$funciones;
//    $modificaciones = $row3['modificaciones'].",".$folio;
//    $sql1 = "update contrato_honorario set
//                funciones = '$funciones', monto_total = monto_total + '$montoe', fecha_termino='$fechate',
//                modificaciones = '$modificaciones'
//                where id_contrato='$id_contrato' and rut ='$rut' and anio = '$anio'";
//    mysql_query($sql1);
//}
//
//
//
//
//
//
//
//
//
//
//
//







//**********************************************************************************************************************
//**********************************************************************************************************************
//TODA LA CHACHARA DE ABAJO HAY QUE ANALIZARLA PARA VER QUE SIRVE Y QUE NO




//$sql = "select * from directiva
//        where id_org='$id_org' and hasta='$hasta' limit 1";
//echo $sql;
//$row = mysql_fetch_array(mysql_query($sql));
//
//
//
//
////presidente
//if($presidente=='ELIMINAR'){
//    //SE DEBE CAMBIAR AL PRESIDENTE DE LA ORGANIZACION
//
//    //BUSCAMOS AL SUPLENTE QUE LO CAMBIARA
//    if($otro1=='P'){
//        $new_presidente = $row['otro1'];
//        $new_otro1 = '';
//    }else{
//        if($otro2=='P'){
//            $new_presidente = $row['otro2'];
//            $new_otro2 = '';
//        }if($otro3=='P'){
//            $new_presidente = $row['otro3'];
//            $new_otro3 = '';
//        }
//    }
//}else{
//    $new_presidente = $row['presidente'];
//}
////secretario
//if($secretario=='ELIMINAR'){
//    //SE DEBE CAMBIAR AL PRESIDENTE DE LA ORGANIZACION
//
//    //BUSCAMOS AL SUPLENTE QUE LO CAMBIARA
//    if($otro1=='S'){
//        $new_secretario = $row['otro1'];
//        $new_otro1 = '';
//    }else{
//        if($otro2=='S'){
//            $new_secretario = $row['otro2'];
//            $new_otro2 = '';
//        }if($otro3=='S'){
//            $new_secretario = $row['otro3'];
//            $new_otro3 = '';
//        }
//    }
//}else{
//    $new_secretario = $row['secretario'];
//}
////tesorero
//if($tesorero=='ELIMINAR'){
//    //SE DEBE CAMBIAR AL PRESIDENTE DE LA ORGANIZACION
//
//    //BUSCAMOS AL SUPLENTE QUE LO CAMBIARA
//    if($otro1=='S'){
//        $new_tesorero = $row['otro1'];
//        $new_otro1 = '';
//    }else{
//        if($otro2=='S'){
//            $new_tesorero = $row['otro2'];
//            $new_otro2 = '';
//        }if($otro3=='S'){
//            $new_tesorero = $row['otro3'];
//            $new_otro1 = '';
//        }
//    }
//}else{
//    $new_tesorero = $row['tesorero'];
//}
//
//
//if($otro1 ==''){
//    $new_otro1 = $row['otro1'];
//}
//if($otro2==''){
//    $new_otro2 = $row['otro2'];
//}
//if($otro3==''){
//    $new_otro3 = $row['otro3'];
//}
//
//
//
//$sql1 = "update directiva set
//                presidente='$new_presidente',secretario='$new_secretario',tesorero='$new_tesorero',
//                otro1='$new_otro1',otro2='$new_otro2',otro3='$new_otro3'
//                where id_org='$id_org' and hasta='$hasta'";
//mysql_query($sql1);

?>