<?php
include("../../php/config.php");
include("../../php/objetos/functionario.php"); //se realizan llamadas de datos de los funcionarios y se pasa $f que es una funcion
include("../../php/objetos/persona.php");
include("../../php/class/decreto.php");
require_once('../config/lang/cat.php');
require_once('../tcpdf.php');

error_reporting(0);

//Eliminamos los textos del documento



$id_mio = $_SESSION['id_empleado'];
$f_mio = new functionario($id_mio);

$id_reunion = $_POST['id_reunion'];

$sql1 = "SELECT * from concejo_reunion where id_reunion='$id_reunion' limit 1";
$row1 = mysql_fetch_array(mysql_query($sql1));
$hora = $row1['hora'];
$lugar = $row1['lugar'];
$tipo_reunion=$row1['tipo_reunion'];

$sql1_1 = "select * from firmantes where id_empleado='".$row1['id_presidente']."' limit 1";
$row1_1 = mysql_fetch_array(mysql_query($sql1_1));
$presidente = $row1_1['nombre_firma']."<br />Presidente";

$sql1_2 = "select * from firmantes where id_empleado='".$row1['id_secretario']."' limit 1";
$row1_2 = mysql_fetch_array(mysql_query($sql1_2));
$secretario = $row1_2['nombre_firma']."<br />Secretario Municipal";



$sql2 = "select * from concejal
        where desde<=current_date() and hasta>=CURRENT_DATE() and cargo='consejal' and cargo='alcalde'";
$res2 = mysql_query($sql2);


$firmate =$row1_2['nombre_firma'];

$reunion_cosejo = $row1['numero_reunion'];
$fecha= $row1['fecha'];

$sql3 = "SELECT * from concejo_reunion
            inner join concejo_temas_reunion using(id_reunion)
            inner join concejo_temas using(id_tema) 
            where id_reunion='$id_reunion' 
            order by orden asc";
$res3 = mysql_query($sql3);

//******************************************GENERACION DEL LISTADO DE TEMAS*************************************//
$cant_temas = 0;
$lista_temas_reunion.= '<li>Aprobación u objeción al Acta Anterior y Firma</li>
<li>Correspondencia Recibida y Despachada</li>
<li>Informe de Presidente</li>';
while($row3 = mysql_fetch_array($res3)) {
    $lista_temas_reunion.='<li>'.$row3['tema_texto'].'</li>';
    $cant_temas+=1;
}
$lista_temas_reunion.= '<li>Varios</li>';

//***************************************************************************************************************//


// caga los consejales
$lista_concejales_asistencia = array();
$lista_cargos = array();

$sql2 = "select * from concejal
        where desde<=current_date() 
        and hasta>=CURRENT_DATE()  
        order by id_concejal asc";
$res2 = mysql_query($sql2);
$i=0;
while($row2 = mysql_fetch_array($res2)){
    $lista_concejales_asistencia[$i]= $row2['nombre_concejal'];
    $lista_cargos[$i]= $row2['cargo'];
    $i++;
}

$temas =$row3['tema_texto'];
$f = new functionario($id_secretario);

$firmante = $f->nombre_completo;

$dias = Array("Domingo", "Lunes", "Martes", "Miercoles", "Jueves", "Viernes", "Sabado");
$meses = Array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");

$a = date('Y');
$m = date('m');
$d = date('d');
$dia = diaSemana($a, $m, $d);

$fecha_letras = $dias[$dia] . " " . $d . " de " . $meses[$m - 1] . " del " . $a;


// create new PDF document
define ('PDF_PAGE_FORMAT', 'LETTER');
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'iso-8859-1', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('I. Municipalidad de Carahue');
$pdf->SetTitle('Certificado');
$pdf->SetSubject('Decreto');
$pdf->SetKeywords('Decreto, PDF, documento, Documento');


$title_pdf = "I. Municipalidad de Carahue";
$sub_title_pdf = "Secretaría Municipal\nPortales #295, Carahue";

// set default header data
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, $title_pdf, $sub_title_pdf, array(0, 0, 0), array(0, 0, 0));


// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));


// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

