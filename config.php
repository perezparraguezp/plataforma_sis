<!DOCTYPE html>
<html lang="es">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="msapplication-tap-highlight" content="no">
    <meta name="description" content="Materialize is a Material Design Admin Template,It's modern, responsive and based on Material Design by Google. ">
    <meta name="keywords" content="materialize, admin template, dashboard template, flat admin template, responsive admin template,">
    <title>Bienvenido - CONFIGURACIÓN</title>
    <link rel="stylesheet" href="jqwidgets/styles/jqx.base.css" type="text/css" />
    <!-- Favicons-->
    <link rel="icon" href="images/O.ico" sizes="32x32">
    <!-- Favicons-->
    <link rel="apple-touch-icon-precomposed" href="images/O.ico">
    <!-- For iPhone -->
    <meta name="msapplication-TileColor" content="#00bcd4">
    <meta name="msapplication-TileImage" content="images/O.ico">


    <!-- CORE CSS-->
    <link href="css/materialize.css" type="text/css" rel="stylesheet" media="screen,projection">
    <link href="css/style.css" type="text/css" rel="stylesheet" media="screen,projection">


    <!-- INCLUDED PLUGIN CSS ON THIS PAGE -->
    <link href="js/plugins/perfect-scrollbar/perfect-scrollbar.css" type="text/css" rel="stylesheet" media="screen,projection">
    <link href="js/plugins/jvectormap/jquery-jvectormap.css" type="text/css" rel="stylesheet" media="screen,projection">

    <!-- JQUERY -->
    <?php include 'php/html/script.php';  ?>
    <link href="jqwidgets/styles/jqx.energyblue.css" type="text/css" />

    <script type="text/javascript">
        function menuEhOpen(menu) {
            $.post('php/html/menu.php',{
                menu:menu
            },function(data){
                $("#div_menu").html(data);
            });
        }
    </script>
</head>

<body onload="menuEhOpen('config');init();">
<a id="btn-modal" class="modal-trigger" href="#modal"></a>
<div id="modal" class="modal modal-fixed-footer">
</div>
<!-- Start Page Loading -->
<div id="loader-wrapper">
    <div id="loader"></div>
    <div class="loader-section section-left"></div>
    <div class="loader-section section-right"></div>
</div>
<!-- End Page Loading -->

<!-- //////////////////////////////////////////////////////////////////////////// -->

<!-- START HEADER -->
<?php include 'php/html/header.php'; ?>
<!-- END HEADER -->

<!-- //////////////////////////////////////////////////////////////////////////// -->

<!-- START MAIN -->
<div id="main">
    <!-- START WRAPPER -->
    <div class="wrapper">

        <!-- START LEFT SIDEBAR NAV-->
        <div id="div_menu"></div>
        <!-- END LEFT SIDEBAR NAV-->

        <!-- //////////////////////////////////////////////////////////////////////////// -->

        <!-- START CONTENT -->
        <section id="content">
            <!--start container-->
            <div class="container">
                <?php include 'php/menu/config.php';?>
            </div>
            <!--end container-->
        </section>
        <!-- END CONTENT -->

        <!-- //////////////////////////////////////////////////////////////////////////// -->
        <!-- START RIGHT SIDEBAR NAV-->
        <aside id="right-sidebar-nav">
            <?php include 'php/html/mensajes.php';?>
        </aside>
        <!-- LEFT RIGHT SIDEBAR NAV-->
    </div>
    <!-- END WRAPPER -->

</div>
<!-- END MAIN -->
<!-- //////////////////////////////////////////////////////////////////////////// -->

<!-- START FOOTER -->
<footer class="page-footer" >
    <?php include 'php/html/footer.php'; ?>
</footer>
<!-- END FOOTER -->


<!-- ================================================
Scripts
================================================ -->



</body>

</html>