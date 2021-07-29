<?php

include("../../php/config.php");
include("../../php/objetos/functionario.php");
include("../../php/objetos/documento.php");

session_start();
error_reporting(0);
//Eliminamos los textos del documento

$id_mio = $_SESSION['id_empleado'];
$f_mio = new functionario($id_mio);
$departamento = $f_mio->nombre_depto;


$folio = ' - ';


$a = date('Y');
$m = date('m');
$d = date('d');
$dia = diaSemana($a, $m, $d);
$fecha = $dias[$dia] . " " . $d . " de " . $meses[$m - 1] . " del " . $a;

$documento = new documento('Certificado de Contabilidad',"Aprobación de Presupuesto\nMunicipalidad de Carahue",'Certificado');
$documento->updateTipoDocumento("Certificados",'Aprobar Presupuesto Municipal');
$documento->updateDatosDocumento('','','Aprobacion de Presupuesto Municipal');

$documento->crearFolio();
$folio = $documento->folio;
$documento->NumerarDocumento(date('Y'));
$numero_certificado = $documento->numero_decreto;


$id_centro_costo = $_POST['centro_costo'];
$anio = $_POST['anio_presupuesto'];


$sql1 = "select * from pc_area_gestion inner join pc_centro_costo using(id_area_gestion) 
          where id_centro_costo='$id_centro_costo' limit 1";
$row1 = mysql_fetch_array(mysql_query($sql1));
$nombre_area_gestion = $row1['nombre_area'];
$nombre_centro_costo = $row1['nombre_centro'];

$sql1 = "select * from pc_cuentas_centro_costo 
          inner join pc_cuenta on pc_cuentas_centro_costo.codigo_cuenta=codigo_general 
          where id_centro_costo='$id_centro_costo'
          order by pc_cuentas_centro_costo.codigo_cuenta";
$res1 = mysql_query($sql1);
$tabla = '<table border="1" style="width: 100%;">
                <tr style="background-color: #d7efff;font-weight: bold;font-size: 0.7em;">
                    <td style="width: 20%;">CUENTA</td>
                    <td style="width: 60%;">NOMBRE CUENTA</td>
                    <td style="width: 20%;">MONTO</td>
                </tr>';
$fila   = '';
while($row1 = mysql_fetch_array($res1)){

    $id_cuenta      = $row1['id_cuenta_centro_costo']; // id de enlace
    $cuenta         = $row1['codigo_general'];
    $nombre_cuenta  = $row1['nombre_cuenta'];

    $sql2 = "select * from pc_monto_cuenta_presupuesto 
                      where anio='$anio' and id_cuenta_centro_costo='$id_cuenta'
                      limit 1";

    $row2 = mysql_fetch_array(mysql_query($sql2));
    $monto = $row2['monto_asignado'];



    $fila .= '<tr style="font-size: 0.7em;line-height: 2px;">
                    <td>'.$cuenta.'</td>
                    <td>'.$nombre_cuenta.'</td>
                    <td style="text-align: right;">$ '.number_format($monto,0,'','.').'</td>
                </tr>';
}
$tabla .=$fila.'</table>';

$html = '
<style type="text/css">
    p{
        text-align: left;
        font-size:0.9em;;
        margin-top: 0px;
        
    }
    BLOCKQUOTE{
        font-size:10pt;
    }
    table{
        font-size:0.8em;
    }    
    li{
        font-size: 0.7em;;
        text-align: left;
    }
    h2{
    font-size: 1.1em;
    text-align: center;;
    }
</style>
<p style="text-align: right">'.date('d-m-Y').'</p>
<h2>Certificado Nº '.$numero_certificado.'</h2>
<br />
<p style="text-align: justify;">Se emite el presente certificado, para dar cuenta de la aprobación del Presupuesto Municipal del Centro de Costo <strong>'.$nombre_centro_costo.'</strong> Correspondiente al Area de Gestion <strong>'.$nombre_area_gestion.'</strong> para el año '.$anio.'.</p>
<p>Este Presupuesto se Aprueba de la Siguiente Forma:</p>
'.$tabla.'
<p></p>
<p></p>
<table style="width: 100%;">
<tr>
    <td style="width: 60%"></td>
    <td style="width: 40%"></td>
</tr>
<tr>
    <td style="width: 60%"></td>
    <td style="width: 40%;text-align: center;">Firma y Timbre</td>
</tr>
</table>
<p></p>
<p></p>
<table style="width: 100%;">
    <tr>
        <td style="font-size: 0.8em;">
            <strong style="text-align: left;font-size: 0.8em;"><u>DISTRIBUICION</u></strong><br />
            -Interesado<br />
            -Archivo Municipal<br />
            -Contabilidad<br />
            -Folio: <strong>'.$folio.'</strong><br />
        </td>
        <td></td>
        <td></td>
    </tr>
</table>


';
$documento->CrearPDF($html);