<!--
se crean los atributos del establecimiento como patio, si tiene media, basica, sala cuna entre otros

vatiables      tipo
tipo_atributo = tipos de atributos que tiene el establecimiento
nombre_atributos = nombre el atributo como se llama por eje patio techado
descripcion_atributos = es la descripcion del atribitos metros cuadras o las medias de un gimnacio

-->
<div class=" card-panel">
    <form class="col l12" id="tipo_atributo_establecimiento">
        <div class="row">
            <h4 class="header">Crear atributos de establecimiento   </h4>
            <p class="left"> Se crean los atributos del establecimiento como se tiene patio, gimnacio, educacion basica, media y otros  </p>
        </div>
        <div class="row">
            <i class="mdi-social-group prefix"></i>
            <label>Tipos de atributos</label>
            <select  name="tipo_atributo" id="tipo_atributo">
                <option>ESTADO</option>
                <option>NUMERICO</option>
                <option>TEXTO</option>

            </select>
        </div>
        <div class="row">
            <div class="col l12">
                <i class="mdi-editor-mode-edit prefix"></i>
                <label for="tipo">Nombre atributos</label>
                <input id="nombre_atributos" type="text" name="nombre_atributos" class="validate">
            </div>
        </div>
        <div class="row">
            <div class="col l12">
                <i class="mdi-editor-mode-edit prefix"></i>
                <label for="descripcion">Descripcion atributos</label>
                <textarea id="descripcion_atributos"  name="descripcion_atributos" class="materialize-textarea"></textarea>
            </div>
        </div>

        <div class="row">
            <div class="input-field col s12">
                <a href="#" onclick="insert_tipo_agrupacion()"  class="btn waves-effect waves-light  col s12"> Crear Atributos del Establecimiento</a>
            </div>
        </div>
    </form>
</div>
<script type="text/javascript">
    function insert_tipo_agrupacion(){
        $.post('php/db/insert/atributo_establecimiento.php',$("#tipo_atributo_establecimiento").serialize(),
            function (data) {
                if(data !== 'ERROR_SQL'){
                    loadListaConfig_establecimiento();
                }
            });
    }
</script>