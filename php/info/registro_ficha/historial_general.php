<?php
$rut = $_POST['rut'];
?>
<script type="text/javascript">
    $(document).ready(function () {
        // prepare the data
        var source =
            {
                url: 'php/json/persona/historial_general.php?rut=<?php echo $rut; ?>',
                datatype: "json",
                root: 'Rows',
                datafields:
                    [
                        {name: 'tipo', type: 'string'},
                        {name: 'fecha', type: 'string'},
                        {name: 'texto', type: 'string'},
                        {name: 'profesional', type: 'string'},
                        {name: 'borrar', type: 'string'},
                    ],
                cache: false
            };
        var dataAdapter = new $.jqx.dataAdapter(source);


        var cell_borrar = function (row, columnfield, value, defaulthtml, columnproperties, rowdata) {
            if(value!==''){
                return '<i class="mdi-action-delete red-text tiny center-align center" style="cursor:pointer;" ' +
                    'onclick="deleteHisotialMio(\''+value+' \')"></i>';
            }else{
                return '';
            }

        };

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
                rowsheight: 20,
                pagesize: 20,
                pagesizeoptions: ['20', '30', '100'],
                columns: [
                    { text: 'TIPO', datafield: 'tipo', width: 250 ,cellsalign: 'left',filtertype: 'checkedlist'},
                    { text: 'FECHA', datafield: 'fecha', width: 150 },
                    { text: 'REGISTRO', datafield: 'texto', width: 400 },
                    { text: 'PROFESIONAL', datafield: 'profesional', width: 300 ,filtertype: 'checkedlist'},
                    { text: ' ', datafield: 'borrar', width: 50 , cellsalign: 'center',cellsrenderer: cell_borrar,filterable:false},


                ]
            });
        $("#excelExport").click(function () {
            $("#grid").jqxGrid('exportdata', 'xls', 'jqxGrid');
        });
    });

    function deleteHisotialMio(id){
        if(confirm("Seguro que desea eliminar esta información, Recuerde que esto no eliminara los atributos asignados a este paciente")){
            $.post('php/db/delete/historial_paciente.php',{
                id:id
            },function(data){
                if(data !== 'ERROR_SQL'){
                    var texto = 'REGISTRO ELIMINADO: EL REGISTRO FUE ELIMINADO CON EXITO';
                    alertaLateral(texto);
                    loadHistorialPaciente('<?php echo $rut; ?>');
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
        <div class="col l10">
            <div id="grid"></div>
        </div>
        <div class="col l2">
            <h5>Histotial General del Paciente</h5>
            <div class="col l12">
                <img src="images/medico_historia.png" width="100%" />
            </div>
        </div>
    </div>

</div>

