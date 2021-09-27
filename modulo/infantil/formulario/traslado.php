<?php
/**
 * Created by PhpStorm.
 * User: ipapo
 * Date: 7/13/20
 * Time: 10:18 AM
 */
?>
<form action="pdf/traslado.php"
      method="post"
      target="_blank"
      class="container" >
    <div class="card-panel">
        <div class="row center-align">
            <div class="col L12 m12 s12 center">
                <label for="rut_paciente">BUSCAR PACIENTE</label>
                <input type="text" name="rut_paciente" id="rut_paciente" />
            </div>
        </div>
        <div class="row center-align">
            <div class="col l6 s6 m6">
                <input type="radio" name="tipo_informe" id="traslado_input" value="TRASLADO" checked />
                <label class="black-text" for="traslado_input">TRASLADO</label>
            </div>
            <div class="col l6 s6 m6">
                <input type="radio" name="tipo_informe" id="alta_input" value="ALTA" />
                <label class="black-text" for="alta_input">ALTA</label>
            </div>
        </div>
        <div class="row center-align">
            <div class="col L12 m12 s12 center">
                <input type="submit"
                       class="btn"
                       style="width: 100%;"
                       value="GENERAR INFORME" />
            </div>
        </div>
    </div>
</form>
<script type="text/javascript">
    $(function () {

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


</script>