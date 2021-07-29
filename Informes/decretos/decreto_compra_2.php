<?php
include "../../php/config.php";
include "../../php/objetos/documento.php";
include "../../php/objetos/functionario.php";
session_start();
$myId = $_SESSION['id_empleado'];
$f = new functionario($myId);

$titulo_superior = "Decreto de Compra                       ";
$titulo_inferior = $f->nombre_depto."\nMunicipalidad de Carahue";

$documento = new documento($titulo_superior,$titulo_inferior,'DECRETO DE COMPRA');

$html = '<style type="text/css">
    p{
        text-align:left;
        text-indent: 280px;
        font-size:.7em;
        padding: 0px;
        margin: 0px;
        clear: top;
    }
    ul,li{
        font-size:.7em;
        padding: 0px;
        margin: 0px;
        clear: top;
        text-align: left;
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
</style>

<p>DECRETO N: <strong>XXX</strong></p>
<p>Carahue, <strong>XXX</strong></p>
';
$html .= trim($_POST['vistos']);



//echo $html;
$documento->CrearPDF($html);