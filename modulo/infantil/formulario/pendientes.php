<?php
$rut = $_POST['rut'];
?>

<script type="text/javascript">
    $(function () {
        var source =
            {
                url: 'json/pendientes.php?rut=<?php echo $rut; ?>',
                datatype: "json",
                root: 'Rows',
                datafields:
                    [
                        {name: 'nombre', type: 'string'},
                        {name: 'edad_actual', type: 'string'},
                        {name: 'tipo', type: 'string'},
                        {name: 'indicador', type: 'string'},
                        {name: 'pendiente', type: 'string'},
                        {name: 'establecimiento', type: 'string'},
                        {name: 'profesional', type: 'string'}
                    ],
                cache: false
            };


        var dataAdapter = new $.jqx.dataAdapter(source);


        $("#grid_pendientes_paciente").jqxGrid(
            {
                width: '100%',
                theme: 'eh-open',
                source: dataAdapter,
                height:300,
                columnsresize: true,
                sortable: true,
                filterable: true,
                autoshowfiltericon: true,
                showfilterrow: true,
                showstatusbar: true,
                statusbarheight: 30,
                showaggregates: true,
                editable:true,
                columns: [
                    { text: 'Nombre Completo', datafield: 'nombre',
                        aggregates: ['count'],
                        aggregatesrenderer: function (aggregates, column, element, summaryData) {
                            var renderstring = "<div  style='float: left; width: 100%; height: 100%;'>";
                            $.each(aggregates, function (key, value) {
                                var name = 'Total Pendientes';
                                renderstring += '<div style="; position: relative; margin: 6px; text-align: right; overflow: hidden;">' + name + ': ' + value + '</div>';
                            });
                            renderstring += "</div>";
                            return renderstring;
                        }
                    },
                    { text: 'Indicador', datafield: 'tipo', width: 200 ,filtertype: 'checkedlist', cellsalign: 'center'},
                    { text: 'Pendiente', datafield: 'indicador', width: 200 ,filtertype: 'checkedlist', cellsalign: 'center'},


                ]
            });
    });
</script>
<div class="card-panel">
    <div class="row">
        <div class="col l12">
            <div class="card-panel blue lighten-4">
                <p class="black-text">EN ESTA SECCIÓN PODEMOS ENCONTRAR INFORMACIÓN SOBRE LAS PRESTACIONES QUE EL PACIENTE TIENE PENDIENTE</p>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col l12 right-align">
            <input type="button" class="btn  green lighten-3"
                   value="Exportar a Excel" id='excelExport' />
        </div>
    </div>
    <div class="row">
        <div class="col l12">
            <div id="grid_pendientes_paciente"></div>
        </div>

    </div>
</div>
