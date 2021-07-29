<!--
formulario tipo de agrupacion

sirve para crear los diferentes tipos de agrupaciones que se cargaran posteriormente

variables
tipo = indica el nombre del tipo a crear
descripcion = tipo de agrupacion


-->
<div class=" card-panel">
    <form class="col l12" id="form_tipo_agrupacion">
        <div class="row">
            <h4 class="header">Crear tipo de agrupacion  </h4>
            <p class="left"> Se crear los tipos de agrupacion como centro de padres, centro de alumnos, otros</p>
            <div class="input-field col l10">
                <i class="mdi-editor-mode-edit prefix"></i>
                <label for="tipo">Tipo de agrupacion </label>
                <input id="tipo" type="text" name="tipo" class="validate">
            </div>
        </div>
        <div class="row">
            <div class=" input-field col l10">
                <i class="mdi-editor-mode-edit prefix"></i>
                <label for="descripcion">Descripcion</label>
                <textarea id="descripcion"  name="descripcion" class="materialize-textarea"></textarea>
            </div>
        </div>
        <div class="row">
            <div class="input-field col s12">
                <a href="#" onclick="insertTipoAgrupacion()"  class="btn waves-effect waves-light col s12"> Crear Tipo de agrupacion</a>
            </div>
        </div>
    </form>
</div>
<script type="text/javascript">
    function insertTipoAgrupacion() {
        $.post('php/db/insert/tipo_agrupacion.php',$("#form_tipo_agrupacion").serialize(),
            function (data) {
                if(data !== 'ERROR_SQL'){
                    loadListadoTipoAgrupacion();
                }
            });
    }
</script>