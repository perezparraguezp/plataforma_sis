<?php
include '../../config.php';

?>
<!--
formulario cargar docuemnto

sirve para crear los tipos de agrupaciones    que se cargaran posteriormente

variables
tipo_documento = indica el tipo de docuemto que se va a cargar
carga_docuemento  = busca el documento que se va a subir
descripcion_documento= descripcion del tipo de documento que se ingresa o los motivos del documento
-->
<div class=" card-panel">
    <form class="col l12" id="form_newDocumento" enctype="multipart/form-data" method="post">
        <div class="row">
            <h4 class="header">Cargar documentos   </h4>
            <p class="left"> se cargan los documentos como planos del establecimiento, escritutas del establecimiento y otros </p>
        </div>
        <hr />
        <div class="row" style="padding-left: 10px;">
            <label>Documento a Ingresar </label>
            <select name="tipo_documento" id="tipo_documento">
                <?php
                $sql1 = "select * from tipo_documento order by nombre_tipo_doc";
                $res = mysql_query($sql1);
                while($row = mysql_fetch_array($res)){
                    ?>
                    <option value="<?php echo $row['id_tipo_doc']; ?>"><?php echo $row['nombre_tipo_doc'];  ?></option>
                    <?php
                }
                ?>
            </select>
        </div>
        <div class="row" style="padding-left: 10px;">
            <label>Buscar Documento </label><br />
            <input type="file" name="documento" max="10"
                   id="documento" class="btn" value="EXAMINAR EN MI PC" style="width: 100%"  />

        </div>
        <div class="row">
            <div class=" input-field col l10">
                <i class="mdi-editor-mode-edit prefix"></i>
                <label for="descripcion_documento">Descripcion</label>
                <textarea id="descripcion_documento"  name="descripcion_documento" class="materialize-textarea"></textarea>
            </div>
        </div>

        <div class="row">
            <div class="input-field col s12">
                <input type="submit" value="CARGAR DOCUMENTO" class="btn waves-effect waves-light  col s12" />
            </div>
        </div>
    </form>
</div>
<script type="text/javascript">
    function insertDocumentoEstablecimiento() {
        $.post('php/db/insert/documento_establecimiento.php',$("#form_newDocumento").serialize(),
            function (data) {
                if(data !== 'ERROR_SQL'){
                    loadListaAgrupaciones();
                }
            });
    }
    $(function(){
        $("#form_newDocumento").on("submit", function(e){

            e.preventDefault();
            var f = $(this);
            var formData = new FormData(document.getElementById("form_newDocumento"));
            formData.append("dato", "valor");
            //formData.append(f.attr("name"), $(this)[0].files[0]);
            $.ajax({
                url: "php/db/insert/documento_establecimiento.php",
                type: "post",
                dataType: "html",
                data: formData,
                cache: false,
                contentType: false,
                processData: false
            })
                .done(function(res){
                    if(res !== 'ERROR_SQL' && res !== 'ERROR_DIR'){
                        loadListaDocumentos();
                    }else{
                        alert('ERROR');
                    }
                });
        });
    });
</script>