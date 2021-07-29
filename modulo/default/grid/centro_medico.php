<script type="text/javascript">
    $(document).ready(function () {
        // prepare the data
        var source =
            {
                url: 'json/centros_medicos.php',
                datatype: "json",
                root: 'Rows',
                datafields:
                    [
                        {name: 'codigo', type: 'string'},
                        {name: 'centro', type: 'string'},
                        {name: 'direccion', type: 'string'},
                        {name: 'sector_comunal', type: 'string'},
                        {name: 'link', type: 'string'},
                        {name: 'borrar', type: 'string'},
                    ],
                cache: false
            };
        var dataAdapter = new $.jqx.dataAdapter(source);


        var cell_borrar = function (row, columnfield, value, defaulthtml, columnproperties, rowdata) {
            return ' <i class="mdi-action-delete red-text tiny" style="cursor:pointer;" ' +
                'onclick="delete_centro_interno(\''+value+' \')"></i>';
        };

        var cell_link = function (row, columnfield, value, defaulthtml, columnproperties, rowdata) {
            return ' <i class="mdi-communication-contacts green-text tiny" style="cursor:pointer;" ' +
                'onclick="boxInfoCentroInterno(\''+value+' \')"></i>';

        }
        var cell_css_borrar = function(){
            return 'center';
        }
        $("#grid").jqxGrid(
            {
                width: '100%',
                source: dataAdapter,
                height:alto-300,
                columnsresize: true,
                sortable: true,
                filterable: true,
                filtermode: 'excel',
                autoshowfiltericon: false,
                showfilterrow: true,
                statusbarheight: 30,
                showaggregates: true,
                rowsheight: 40,
                showstatusbar: true,
                columns: [
                    { text: ' ', datafield: 'link', width: 100 , aggregates: ['count'],

                        cellsalign: 'right',
                        cellsrenderer: cell_link,
                        filterable:false},

                    { text: 'SECTOR COMUNAL', datafield: 'sector_comunal', width: 200 },
                    { text: 'Establecimiento', datafield: 'centro', width: 300},
                    { text: 'Direccion', datafield: 'direccion' },
                    { text: ' ', datafield: 'borrar', width: 50 ,
                        cellsalign: 'center',
                        cellclassname:cell_css_borrar,
                        cellsrenderer: cell_borrar,
                        filterable:false},


                ]
            });
        //$("#excelExport").jqxButton();
        $("#excelExport").click(function () {
            $("#grid").jqxGrid('exportdata', 'xls', 'jqxGrid');
        });
    });
    function boxInfoCentroInterno(id){
        $.post('modal/centro_medico.php',{
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
            $.post('db/delete/centro_interno.php',{
                id:id
            },function(data){
                if(data !== 'ERROR_SQL'){
                    var texto = 'REGISTRO ELIMINADO: EL REGISTRO FUE ELIMINADO CON EXITO';
                    alertaLateral(texto);
                    loadListaConfig_establecimiento();
                }
            });
        }
    }

    function loadForm_newCentro(){
        var div = 'contenido_menu';
        loading(div);
        $.post('formulario/ingreso_centro.php',{

        },function(data){
            if(data !=='ERROR_SQL' ){
                $("#"+div).html(data);



            }else{

            }
        });
    }
</script>
<div class="card-panel">
    <div class="row">
        <div class="col l8 m6 s12">
            <button class="btn col l12" onclick="loadForm_newCentro()">AGREGAR NUEVO ESTABLECIMIENTO</button>
        </div>
        <div class="col l4 m6 s12">
            <input type="button" class="btn green col l12 white-text" value="Exportar a Excel" id='excelExport' />
        </div>
    </div>
    <div class="row">
        <h5>CENTROS MEDICOS DE SALUD</h5>
    </div>
    <div class="row margin"></div>
    <div class="row">
        <div class="col l12 m12 s12">
            <div id="grid"></div>
        </div>
    </div>
</div>

