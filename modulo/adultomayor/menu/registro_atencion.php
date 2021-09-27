<?php
$rut = $_POST['rut'];
?>

<div class="col l12 m12 s12">
    <div id='tabs_formulario_registro'
         style="font-size: 0.8em;">
        <ul>
            <li style="margin-left: 30px;text-align: center">REGISTRO POR RUT</li>
        </ul>
        <div style="padding-left: 10px;">
            <!-- REGISTRO POR RUT -->
            <div id="form_busqueda">

                <div class="row center center-align" style="width: 95%;">
                    <div class="row">
                        <div class="col l12 m12 s12">
                            <p>Ingrese el RUT del paciente para comenzar con el registro.</p>
                        </div>
                    </div>
                    <div class="row" style="width: 40%;">
                        <div class="col l12 m12 s12">
                            <label for="rut_paciente">PACIENTE</label>
                            <input id="rut_paciente"
                                   name="rut_paciente"
                                   onchange="validar_Rut()"
                                   value="<?php echo $rut; ?>"
                                   type="text" required placeholder="Ej. 11222333-4" />
                        </div>
                    </div>
                    <div class="row" style="width: 40%;">
                        <div class="col l12 m12 s12">
                            <label for="fecha_registro">FECHA REGISTRO</label>
                            <input type="date"
                                   style="font-size: 0.9em"
                                   value="<?php echo date('Y-m-d'); ?>"
                                   id="fecha_registro" name="fecha_registro"  />
                        </div>
                    </div>
                    <div class="row" style="width: 40%;">
                        <div class="col l12 m12 s12">
                            <button class="btn" onclick="loadFormRegistro_AtencionAM()">
                                <i class="mdi-av-my-library-books right"></i>
                                COMENZAR REGISTRO
                            </button>

                        </div>
                    </div>
                </div>
                <div class="row">
                    <div id="div_listado_pacientes_dsm"></div>
                </div>
            </div>


            <div id="form_ficha">
                <div class="row">
                    <div class="col l12" id="registroFichaLocal">

                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<script type="text/javascript">
    $(function(){
        $('#tabs_formulario_registro').jqxTabs({
            width: '100%', height: 800,
            position: 'top',
            theme: 'eh-open',
            scrollPosition: 'both'});
        $("#rut_paciente").jqxInput(
            {
                width:'100%',
                theme: 'eh-open',
                height:25,
                placeHolder: "BUSCAR PACIENTE",
                source: function (query, response) {
                    var dataAdapter = new $.jqx.dataAdapter
                    (
                        {
                            datatype: "json",
                            datafields: [
                                { name: 'rut', type: 'string'},
                                { name: 'nombre', type: 'string'}
                            ],
                            url: 'json/autocomplete_pacientes.php'
                        },
                        {
                            autoBind: true,
                            formatData: function (data) {
                                data.query = query;
                                return data;
                            },
                            loadComplete: function (data) {
                                if (data.length > 0) {
                                    response($.map(data, function (item) {
                                        return item.rut+ ' | '+item.nombre;
                                    }));
                                }
                            }
                        }
                    );
                }
            }
        );
    });


    function validar_Rut(){


    }

    function loadFormRegistro_AtencionAM() {

        var rut = $("#rut_paciente").val();
        if(rut !== ''){
            $.post('formulario/atencion_paciente.php',{
                rut: rut,
                fecha_registro:$("#fecha_registro").val()
            },function(data){
                $("#form_busqueda").hide();
                $("#registroFichaLocal").html(data);
                $("#form_ficha").show();
            });
        }else{
            alertaLateral('DEBE INGRESAR UN RUT PARA REALIZAR LA BUSQUEDA');
        }
    }
    function volverFichaSearch_1(){
        $("#form_ficha").hide();
        $("#form_busqueda").show();
    }
    function loadForm_offline(){
        $.post('php/formulario/registro_ficha/registro_offline.php',{

        },function(data){
            $("#div_registro_importacion").html(data);
        });
    }

    function loadForm_gridPacientes_pcvc(){
        $.post('grid/pacientes.php',
            {},function (data){
                $("#div_listado_pacientes_dsm").html(data);
            });
    }


</script>