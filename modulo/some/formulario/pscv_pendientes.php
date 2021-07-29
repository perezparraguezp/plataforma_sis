<script type="text/javascript">

    var source =
        {
            url: 'json/pendientes.php?indicador=<?php echo $_POST['indicador']; ?>&rut=<?php echo $_POST['rut']; ?>',
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
                    {name: 'ultima_ev', type: 'string'},
                    {name: 'mail', type: 'string'},
                    {name: 'establecimiento', type: 'string'},
                    {name: 'contacto', type: 'string'}
                ],
            cache: false,
            sortcolumn: 'nombre',
            sortdirection: 'asc'
        };


    var cellLinkRegistroTarjetero = function(row, columnfield, value, defaulthtml, columnproperties, rowdata) {
        return ''+
            '<a onclick="loadMenu_CardioVascular(\'menu_1\',\'registro_atencion\',\''+value+'\')"  style="color: black;" >' +
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
            height:alto-400,
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
                { text: 'Atributo', datafield: 'indicador', width: 150 ,filtertype: 'checkedlist', cellsalign: 'center'},
                { text: 'Edad Actual', datafield: 'edad_actual', width: 200 },
                { text: 'ULTIMA EV.', datafield: 'ultima_ev', width: 200 },
                { text: 'ESTABLECIMIENTO', datafield: 'establecimiento' ,filtertype: 'checkedlist'},



            ]
        });
    $("#excelExport").click(function () {
        $("#grid_pendientes").jqxGrid('exportdata', 'xls', 'jqxGrid');
    });
</script>
<div class="row">
    <div class="col l12 m12 s12 right-align">
        <input type="button" class="btn  green lighten-3"
               value="Exportar a Excel" id='excelExport' />
    </div>
</div>
<div class="row">
    <div id="grid_pendientes" style="font-size:1em;"></div>
</div>
