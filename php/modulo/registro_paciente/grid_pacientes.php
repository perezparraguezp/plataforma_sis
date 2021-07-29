<style type="text/css">
    .cursor_cell_link{
        cursor: pointer;
    }
</style>
<script type="text/javascript">
    $(document).ready(function() {
        var source =
            {
                url: 'php/json/pacientes/listado.php',
                datatype: "json",
                root: 'Rows',
                datafields:
                    [
                        {name: 'rut', type: 'string'},
                        {name: 'nombre', type: 'string'},
                        {name: 'edad', type: 'number'},
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
            return '<i class="mdi-hardware-keyboard-return"></i> '+
                '<a href="registrar_ficha.php?rut='+value+'" style="color: black;" >' +
                'IR' +
                '</a>';
        }
        var cellIrClass = function(row, columnfield, value, defaulthtml, columnproperties, rowdata) {
            return  "green center-align cursor_cell_link black-text";

        }
        var cellEditarPaciente = function(row, columnfield, value, defaulthtml, columnproperties, rowdata) {

            return '<i class="mdi-editor-mode-edit" ' +
                'onclick="boxEditarPaciente(\''+value+'\')"></i>';

        }
        var cellEdadAnios = function(row, columnfield, value, defaulthtml, columnproperties, rowdata) {
            var anios = parseInt(value/12);
            var meses = value%12;
            if(anios===0){
                return  "<div style='padding-left: 10px;'>"+meses+" Meses</div>";
            }else{
                return  "<div style='padding-left: 10px;'>"+anios + " Años "+meses+" Meses</div>";
            }

        }
        var cellSexo = function(row, columnfield, value, defaulthtml, columnproperties, rowdata) {
            if(value=='M'){
                return  "light-blue lighten-1";
            }else{
                return  "pink lighten-1";
            }

        }
        var cellEditarClass = function(row, columnfield, value, defaulthtml, columnproperties, rowdata) {
            return  "green center-align cursor_cell_link black-text";

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
                width: '90%',
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
                    { text: 'IR', datafield: 'link', width: 100,
                        cellclassname:cellIrClass,
                        cellsrenderer:cellLinkRegistroTarjetero ,filterable:false},
                    { text: 'EDITAR', datafield: 'editar', width: 100,
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
                    { text: 'Edad', datafield: 'edad', width: 150,
                        cellsalign: 'right',cellsrenderer:cellEdadAnios,align:'right'},
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
            $("#grid").jqxGrid('exportdata', 'xls', 'jqxGrid');
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
    function cargarListado(){
        // prepare the data

    }
</script>
<div class="row center-align">
    <div class="col l12">
        <div class="card-panel">
            <div class="row">
                <div class="col l12">
                    <input type="button" value="Exportar a Excel" id='excelExport' />
                </div>
            </div>
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

