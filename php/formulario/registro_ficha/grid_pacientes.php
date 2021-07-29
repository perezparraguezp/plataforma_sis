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
                        {name: 'establecimiento', type: 'string'},
                        {name: 'sector_comunal', type: 'string'},
                        {name: 'sector_interno', type: 'string'},
                        {name: 'link', type: 'string'},
                    ],
                cache: false
            };

        var cellLinkRegistroTarjetero = function(row, columnfield, value, defaulthtml, columnproperties, rowdata) {

            return '<i class="mdi-hardware-keyboard-return"></i> '+
                '<a onclick="$(\'#rut_paciente\').val(\''+value+'\')" style="color: black;cursor:pointer;" >' +
                'IR' +
                '</a>';
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
        var cellLink = function(row, columnfield, value, defaulthtml, columnproperties, rowdata) {
            return  "center green black-text ";

        }

        var dataAdapter = new $.jqx.dataAdapter(source);

        $("#grid_listado").jqxGrid(
            {
                width: '100%',
                source: dataAdapter,
                height:alto-270,
                columnsresize: true,
                sortable: true,
                filterable: true,
                filtermode: 'excel',
                autoshowfiltericon: false,
                showfilterrow: true,
                showstatusbar: true,
                statusbarheight: 30,
                showaggregates: true,
                columns: [
                    { text: ' ', datafield: 'link', width: 80,filterable:false,
                        cellsrenderer:cellLinkRegistroTarjetero,cellclassname: cellLink },
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
                    { text: 'Edad', datafield: 'edad', width: 150,cellsalign: 'right',cellsrenderer:cellEdadAnios,align:'right'},
                    { text: 'Sexo', datafield: 'sexo', width: 80 ,filtertype: 'checkedlist', cellsalign: 'center',cellclassname: cellSexo},
                    //{ text: 'Comuna', datafield: 'comuna', width: 120 ,filtertype: 'checkedlist', cellsalign: 'center'},
                    { text: 'SECTOR COMUNAL', datafield: 'sector_comunal', width: 150 ,filtertype: 'checkedlist'},
                    { text: 'ESTABLECIMIENTO', datafield: 'establecimiento', width: 300 ,filtertype: 'checkedlist'},
                    { text: 'SECTOR INTERNO', datafield: 'sector_interno', width: 150 ,filtertype: 'checkedlist'},


                ]
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
<div class="card-panel">
    <div class="row">
        <div class="col l12">
            <div id="grid_listado"></div>
        </div>
    </div>
</div>

