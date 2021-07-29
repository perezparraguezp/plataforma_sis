<?php
include "../../config.php";
include "../../objetos/establecimiento.php";

$id_atributo = $_POST['id_atributo'];
if($_POST['valor']){
    if(strtoupper($_POST['valor'])=='ON'){
        $valor = 'SI';
    }else{
        $valor = $_POST['valor'];
    }

}else{
    $valor = 'NO';
}
$observaciones = $_POST['observaciones'];
$e = new establecimiento($_SESSION['id_establecimiento']);

$e->updateAtributo($id_atributo,$valor,$observaciones,$_SESSION['id_usuario']);

