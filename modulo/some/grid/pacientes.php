<style type="text/css">
    .cursor_cell_link{
        cursor: pointer;
    }
</style>

<script type="text/javascript">
    $(document).ready(function() {


        $("#excelExport").click(function () {
            $("#grid").jqxGrid('exportdata', 'xls', 'jqxGrid');
        });

        $("#tipo_paciente").on('change',function () {
            loadGrid();
        });

        loadGrid();
    });
    function loadGrid(){
        var tipo = $("#tipo_paciente").val();
        var source =
            {
                url: 'json/pacientes.php?tipo='+tipo,
                datatype: "json",
                root: 'Rows',
                datafields:
                    [
                        {name: 'rut', type: 'string'},
                        {name: 'nombre', type: 'string'},
                        {name: 'edad', type: 'number'},
                        {name: 'anios', type: 'number'},
                        {name: 'meses', type: 'number'},
                        {name: 'dias', type: 'number'},
                        {name: 'nacimiento', type: 'string'},
                        {name: 'sexo', type: 'string'},
                        {name: 'comuna', type: 'string'},
                        {name: 'telefono', type: 'string'},
                        {name: 'mail', type: 'string'},
                        {name: 'establecimiento', type: 'string'},
                        {name: 'sector_comunal', type: 'string'},
                        {name: 'sector_interno', type: 'string'},
                        {name: 'link', type: 'string'},
                        {name: 'CONTACTO', type: 'string'},
                        {name: 'editar', type: 'string'},
                        {name: 'migrantes', type: 'string'},
                        {name: 'originarios', type: 'string'},
                        {name: 'nanea', type: 'string'},
                        {name: 'tarjetero', type: 'string'},
                    ],
                cache: false
            };

        var cellLinkRegistroTarjetero = function(row, columnfield, value, defaulthtml, columnproperties, rowdata) {
            return ''+
                '<a onclick="loadMenu_Infantil(\'menu_1\',\'registro_tarjetero\',\''+value+'\')"  style="color: black;" >' +
                '<i class="mdi-hardware-keyboard-return"></i> IR' +
                '</a>';
        }
        var cellIrClass = function(row, columnfield, value, defaulthtml, columnproperties, rowdata) {
            return  "green center-align cursor_cell_link black-text";

        }
        var cellEditarPaciente = function(row, columnfield, value, defaulthtml, columnproperties, rowdata) {

            return '<i class="mdi-editor-mode-edit" ' +
                'onclick="boxEditarPaciente_SOME(\''+value+'\')"></i>';

        }


        var cellEditarClass = function(row, columnfield, value, defaulthtml, columnproperties, rowdata) {
            return  "green center-align cursor_cell_link black-text";

        }

        var dataAdapter = new $.jqx.dataAdapter(source);



        $("#grid").jqxGrid(
            {
                width: '98%',
                source: dataAdapter,
                height:400,
                columnsresize: true,
                sortable: true,
                filterable: true,
                autoshowfiltericon: true,
                showfilterrow: true,
                showstatusbar: true,
                statusbarheight: 30,
                showaggregates: true,
                columns: [
                    { text: '', datafield: 'editar', width: 40,
                        cellsrenderer:cellEditarPaciente ,
                        cellclassname:cellEditarClass,
                        filterable:false},
                    { text: 'RUT', datafield: 'rut', width: 100,cellsalign: 'right'},
                    { text: 'Nombre Completo', datafield: 'nombre', width: 280,
                        aggregates: ['count'],
                        aggregatesrenderer: function (aggregates, column, element, summaryData) {
                            var renderstring = "<div  style='float: left; width: 100%; height: 100%;'>";
                            $.each(aggregates, function (key, value) {
                                var name = 'Total Pacientes';
                                renderstring += '<div style="; position: relative; margin: 6px; text-align: right; overflow: hidden;">' + name + ': ' + value + '</div>';
                            });
                            renderstring += "</div>";
                            return renderstring;
                        }
                    },
                    { text: 'Fecha Nacimiento', datafield: 'nacimiento', width: 100},
                    // { text: 'Edad', datafield: 'edad', width: 150,cellsalign: 'right',cellsrenderer:cellEdadAnios,align:'right'},
                    { text: 'AÑO', datafield: 'anios', width: 80 ,filtertype: 'checkedlist', cellsalign: 'center'},
                    { text: 'MES', datafield: 'meses', width: 80 ,filtertype: 'checkedlist', cellsalign: 'center'},
                    { text: 'DIA', datafield: 'dias', width: 80 ,filtertype: 'checkedlist', cellsalign: 'center'},
                    //{ text: 'Comuna', datafield: 'comuna', width: 120 ,filtertype: 'checkedlist', cellsalign: 'center'},
                    { text: 'SECTOR COMUNAL', datafield: 'sector_comunal', width: 150 ,filtertype: 'checkedlist'},
                    { text: 'ESTABLECIMIENTO', datafield: 'establecimiento', width: 300 ,filtertype: 'checkedlist'},
                    { text: 'SECTOR INTERNO', datafield: 'sector_interno', width: 150 ,filtertype: 'checkedlist'},
                    { text: 'CONTACTO', datafield: 'CONTACTO', width: 250 },
                    { text: 'NANEA', datafield: 'nanea', width: 250  ,filtertype: 'checkedlist'},
                    { text: 'PUEBLO ORIGINARIO', datafield: 'originarios', width: 250  ,filtertype: 'checkedlist'},
                    { text: 'POBLACION MIGRANTE', datafield: 'migrantes', width: 250  ,filtertype: 'checkedlist'},
                    { text: 'TARJETERO', datafield: 'tarjetero', width: 150},
                ]
            });
        $("#excelExport").click(function () {
            $("#grid").jqxGrid('exportdata', 'xls', 'PACIENTES', true,null,true, 'excel/save-file.php');

        });
    }
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
    function boxEditarPaciente_SOME(rut) {
        $.post('../default/formulario/editar_paciente.php',{
            rut:rut,
        },function(data){
            if(data !== 'ERROR_SQL'){
                $("#modal").html(data);
                $("#modal").css({'width':'1100px'});
                document.getElementById("btn-modal").click();
            }
        });
    }
</script>
<div class="row center-align">
    <div class="col l12">
        <div class="card-panel">
            <div class="row">
                <div class="col l8 m6 s6">
                    <select id="tipo_paciente"
                            style="font-size: 1em;"
                            name="tipo_paciente" class="browser-default">
                        <option>TODOS</option>
                        <option>INFANTIL</option>
                        <option>CARDIOVASCULAR</option>
                        <option>ADULTO MAYOR</option>
                        <option>ADOLESCENTE</option>
                        <option>DE LA MUJER</option>
                    </select>
                </div>
                <div class="col l4 m6 s6">
                    <button class="btn right-align" id="excelExport" >
                        <i class="mdi-action-open-in-new left"></i>
                        EXPORTAR EXCEL
                    </button>
                </div>
            </div>
            <hr />
            <div class="row">
                <div class="col l12">
                    <div id="grid"></div>
                </div>
            </div>
            <div class="row">
                <div class="col l12 m12 s12">
                    <i class="mdi-hardware-keyboard-return"></i>PERMITE PODER INGRESAR LA FICHA CORRESPONDIENTE AL USUARIO SELECCIONADO
                </div>
                <div class="col l12 m12 s12">
                    <i class="mdi-editor-mode-edit"></i>PERMITE EDITAR AL PACIENTE, PARA MODIFICAR SUS DATOS PERSONALES Y ADEMAS CAMBIARLO DE ESTABLECIMIENTO.
                </div>
            </div>
        </div>
    </div>
</div>

