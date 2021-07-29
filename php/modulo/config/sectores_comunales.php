<script type="text/javascript">
    $(document).ready(function () {
        // prepare the data
        var source =
            {
                url: 'php/json/config/sectores_comunlaes.php',
                datatype: "json",
                root: 'Rows',
                datafields:
                    [
                        {name: 'codigo', type: 'string'},
                        {name: 'nombre', type: 'string'},
                        {name: 'borrar', type: 'string'},
                    ],
                cache: false
            };
        var dataAdapter = new $.jqx.dataAdapter(source);




        var cell_borrar = function (row, columnfield, value, defaulthtml, columnproperties, rowdata) {
            return '<a ' +
                'onclick="deleteSectorComunal(\''+value+'\')" ' +
                'class="btn red accent-4 white-text" style="margin-left: 10px;">BORRAR</a>';
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
                rowsheight: 40,
                pagesize: 20,
                pagesizeoptions: ['20', '30', '100'],
                columns: [
                    { text: 'CODIGO', datafield: 'codigo', width: 100 ,cellsalign: 'right'},
                    { text: 'NOMBRE', datafield: 'nombre'},
                    { text: ' ', datafield: 'borrar', width: 120 , cellsalign: 'center',cellsrenderer: cell_borrar,filterable:false},


                ]
            });
        //$("#excelExport").jqxButton();
        $("#excelExport").click(function () {
            $("#grid").jqxGrid('exportdata', 'xls', 'jqxGrid');
        });
    });
    function insertSectorComunal(){
        var nombre = $("#nombre_sector").val();
        if(nombre!==''){
            $.post('php/db/insert/sector_comunal.php',{
                nombre:nombre
            },function(data){
                alertaLateral(data);
                loadForm_sectoresComunales();
            });
        }

    }
    function deleteSectorComunal(id){
        if(confirm("Seguro que desea eliminar sector comunal")){
            $.post('php/db/delete/sector_comunal.php',{
                id:id
            },function(data){
                alertaLateral(data);
                loadForm_sectoresComunales();
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
            <div class="row">
                <div class="col l12">
                    <input type="button" class="btn green col l12 white-text" value="Exportar a Excel" id='excelExport' />
                </div>
            </div>
            <div class="row">
                <div class="col l12">
                    <div id="grid"></div>
                </div>
            </div>
        </div>
        <div class="col l4">
            <h5>Listado de Sectores de la Comuna</h5>
            <p>texto </p>
            <hr class="row" />
            <form id="form_Sector_comunal" class="col l12" style="background-color: #d7efff;border: solid 1px black;">
                <div class="row">
                    <div class="col l12">
                        <label for="nombre_sector">NOMBRE DEL SECTOR</label>
                        <input type="text" id="nombre_sector" name="nombre_sector" PLACEHOLDER="INGRESE NOMBRE SECTOR" />
                    </div>
                </div>
                <hr class="row" />
                <div class="row">
                    <div class="col l12 center-align center">
                        <input type="button" class="btn col 12" style="width: 100%;"
                               value="REGISTRAR NUEVO SECTOR COMUNAL" onclick="insertSectorComunal()" />
                    </div>
                </div>

            </form>

            <p>
                <!--
                <button class="btn col l12" onclick="loadForm_newSectorCentro()">AGREGAR NUEVO SECTOR</button>
                -->
            </p>
            <p>

            </p>
        </div>
    </div>

</div>