//set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);


//set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
//set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

//set some language-dependent strings
$pdf->setLanguageArray($l);

// ---------------------------------------------------------
// set default font subsetting mode
$pdf->setFontSubsetting(true);

// Set font
// dejavusans is a UTF-8 Unicode font, if you only need to
// print standard ASCII chars, you can use core fonts like
// helvetica or times to reduce file size.
$pdf->SetFont('dejavusans', '', 14, '', true);

// Add a page
// This method has several options, check the source code documentation for more information.

// Set some content to print

$html1 = '

<style type="text/css">

    p{
        text-align:justify;
        text-indent: 100px;
        text-align: left;
        font-size: 0.9em;
        margin-top: 0px;
        font-family: "Times New Roman", Georgia, Serif;
        
    }
    strong{
    font-family: "Times New Roman", Georgia, Serif;
    }
    ol{
        text-align: left;
        font-size: 1em;
        margin-top: 0px;
        font-family: "Times New Roman", Georgia, Serif;
        
    }
    h1{
        text-align: left;
        font-size: 1em;
        margin-top: 0px;
    }
   
    table{
        font-size:1em;
        font-family: "Times New Roman", Georgia, Serif;
    }
    #p1{
        text-align: center;
    }
  
    h2{
        text-align: center;
        font-size: 1em;;
    }
</style>
<p style="text-align: right;">Carahue, '.$fecha_letras.'</p>
<h2>ACTA DE RECEPCIÓN DE <br />DOCUMENTACIÓN DE CONCEJO</h2>
<p></p>
<p>Se realiza la entrega conforme de la documentación necesaria para la Reunión '.$tipo_reunion.' N°'.$reunion_cosejo.' del Concejo Municipal a Celebrarse el día '.fechaNormal($fecha).', a las '.$hora.' horas en '.$lugar.'</p>
<p></p>
<p></p>
<table>
    <tr>
        <td style="text-align:center"></td>
        <td style="text-align:center"></td>
        
    </tr>';

for ($i = 0; $i < sizeof($lista_concejales_asistencia); $i++) {
    //$html1 = $html1."<p>".$lista_concejales_asistencia[$i]."</p>";

    $html1 = $html1.'
        <tr>
               <td><h6><strong>'.strtoupper($lista_concejales_asistencia[$i]).'</strong></h6></td>
               <td style="text-align:center"><h6>__________________________________________</h6></td>
        </tr>
        <tr><td></td><td></td></tr>
        <br/>

    ';
}
$html1 .= '</table>';
$html1 .= '<p></p><p></p><p></p><p></p><p></p><p></p>
<table>
    <tr>
        <td></td>
        <td style="text-align:center"><h5><strong>'.strtoupper($secretario).'</strong></h5></td>
    </tr>
</table>';


// Print text using writeHTMLCell()




$pdf->AddPage();//creas pagina

$pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', $html1, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);//escribes pagina


$pdf->resetHeaderTemplate();
$title_pdf = "I. Municipalidad de Carahue";
$sub_title_pdf = "Secretaría Municipal\nPortales #295, Carahue";


// set default header data
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, $title_pdf, $sub_title_pdf, array(0, 0, 0), array(0, 0, 0));

$sql2 = "select * from concejal
        where desde<=current_date() and hasta>=CURRENT_DATE()  ";
$res2 = mysql_query($sql2);

