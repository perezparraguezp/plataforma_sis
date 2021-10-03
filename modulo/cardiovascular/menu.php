
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
            style="background-image: url(sis_cardiovascular.jpeg);width: 100%;height: 80px;">
            <div class="row">

                <div class="col col s12 m12 l12" style="height: 70px;">

                </div>
            </div>
        </li>
        <li id="menu_0" onclick="loadMenu_CardioVascular('menu_0','dashboard','')" class="bold"><a href="#" class="waves-effect waves-cyan"><i class="mdi-action-dashboard"></i> Inicio</a></li>
        <li id="menu_1" onclick="loadMenu_CardioVascular('menu_1','registro_atencion','')"  class="bold"><a href="#"  class="waves-effect waves-cyan"><i class="mdi-action-assignment"></i> Registrar Atención</a></li>
        <li id="menu_2" onclick="loadMenu_CardioVascular('menu_2','pendientes','')" class="bold"><a href="#" class="waves-effect waves-cyan"><i class="mdi-social-notifications-on"></i> Pendientes <span class="new badge">new</span></a></li>
        <li id="menu_3" onclick="loadMenu_CardioVascular('menu_3','pacientes','')"class="bold"><a href="#" class="waves-effect waves-cyan"><i class="mdi-action-account-child"></i> Pacientes</a></li>
        <li id="menu_5" onclick="loadMenu_CardioVascular('menu_5','informes','')"class="bold"><a href="#" class="waves-effect waves-cyan"><i class="mdi-action-assignment-turned-in"></i> Informes</a></li>

        <li class="bold"><a href="../../php/salir.php" class="waves-effect waves-cyan"><i class="mdi-action-lock"></i> CERRAR SESSIÓN </a></li>
    </ul>
    <a href="#" data-activates="slide-out" class="sidebar-collapse btn-floating btn-medium waves-effect waves-light hide-on-large-only darken-2"><i class="mdi-navigation-menu" ></i></a>
</aside>