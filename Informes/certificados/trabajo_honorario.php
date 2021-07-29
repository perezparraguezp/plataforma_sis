<?php

include("../../php/config.php");
include("../../php/objetos/persona.php");
include("../../php/objetos/functionario.php");
include("../../php/objetos/documento.php");

session_start();
//error_reporting(0);

$listado = $_POST['list_decreto'];

$mes  = $_POST['mes'];
$anio = $_POST['anio'];

$de = $_POST['de'];
$para = $_POST['para'];
$fecha_certificado = $_POST['fecha_certificado'];


$sql1 = "select * from firmantes where id_empleado='$de' limit 1";
$row1 = mysql_fetch_array(mysql_query($sql1));

$f_de = new functionario($de);
$depto_de = $f_de->nombre_depto;

$de_nombre = limpiaCadena($row1['nombre_firma']);
$cargo_de = $row1['cargo'];


$sql1 = "select * from firmantes where id_empleado='$para' limit 1";
$row1 = mysql_fetch_array(mysql_query($sql1));

$para_nombre = limpiaCadena($row1['nombre_firma']);
$cargo_para = $row1['cargo'];

//CREAMOS EL OBJETO DOCUMENTO
$documento = new documento('Informe de Prestación de Servicios',$depto_de."\nMunicipalidad de Carahue",'Informe');

$documento->crearCabeceraPagina();

// Add a page
// This method has several options, check the source code documentation for more information.
$item = explode("#",$listado);
foreach ($item as $i => $folio) {
    if($folio != ''){

        $sql1 = "select * from contrato_honorario 
                  inner join programas_municipales using(id_programa)
                  inner join decretos on id_interno=contrato_honorario.folio  
                  where contrato_honorario.folio='$folio' limit 1";

        $row1 = mysql_fetch_array(mysql_query($sql1));
        if($row1['numero_decreto']!=0){
            //existe un contrato de honorario
            $p = new persona($row1['rut']);
            $persona = $p->nombre_completo;
            $programa = $row1['nombre_programa'];

            $numero_decreto = $row1['numero_decreto'];
            $fecha_decreto = $row1['fecha_decreto'];

            $id_responsable = $row1['id_responsable'];

            $responsable = new functionario($id_responsable);



            mysql_query("update contador_documentos set numero_informe_honorario=(numero_informe_honorario + 1) ");
            $sql = "select * from contador_documentos limit 1";
            $row = mysql_fetch_array(mysql_query($sql));
            $numero = $row['numero_informe_honorario'];

            $funciones = '';

            if($row1['funciones']!='<ul></ul>'){
                if($row1['funciones']!=''){
                    $funciones = '<br />Lista de Funciones realizadas:<br />'.str_replace('<li></li>','',$row1['funciones']);
                }
            }


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
            <p style="text-indent: 400px;">Informe N: <strong>'.$numero.'</strong>   </p>
            <p style="text-indent: 400px;">Carahue, '.fechaNormal($fecha_certificado).'</p><br />
            
            <strong>DE:</strong><p style="text-indent: 100px;">'.$responsable->nombre_completo.'
                                <br />'.$responsable->nombre_escalafon.'</p>
            <strong>PARA:</strong><p style="text-indent: 100px;">'.$para_nombre.'
                                <br />'.$cargo_para.'</p>
            
            
            <hr />
            
            <p>1.- De acuerdo a el decreto Nº '.$row1['numero_decreto'].', que aprueba el contrato a Honorarios suscrito entre
            la Municipalidad de Carahue y el Señor(a): <strong>'.$persona.'</strong>, para cumplir sus funciones 
             designadas en el contrato en el programa '.$programa.'</p>
            
            <p>2.- Quien suscribe, informa que el Señor(a) <strong>'.$persona.'</strong> cumplio con las funciones correspondiente al mes de '.$mes.'
            del año '.$anio.', las cuales se encuentran indicadas en el Contrato Aprobado mediante Decreto Nº '.$numero_decreto.' de Fecha '.fechaNormal($fecha_decreto).'.</p>
            
            <p>3.- El Señor(a) <strong>'.$persona.'</strong> remitió al Municipio la boleta a Honorarios correspondiente al mes
            '.$mes.' del año '.$anio.', la cual es entregada para la cancelación de los servicios prestados correspondientes al Decreto Nº '.$numero_decreto.' de Fecha '.fechaNormal($fecha_decreto).'</p>
          
            
            
            
            <p></p>
            <p></p>
            <table style="font-size: 0.7em;font-weight: bold;text-align: center;width: 100%;">
                <tr>
                    <td>
                        '.strtoupper($responsable->nombre_completo).'<br />'.strtoupper($responsable->nombre_escalafon).'
                    </td>
                    <td>
                    '.$persona.'<br />PRESTADOR DE SERVICIO
                     </td>
                </tr>
            </table>
            
            
            ';
            $documento->addPagina_Vertical($html);
        }
    }
}

$documento->outDocuemnto();