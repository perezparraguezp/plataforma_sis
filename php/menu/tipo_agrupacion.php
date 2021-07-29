

<div class="container">
    <div class="col s12 m12 l12" style="margin-top: 10px;">
        <nav class="blue lighten-3">
            <div class="nav-wrapper">
                <div class="col s12">
                    <a href="#!" class="brand-logo"><i class="mdi-action-settings-applications"></i> </a>
                    <ul class="right hide-on-med-and-down">
                        <li onclick="loadListadoTipoAgrupacion()"><a href="#"><i class="mdi-action-search left"></i>Listado</a></li>
                        <li onclick="loadForm_newTipoAgrupacion()"><a href="#"><i class="mdi-av-my-library-add left"></i>Nuevo</a></li>
                    </ul>
                </div>
            </div>
        </nav>
    </div>
    <div id="contenido_menu">
        <div class="card-panel center">
            <img src="../loading.gif" />
        </div>
    </div>
</div>
<script type="text/javascript">
    $(function(){
        loadListadoTipoAgrupacion();
    });
    function loading(div){
        $("#"+div).html('<div class="card-panel center"><img src="../loading.gif" /></div>');
    }
    function loadListadoTipoAgrupacion(){
        var div = 'contenido_menu';
        loading(div);
        $.post('php/modulo/tipo_agrupacion/listado.php',{

        },function(data){
            if(data !=='ERROR_SQL' ){
                $("#"+div).html(data);
            }else{

            }
        });
    }
    function loadForm_newTipoAgrupacion(){
        var div = 'contenido_menu';
        loading(div);
        $.post('php/formulario/tipo_agrupacion/nuevo.php',{

        },function(data){
            if(data !=='ERROR_SQL' ){
                $("#"+div).html(data);
            }else{

            }
        });
    }
</script>
