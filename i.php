<?php
$LOGIN = $_GET['LOGIN'];
if($LOGIN=='TRUE'){
    //header('Location: escritorio.php');
}
session_start();
?>
<!DOCTYPE html>
<html lang="es">

<!--================================================================================
	Item Name: Materialize - Material Design Admin Template
	Version: 1.0
	Author: GeeksLabs
	Author URL: http://www.themeforest.net/user/geekslabs
================================================================================ -->

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="msapplication-tap-highlight" content="no">
    <meta name="description" content="Materialize is a Material Design Admin Template,It's modern, responsive and based on Material Design by Google. ">
    <meta name="keywords" content="materialize, admin template, dashboard template, flat admin template, responsive admin template,">
    <title>SELECCIONAR MODULO | EH-OPEN SOFTWARE</title>


    <!-- Favicons-->
    <link rel="icon" href="images/O.ico" sizes="32x32">
    <!-- Favicons-->
    <link rel="apple-touch-icon-precomposed" href="images/O.ico">
    <!-- For iPhone -->
    <meta name="msapplication-TileColor" content="#00bcd4">
    <meta name="msapplication-TileImage" content="images/O.ico">
    <!-- For Windows Phone -->


    <!-- CORE CSS-->

    <link href="css/materialize.css" type="text/css" rel="stylesheet" media="screen,projection">
    <link href="css/style.css" type="text/css" rel="stylesheet" media="screen,projection">
    <link href="css/page-center.css" type="text/css" rel="stylesheet" media="screen,projection">

    <!-- INCLUDED PLUGIN CSS ON THIS PAGE -->
    <link href="css/prism.css" type="text/css" rel="stylesheet" media="screen,projection">
    <link href="js/plugins/perfect-scrollbar/perfect-scrollbar.css" type="text/css" rel="stylesheet" media="screen,projection">

</head>

<body class="cyan">
<!-- Start Page Loading -->
<div id="loader-wrapper">
    <div id="loader"></div>
    <div class="loader-section section-left"></div>
    <div class="loader-section section-right"></div>
</div>
<!-- End Page Loading -->
<style type="text/css">
    @media only screen
    and (min-device-width : 320px)
    and (max-device-width : 568px) { /* Aquí van los estilos */
        img{
            width: 50%;
        }
    }
    a{
        border: none;
        text-decoration: none;
    }
    a:hover{
        background-color: #438eb9;
    }
    img.responsive-img, video.responsive-video {
        max-width: 90%;
    }
</style>
<div id="login-page" class="row">
    <div class="card-panel center" style="width: 98%;padding: 10px;">
        <div class="row">
            <div class="col l4 m3 s12"><div class="clear" style="color: white;">-</div> </div>
            <a href="modulo/some/index.php" target="_blank"
               class="col l4 m6 s12 card-panel teal lighten-5 center" style="margin: 0px;">
                <div class="row">
                    <div class="col l12 m12 s12">
                        <div class="row">
                            <div class="col l12 m12 s12">
                                <img src="images/some.png" class="responsive-img" height="200px" />
                            </div>
                        </div>
                    </div>
                    <div class="col l12 m12 s12">
                        <div class="row">
                            <div class="col l12 m12 s12">
                                <header class="center">INGRESO DE PACIENTES</header>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
            <?php
            if($_SESSION['tipo_usuario']=='ADMINISTRADOR'){
                ?>
                <a href="modulo/default/index.php" target="_blank"
                   class="col l4 m6 s12 card-panel teal lighten-5 center" style="margin: 0px;text-align: center;">
                    <div class="row">
                        <div class="col l12 m12 s12">
                            <div class="row">
                                <div class="col l12 m12 s12 center">
                                    <img src="images/icono_ajustes.png"
                                         class="responsive-img" height="200px" />
                                </div>
                            </div>
                        </div>
                        <div class="col l12 m12 s12">
                            <div class="row">
                                <div class="col l12 m12 s12">
                                    <header class="center">CONFIGURACIONES GENERALES</header>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
                <?php
            }else{
                ?>
                <div class="col l4 m3 s12"><div class="clear" style="color: white;">-</div> </div>
                <?php
            }
            ?>
        </div>
        <div class="row">
            <?php
            include('php/config.php');
            session_start();
            $id_establecimiento = $_SESSION['id_establecimiento'];
            $rut = $_SESSION['rut'];

            $sql = "select * from menu_usuario 
                        inner join modulos_ehopen using(id_modulo)
                        where rut='$rut' and id_establecimiento='$id_establecimiento' 
                        order by id_modulo";

            $res = mysql_query($sql);
            while($row = mysql_fetch_array($res)){
                echo  $row['html'];
            }
            ?>
        </div>
        <div class="row">
            <div class="col l12 s12 m12">
                <a href="php/salir.php" class="btn-large" style="width: 100%;" >CERRAR SESSION</a>
            </div>
        </div>
        <div class="card-panel" class="card-panel center" style="width: 98%;padding: 10px;">
            <div class="row">
                Para Obtener Soporte escribanos <a href="mailto:soporte@eh-open.com">SOPORTE@EH-OPEN.COM</a>
            </div>
        </div>
    </div>
</div>

<!-- ================================================
  Scripts
  ================================================ -->

<!-- jQuery Library -->
<script type="text/javascript" src="js/jquery-1.11.2.min.js"></script>
<!--materialize js-->
<script type="text/javascript" src="js/materialize.js"></script>
<!--prism-->
<script type="text/javascript" src="js/prism.js"></script>
<!--scrollbar-->
<script type="text/javascript" src="js/plugins/perfect-scrollbar/perfect-scrollbar.min.js"></script>

<!--plugins.js - Some Specific JS codes for Plugin Settings-->
<script type="text/javascript" src="js/plugins.js"></script>

</body>

</html>