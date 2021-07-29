<!--
formulario tipo de docuemto

sirve para crear los diferentes tipos de documentos  que se cargaran posteriormente

variables
documento  = indica el tipo de documento como contratos, planos, otros
descripcion = tipo de documento creado

-->


<div class=" card-panel">
    <form class="col l12" id="form_tipo_doc">
        <div class="row">
            <h4 class="header">Crear tipo de documentos  </h4>
            <p class="left"> Se crear los tipos de documentos como contrato, planos, certificados, estatutos, otros</p>
            <div class="input-field col l10">

                <i class="mdi-editor-mode-edit prefix"></i>
                <label for="documento"> Tipo documento  </label>
                <input id="documento" type="text" name="documento" class="validate">
            </div>
        </div>
        <div class="row">
            <div class=" input-field col l10">
                <i class="mdi-editor-mode-edit prefix"></i>
                <label for="descripcion">Descripcion del documento</label>
                <textarea id="descripcion"  name="descripcion" class="materialize-textarea"></textarea>
            </div>
        </div>
        <div class="row">
            <div class="input-field col s12">
                <a href="#"  class="btn waves-effect waves-light  col s12" onclick="insertTipoDoc()"> Crear Tipo de documento</a>
            </div>
        </div>
    </form>
</div>
<script type="text/javascript">
    function insertTipoDoc() {
        $.post('php/db/insert/tipo_documento.php',$("#form_tipo_doc").serialize(),
        function (data) {
            if(data !== 'ERROR_SQL'){
                loadListadoTipoDoc();
            }
        });
    }
</script>