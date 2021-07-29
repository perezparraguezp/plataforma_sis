<script type="text/javascript">
    $(document).ready(function() {
        var source =
            {
                url: 'php/json/establecimiento/listado.php',
                datatype: "json",
                root: 'Rows',
                datafields:
                    [
                        {name: 'comuna', type: 'string'},
                        {name: 'nombre', type: 'string'},
                        {name: 'tipo', type: 'string'},
                        {name: 'info', type: 'string'}
                    ],
                cache: false
            };
        var cellsrenderer = function (row, columnfield, value, defaulthtml, columnproperties, rowdata) {
            if (value === 'NO') {
                return '<span style="color: #ff0000;width: 100%;text-align: center;">' + value + '</span>';
            }
            else {
                return '<span style="color: #008000;width: 100%;text-align: center;">' + value + '</span>';
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
                    { text: 'Comuna', datafield: 'comuna', width: 180,filtertype: 'checkedlist', pinned: true },
                    { text: 'Establecimiento', datafield: 'nombre', pinned: true},
                    { text: 'Tipo', datafield: 'tipo', width: 120 ,filtertype: 'checkedlist', cellsalign: 'center'}

                ]
            });
        $("#excelExport").jqxButton();
        $("#excelExport").click(function () {
            $("#grid").jqxGrid('exportdata', 'xls', 'jqxGrid');
        });
    });
    function boxInfoEstablecimiento(id){
        $.post('php/modal/establecimiento/informacion.php',{
            id:id
        },function(data){
            if(data !== 'ERROR_SQL'){
                $("#modal").html(data);
                document.getElementById("btn-modal").click();
            }
        });
    }
    function cargarListado(){
        // prepare the data

    }
</script>
<div class="card-panel">
    <div class="row">
        <input type="button" value="Exportar a Excel" id='excelExport' />
    </div>
    <div class="row margin"></div>
    <div class="row col l12">
        <div id="grid"></div>
    </div>

</div>

