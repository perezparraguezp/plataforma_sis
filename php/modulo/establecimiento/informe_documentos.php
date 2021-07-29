<?php
include "../../config.php";
$sql = "SELECT count(*) as total FROM documento_establecimiento;";
$row = mysql_fetch_array(mysql_query($sql));
if($row){
    $total_doc = $row['total'];
}else{
    $total_doc = 0;
}
?>
<script type="text/javascript">
    $(document).ready(function () {
        // prepare the data
        var source =
            {
                url: 'php/json/establecimiento/lista_documentos.php',
                datatype: "json",
                root: 'Rows',
                datafields:
                    [
                        {name: 'comuna', type: 'string'},
                        {name: 'establecimiento', type: 'string'},
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
                    { text: 'Comuna', datafield: 'comuna',filtertype: 'checkedlist', width: 150},
                    { text: 'Establecimiento', datafield: 'establecimiento',filtertype: 'checkedlist'},
                    { text: 'Documento', datafield: 'tipo',filtertype: 'checkedlist'},
                    { text: 'link', datafield: 'link', width: 50 , align: 'center',cellsrenderer: cell_link,filterable:false}

                ]
            });
        $("#excelExport").jqxButton();
        $("#excelExport").click(function () {
            $("#grid").jqxGrid('exportdata', 'xls', 'jqxGrid');
        });


        //graficos
        loadGRafico_documentos_por_comuna();
        loadGRafico_total_documento();



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
    function loadGRafico_documentos_por_comuna(){
        $.post('php/graficos/documentos_por_comuna.php',$("#form_grafico1").serialize(),function(data){
            if(data !== 'ERROR_SQL'){
                $("#grafico1").html(data);
            }
        });
    }
    function loadGRafico_total_documento(){
        $.post('php/graficos/total_documentos_por_comuna.php',$("#form_grafico2").serialize(),function(data){
            if(data !== 'ERROR_SQL'){
                $("#grafico2").html(data);
            }
        });
    }
</script>
<div class="card-panel">
    <div class="row">
        <div class="col l4">
            <input type="button" class="btn green" value="Exportar a Excel" id='excelExport' />
        </div>
        <div class="col l8">
            <label>Total Documentos: <strong><?php echo $total_doc; ?></strong></label>
        </div>
    </div>
</div>
<div class="card-panel">
    <div class="row">
        <div id="grid"></div>
    </div>
</div>
<div class="card-panel col l12" style="padding: 10px;" id="grafico1">
    <form id="form_grafico1"></form>
</div>
<div class="card-panel col l12" style="padding: 10px;" id="grafico2">
    <form id="form_grafico2"></form>
</div>

