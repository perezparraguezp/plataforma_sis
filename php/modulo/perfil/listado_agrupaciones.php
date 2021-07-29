<script type="text/javascript">
    $(document).ready(function () {
        // prepare the data
        var source =
            {
                url: 'php/json/perfil/listado_agrupaciones.php',
                datatype: "json",
                root: 'Rows',
                datafields:
                    [
                        {name: 'desde', type: 'string'},
                        {name: 'hasta', type: 'string'},
                        {name: 'tipo', type: 'string'},
                        {name: 'info', type: 'string'},
                        {name: 'estado', type: 'string'}
                    ],
                cache: false
            };
        var cellsrenderer = function (row, columnfield, value, defaulthtml, columnproperties, rowdata) {
            if (value === 'VIGENTE') {
                return '<span style="color: green;width: 100%;text-align: center;">' + value + '</span>';
            }
            else {
                return '<span style="color: red;width: 100%;text-align: center;">' + value + '</span>';
            }
        }
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
                    { text: '##', datafield: 'info', width: 40,filterable: false, pinned: true , cellsalign: 'center'},
                    { text: 'Tipo Agrupacion', datafield: 'tipo',filtertype: 'checkedlist'},
                    { text: 'Desde', datafield: 'desde', width: 100 , align: 'center'},
                    { text: 'Hasta', datafield: 'hasta', width: 100, align: 'center'},
                    { text: 'Estado', datafield: 'estado', width: 120 ,filtertype: 'checkedlist', cellsrenderer: cellsrenderer, align: 'center'}

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
            <h5>Listado de Agrupaciones Escolares</h5>
            <p>El listado muestra el historial de todas las agrupaciones
            que han pasado por el establecimiento eduacional.</p>
            <p>
                <button class="btn" onclick="loadForm_newAgrupacion()">AGREGAR NUEVO</button>
            </p>
            <p>
                <input type="button" class="btn green" value="Exportar a Excel" id='excelExport' />
            </p>
        </div>
    </div>

</div>

