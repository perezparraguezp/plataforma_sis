<?php
include '../../config.php';
include '../../objetos/mysql.php';

$id = $_POST['id'];

$mysql = new mysql($_SESSION['id_usuario']);

$mysql->delete_centro_interno($id);



