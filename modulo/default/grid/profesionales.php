<script type="text/javascript">
    $(document).ready(function () {
        // prepare the data
        var source =
            {
                url: 'json/profesionales.php',
                datatype: "json",
                root: 'Rows',
                datafields:
                    [
                        {name: 'id_profesional', type: 'string'},
                        {name: 'codigo', type: 'number'},
                        {name: 'rut', type: 'string'},
                        {name: 'nombre', type: 'string'},
                        {name: 'email', type: 'string'},
                        {name: 'tipo', type: 'string'},
                        {name: 'vencimiento', type: 'string'},
                        {name: 'link', type: 'string'}
                    ],
                cache: false
            };

        var dataAdapter = new $.jqx.dataAdapter(source);
        var cell_link = function (row, columnfield, value, defaulthtml, columnproperties, rowdata) {

            return ' <i class="mdi-communication-contacts blue-text tiny center center-align" style="cursor:pointer;" ' +
                'onclick="boxInfoProfesional(\''+value+' \')"></i>';
        };
        var classCentro = function (row, datafield, value, rowdata) {
            return 'center green';
        };



        $("#grid").jqxGrid(
            {
                width: '100%',
                height:alto-290,
                source: dataAdapter,
                columnsresize: true,
                sortable: true,
                filterable: true,
                filtermode: 'excel',
                autoshowfiltericon: false,
                showfilterrow: true,
                pageable: true,
                rowsheight: 25,
                pagesize: 20,
                pagesizeoptions: ['20', '30', '100'],
                columns: [
                    { text: '  ', datafield: 'id_profesional', width: 20 ,filterable: false,cellsrenderer: cell_link,cellclassname: classCentro},
                    { text: 'CODIGO', datafield: 'codigo', width: 60 ,filterable: false},
                    { text: 'Tipo', datafield: 'tipo',filtertype: 'checkedlist', width: 250 },
                    { text: 'Rut', datafield: 'rut', width: 110},
                    { text: 'Nombre Completo', datafield: 'nombre', width: 300},
                    { text: 'E-MAIL', datafield: 'email', width: 200},
                    { text: 'Hasta', datafield: 'vencimiento', width: 120},


                ]
            });

        $("#excelExport").click(function () {
            $("#grid").jqxGrid('exportdata', 'xls', 'Profesionales', true,null,true, 'excel/save-file.php');

        });
    });

    function boxInfoProfesional(id){
        $.post('modal/profesional.php',{
            id:id
        },function(data){
            if(data !== 'ERROR_SQL'){
                $("#modal").html(data);
                document.getElementById("btn-modal").click();
            }
        });
    }


</script>
<div class="row">
    <div class="col l12 m12 s12 right-align">
        <input type="button" class="btn  green lighten-3"
               value="Exportar a Excel" id='excelExport' />
    </div>
</div>
<div class="row">
    <div class="col l12 m12 s12">
        <div id="grid"></div>
    </div>
</div>