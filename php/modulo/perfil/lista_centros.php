<script type="text/javascript">
    $(document).ready(function () {
        // prepare the data
        var source =
            {
                url: 'php/json/perfil/lista_centros.php',
                datatype: "json",
                root: 'Rows',
                datafields:
                    [
                        {name: 'codigo', type: 'string'},
                        {name: 'centro', type: 'string'},
                        {name: 'direccion', type: 'string'},
                        {name: 'link', type: 'string'},
                        {name: 'borrar', type: 'string'},
                    ],
                cache: false
            };
        var dataAdapter = new $.jqx.dataAdapter(source);


        var cell_borrar = function (row, columnfield, value, defaulthtml, columnproperties, rowdata) {
            return '<i class="mdi-action-delete red-text tiny center-align center" style="cursor:pointer;" ' +
                'onclick="delete_centro_interno(\''+value+' \')"></i>';
        };

        var cell_link = function (row, columnfield, value, defaulthtml, columnproperties, rowdata) {
            return '<i class="mdi-action-search blue-text tiny center-align center" style="cursor:pointer;" ' +
                'onclick="boxInfoCentroInterno(\''+value+' \')"></i>';
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
                    { text: ' ', datafield: 'link', width: 50 ,cellsalign: 'center',cellsrenderer: cell_link,filterable:false},
                    { text: 'COD. CENTRO', datafield: 'codigo', width: 100 ,cellsalign: 'center'},
                    { text: 'Centro', datafield: 'centro',filtertype: 'checkedlist'},
                    { text: 'Direccion', datafield: 'direccion', width: 300 },
                    { text: ' ', datafield: 'borrar', width: 50 , cellsalign: 'center',cellsrenderer: cell_borrar,filterable:false},


                ]
            });
        //$("#excelExport").jqxButton();
        $("#excelExport").click(function () {
            $("#grid").jqxGrid('exportdata', 'xls', 'jqxGrid');
        });
    });
    function boxInfoCentroInterno(id){
        $.post('php/modal/perfil/centro_interno.php',{
            id:id
        },function(data){
            if(data !== 'ERROR_SQL'){
                $("#modal").html(data);
                document.getElementById("btn-modal").click();
            }
        });
    }
    function delete_centro_interno(id){
        if(confirm("Seguro que desea eliminar este centro de salud")){
            $.post('php/db/delete/centro_interno.php',{
                id:id
            },function(data){
                if(data !== 'ERROR_SQL'){
                    var texto = 'REGISTRO ELIMINADO: EL REGISTRO FUE ELIMINADO CON EXITO';
                    alertaLateral(texto);
                    loadGrid_Centros();
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
            <h5>Listado de Establecimientos</h5>
            <p>Este listado ofrece al usuario la posibilidad de Administrar los diferentes Establecimientos existentes
                en su Administración.</p>
            <p>
                <button class="btn col l12" onclick="loadForm_newCentro()">AGREGAR NUEVO ESTABLECIMIENTO</button>
            </p>
            <p>
                <!--
                <button class="btn col l12" onclick="loadForm_newSectorCentro()">AGREGAR NUEVO SECTOR</button>
                -->
            </p>
            <p>
                <input type="button" class="btn green col l12 white-text" value="Exportar a Excel" id='excelExport' />
            </p>
        </div>
    </div>

</div>

