<?php
include '../../../php/config.php';
include '../../../php/objetos/establecimiento.php';

session_start();

$id_establecimiento = $_SESSION['id_establecimiento'];

$dsm = new establecimiento($id_establecimiento);


?>
<ul id="dropdown_menu_interno" class="dropdown-content">
    <li onclick="loadForm_sectoresComunales()"><a href="#!">SECTORES COMUNALES</a></li>
    <li onclick=""><a href="#!">ACTUALIZAR PERFIL</a></li>
    <li class="divider"></li>
</ul>
<ul id="dropdown_menu_informes" class="dropdown-content" style="width: 400px;">
    <li onclick="load_estadistica_REMP2()"><a href="#!">REM P2</a></li>
    <li class="divider"></li>
</ul>
<div class="container">
    <div class="col s12 m12 l12" style="margin-top: 10px;">
        <nav class="eh-open_principal">
            <div class="nav-wrapper">
                <div class="col s12">
                    <a href="#!" class="brand-logo"><i class="mdi-action-settings-applications"></i></a>
                    <ul class="right hide-on-med-and-down">
                        <li onclick="load_estadistica_REMP2()"><a href="#"><i class="mdi-action-assignment-turned-in left"></i>REM P2</a></li>
                        <li onclick="load_estadistica_REMA3()"><a href="#"><i class="mdi-action-assignment-turned-in left"></i>REM A03</a></li>
                        <li onclick="load_estadistica_REMA9()"><a href="#"><i class="mdi-action-assignment-turned-in left"></i>REM A09</a></li>
<!--                        <li onclick="load_infoTraslado_menu()()"><a href="#"><i class="mdi-action-assignment-turned-in left"></i>TRASLADO NIÑO(A)</a></li>-->
                    </ul>
                </div>
            </div>
        </nav>
    </div>
    <div id="contenido_menu" style="font-family: Helvetica, Arial, Verdana, sans-serif;">
        <div class="card-panel">
            <h4>Estadisticas</h4>
            <div id="estadisticas_infantil">

            </div>
        </div>
    </div>
</div>
<script type="text/javascript">


    function load_estadistica_REMP2() {
        var div = 'contenido_menu';
        loading_div(div);
        $.post('info/P2.php',{
        },function(data){
            if(data !=='ERROR_SQL' ){
                $("#"+div).html(data);
            }else{

            }
        });
    }
    function load_estadistica_REMA3(){
        var div = 'contenido_menu';
        loading_div(div);
        $.post('info/P3.php',{
        },function(data){
            if(data !=='ERROR_SQL' ){
                $("#"+div).html(data);
            }else{

            }
        });
    }
    function load_estadistica_REMA9(){
        var div = 'contenido_menu';
        loading_div(div);
        $.post('info/P9.php',{
        },function(data){
            if(data !=='ERROR_SQL' ){
                $("#"+div).html(data);
            }else{

            }
        });
    }

    function load_infoTraslado_menu() {
        var div = 'contenido_menu';
        loading_div(div);
        $.post('formulario/traslado.php',{
        },function(data){
            if(data !=='ERROR_SQL' ){
                $("#"+div).html(data);
            }else{

            }
        });
    }
</script>
