
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
        <li class="user-details cyan darken-2">
            <div class="row">
                <!--
                <div class="col col s4 m4 l4" style="margin-top: -10px;">
                    <img src="img/h.png" alt="" class="circle responsive-img valign profile-image">
                </div>
                -->
                <div class="col col s12 m12 l12" style="height: 70px;">
                    <a style="left: 55px;"
                       class="btn-flat dropdown-button waves-effect waves-light white-text profile-btn" href="#"
                       data-activates="menu_usuario"><?php echo $profesional->nombre;; ?><i class="mdi-navigation-arrow-drop-down right"></i></a>
                    <p class="user-roal"><?php echo $profesional->tipo_profesional; ?></p>
                </div>
            </div>
        </li>
        <li id="menu_0" onclick="loadMenu_default('menu_0','dashboard','')" class="bold"><a href="#" class="waves-effect waves-cyan"><i class="mdi-action-dashboard"></i> Inicio</a></li>
        <li id="menu_1" onclick="loadMenu_default('menu_1','profesionales','')"  class="bold"><a href="#"  class="waves-effect waves-cyan"><i class="mdi-communication-contacts"></i> Profesionales</a></li>
        <li id="menu_3" onclick="loadMenu_default('menu_3','centro_medico','')"class="bold"><a href="#" class="waves-effect waves-cyan"><i class="mdi-communication-business"></i> Centros Medicos</a></li>

        <li class="bold"><a href="../../php/salir.php" class="waves-effect waves-cyan"><i class="mdi-action-lock"></i> CERRAR SESSIÓN </a></li>
    </ul>
    <a href="#" data-activates="slide-out" class="sidebar-collapse btn-floating btn-medium waves-effect waves-light hide-on-large-only darken-2"><i class="mdi-navigation-menu" ></i></a>
</aside>