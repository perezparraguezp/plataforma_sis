<script type="text/javascript">
    $(document).ready(function () {
        // prepare the data
        var source =
            {
                url: 'php/json/perfil/lista_atributo.php',
                datatype: "json",
                root: 'Rows',
                datafields:
                    [
                        {name: 'tipo', type: 'string'},
                        {name: 'atributo', type: 'string'},
                        {name: 'valor', type: 'string'},
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
                    { text: 'Tipo', datafield: 'tipo',filtertype: 'checkedlist', width: 100 },
                    { text: 'Atributo', datafield: 'atributo'},
                    { text: 'Valor', datafield: 'valor', width: 80 },

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
    function loadForm_updateAtributo(){
        var div = 'contenido_menu';
        loading(div);
        $.post('php/formulario/perfil/ingreso_atributo.php',{

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
                $('#tipo_atributo').on('select', function (event) {
                    var args = event.args;
                    var item = $('#tipo_atributo').jqxDropDownList('getItem', args.index);
                    if (item != null) {
                        loadDataFormulario_atributo($("#tipo_atributo").val());
                    }
                });
            }else{

            }
        });

    }
    function loadDataFormulario_atributo(id){
        loading('data_form');
        $.post('php/formulario/perfil/dato_atributo.php',{
                atributo:id
            },
            function (data) {
                if(data !== 'ERROR_SQL'){
                    $("#data_form").html(data);
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
            <h5>Tipos de Profesionales</h5>
            <p>Este listado permite al Administrador la habilitacion de Perfiles de Usuarios para cada uno de los Profesionales a interactuar con nuestra plataforma.</p>
            <p>
                Esta información es de importancia que sea actualizada por parte
                de cada uno de los establecimientos.
            </p>
            <p>
                <button class="btn" onclick="loadForm_updateAtributo()">AGREGAR NUEVO</button>
            </p>
            <p>
                <input type="button" class="btn green" value="Exportar a Excel" id='excelExport' />
            </p>
        </div>
    </div>

</div>

