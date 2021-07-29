<?php
include '../../config.php';
include '../../objetos/mysql.php';

error_reporting(E_ALL);
$tipo = $_POST['tipo_documento'];
$descripcion = $_POST['descripcion_documento'];

$carpeta = "../../../upload/".$_SESSION['id_establecimiento']."/".date('Y')."/".$tipo;

if (!file_exists($carpeta)) {
    mkdir($carpeta, 0777, true)or die('ERROR: No se pudo Crear la Carpeta');
}

$dir_subida = $carpeta."/";
$fichero_subido = $dir_subida . basename($_FILES['documento']['name']);

$ruta_sql = "upload/".$_SESSION['id_establecimiento']."/".date('Y')."/".$tipo."/".basename($_FILES['documento']['name']);

$tipos = array("image/gif","image/jpeg","image/jpg","image/png"); //Aqui eligo el tipo de archivo (bien)
$maximo = 5500000 * 2; //10 Mb esto es para probar
if ($_FILES["imagen"]["size"] <= $maximo){
    if (move_uploaded_file($_FILES['documento']['tmp_name'], $fichero_subido)) {

        $mysql = new mysql($_SESSION['id_usuario']);
        $mysql->insert_documento($tipo,$ruta_sql,$descripcion,$_SESSION['id_establecimiento']);
        if($mysql->result==true){
            echo "OK";
        }else{
            echo "ERROR_SQL";
        }


    } else {
        echo "ERROR_DIR";
    }
}