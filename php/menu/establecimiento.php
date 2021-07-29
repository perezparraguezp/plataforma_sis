
<div class="container">
    <div class="col s12 m12 l12" style="margin-top: 10px;">
        <nav class="blue lighten-3">
            <div class="nav-wrapper">
                <div class="col s12">
                    <a href="#!" class="brand-logo"><i class="mdi-communication-business"></i> </a>
                    <ul class="right hide-on-med-and-down">
                        <li id="click1" onclick="loadListadoEstablecimientos()"><a href="#"><i class="mdi-action-search left"></i>Listado</a></li>
                        <li onclick="loadForm_newEstablecimiento()"><a href="#"><i class="mdi-av-my-library-add left"></i>Nuevo</a></li>
                        <li>
                            <a class="dropdown-button" href="#!" data-activates="dropdown_menu_interno">
                                <i class="mdi-file-folder left"></i>Informes
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </div>
    <div id="contenido_menu">
        <div class="card-panel">
            <h4>Bienvenido a la Administración de los Centros de Salud</h4>
            <p>En esta seccion pordrá encontrar diferente información relacionada con los establecimientos,
            además de estadistica de cada uno de ellos.</p>
            <p>Este sistema permite que toda la información entregada pueda ser exportada de manera facíl
            en formato XLS, para posterior uso.</p>
        </div>
    </div>
</div>
<script type="text/javascript">
    function loading(div){
        $("#"+div).html('<div class="card-panel center"><img src="../loading.gif" /></div>');
    }
    function loadListadoEstablecimientos(){
        var div = 'contenido_menu';
        loading(div);
        $.post('php/modulo/establecimiento/listado.php',{
        },function(data){
            if(data !=='ERROR_SQL' ){
                $("#"+div).html(data);
                cargarListado();
            }else{

            }
        });
    }
    function loadInformeDocumentos(){
        var div = 'contenido_menu';
        loading(div);
        $.post('php/modulo/establecimiento/informe_documentos.php',{

        },function(data){
            if(data !=='ERROR_SQL' ){
                $("#"+div).html(data);
            }else{

            }
        });
    }
    function loadInformeAgrupaciones(){
        var div = 'contenido_menu';
        loading(div);
        $.post('php/modulo/establecimiento/informe_agrupaciones.php',{

        },function(data){
            if(data !=='ERROR_SQL' ){
                $("#"+div).html(data);
            }else{

            }
        });
    }
    function loadForm_newEstablecimiento(){
        var div = 'contenido_menu';
        loading(div);
        $.post('php/formulario/establecimiento/nuevo.php',{

        },function(data){
            if(data !=='ERROR_SQL' ){
                $("#"+div).html(data);
                $("#fecha").jqxDateTimeInput({ width: '300px', height: '25px' });
            }else{

            }
        });
    }

</script>
