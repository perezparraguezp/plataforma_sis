<script type="text/javascript">
    $(document).ready(function () {
        // prepare the data
        var source =
            {
                url: 'php/json/perfil/lista_documentos.php',
                datatype: "json",
                root: 'Rows',
                datafields:
                    [
                        {name: 'tipo', type: 'string'},
                        {name: 'obs', type: 'string'},
                        {name: 'borrar', type: 'string'},
                        {name: 'link', type: 'string'}
                    ],
                cache: false
            };
        var dataAdapter = new $.jqx.dataAdapter(source);

        var cell_link = function (row, columnfield, value, defaulthtml, columnproperties, rowdata) {
            return '<a href="'+value+'" download target="_blank" > VER </span>';
        }
        var cell_borrar = function (row, columnfield, value, defaulthtml, columnproperties, rowdata) {
            return '<i class="mdi-action-delete red-text tiny center-align" style="cursor:pointer;" ' +
                'onclick="delete_documento(\''+value+' \')"></i>';
        }

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
                    { text: 'Documento', datafield: 'tipo',filtertype: 'checkedlist'},
                    { text: 'Observaciones', datafield: 'obs', width: 300 },
                    { text: 'link', datafield: 'link', width: 50 , align: 'center',cellsrenderer: cell_link},
                    { text: ' ', datafield: 'borrar', width: 50 , align: 'center',cellsrenderer: cell_borrar}

                ]
            });
        $("#excelExport").jqxButton();
        $("#excelExport").click(function () {
            $("#grid").jqxGrid('exportdata', 'xls', 'jqxGrid');
        });
    });
    function boxInfoAgrupacion(id){
        $.post('php/modal/perfil/agrupacion.php',{
            id:id
        },function(data){
            if(data !== 'ERROR_SQL'){
                $("#modal").html(data);
                document.getElementById("btn-modal").click();
            }
        });
    }
    function delete_documento(id){
        if(confirm("Seguro que desea eliminar este documento del sistema")){
            $.post('php/db/delete/documento.php',{
                id:id
            },function(data){
                if(data !== 'ERROR_SQL'){
                    loadListaDocumentos();
                }
            });
        }
    }
</script>
<div class="card-panel">
    <div class="row">

    </div>
    <div class="row margin"></div>
    <div class="row">
        <div class="col l8">
            <div id="grid"></div>
        </div>
        <div class="col l4">
            <h5>Listado de Documentos</h5>
            <p>Este listado permite dar a conocer toda la informacion
                propia del establecimiento.</p>
            <p>
                Es de importancia saber que esta información es cargada unicamente
                por los responsables del establecimiento.
            </p>
            <p>
                <button class="btn" onclick="loadForm_newDocumento()">AGREGAR NUEVO</button>
            </p>
            <p>
                <input type="button" class="btn green" value="Exportar a Excel" id='excelExport' />
            </p>
        </div>
    </div>

</div>

