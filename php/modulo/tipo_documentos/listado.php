<script type="text/javascript">
    $(document).ready(function () {
        // prepare the data
        var source =
            {
                url: 'php/json/tipo_documentos/listado.php',
                datatype: "json",
                root: 'Rows',
                datafields:
                    [
                        {name: 'nombre', type: 'string'},
                        {name: 'texto', type: 'string'},
                        {name: 'edit', type: 'string'},
                        {name: 'delete', type: 'string'},
                        {name: 'info', type: 'string'}
                    ],
                cache: false
            };
        var dataAdapter = new $.jqx.dataAdapter(source);

        $("#grid").jqxGrid(
            {
                width: '100%',
                source: dataAdapter,
                columnsresize: true,
                sortable: true,
                filterable: true,
                filtermode: 'excel',
                autoshowfiltericon: false,
                showfilterrow: true,
                pageable: true,
                rowsheight: 20,
                pagesize: 20,
                pagesizeoptions: ['20', '30', '100'],
                columns: [
                    { text: '##', datafield: 'info', width: 40, cellsalign: 'center',filterable: false, pinned: true },
                    { text: 'Nombre Tipo', datafield: 'nombre', width: 180, pinned: true },
                    { text: 'Descripcion', datafield: 'texto'},
                    { text: '', datafield: 'edit', width: 20,filterable: false, cellsalign: 'center'},
                    { text: '', datafield: 'delete', width: 20,filterable: false, cellsalign: 'center'},

                ]
            });
    });
</script>
<div class="card-panel">
    <div class="row">
        <div class="col l8">
            <div id="grid"></div>
        </div>
        <div class="col l4">
            <h5>Listado de Tipos de Documentos</h5>
            <p>En esta sección el usuario podra indicar al sistema que tipos de documentos
                podran cargar los establecimientos.
            </p>
            <p>
                Estos documentos podrán ser utilizados como referencia al momento de querer subir un
                archivo al sistema.
            </p>
        </div>
    </div>

</div>

