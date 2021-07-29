<div class="row">
    <div class="col l12">

    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        // prepare the data
        var source =
            {
                url: 'php/json/perfil/lista_personal.php',
                datatype: "json",
                root: 'Rows',
                datafields:
                    [
                        {name: 'rut', type: 'string'},
                        {name: 'nombre', type: 'string'},
                        {name: 'tipo', type: 'string'},
                        {name: 'vencimiento', type: 'string'},
                        {name: 'link', type: 'string'}
                    ],
                cache: false
            };
        var dataAdapter = new $.jqx.dataAdapter(source);

        var cell_link = function (row, columnfield, value, defaulthtml, columnproperties, rowdata) {
            return '<a href="#" onclick="boxInfoPersonal('+value+')"  > VER </a>';
        }


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
                    { text: 'Tipo', datafield: 'tipo',filtertype: 'checkedlist', width: 150 },
                    { text: 'Rut', datafield: 'rut', width: 100},
                    { text: 'Nombre Completo', datafield: 'nombre'},
                    { text: 'Hasta', datafield: 'vencimiento', width: 100},
                    { text: '  ', datafield: 'link', width: 50 ,filterable: false,cellsrenderer: cell_link},

                ]
            });

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
                $('#tipo_contrato').jqxDropDownList({
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
    function loadForm_newPersonal(){
        var div = 'contenido_menu';
        loading(div);
        $.post('php/formulario/config/profesional.php',{

        },function(data){
            if(data !=='ERROR_SQL' ){
                $("#"+div).html(data);
                $('#tipo_contrato').jqxDropDownList({
                    theme: 'energyblue',
                    filterable: true,
                    filterPlaceHolder: "Buscar",
                    width: '100%',
                    height: '25px'
                });
                $(".fecha").jqxDateTimeInput({ width: '300px', height: '25px' ,formatString: "yyyy-MM-dd"});
            }else{

            }
        });
    }
</script>
<div class="card-panel">
    <div class="row">
        <div class="col l8">
            <button class="btn col l12" onclick="loadForm_newPersonal()">INGRESAR NUEVO</button>
        </div>
        <div class="col l4">
            <input type="button" class="btn green col l12 white-text" value="Exportar a Excel" id='excelExport' />
        </div>
    </div>
    <div class="row">
        <h5>Listado de Profesionales</h5>
        <p>Este listado muestra a todo el personal existente en este establecimiento.</p>
    </div>
    <div class="row margin"></div>
    <div class="row">
        <div class="col l12 m12 s12">
            <div id="grid"></div>
        </div>
    </div>

</div>

