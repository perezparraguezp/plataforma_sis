<?php

include 'php/objetos/establecimiento.php';

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
        <nav class="blue lighten-3">
            <div class="nav-wrapper">
                <div class="col s12">
                    <a href="#!" class="brand-logo"><i class="mdi-action-settings-applications"></i></a>

                    <ul class="right hide-on-med-and-down">
                        <li onclick="loadGrid_Personal()"><a href="#"><i class="mdi-social-people left"></i>PROFESIONALES</a></li>
                        <li onclick="loadGrid_Centros()"><a href="#"><i class="mdi-communication-business left"></i>ESTABLECIMIENTOS</a></li>
                        <li>
                            <a class="dropdown-button" href="#!" data-activates="dropdown_menu_informes">
                                <i class="mdi-editor-insert-drive-file left"></i>INFORMES
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-button" href="#!" data-activates="dropdown_menu_interno">
                                <i class="mdi-action-settings left"></i>AJUSTES GENERALES
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </div>
    <div id="contenido_menu">
        <div class="card-panel">
            <h4>Bienvenido a la Administracion General <br /><?php echo $dsm->nombre; ?></h4>
            <p>En esta seccion el usuario podrá administrar su centro de salud, conmfigurando los parametros basiscos ademas
                de la gestion de usuarios y zonas de trabajo</p>

        </div>
    </div>
</div>
<script type="text/javascript">
    function loading(div){
        $("#"+div).html('<div class="card-panel center"><img src="../loading.gif" /></div>');
    }
    function loadListaAgrupaciones(){
        var div = 'contenido_menu';
        loading(div);
        $.post('php/modulo/perfil/listado_agrupaciones.php',{

        },function(data){
            if(data !=='ERROR_SQL' ){
                $("#"+div).html(data);
            }else{

            }
        });
    }
    function loadForm_newAgrupacion(){
        var div = 'contenido_menu';
        loading(div);
        $.post('php/formulario/perfil/ingreso_agrupacion.php',{

        },function(data){
            if(data !=='ERROR_SQL' ){
                $("#"+div).html(data);
                $(".fecha").jqxDateTimeInput({ width: '300px', height: '25px' ,formatString: "yyyy-MM-dd"});
                $('#tipo_agrupacion').jqxDropDownList({
                    theme: 'energyblue',
                    filterable: true,
                    filterPlaceHolder: "Buscar",
                    width: '100%',
                    height: '25px'
                });
            }else{

            }
        });
    }

    function loadGrid_Centros(){
        var div = 'contenido_menu';
        loading(div);
        $.post('php/modulo/config/lista_establecimientos.php',{

        },function(data){
            if(data !=='ERROR_SQL' ){
                $("#"+div).html(data);

            }else{

            }
        });
    }
    function loadGrid_Personal(){
        var div = 'contenido_menu';
        loading(div);
        $.post('php/modulo/config/lista_personal.php',{

        },function(data){
            if(data !=='ERROR_SQL' ){
                $("#"+div).html(data);
                $('#tipo_documento').jqxDropDownList({
                    filterable: true,
                    filterPlaceHolder: "Buscar",
                    width: '100%',
                    height: '25px'
                });
            }else{

            }
        });
    }
    function loadForm_newSectorCentro(){
        var div = 'contenido_menu';
        loading(div);
        $.post('php/formulario/perfil/ingreso_sector_centro.php',{
        },function(data){
            if(data !=='ERROR_SQL' ){
                $("#"+div).html(data);
                $('#id_centro_interno').jqxDropDownList({
                    filterable: true,
                    filterPlaceHolder: "Buscar Centro",
                    width: '100%',
                    height: '25px'
                });
            }else{

            }
        });
    }
    function loadForm_newCentro(){
        var div = 'contenido_menu';
        loading(div);
        $.post('php/formulario/config/establecimiento_nuevo.php',{

        },function(data){
            if(data !=='ERROR_SQL' ){
                $("#"+div).html(data);



            }else{

            }
        });
    }
    function loadForm_updateAtributo(){
        var div = 'contenido_menu';
        loading(div);
        $.post('php/formulario/perfil/ingreso_atributo.php',{

        },function(data){
            if(data !=='ERROR_SQL' ){
                $("#"+div).html(data);
                $('#tipo_atributo').jqxDropDownList({
                    filterable: true,
                    filterPlaceHolder: "Buscar",
                    width: '100%',
                    height: '25px'
                });
            }else{

            }
        });
    }
    function loadListaDocumentos(){
        var div = 'contenido_menu';
        loading(div);
        $.post('php/modulo/perfil/lista_documentos.php',{

        },function(data){
            if(data !=='ERROR_SQL' ){
                $("#"+div).html(data);
            }else{

            }
        });
    }
    function loadForm_sectoresComunales() {
        var div = 'contenido_menu';
        loading(div);
        $.post('php/modulo/config/sectores_comunales.php',{

        },function(data){
            if(data !=='ERROR_SQL' ){
                $("#"+div).html(data);
            }else{

            }
        });
    }
    function load_estadistica_REMP2() {
        var div = 'contenido_menu';
        loading(div);
        $.post('php/modulo/config/REM_P2.php',{

        },function(data){
            if(data !=='ERROR_SQL' ){
                $("#"+div).html(data);
            }else{

            }
        });
    }
    function loadForm_newAtributo(){
        var div = 'contenido_menu';
        loading(div);
        $.post('php/modulo/perfil/lista_atributo.php',{

        },function(data){
            if(data !=='ERROR_SQL' ){
                $("#"+div).html(data);
            }else{

            }
        });
    }

</script>
