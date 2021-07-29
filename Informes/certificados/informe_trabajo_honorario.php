<?php

include("../../php/config.php");
include("../../php/objetos/persona.php");
include("../../php/objetos/functionario.php");
include("../../php/objetos/documento.php");
include("../../php/objetos/contrato_honorario.php");

session_start();
//error_reporting(0);

$id_contrato = $_POST['id_contrato'];
$contrato = new contrato_honorario($id_contrato);

$mes = $_POST['mes'];
$anio = date('Y');

$de = $_POST['de'];
$para = $_POST['para'];

$nro_boleta = $_POST['nro_boleta'];
$monto_boleta = $_POST['MONTO'];
$bruto = str_replace("$ ","",str_replace(".","",$_POST['MONTO']));
$impuesto =str_replace("$ ","",str_replace(".","",$_POST['impuesto']));
$liquido = str_replace("$ ","",str_replace(".","",$_POST['liquido']));

$fecha_certificado = $_POST['fecha_informe'];

$sql0 = "select * from contrato_honorario where id_contrato='$id_contrato' limit 1";
$row0 = mysql_fetch_array(mysql_query($sql0));

$responsable = new functionario($row0['id_responsable']);


$sql1 = "select * from firmantes where id_empleado='$de' limit 1";
$row1 = mysql_fetch_array(mysql_query($sql1));

$f_de = new functionario($de);
$depto_de = $f_de->nombre_depto;

$de_nombre = limpiaCadena($responsable->nombre_completo);
$cargo_de = $responsable->nombre_escalafon;


$sql1 = "select * from firmantes where id_empleado='$para' limit 1";
$row1 = mysql_fetch_array(mysql_query($sql1));

$para_nombre = limpiaCadena($row1['nombre_firma']);
$cargo_para = $row1['cargo'];

//CREAMOS EL OBJETO DOCUMENTO
$documento = new documento('Informe de Prestación de Servicios',$depto_de."\nMunicipalidad de Carahue",'Informe');

$documento->crearCabeceraPagina();
$documento->updateTipoDocumento("Informe de Trabajo",'CONTRATO HONORARIO');

$documento->crearFolio();


$sql1 = "select * from contrato_honorario  
                  where contrato_honorario.id_contrato='$id_contrato' limit 1";
$row1 = mysql_fetch_array(mysql_query($sql1));
if($row1['numero_decreto']!=0){
    //existe un contrato de honorario
    $p = new persona($row1['rut']);

    $nombre_programa = $contrato->getNombreProgramaCentro();
    $persona = $p->nombre_completo;
    $programa = $nombre_programa;

    $numero_decreto = $row1['numero_decreto'];
    $cip = $row1['certificado_imputacion'];

    $documento->updateDatosDocumento($p->rut,$p->nombre_completo,'INFORME DE TRABAJO CORRESPONDIENTE AL MES '.$mes." del año ".$anio.", por un monto de ".$monto_boleta);
    $documento->NumerarDocumento($anio);
    $numero_informe = $documento->numero_decreto;

    mysql_query("update contador_documentos set numero_informe_honorario=(numero_informe_honorario + 1) ");
    $sql = "select * from contador_documentos limit 1";
    $row = mysql_fetch_array(mysql_query($sql));
    $numero = $row['numero_informe_honorario'];

    $funciones = '';

    $html = '
            <style type="text/css">
                p{
                    text-align:left;
                    font-size:12pt;
                    margin-top: 0px;
                }
                h5{
                    font-size:0.8em;
                    text-align: center;;
                }
                strong{
                    font-size:12pt;
                }
            </style>
            <p></p>
            <p style="text-indent: 400px;">Informe N: <strong>'.$numero_informe.'</strong>   </p>
            <p style="text-indent: 400px;">Carahue, '.fechaNormal($fecha_certificado).'</p><br />
            
            <strong>DE:</strong><p style="text-indent: 100px;">'.$de_nombre.'
                                <br />'.$cargo_de.'</p>
            <strong>PARA:</strong><p style="text-indent: 100px;">'.$para_nombre.'
                                <br />'.$cargo_para.'</p>
            
            
            <hr />
            
            <p>1.- De acuerdo a el decreto Nº '.$row1['numero_decreto'].', que aprueba el contrato a Honorarios suscrito entre
            la Municipalidad de Carahue y el Señor(a): <strong>'.$persona.'</strong>, para cumplir sus funciones 
             designadas en el contrato en el programa '.$programa.'</p>
            
            <p>2.- Quien suscribe, informa que el Señor(a) <strong>'.$persona.'</strong> cumplio cabalmente con todas y cada una de las cláusulas indicadas en su contrato de Prestación de Servicios, el cual se encuentra aprobado con Decreto Numero <strong>'.$numero_decreto.'</strong> del año '.$anio.'</p>
            
            <p>3.- El Señor(a) <strong>'.$persona.'</strong> remitió al Municipio la boleta a Honorarios Numero '.$nro_boleta.', por un monto de
            '.$monto_boleta.', la que se hace llegar a usted mediante este documento, para la realización del pago correspondiente.</p>
            
           
            <p></p>
            <p></p>
            <table>
                <tr>
                    <td>
                        <h5>'.strtoupper($de_nombre).'<br />'.strtoupper($cargo_de).'</h5>
                    </td>
                    <td>
                    <h5>'.$persona.'<br />PRESTADOR DE SERVICIO</h5>
                     </td>
                </tr>
            </table>
            
            
            ';
    $myId = $_SESSION['id_empleado'];
    /*
     * */

    $sql2 = "insert into honorarios(id_contratoH,rut,mes,anio,nro_boleta,bruto,neto,impuestos,estado,id_digitador,folio_informe)
            values('$id_contrato','$p->rut','$mes','$anio','$nro_boleta','$bruto','$liquido','$impuesto','PENDIENTE','$myId','$documento->folio');";
    mysql_query($sql2);

    $documento->html = $html;
    $documento->addPagina_Vertical($html);
    $documento->outDocuemnto();


    //echo $html;
    //$documento->CrearPDF($html);
}
