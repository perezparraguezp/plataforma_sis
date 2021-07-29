<?php
include "../../php/config.php";
include "../../php/objetos/documento.php";
include "../../php/objetos/functionario.php";
session_start();
$myId = $_SESSION['id_empleado'];
$f = new functionario($myId);

$titulo_superior = "Decreto Anulación                       ";
$titulo_inferior = $f->nombre_depto."\nMunicipalidad de Carahue";

$documento = new documento($titulo_superior,$titulo_inferior,'DECRETO ANULACION');

$html = '<style type="text/css">
    p{
        text-align:left;
        text-indent: 280px;
        font-size:10pt;
        margin-top: -10px;
    }
    blockquote,strong{
        font-size:10pt;
    }
    table{
        font-size:8pt;
    }
    span{
        font-size:10pt;
        text-align: right;
        }
    ol li{
    font-size:10pt;
    }
    br{
    
    }
</style>';
$html .= trim($_POST['editor']);


//echo $html;
$documento->CrearPDF($html);