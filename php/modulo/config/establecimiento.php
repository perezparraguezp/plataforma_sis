<script type="text/javascript">
    $(document).ready(function () {
        // prepare the data
        var source =
            {
                url: 'php/json/config/atributos_establecimiento.php',
                datatype: "json",
                root: 'Rows',
                datafields:
                    [
                        {name: 'tipo', type: 'string'},
                        {name: 'nombre', type: 'string'},
                        {name: 'texto', type: 'string'},
                        {name: 'estado', type: 'string'},
                        {name: 'borrar', type: 'string'}
                    ],
                cache: false
            };
        var dataAdapter = new $.jqx.dataAdapter(source);

        var estado_atributo = function (row, columnfield, value, defaulthtml, columnproperties, rowdata) {
            if(value ==='ACTIVO'){
                return '<strong class="green-text">'+value+'</strong>';
            }else{
                return '<strong class="red-text">'+value+'</strong>';
            }

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
                autorowheight: true,
                pagesize: 20,
                pagesizeoptions: ['20', '30', '100'],
                columns: [
                    { text: ' ', datafield: 'borrar', width: 50 , align: 'center',filterable: false},
                    { text: 'Nombre Atributos', datafield: 'nombre', width: 200 },
                    { text: 'Tipo de Atributo', datafield: 'tipo',filtertype: 'checkedlist', width: 120 },
                    { text: 'Descripcion', datafield: 'texto', width: 300 },
                    { text: 'Estado', datafield: 'estado', width: 100 ,cellsrenderer: estado_atributo}

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
    function loadForm_newTipoAributoEstablecimiento(){
        var div = 'contenido_menu';
        loading(div);
        $.post('php/formulario/config/nuevo_tipo_atributo_establecimiento.php',{

        },function(data){
            if(data !=='ERROR_SQL' ){
                $("#"+div).html(data);
                $('#tipo_atributo').jqxDropDownList({
                    theme: 'energyblue',
                    filterable: true,
                    filterPlaceHolder: "Buscar",
                    width: '100%',
                    height: '25px'
                });
            }else{

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
            <h5>Atributos por Establecimiento</h5>
            <p>Este listado muestra todos los atributos que son requeridos por el establecimiento.</p>
            <p>
                <strong>NOTA:</strong> Los Atributos no pueden ser eliminados, solo pueden ser dados de
                baja por no uso.
            </p>
            <p>
                <button class="btn" onclick="loadForm_newTipoAributoEstablecimiento()">AGREGAR NUEVO</button>
            </p>
            <p>
                <input type="button" class="btn green" value="Exportar a Excel" id='excelExport' />
            </p>
        </div>
    </div>

</div>

