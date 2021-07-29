<?php
include "../../php/config.php";
include "../../php/objetos/documento.php";
include "../../php/objetos/documento_dte.php";
include "../../php/objetos/functionario.php";
include "../../php/objetos/proceso_compra.php";

$funcionario = new functionario($_SESSION['id_empleado']);

$id_adquisicion     = $_POST['id_adquisicion'];  //DECRETO PADRE
$id_adjudicacion    = $_POST['id_adjudicacion']; //DECRETO COMPRA
$id_proceso         = $_POST['id_proceso'];      //ID PROCESO GENERAL

$proceso_compra = new proceso_compra();
$proceso_compra->cargarProcesoCompra($id_proceso);


$sql1 = "select * from adquisicion_proceso_compra 
            inner join decretos on id_interno=folio_decreto   
            where id_adquisicion='$id_adquisicion' limit 1";
$row1 = mysql_fetch_array(mysql_query($sql1));

$decretos = $row1['numero_decreto']."/".$row1['anio_decreto'];



//ID_DTE
$id_dte = $_POST['id_dte'];
$dte = new documento_dte();
$dte->cargar_dte($id_dte); //

$proveedor = new proveedor($dte->rut);

$documento = new documento('CERTIFICADO DE RECEPCION',"INVENTARIO MUNICIPAL\nMunicipalidad de Carahue",'Recepcion Bodega');
$documento->updateTipoDocumento('CERTIFICADO DE RECEPCION','BODEGA - BIENES');
$documento->updateDatosDocumento('','','Recepcion de Factura '.$dte->folio.', por un monto de $'.$dte->monto_total);



$tipo = $_POST['tipo'];
$nombre = $_POST['nombre'];
$cantidad = $_POST['cantidad'];
$descripcion = $_POST['descripcion'];
$inventariable = $_POST['inventariable'];
$fungible = $_POST['fungible'];
$precio = $_POST['precio'];
$descuento = $_POST['descuento'];
$monto_factura = 0;

$filas = '';


