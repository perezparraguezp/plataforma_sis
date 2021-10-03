<script type="text/javascript">

    var source =
        {
            url: 'json/pendientes.php?indicador=<?php echo $_POST['indicador'] ?>',
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
        return '<i onclick="loadMenu_Infantil(\'menu_1\',\'registro_tarjetero\',\''+value+'\')"  class="mdi-hardware-keyboard-return"></i> IR';
    }
    var cellIrClass = function(row, columnfield, value, defaulthtml, columnproperties, rowdata) {
        return  "eh-open_principal white-text cursor_cell_link center";

    }

    var dataAdapter = new $.jqx.dataAdapter(source);


    $("#grid_pendientes").jqxGrid(
        {
            width: '100%',
            theme: 'eh-open',
            source: dataAdapter,
            height:alto-250,
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
        $("#grid_pendientes").jqxGrid('exportdata', 'xls', 'Lista_Pacientes_PSCV', true,null,true, 'excel/save-file.php');
    });
</script>
<div class="row">
    <div class="col l12 m12 s12 right-align">
        <input type="button" class="btn eh-open_principal"
               value="Exportar a Excel" id='excelExport' />
    </div>
</div>
<div class="row">
    <div id="grid_pendientes" style="font-size: 0.7em;"></div>
</div>
