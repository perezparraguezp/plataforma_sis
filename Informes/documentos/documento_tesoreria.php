<?php
include("../../php/conex.php");
include("../../php/config.php");
include("../../php/objetos/documento.php");
include("../../php/objetos/decreto.php");
include("../../php/objetos/functionario.php");
session_start();
$funcionario = $_SESSION['id_empleado'];

$rut = str_replace(".","",$_POST['rut']);
$nombre = $_POST['nombre'];
$proceso_compra = $_POST['proceso_compra'];
$fecha_vencimiento = $_POST['fecha_vencimiento'];
$tipo_documento = strtoupper($_POST['tipo_documento']);
$tipo_documento_nombre = strtoupper($_POST['tipo_documento']);
$monto = str_replace(",","",$_POST['monto']);
$monto = str_replace(".","",$monto);
$codigo = $_POST['codigo'];
$descripcion = $_POST['descripcion'];
$responsable = strtoupper($_POST['responsable']);

$numero_proceso = mysql_fetch_array(mysql_query("select numero_proceso, nombre_proceso from proceso_compra where id_proceso_compra = '$proceso_compra'"));
$nombre_proceso = $numero_proceso['nombre_proceso'];
$numero_proceso = $numero_proceso['numero_proceso'];


$query = mysql_fetch_array(mysql_query("select * from documentos_retenidos
        where upper(nombre_documento) like upper('%$tipo_documento%')
        group by nombre_documento "));

if ($query['id_documento']==""){
    mysql_query("insert into documentos_retenidos (nombre_documento) values ('$tipo_documento')");
    $query2 = mysql_fetch_array(mysql_query("select * from documentos_retenidos
        where upper(nombre_documento) like upper('%$tipo_documento%')
        group by nombre_documento"));
    $tipo_documento = $query2['id_documento'];
}else{
    $tipo_documento = $query['id_documento'];
}

mysql_query("insert into documentos_tesoreria (rut_proveedor,nombre_proveedor,tipo_documento,monto,descripcion,
                                            codigo_identificacion,responsable,fecha_vencimiento,estado,id_funcionario_receptor,id_proceso_compra) values
                                            ('$rut','$nombre','$tipo_documento','$monto','$descripcion','$codigo',
                                            '$responsable','$fecha_vencimiento','RECEPCIONADO','$funcionario','$proceso_compra')") or die ("ERROR3");

$meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");

$responsable = mysql_fetch_array(mysql_query("select nombre_firma from firmantes where id_empleado = '$responsable'"));
$responsable = $responsable['nombre_firma'];

$html = "<style type=\"text/css\">
    p{
        text-align:justify;
        font-size:12pt;
        margin-top: 0px;
        
    }
    BLOCKQUOTE{
        font-size:10pt;
    }
    table{
        font-size:9pt;
        width: 100%;
        border: 1px solid black;
    }
    th, tr,td{
        border: 1px solid black;
    }

    span{
        font-size:10pt;
        text-align: right;
        }
    li{
    font-size:10pt;
    }
    h4{
    text-align: center;
    }
</style>

<br/>
<br/>
<br/>
<br/>
<p>Hoy, con fecha <strong>".date("d")." de ".$meses[date('m')-1]." del ".date("Y")."</strong> se da cuenta de una 
nueva recepcion de documento, correspondiente a una de las adjudicaciones del proceso código <strong>$numero_proceso</strong>, 
nombrado como <strong>$nombre_proceso</strong>. Las caracteristicas del documento recepcionado de describen a continuación:</p>
<br/>
<table>
    <tr>
        <td><strong>Razón Social</strong></td>
        <td><strong>RUT Emisor</strong></td>
        <td><strong>Tipo Documento</strong></td>
        <td><strong>Fecha de Vencimiento</strong></td>
    </tr>
    <tr>
        <td>$nombre</td>
        <td>$rut</td>
        <td>$tipo_documento_nombre</td>
        <td>".fechaNormal($fecha_vencimiento)."</td>
    </tr>
</table>
<br/>
<p>El responsable del documento recepcionado codificado internamente como <strong>$codigo</strong>, pasa a ser <strong>$responsable</strong>.";



if($monto !=0 or $monto !=""){
    $html.= " El monto del documento asciende a la suma de $ <strong>$monto</strong>";
    if($descripcion!=""){
        $html.= ", respondiendo además al siguiente detalle:</p><p>$descripcion.</p>";
    }else{
        $html.=".</p>";
    }
}else{
    if($descripcion!=""){
        $html.= " El detalle del documento responde a lo siguiente:</p><p>$descripcion.</p>";
    }
}


$title_pdf = "Recepcion de documentos                                            ";
$sub_title_pdf = "Tesoreria"."\n"."Municipalidad de Carahue";
$documento = new documento($title_pdf,$sub_title_pdf,"Recepcion de documentos");

$documento -> crearCabeceraPagina();
$documento -> addPagina_Vertical($html);//Decreto
$documento -> html = $html;
$documento -> outDocuemnto();


//ENVIAR CORREO AQUI.

?>
