<?php
include '../../../php/config.php';
include '../../../php/objetos/establecimiento.php';

session_start();

$id_establecimiento = $_SESSION['id_establecimiento'];

$dsm = new establecimiento($id_establecimiento);


?>

<div class="container">
    <div class="col s12 m12 l12" style="margin-top: 10px;">
        <nav class="eh-open_principal">
            <div class="nav-wrapper">
                <div class="col s12">
                    <a href="#!" class="brand-logo"><i class="mdi-action-settings-applications"></i></a>
                    <ul class="right hide-on-med-and-down">
                        <li onclick="load_estadistica_REMP9()"><a href="#"><i class="mdi-action-assignment-turned-in left"></i>REM P9</a></li>

                    </ul>
                </div>
            </div>
        </nav>
    </div>
    <div id="contenido_menu">
        <div class="card-panel">
            <h4>Bienvenido!<br /><?php echo $dsm->nombre; ?></h4>
            <p>En esta seccion el usuario podrá generar los informes necesarios para realizar una gestión de calidad.</p>

        </div>
    </div>
</div>
<script type="text/javascript">
    load_estadistica_REMP9();

    function load_estadistica_REMP9() {
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
</script>
