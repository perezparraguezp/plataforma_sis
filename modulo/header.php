<?php
include '../../php/config.php';
include '../../php/objetos/profesional.php';
session_start();
//print_r($_SESSION);
$myId = $_SESSION['id_usuario'];
$profesional = new profesional($myId);
?>
<header id="header" class="page-topbar">
    <!-- start header nav-->
    <div class="navbar-fixed">
        <nav class="grey lighten-2">
            <div class="nav-wrapper">
                <ul class="right hide-on-med-and-down black-text">
                    <li>
                        <strong><?php
                            echo 'Usuario: '.$profesional->nombre;
                            ?></strong>
                    </li>
                    <li>
                        <a href="javascript:void(0);" class="waves-effect waves-block blue-text toggle-fullscreen"><i class="mdi-action-settings-overscan"></i>
                        </a>
                    </li>
                    <!-- Dropdown Trigger -->
                    <li>
                        <a href="#" data-activates="chat-out" class="waves-effect waves-block blue-text chat-collapse"><i class="mdi-editor-insert-chart"></i></a>
                    </li>
                </ul>
            </div>
        </nav>
    </div>
    <!-- end header nav-->
</header>
