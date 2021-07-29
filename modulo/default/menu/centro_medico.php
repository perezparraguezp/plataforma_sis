

<div class="container">
    <div class="col s12 m12 l12" style="margin-top: 10px;">
        <nav class="blue lighten-3">
            <div class="nav-wrapper">
                <div class="col s12">
                    <a href="#!" class="brand-logo"><i class="mdi-action-settings-applications"></i> </a>
                    <ul class="right hide-on-med-and-down">
                        <li onclick="loadListaConfig_establecimiento()"><a href="#"><i class="mdi-action-search left"></i>Listado</a></li>
                    </ul>
                </div>
            </div>
        </nav>
    </div>
    <div id="contenido_menu">
        <div class="card-panel">
            <h4>Bienvenido</h4>
        </div>
    </div>
</div>
<script type="text/javascript">
    function loading(div){
        $("#"+div).html('<div class="card-panel center"><img src="../loading.gif" /></div>');
    }
    function loadListaConfig_establecimiento(){
        var div = 'contenido_menu';
        loading(div);
        $.post('grid/centro_medico.php',{

        },function(data){
            if(data !=='ERROR_SQL' ){
                $("#"+div).html(data);
            }else{

            }
        });
    }


</script>
