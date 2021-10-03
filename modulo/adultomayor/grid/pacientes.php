<style type="text/css">
    .cursor_cell_link{
        cursor: pointer;
    }
</style>
<script type="text/javascript">
    $(document).ready(function() {
        var source =
            {
                url: 'json/pacientes.php',
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
                        {name: 'editar', type: 'string'},
                    ],
                cache: false
            };

        var cellLinkRegistroTarjetero = function(row, columnfield, value, defaulthtml, columnproperties, rowdata) {
            return '<i onclick="loadMenu_AM(\'menu_1\',\'registro_atencion\',\''+value+'\')" class="mdi-hardware-keyboard-return"></i> IR';
        }
        var cellIrClass = function(row, columnfield, value, defaulthtml, columnproperties, rowdata) {
            return  "eh-open_principal white-text cursor_cell_link center";

        }
        var cellEditarPaciente = function(row, columnfield, value, defaulthtml, columnproperties, rowdata) {

            return '<i class="mdi-editor-mode-edit" ' +
                'onclick="boxEditarPaciente_AM(\''+value+'\')"></i>';

        }
        var cellEdadAnios = function(row, columnfield, value, defaulthtml, columnproperties, rowdata) {
            var anios = parseInt(value/12);
            var meses = value%12;
            if(anios===0){
                return  "<div style='padding-left: 10px;'>"+meses+" Meses</div>";
            }else{
                if(anios>20){
                    return  "<div style='padding-left: 10px;'>"+anios + " Años</div>";
                }else{
                    return  "<div style='padding-left: 10px;'>"+anios + " Años "+meses+" Meses</div>";
                }

            }

        }
        var cellSexo = function(row, columnfield, value, defaulthtml, columnproperties, rowdata) {
            if(value=='M'){
                return  "light-blue lighten-1";
            }else{
                return  "pink lighten-1 white-text";
            }

        }
        var cellEditarClass = function(row, columnfield, value, defaulthtml, columnproperties, rowdata) {
            return  "eh-open_principal white-text cursor_cell_link center";

        }

        var dataAdapter = new $.jqx.dataAdapter(source);

        var addfilter = function () {
            var filtergroup = new $.jqx.filter();
            var filter_or_operator = 1;
            var filtervalue = '';
            var filtercondition = 'contains';
            var filter1 = filtergroup.createfilter('stringfilter', filtervalue, filtercondition);
            filtervalue = 'Andrew';
            filtercondition = 'contains';
            var filter2 = filtergroup.createfilter('stringfilter', filtervalue, filtercondition);
            filtergroup.addfilter(filter_or_operator, filter1);
            filtergroup.addfilter(filter_or_operator, filter2);
            // add the filters.
            $("#grid").jqxGrid('addfilter', 'edad', filtergroup);
            // apply the filters.
            $("#grid").jqxGrid('applyfilters');
        };

        $("#grid").jqxGrid(
            {
                width: '98%',
                theme: 'eh-open',
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
                selectionmode: 'multiplecellsextended',
                columns: [
                    { text: '', datafield: 'link', width: 60,
                        cellclassname:cellIrClass,
                        cellsrenderer:cellLinkRegistroTarjetero ,filterable:false},
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
                    { text: 'AÑO', datafield: 'anios', width: 80 ,filtertype: 'checkedlist', cellsalign: 'center'},
                    { text: 'MES', datafield: 'meses', width: 80 ,filtertype: 'checkedlist', cellsalign: 'center'},
                    { text: 'DIA', datafield: 'dias', width: 80 ,filtertype: 'checkedlist', cellsalign: 'center'},
                    { text: 'Sexo', datafield: 'sexo', width: 80 ,filtertype: 'checkedlist', cellsalign: 'center',cellclassname: cellSexo},
                    //{ text: 'Comuna', datafield: 'comuna', width: 120 ,filtertype: 'checkedlist', cellsalign: 'center'},
                    { text: 'SECTOR COMUNAL', datafield: 'sector_comunal', width: 150 ,filtertype: 'checkedlist'},
                    { text: 'ESTABLECIMIENTO', datafield: 'establecimiento', width: 300 ,filtertype: 'checkedlist'},
                    { text: 'SECTOR INTERNO', datafield: 'sector_interno', width: 150 ,filtertype: 'checkedlist'},
                    { text: 'Telefono', datafield: 'telefono', width: 150 },
                    { text: 'E-mail', datafield: 'mail', width: 150 },


                ]
            });
        $("#excelExport").jqxButton();
        $("#excelExport").click(function () {
            // $("#grid").jqxGrid('exportdata', 'xls', 'jqxGrid');
            $("#grid").jqxGrid('exportdata', 'xls', 'Pacientes PSCV', true,null,true, 'excel/save-file.php');
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
    function boxEditarPaciente_AM(rut) {
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
    function cargarListado(){
        // prepare the data

    }
</script>
<div class="row center-align">
    <div class="col l12">
        <div class="card-panel">
            <div class="row">
                <div class="col l12 right-align">
                    <input type="button" class="btn eh-open_principal"
                           value="Exportar a Excel" id='excelExport' />
                </div>
            </div>
            <div class="row">
                <div class="col l12">
                    <div id="grid"></div>
                </div>
            </div>
            <div class="row left-align">
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

