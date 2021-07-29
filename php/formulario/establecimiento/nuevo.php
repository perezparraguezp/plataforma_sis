<div class=" card-panel">
    <form class="col l12" id="form_newEstablecimiento">
        <div class="row">
            <label>Región</label>
            <select  name="region" id="region">
                <option disabled selected>Seleccionar</option>
                <?php
                include "../../config.php";
                $sql1 = "select * from regiones order by id asc";
                $res1 = mysql_query($sql1);
                while ($row1 = mysql_fetch_array($res1)){
                    ?>
                    <option value="<?php echo $row1['id']; ?>"><?php echo utf8_decode($row1['region']); ?></option>
                <?php
                }
                ?>
            </select>
            <script type="text/javascript">
                $(function(){
                    $("#region").jqxDropDownList({
                        width: '100%', height: 30});

                    $("#region").on('change',function(){
                        var region = $("#region").val();
                        $.post('php/ajax/select/provincias.php',{
                            region:region
                        },function(data){
                            $("#provincia").html(data);

                            $("#provincia").jqxDropDownList({
                                width: '100%', height: 30});

                            $("#provincia").on('change',function(){
                                var region = $("#region").val();
                                var provincia = $("#provincia").val();

                                $.post('php/ajax/select/comunas.php',{
                                    region:region,
                                    provincia:provincia
                                },function(data){
                                    $("#comuna").html(data);
                                    $("#comuna").jqxDropDownList({
                                        width: '100%', height: 30});
                                });
                            });
                        });
                    });

                });
            </script>
        </div>
        <div class="row">
            <label>Provincia</label>
            <select  name="provincia" id="provincia">

            </select>
        </div>
        <div class="row">
            <label>Comuna</label>
            <select  name="comuna" id="comuna">

            </select>
        </div>
        <div class="row">
            <label>Tipo de Establecimiento</label>
            <select class="browser-default" name="tipo" id="tipo">
                <option>MUNICIPAL</option>
                <option>PRIVADO</option>
                <option>PARTICULAR</option>
            </select>
        </div>
        <div class="row">
            <div class="input-field col l10">
                <i class="mdi-social-person prefix"></i>
                <input id="rut_es" type="text" onkeypress="return soloLetras(event)" class="atributosText" name="rut_es">
                <label for="rut_es">RUT</label>
            </div>
        </div>
        <div class="row">
            <div class="input-field col l10">
                <i class="mdi-social-person prefix"></i>
                <input id="nombre_es" type="text" onkeypress="return soloLetras(event)" class="atributosText" name="nombre_es">
                <label for="nombre_es">Nombre</label>
            </div>
        </div>
        <div class="row">
            <div class="input-field col l10">
                <i class="mdi-maps-directions prefix"></i>
                <input id="dire_es" type="text" onkeypress="return soloLetras(event)" class="atributosText" name="dire_es">
                <label for="dire_es">Direccion</label>
            </div>
        </div>
        <div class="row">
            <div class="input-field col l10">
                <i class="mdi-content-mail prefix"></i>
                <input id="mail_es" type="text" onkeypress="return soloLetras(event)" class="atributosText" name="mail_es">
                <label for="mail_es">Mail</label>
            </div>
        </div>
        <div class="row">
            <div class="input-field col l10">
                <i class="mdi-communication-phone prefix"></i>
                <input id="tel_es" type="text" onkeypress="return soloLetras(event)" class="atributosText" name="tel_es">
                <label for="tel_es">Telefono</label>
            </div>
        </div>
        <div class="row">
            <div class="input-field col l10">
                <div class="col l1"><i class="mdi-action-today"></i></div>
                <div class="col l3">Fecha Creacion</div>
                <div class="col l8">
                    <div id="fecha" name="fecha"></div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="input-field col s12">
                <a href="#" onclick="insertEstablecimiento()" class="btn waves-effect waves-light  col s12"> Crear Centro de Salud</a>
            </div>
        </div>
    </form>
</div>
<script type="text/javascript">
    function insertEstablecimiento() {
        $.post('php/db/insert/establecimiento.php',$("#form_newEstablecimiento").serialize(),
            function (data) {
                if(data !== 'ERROR_SQL'){
                    loadListadoEstablecimientos();
                }
            });
    }
    $(function(){

        $('#rut_es').Rut({
            on_error: function() {
                $(this).focus();
                //alert('Rut incorrecto');
                alertaLateral('RUT INCORRECTO');
                $('#rut_es').val('');
                $(this).css({
                    "border": "solid red 1px"
                });
            }
        });
    })
</script>
