<script type="text/javascript">
    $(document).ready(function () {
        // prepare the data
        var source =
            {
                url: 'php/json/tipo_agrupacion/listado.php',
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
            <h5>Listado de Tipos de Agrupaciones</h5>
            <p>
                En esta sección se podra indicar cuales son las distintas agrupaciones que puede tener
                algun establecimiento educacional.
            </p>

        </div>
    </div>

</div>