$item=0;
while($row2 = mysql_fetch_array($res2)){


    $html_concejal = '

    <style type="text/css">
        p{
            text-align:justify;
        text-indent: 100px;
        text-align: left;
        font-size: 1em;
            margin-top: 0px;
            font-family: "Times New Roman", Georgia, Serif;
            
        }
        ol{
            text-align: left;
            font-size: 1em;
            margin-top: 0px;
            font-family: "Times New Roman", Georgia, Serif;
            
        }
        h1{
            text-align: left;
            font-size: 0.7em;
            margin-top: 0px;
        }
       
        table{
            font-size:1em;
            font-family: "Times New Roman", Georgia, Serif;
        }
      
        h2{
            text-align: center;
        }
    </style>
    <p></p>
    <p style="text-align: right;">Carahue, '.$fecha_letras.'</p>
    <p></p>
    <p>Remito a usted la tabla a tratar en la sesión '.$tipo_reunion.' N°'.$reunion_cosejo.' del Concejo Municipal de Carahue a celebrarse al día '.fechaNormal($fecha).', a las '.$hora.' horas en '.$lugar.'.</p>
    <h6><strong><u>TABLA A TRATAR</u></strong></h6>
    <ol>'.$lista_temas_reunion.'</ol>';
    if ($cant_temas<8){
        $html_concejal.="<p></p>";
    }
    if ($cant_temas<4){
        $html1.="<p></p>";
    }
    $cant_temass = 14-($cant_temas + 4);
    if ($cant_temass>0){
        for ($i = 0; $i <= $cant_temass/2; $i++){
            $html_concejal.="<p></p>";
        }
    }
    $html_concejal.= '
    <p>Sin otro particular, le saluda atentamente.</p>
    <p></p>
    <p></p>
    <p></p>
    <p></p>
    <table>
        <tr>
    
            <td></td>
            <td style="text-align:center"><h5 style="font-size: 1em;"> <strong>'.strtoupper($secretario).'</strong></h5></td>
        </tr>
    </table>
    <p></p>
    <table >
        <tr>
            <td style="text-align:center"></td>
            <td style="text-align:center"></td>
            
        </tr>
    
    </table>
    <p></p>
    <td style="text-align:left; font-size: 0.7em;">'.strtoupper('<strong>Al Señor: <br />'.$lista_cargos[$item].'  de Carahue, <br />Don '.$lista_concejales_asistencia[$item].'</strong><br/><strong>Presente</strong>.').'</td>
    <p></p>
    <p></p>
    <p></p>
    <p></p>
    <p></p>
    <p></p>
    <p></p>
    
    ';

    $pdf->AddPage();//creas pagina
    $pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', $html_concejal, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);//escribes pagina

    $item++;
}

//secretario municipal

$html_concejal = '

    <style type="text/css">
        p{
            text-align: left;
           font-size: 0.7em;
            margin-top: 0px;
            
        }
        ol{
            text-align: left;
            font-size: 0.7em;
            margin-top: 0px;
            
        }
        h1{
            text-align: left;
            font-size: 0.7em;
            margin-top: 0px;
        }
       
        table{
            font-size:0.7em;
        }
      
        h2{
            text-align: center;
        }
    </style>
    <p style="text-align: right;">Carahue, '.$fecha_letras.'</p>
    <p></p>
    <p>Remito a usted la tabla a tratar en la sesión '.$tipo_reunion.' N°'.$reunion_cosejo.' del Concejo Municipal de Carahue a celebrarse al día '.fechaNormal($fecha).', a las '.$hora.' horas en '.$lugar.'.</p>
    <h6><strong>TABLA A TRATAR</strong></h6>
    <ol>'.$lista_temas_reunion.'</ol>
    <p></p>
    <p>Sin otro particular, le saluda atentamente,</p>
    <p></p>
    <p></p>
    <p></p>
    <p></p>
    <table>
        <tr>
    
            <td></td>
            <td style="text-align:center"><h6 style="font-size: 1em;"><strong>'.strtoupper($secretario).'</strong></h6></td>
        </tr>
    </table>
    <p></p>
    <table>
        <tr>
            <td style="text-align:center"></td>
            <td style="text-align:center"></td>
        </tr>
    </table>
    <p></p>
    <td style="text-align:left;font-size: 1em;" ><h6>'.strtoupper('<strong>Al Señor: <br />Secretario Municipal, <br />Don '.$row1_2['nombre_firma'].'</strong><br />Presente.').'</h6></td>
    <p></p>
    <p></p>
    <p></p>
    <p></p>
    <p></p>
    <p></p>
    <p></p>
    
    ';

$pdf->AddPage();//creas pagina
$pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', $html_concejal, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);//escribes pagina
$i++;






// Close and output PDF document
// This method has several options, check the source code documentation for more information.
$pdf->Output('Documento.pdf', 'I');
