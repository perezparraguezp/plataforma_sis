<?php

$hostname = "localhost";
$database = "ehopen";
$username = "root";
$password = "pablo2012";
if (!($conexion = mysql_connect($hostname, $username, $password))) {
    echo "Error conectando a la base de datos.";
    exit();
}

if (!mysql_select_db($database, $conexion)) {
    echo "Error seleccionando la base de datos.";
    exit();
}

$username = $_POST['username'];
$password = $_POST['password'];

$sql = "select * from usuarios inner join personal_establecimiento on usuarios.rut=personal_establecimiento.rut
            where usuarios.rut='$username' and clave='$password'
            limit 1";
$row = mysql_fetch_array(mysql_query($sql));
if($row){
    session_start();

    $_SESSION['id_usuario'] = $row['id_profesional'];
    $_SESSION['rut'] = $row['rut'];
    $_SESSION['id_establecimiento'] = $row['id_establecimiento'];

    $_SESSION['login'] = 'true';
    //actualizamos las edades
    mysql_query("UPDATE persona SET edad_total=TIMESTAMPDIFF(MONTH, fecha_nacimiento, current_date())");
    mysql_query("UPDATE persona SET edad_total_dias=TIMESTAMPDIFF(DAY , fecha_nacimiento, current_date());");
    header('Location: ../i.php?LOGIN=TRUE');
}else{
    header('Location: ../login.php?LOGIN=FALSE');
}

