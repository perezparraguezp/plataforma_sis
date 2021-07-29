<?php
include '../../../../php/config.php';
include '../../../../php/objetos/mysql.php';

$id = $_POST['id'];

$mysql = new mysql($_SESSION['id_usuario']);

$mysql->delete_agendamiento($id);




