<?php
$rut = $_POST['rut'];
?>

<div class="col l12 m12 s12">
    <div id='tabs_formulario_registro'
         style="font-size: 0.8em;">
        <ul>
            <li style="margin-left: 30px;text-align: center">REGISTRO POR RUT</li>
<!--            <li>IMPORTAR INFORMACION</li>-->
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
                            <button class="btn" onclick="loadFormRegistro_AtencionPCVP()">
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
<!--        <div id="div_registro_importacion">-->
<!--             REGISTRO POR IMPORTACION -->
<!---->
<!--            <div class="row" style="margin-top: 10px;">-->
<!---->
<!--                <div id="form_load_excel">-->
<!--                    <form name="frmload" id="frmload" method="post"-->
<!--                          class="card-panel center center-align"-->
<!--                          enctype="multipart/form-data">-->
<!--                        <header style="font-size: 2em;">CARGAR ARCHIVOS DE REGISTROS</header>-->
<!--                        <hr />-->
<!--                        <input type="hidden" name="batch" id="batch" />-->
<!--                        <input type="hidden" name="offset" id="offset" />-->
<!--                        <input type="hidden" name="html_error" id="html_error" />-->
<!--                        <div class="row" style="width: 50%;">-->
<!--                            <div class="col l12 m12 s12">-->
<!--                                <input type="file" name="file" id="file" />-->
<!--                            </div>-->
<!--                        </div>-->
<!--                        <div class="row" style="width: 50%;">-->
<!--                            <div class="col l12 m12 s12">-->
<!--                                <input type="button" onclick="paso1_offline_pscv()"-->
<!--                                       style="background-color: #438eb9;color: white;padding: 10px;"-->
<!--                                       value="----- IMPORTAR REGISTROS OFF-LINE -----"  />-->
<!--                            </div>-->
<!--                        </div>-->
<!--                    </form>-->
<!--                    <div id="show_excel">-->
<!--                    </div>-->
<!--                    <script type="text/javascript">-->
<!--                        function paso2_offline_pscv(offset, batch = false,html) {-->
<!--                            $("#offset").val(offset);-->
<!--                            $("#html_error").val(html);-->
<!--                            if (batch == false) {-->
<!--                                batch = parseInt($('#batch').val());-->
<!--                            } else {-->
<!--                                batch = parseInt(batch);-->
<!--                            }-->
<!---->
<!--                            if (offset == 0) {-->
<!--                                $('#form_load_excel').hide();-->
<!--                                $('#loading_seccion').show();-->
<!--                                loading_div('div_loading');-->
<!--                            }else{-->
<!--                                $("#info_loading").html('LEYENDO '+offset+' de '+(batch)+' ('+parseInt(offset*100/batch)+'%)');-->
<!--                            }-->
<!---->
<!--                            var formData = new FormData(document.getElementById("frmload"));-->
<!---->
<!--                            $("#show_excel").html('');-->
<!---->
<!--                            $.ajax({-->
<!--                                url: "../..//importar/pscv/paso2.php",-->
<!--                                type: "post",-->
<!--                                dataType: "html",-->
<!--                                data: formData,-->
<!--                                success: function(response) {-->
<!--                                    var content = JSON.parse(response);-->
<!--                                    var html = content.html;-->
<!---->
<!--                                    if ( parseInt(offset*100/batch) >= 100) {-->
<!--                                        alert('DATOS CARGADOS CON EXITO');-->
<!--                                        $('#loading_seccion').hide();-->
<!--                                        $('#content').show();-->
<!--                                        $('#form_load_excel').show();-->
<!--                                        $('#show_excel').show();-->
<!--                                        $('#show_excel').html(html);-->
<!--                                        $('#frmload' +-->
<!--                                            '')[0].reset();-->
<!--                                    } else {-->
<!--                                        var newOffset = offset + 1;-->
<!--                                        paso2_offline_pscv(newOffset, batch,html);-->
<!--                                    }-->
<!--                                    $("html_error").val(html);-->
<!--                                },-->
<!--                                error: function(XMLHttpRequest, textStatus, errorThrown) {-->
<!--                                    if (textStatus == 'parsererror') {-->
<!--                                        textStatus = 'Technical error: Unexpected response returned by server. Sending stopped.';-->
<!--                                    }-->
<!--                                    alert(textStatus);-->
<!--                                },-->
<!--                                cache: false,-->
<!--                                contentType: false,-->
<!--                                processData: false,-->
<!--                            });-->
<!---->
<!--                        }-->
<!--                        function paso1_offline_pscv(){-->
<!--                            var file = $("#file").val();-->
<!--                            if(file!==''){-->
<!--                                var formData = new FormData(document.getElementById("frmload"));-->
<!--                                $("#loading_seccion").show();-->
<!--                                $("#content").hide();-->
<!--                                $("#form_load_excel").hide();-->
<!--                                $("#show_excel").html('');-->
<!--                                $.ajax({-->
<!--                                    url: "../../importar/pscv/paso1.php",-->
<!--                                    type: "post",-->
<!--                                    dataType: "html",-->
<!--                                    data: formData,-->
<!--                                    cache: false,-->
<!--                                    contentType: false,-->
<!--                                    processData: false,-->
<!--                                })-->
<!--                                    .done(function(res){-->
<!--                                        $("#batch").val(res);-->
<!--                                        paso2_offline_pscv(0);-->
<!--                                    });-->
<!--                            }else{-->
<!--                                alertaLateral('DEBE SELECCIONAR UN ARCHIVO');-->
<!--                            }-->
<!---->
<!--                        }-->
<!---->
<!--                    </script>-->
<!--                </div>-->
<!--            </div>-->
<!--        </div>-->
    </div>
</div>

<script type="text/javascript">
    $(function(){
        $('#tabs_formulario_registro').jqxTabs({
            width: '100%',
            theme: 'eh-open',
            height: alto-150,
            position: 'top',
            scrollPosition: 'both'
        });

        $("#rut_paciente").jqxInput(
            {
                width:'100%',
                height:25,
                theme: 'eh-open',
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
    function loadFormRegistro_AtencionPCVP() {

        var rut = $("#rut_paciente").val();
        if(rut !== ''){
            $.post('formulario/atencion_paciente.php',{
                rut: rut,
                fecha_registro:$("#fecha_registro").val()
            },function(data){
                if(data!='ERROR_RUT'){
                    $("#form_busqueda").hide();
                    $("#registroFichaLocal").html(data);
                    $("#form_ficha").show();
                }else{
                    alertaLateral('EL RUT INGRESADO NO ES VALIDO');
                }

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