foreach ($tipo as $i => $value) {


    $sql0 = "select * from bdg_categoria where id_categoria='".$tipo[$i]."' limit 1";
    $row0 = mysql_fetch_array(mysql_query($sql0));
    $id_rubro = $row0['id_rubro'];
    $tipo_inventario = $fungible[$i];

    $valor = str_replace("$ ","",str_replace(".","",$precio[$i]));

    if($tipo_inventario == ' FUNGIBLE'){
        $nombre_objeto = $nombre[$i]." [Cant. ".$cantidad[$i]."]";
    }else{
        $nombre_objeto = $nombre[$i];
    }

    $sql = "insert into bdg_objeto(id_factura,id_categoria,marca,precio,cantidad,stock,inventariable,activado,depreciable,tipo_objeto,id_dte)
        values('$id_factura','".$tipo[$i]."',upper('".$nombre_objeto."'),'".$valor."','".$cantidad[$i]."','".$cantidad[$i]."','".$tipo_inventario."','NO','NO','$tipo_inventario','".$dte->id_documento."');";
    //echo $sql;
    mysql_query($sql);

    $row = mysql_fetch_array(mysql_query("select * from bdg_objeto order by id_objeto desc limit 1"));
    $id_objeto = $row['id_objeto'];
    $codigo = $tipo[$i]."-".$id_objeto;
    mysql_query(("update bdg_objeto set codigo='$codigo' where id_objeto='".$id_objeto."' limit 1"));

    $v = str_replace("$ ","",str_replace(".","",$precio[$i]));

    $precion_con_iva = (int)($v) * 1.19 * $cantidad[$i];

    $filas .= '<tr>'

        . '<td>'.$row0['nombre_categoria'].'</td>'
        . '<td>'.$nombre[$i].'</td>'
        . '<td style="text-align:center;">'.$cantidad[$i].'</td>'
        . '<td style="text-align:right;">'.$precio[$i].'</td>'
        . '<td style="text-align:right;">$ '.number_format($precion_con_iva,0,'','.') .'</td>'
        . '<td> ';

    if($tipo_inventario != 'FUNGIBLE'){//solo se codifican los activos fijos

        //creamos todos los productos inventariables
        for($j=0;$j<$cantidad[$i];$j++){
            $codigo_producto = $codigo."-".$j;
            mysql_query("insert into bdg_producto(codigo_producto,id_objeto,estado_producto,id_bodega,id_lugar,id_empresa,tipo_inventario,fecha_codificado,id_rubro) 
                                values('$codigo_producto','$id_objeto','NUEVO','$id_bodega',0,'$id_empresa','$tipo_inventario',now(),'$id_rubro')");
            if($tipo_inventario != 'FUNGIBLE'){//solo se codifican los activos fijos
                $filas .= $codigo_producto.'<br />';
            }else{
                $filas .='<strong></strong>';
            }
        }
        //$filas .= '<li>'.$codigo_producto.'</li>';
    }else{
        $codigo_producto = $codigo."-0";
        mysql_query("insert into bdg_producto(codigo_producto,id_objeto,estado_producto,id_bodega,id_lugar,id_empresa,tipo_inventario,fecha_codificado,id_rubro) 
                                values('$codigo_producto','$id_objeto','NUEVO','$id_bodega',0,'$id_empresa','$tipo_inventario',now(),'$id_rubro')");
        $filas .='<strong></strong>';
    }


    $total_iva+= $precion_con_iva;
    $filas .=  '</td>
            </tr>';
}

$table_head = '<br style="width: 100%;clear: both;" />
        <table widht="100%" style="font-size:0.6em;" border="1">'
    . '<tr>'
    . '<td style="width: 12%;background-color: #d7efff;">Rut Proveedor</td>'
    . '<td style="width: 18%;">'.$proveedor->rut.'</td>'
    . '<td style="width: 15%;background-color: #d7efff;">Nombre Proveedor</td>'
    . '<td style="width: 55%;">'.$proveedor->razon_social.'</td>'
    . '</tr>'
    . '<tr>'
    . '<td style="background-color: #d7efff;">Nº Documento</td>'
    . '<td>'.$dte->folio.'</td>'
    . '<td style="background-color: #d7efff;">Fecha Emisión</td>'
    . '<td>'.fechaNormal($dte->fecha_emision).'</td>'
    . '</tr>'
    . '<tr>'
    . '<td style="background-color: #d7efff;">Decreto de Adquisicion</td>'
    . '<td>'.$decretos.'</td>'
    . '<td style="background-color: #d7efff;">Nombre Proceso Compra</td>'
    . '<td>'.$proceso_compra->nombre_proceso.'<br />Cod.['.$proceso_compra->numero_proceso.']</td>'
    . '</tr>'
    . '</table>';
$table = '<br style="width: 100%;clear: both;" /><hr /><table border="1px" widht="100%" style="font-size:0.5em;font-family: Roboto, HelveticaNeue, sans-serif"> '
    . '<tr style="background-color: rgba(204,253,255,0.99);font-weight: bold;padding: 3px;">'
    . '<td style="width: 20%;">Categoria</td>'
    . '<td style="width: 25%;">Objeto</td>'
    . '<td style="width: 5%;">Cant.</td>'
    . '<td style="width: 15%;">Precio Uni.</td>'
    . '<td style="width: 15%;">Total + IVA</td>'
    . '<td style="width: 20%;">CODIGO UNICO</td>'
    . '</tr>';
$table.=$filas;
$total_factura = $total_iva;



$table .= '</table>';

$footer = '<p></p>';
$footer .= '<p style="font-size: 0.7em;">SE RECEPCIONA EN CONFORMIDAD EL DOCUMENTO TRIBUTARIO ELECTRONICO SEGUN DETALLE DE PRODUCTOS DESCRITOS EN LA TABLA ANTERIOR, ';
$footer .= 'ADEMAS EL DOCUMENTO TRIBUTARIO ELECTRONICO CUENTA CON UN TOTAL POR PAGAR DE <strong>$ '.number_format($dte->monto_total,0,'','.').'</strong> ('.convertir_numero_a_letra($dte->monto_total).')</p>';
$footer .= '<p></p>';
$footer .= '<p></p>';
$footer .= '<p></p>';
$footer .= '<p></p>';
$footer .= '<p style="font-size: 0.8em;text-align: center;font-weight: bold;">'.$funcionario->nombre_completo.'<br />Bodeguero Municipal</p>';

$footer .= '<p></p>';
$footer .= '<p></p>';
$footer .= '<hr style="width: 100%;" />';
$footer .= '<p></p>';
$footer .= '<p style="font-size: 0.7em;">Se hace entrega del certificado y Documento Electronico Tributario (DTE) Originales a la unidad de Adquisiciones correspondiente, 
                la cual entrega copia de este timbrado y fechado al Bodeguero, el cual sera empleado para su archivo personal.</p>';
$footer .= '<p style="font-size: 0.7em;">La Unidad de Adquisiciones debera indicar el destino final de los productos inventariados y recepcionados por '.$funcionario->nombre_completo.', para proceder con el proceso de activacion por parte de la Unidad de Contabilidad Municipal.</p>';
$footer .= '<table style="width: 100%;" border="1px" >
                <tr STYLE="line-height: 10px;">
                    <td style="font-size: 0.7em;text-align: left;width: 30%">DESTINO DE LOS PRODUCTOS</td>    
                    <td style="font-size: 0.7em;text-align: center;width: 70%"></td>    
                </tr>
            </table>';
$footer .= '<p></p>';
$footer .= '<p></p>';
$footer .= '<p></p>';
$footer .= '<p></p>';
$footer .= '<table style="width: 100%;" >
                <tr>
                    <td style="font-size: 0.7em;text-align: center;">NOMBRE Y FIRMA <br />RECEPTOR DE CERTIFICADO</td>    
                    <td style="font-size: 0.7em;text-align: center;">TIMBRE</td>    
                </tr>
            </table>';
$documento->crearFolio();
$documento->NumerarDocumento(date('Y'));
$numero_certificado = $documento->numero_decreto."/".date('Y');

$html ='<style type="text/css">
h4{
text-align: center;;
}
</style>';
$html .= '<p style="font-size: 0.8em;text-align:right;text-indent: 380px;">En Carahue, '.date('d/m/Y').'</p>';
$html .= '<h4>CERTIFICADO DE RECEPCION<br />Nº '.$numero_certificado.'</h4>';
$html .= $table_head.$table.$footer;
$documento->CrearPDF($html);
