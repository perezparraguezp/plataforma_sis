<script type="text/javascript">
    load_ListaPendientes();
    function load_ListaPendientes(){
        var indicador = $("#indicador").val();
        var source =
            {
                url: 'php/json/pendientes/pacientes_global.php?indicador='+indicador,
                datatype: "json",
                root: 'Rows',
                datafields:
                    [
                        {name: 'link', type: 'string'},
                        {name: 'rut', type: 'string'},
                        {name: 'nombre', type: 'string'},
                        {name: 'edad_actual', type: 'string'},
                        {name: 'tipo', type: 'string'},
                        {name: 'indicador', type: 'string'},
                        {name: 'mail', type: 'string'},
                        {name: 'establecimiento', type: 'string'},
                        {name: 'contacto', type: 'string'}
                    ],
                cache: false,
                sortcolumn: 'nombre',
                sortdirection: 'asc'
            };


        var cellLinkRegistroTarjetero = function(row, columnfield, value, defaulthtml, columnproperties, rowdata) {
            return ' ' +
                '<a href="registrar_ficha.php?rut=' + value + '" style="color: black;" >' +
                '<i class="mdi-hardware-keyboard-return"></i> IR' +
                '</a>';
        }
        var cellIrClass = function(row, columnfield, value, defaulthtml, columnproperties, rowdata) {
            return  "green center-align cursor_cell_link black-text";

        }


        var dataAdapter = new $.jqx.dataAdapter(source);


        $("#grid_pendientes").jqxGrid(
            {
                width: '100%',
                source: dataAdapter,
                height:alto-190,
                columnsresize: true,
                sortable: true,
                filterable: true,
                autoshowfiltericon: true,
                showfilterrow: true,
                showstatusbar: true,
                statusbarheight: 30,
                showaggregates: true,
                editable:true,
                columns: [
                    { text: ' ', datafield: 'link', width: 80 ,editable:false,
                        filterable:false,
                        cellclassname:cellIrClass,
                        cellsrenderer:cellLinkRegistroTarjetero },
                    { text: 'RUT', datafield: 'rut', width: 100,cellsalign: 'right'},
                    { text: 'Nombre Completo', datafield: 'nombre', width: 280,
                        aggregates: ['count']
                    },
                    { text: 'Atributo', datafield: 'indicador', width: 150 ,filtertype: 'checkedlist', cellsalign: 'center'},
                    { text: 'Edad Actual', datafield: 'edad_actual', width: 200 },
                    { text: 'Teléfono', datafield: 'contacto', width: 120 ,filtertype: 'checkedlist'},
                    { text: 'E-mail', datafield: 'mail', width: 150},
                    { text: 'ESTABLECIMIENTO', datafield: 'establecimiento', width: 300 ,filtertype: 'checkedlist'},



                ]
            });
        $("#excelExport").click(function () {
            $("#grid_pendientes").jqxGrid('exportdata', 'xls', 'jqxGrid');
        });
    }


</script>

