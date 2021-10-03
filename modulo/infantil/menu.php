
<?php
include "../../php/config.php";
include "../../php/objetos/profesional.php";

$menu = $_POST['menu'];

session_start();


$myId = $_SESSION['id_usuario'];

$id_establecimiento = $_SESSION['id_establecimiento'];


$profesional = new profesional($_SESSION['id_usuario']);


?>
<ul id="menu_usuario" class="dropdown-content">
    <li><a href="#"><i class="mdi-action-face-unlock"></i> Perfil</a></li>
</ul>
<aside id="left-sidebar-nav">
    <ul id="slide-out" class="side-nav fixed leftside-navigation">
        <li class="user-details cyan darken-2"
            style="background-image: url(sis_infantil.jpeg);height: 80px;width: 100%;">
            <div class="row">
                <!--
                <div class="col col s4 m4 l4" style="margin-top: -10px;">
                    <img src="img/h.png" alt="" class="circle responsive-img valign profile-image">
                </div>
                -->
                <div class="col col s12 m12 l12"  >
                </div>
            </div>
        </li>
        <?php
        if($profesional->tipo_profesional!='SOME'){
        ?>
            <li id="menu_0" onclick="loadMenu_Infantil('menu_0','dashboard','')" class="bold"><a href="#" class="waves-effect waves-cyan"><i class="mdi-action-dashboard"></i> Inicio</a></li>
            <li id="menu_1" onclick="loadMenu_Infantil('menu_1','registro_tarjetero','')"  class="bold"><a href="#"  class="waves-effect waves-cyan"><i class="mdi-action-assignment"></i> Tarjetero Infantil</a></li>
            <li id="menu_2" onclick="loadMenu_Infantil('menu_2','pendientes','')" class="bold"><a href="#" class="waves-effect waves-cyan"><i class="mdi-social-notifications-on"></i> Pendientes <span class="new badge">new</span></a></li>
        <?php
        }
        ?>

        <li id="menu_3" onclick="loadMenu_Infantil('menu_3','pacientes','')"class="bold"><a href="#" class="waves-effect waves-cyan"><i class="mdi-action-face-unlock"></i> Pacientes</a></li>


        <?php
        if($profesional->tipo_profesional!='SOME'){
            ?>
            <li id="menu_4" onclick="loadMenu_Infantil('menu_4','administracion','')" class="bold"><a href="#" class="waves-effect waves-cyan"><i class="mdi-action-assignment-turned-in"></i> Informes</a></li>
            <?php
        }
        ?>


        <?php
        if($profesional->tipo_profesional=='ADMINISTRADOR'){
            ?>
<!--            <li id="menu_4" onclick="loadMenu_Infantil('menu_4','administracion','')" class="bold"><a href="#" class="waves-effect waves-cyan"><i class="mdi-action-assignment-turned-in"></i> Informes</a></li>-->
            <?php
        }
        ?>

        <?php
        if($profesional->tipo_profesional=='ROOT'){
            ?>
            <li class="no-padding">
                <ul class="collapsible collapsible-accordion">
                    <li class="bold">
                        <a class="collapsible-header waves-effect waves-cyan">
                            <i class="mdi-action-settings-applications"></i> Configuracion ROOT
                        </a>
                        <div class="collapsible-body">
                            <ul>
                                <!--
                                <li><a href="tipo_documentos.php">Tipos Documentos</a></li>
                                <li><a href="tipo_agrupacion.php">Tipos Agrupaciones</a></li>
                                -->
                                <li><a href="config_establecimientos.php">Tipos de Usuarios </a></li>
                            </ul>
                        </div>
                    </li>
                </ul>
            </li>
            <?php
        }
        ?>
        <?php
        if($profesional->tipo_profesional=='ROOT'){
            ?>
            <li class="bold"><a href="establecimientos.php" class="waves-effect waves-cyan"><i class="mdi-maps-local-hospital"></i> Clientes </a></li>
            <?php
        }
        ?>
        <li class="bold"><a href="../../php/salir.php" class="waves-effect waves-cyan"><i class="mdi-action-lock"></i> CERRAR SESSIÓN </a></li>
    </ul>
    <a href="#" data-activates="slide-out" class="sidebar-collapse btn-floating btn-medium waves-effect waves-light hide-on-large-only darken-2"><i class="mdi-navigation-menu" ></i></a>
</aside>