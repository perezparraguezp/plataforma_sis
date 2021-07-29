<?php


include "../../../../php/config.php";
include "../../../../php/objetos/persona.php";

session_start();

$id_establecimiento = $_SESSION['id_establecimiento'];


$sector_comunal = explode(",",$_POST['sector_comunal']);
$centro_interno = explode(",",$_POST['centro_interno']);
$sector_interno = explode(",",$_POST['sector_interno']);


$indicador      = $_POST['indicador'];
$atributo       = $_POST['atributo'];
$estado         = $_POST['estado'];




$TITULO_GRAFICO = strtoupper(str_replace("_"," ",$atributo));



$sql_column = '';
$sql_column .= ",sum(paciente_pscv.$atributo='$estado' and paciente_pscv.$atributo!='' ) as total_indicador";
$sql_column .= ",sum(paciente_pscv.$atributo='$estado' and paciente_pscv.$atributo!='' and persona.sexo='M') as total_hombres";
$sql_column .= ",sum(paciente_pscv.$atributo='$estado' and paciente_pscv.$atributo!='' and persona.sexo='F') as total_mujeres";


$filtro = " and paciente_establecimiento.m_cardiovascular='SI' and id_sector!=0 ";
$comunal = $establecimientos = $sectores = false;

if(in_array('TODOS',$sector_comunal)){

    $comunal = true;
    $sql1 = "select * from paciente_pscv 
                        inner join paciente_establecimiento using (rut) 
                        inner join persona using(rut) 
                        inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno
                        inner join centros_internos on sectores_centros_internos.id_centro_interno=centros_internos.id_centro_interno
                        inner join sector_comunal on centros_internos.id_sector_comunal=sector_comunal.id_sector_comunal
                        where m_cardiovascular='SI' and persona.rut!='' 
                        and paciente_establecimiento.id_establecimiento='$id_establecimiento' ";
}else{
    if(in_array('TODOS',$centro_interno)){
        $establecimientos = true;
        $sql1 = "select * from paciente_pscv 
                        inner join paciente_establecimiento using (rut) 
                        inner join persona using(rut) 
                        inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno
                        inner join centros_internos on sectores_centros_internos.id_centro_interno=centros_internos.id_centro_interno
                        inner join sector_comunal on centros_internos.id_sector_comunal=sector_comunal.id_sector_comunal
                        where m_cardiovascular='SI' and persona.rut!='' 
                        and paciente_establecimiento.id_establecimiento='$id_establecimiento' ";
        $sql1 .= 'and (';
        $a = 0;
        foreach ($sector_comunal as $i => $id_sector_comunal){
            $id_sector_comunal = trim($id_sector_comunal);
            if($id_sector_comunal!='' && $id_sector_comunal != null){
                if($a>0){
                    $sql1.=' or ';
                }
                $sql1 .= "centros_internos.id_sector_comunal='$id_sector_comunal' ";
                $a++;
            }

        }
        $sql1.=') 
        group by persona.rut';
    }else{
        if(in_array('TODOS',$sector_interno)){

            $sql1 = "select * from paciente_pscv 
                        inner join paciente_establecimiento using (rut) 
                        inner join persona using(rut) 
                        inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno
                        inner join centros_internos on sectores_centros_internos.id_centro_interno=centros_internos.id_centro_interno
                        inner join sector_comunal on centros_internos.id_sector_comunal=sector_comunal.id_sector_comunal
                        where m_cardiovascular='SI' and persona.rut!='' 
                        and paciente_establecimiento.id_establecimiento='$id_establecimiento' ";
            $sql1 .= 'and (';
            $a = 0;
            foreach ($centro_interno as $i => $id_centro_interno){
                $id_centro_interno = trim($id_centro_interno);
                if($id_centro_interno!='' && $id_centro_interno != null){
                    if($a>0){
                        $sql1.=' or ';
                    }
                    $sql1 .= "centros_internos.id_centro_interno='$id_centro_interno' ";
                    $a++;
                }

            }
            $sql1.=') group by persona.rut';

            $sectores = true;

        }else{
            $sql1 = "select * from paciente_pscv 
                        inner join paciente_establecimiento using (rut) 
                        inner join persona using(rut) 
                        inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno
                        inner join centros_internos on sectores_centros_internos.id_centro_interno=centros_internos.id_centro_interno
                        inner join sector_comunal on centros_internos.id_sector_comunal=sector_comunal.id_sector_comunal
                        where m_cardiovascular='SI' and persona.rut!='' 
                        and paciente_establecimiento.id_establecimiento='$id_establecimiento' ";
            $sql1 .= 'and (';
            $a = 0;
            foreach ($sector_interno as $i => $id_sector_interno){
                $id_sector_interno = trim($id_sector_interno);
                if($id_sector_interno!='' && $id_sector_interno != null){
                    if($a>0){
                        $sql1.=' or ';
                    }
                    $sql1 .= "sectores_centros_internos.id_sector_centro_interno='$id_sector_interno' ";
                    $a++;
                }

            }
            $sql1.=') group by persona.rut';
        }
    }
}


$res1 = mysql_query($sql1);
$json = '';
$total_pacientes = 0;

while($row1 = mysql_fetch_array($res1)){
    $persona = new persona($row1['rut']);

    if($total_pacientes>0){
        $json.=',';
    }
    $json .= '{ "IR":"'.$persona->rut.'","ESTABLECIMIENTO":"'.$persona->nombre_centro_medico.'", "RUT":"'.$persona->rut.'","NOMBRE":"'.limpiaCadena($persona->nombre).'","EDAD":"'.$persona->edad.'","COMUNAL":"'.$persona->nombre_sector_comunal.'","SECTOR_INTERNO":"'.$persona->nombre_sector_interno.'","INDICADOR":"'.$row1[$atributo].'","ATRIBUTO":"'.$indicador.'","CONTACTO":"'.$persona->getContacto().'","anios":"'.$persona->edad_anios.'","meses":"'.$persona->edad_meses.'","dias":"'.$persona->edad_dias.'"}';
    $total_pacientes++;
}


$rango = '';
$series = '';


if($comunal==true){
    //para todos los sectores comunales
    $sql1 = "select 'GENERAL' as nombre_base,
                      count(*) as total 
                                    $sql_column
                                    from persona
                                    inner join paciente_establecimiento using (rut)
                                    inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno
                                    inner join centros_internos on sectores_centros_internos.id_centro_interno=centros_internos.id_centro_interno
                                    inner join sector_comunal on centros_internos.id_sector_comunal=sector_comunal.id_sector_comunal
                                    inner join paciente_pscv on persona.rut=paciente_pscv.rut
                                    where m_cardiovascular='SI' and persona.rut!='' 
                                    and paciente_establecimiento.id_establecimiento='$id_establecimiento' 
                                    $filtro
                                    ";



    $row1 = mysql_fetch_array(mysql_query($sql1));

    $total = $total_pacientes; // general de pacientes que califican para el indicador

    $total_hombres   = $row1['total_hombres']!='' ?$row1['total_hombres']:0;
    $total_mujeres   = $row1['total_mujeres']!='' ?$row1['total_mujeres']:0;

    $total_indicador = $row1['total_indicador']!='' ?$row1['total_indicador']:0;


    $porcentaje_indicador = number_format(($total_indicador*100/$total),0,'.','');


    $rango .= "\n{ Rango:'GENERAL',GENERAL: ".$porcentaje_indicador."},";
    $series .=" \n{ dataField: 'GENERAL', displayText: '$estado',labels: {visible: true,verticalAlignment: 'top',offset: { x: 0, y: -20 } },formatFunction: function (value) {return value + ' %';} ,total_general:$total,total_indicador:$total_indicador,hombres:$total_hombres,mujeres:$total_mujeres},";

}else{
    if($establecimientos==true){

        //para todos los establecimientos pero segun el sector comunal seleccionado
        $sql1 = "select sector_comunal.nombre_sector_comunal as nombre_base,
                        sector_comunal.id_sector_comunal as id,
                        count(*) as total
                                   $sql_column
                                    from persona
                                    inner join paciente_establecimiento using (rut)
                                    inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno
                                    inner join centros_internos on sectores_centros_internos.id_centro_interno=centros_internos.id_centro_interno
                                    inner join sector_comunal on centros_internos.id_sector_comunal=sector_comunal.id_sector_comunal
                                    inner join paciente_pscv on paciente_pscv.rut=persona.rut  
                                    where paciente_establecimiento.id_establecimiento='$id_establecimiento' 
                                    $filtro 
                                    AND (";

        $a = 0;
        foreach ($sector_comunal as $i => $id_sector_comunal){
            $id_sector_comunal = trim($id_sector_comunal);
            if($id_sector_comunal!='' && $id_sector_comunal != null){
                if($a>0){
                    $sql1.=' or ';
                }
                $sql1 .= "centros_internos.id_sector_comunal='$id_sector_comunal' ";
                $a++;
            }

        }
        $sql1.=') 
        group by centros_internos.id_sector_comunal ';




        $res1 = mysql_query($sql1);
        $rango .= "{ Rango:'".$estado."' ";

        $total = 0;
        $total_indicador = 0;
        $total_hombres= 0;
        $total_mujeres= 0;
        $total_cobertura = 0;
        $res1 = mysql_query($sql1);
        $dato = 0;

        while($row1 = mysql_fetch_array($res1)){
            $nombre_base = $row1['nombre_base'];
            $id = $row1['id'];

            $total = $row1['total']!='' ?$row1['total']:0; // general de pacientes que califican para el indicador
            $total_hombres   = $row1['total_hombres']!='' ?$row1['total_hombres']:0;
            $total_mujeres   = $row1['total_mujeres']!='' ?$row1['total_mujeres']:0;
            $total_indicador = $row1['total_indicador']!='' ?$row1['total_indicador']:0;


            $porcentaje_indicador = number_format(($total_indicador*100/$total),0,'.','');

            $series .=" { dataField: '$id', displayText: '$nombre_base',labels: {visible: true,verticalAlignment: 'top',offset: { x: 0, y: -20 } } ,formatFunction: function (value) {return value + ' %';},total_general:$total,total_indicador:$total_indicador,hombres:$total_hombres,mujeres:$total_mujeres},";
            $rango .= ", $id:$porcentaje_indicador";

        }
        $rango .= "},";

    }else{
        if($sectores==true){
            //para todos los centros interno
            $sql1 = "select count(*) as total,
                                    centros_internos.nombre_centro_interno as nombre_base,
                                    sector_comunal.nombre_sector_comunal as nombre_establecimiento,
                                    centros_internos.id_centro_interno as id 
                                    $sql_column   
                                    from persona
                                    inner join paciente_establecimiento using (rut)
                                    inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno
                                    inner join centros_internos on sectores_centros_internos.id_centro_interno=centros_internos.id_centro_interno
                                    inner join sector_comunal on centros_internos.id_sector_comunal=sector_comunal.id_sector_comunal
                                    inner join paciente_pscv on paciente_pscv.rut=persona.rut  
                                    where paciente_establecimiento.id_establecimiento='$id_establecimiento'
                                    $filtro 
                                    and (";
            $a = 0;
            foreach ($centro_interno as $i => $id_centro_interno){
                $id_centro_interno = trim($id_centro_interno);
                if($id_centro_interno!='' && $id_centro_interno != null){
                    if($a>0){
                        $sql1.=' or ';
                    }
                    $sql1 .= "centros_internos.id_centro_interno='$id_centro_interno' ";
                    $a++;
                }

            }
            $sql1.=') 
        group by centros_internos.id_centro_interno ';




            $res1 = mysql_query($sql1);

            $rango .= "{ Rango:'".$estado."'";

            while($row1 = mysql_fetch_array($res1)){
                $nombre_base = $row1['nombre_base']." [".$row1['nombre_establecimiento']."]";
                $id = $row1['id'];

                $total = $row1['total']!='' ?$row1['total']:0; // general de pacientes que califican para el indicador
                $total_hombres   = $row1['total_hombres']!='' ?$row1['total_hombres']:0;
                $total_mujeres   = $row1['total_mujeres']!='' ?$row1['total_mujeres']:0;
                $total_indicador = $row1['total_indicador']!='' ?$row1['total_indicador']:0;


                $porcentaje_indicador = number_format(($total_indicador*100/$total),0,'.','');


                $series .=" { dataField: '$id', displayText: '$nombre_base',labels: {visible: true,verticalAlignment: 'top',offset: { x: 0, y: -20 } } ,formatFunction: function (value) {return value + ' %';},total_general:$total,total_indicador:$total_indicador,hombres:$total_hombres,mujeres:$total_mujeres},";
                $rango .= ", $id:$porcentaje_indicador";

            }
            $rango .= "},";

        }else{
            //para todos los sectores internos seleccionados

            $sql1 = "select count(*) as total,
                                    sectores_centros_internos.nombre_sector_interno as nombre_base,
                                    sectores_centros_internos.id_sector_centro_interno as id,
                                    centros_internos.nombre_centro_interno as nombre_establecimiento
                                    $sql_column 
                                    from persona
                                    inner join paciente_establecimiento using (rut)
                                    inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno
                                    inner join centros_internos on sectores_centros_internos.id_centro_interno=centros_internos.id_centro_interno
                                    inner join sector_comunal on centros_internos.id_sector_comunal=sector_comunal.id_sector_comunal
                                    inner join paciente_pscv on paciente_pscv.rut=persona.rut  
                                    where paciente_establecimiento.id_establecimiento='$id_establecimiento'
                                    $filtro  
                                    and (";
            $a = 0;
            foreach ($sector_interno as $i => $id_sector_interno){
                $id_sector_interno = trim($id_sector_interno);
                if($id_sector_interno!='' && $id_sector_interno != null){
                    if($a>0){
                        $sql1.=' or ';
                    }
                    $sql1 .= "sectores_centros_internos.id_sector_centro_interno='$id_sector_interno' ";
                    $a++;
                }

            }
            $sql1.=') 
        group by sectores_centros_internos.id_sector_centro_interno';



            $res1 = mysql_query($sql1);

            $rango .= "{ Rango:'".$estado."' ";
            while($row1 = mysql_fetch_array($res1)){
                $nombre_base = $row1['nombre_base']." [".$row1['nombre_establecimiento']."]";
                $id = $row1['id'];


                $total           = $row1['total']!='' ?$row1['total']:0; // general de pacientes que califican para el indicador
                $total_hombres   = $row1['total_hombres']!='' ?$row1['total_hombres']:0;
                $total_mujeres   = $row1['total_mujeres']!='' ?$row1['total_mujeres']:0;
                $total_indicador = $row1['total_indicador']!='' ?$row1['total_indicador']:0;
                $total_cobertura = $row1['total_cobertura']!='' ?$row1['total_cobertura']:0;

                $porcentaje_indicador = number_format(($total_indicador*100/$total),0,'.','');

                $series .=" { dataField: '$id', displayText: '$nombre_base',labels: {visible: true,verticalAlignment: 'top',offset: { x: 0, y: -20 } } ,formatFunction: function (value) {return value + ' %';},total_general:$total,total_indicador:$total_indicador,hombres:$total_hombres,mujeres:$total_mujeres},";
                $rango .= ", $id:$porcentaje_indicador";


            }
            $rango .= "},";

        }
    }
}

$estado = $estado=='' ? 'PENDIENTE':$estado;

?>
<script type="text/javascript">
    $(document).ready(function () {
        // prepare chart data as an array
        var  sampleData = [
            <?php echo $rango; ?>
        ];
        var toolTips_DNI = function (value, itemIndex, serie, group, categoryValue, categoryAxis) {
            var dataItem = sampleData[itemIndex];

            return '<DIV style="text-align:left">' +
                '<b>' +serie.displayText+'</b><br />'+
                'Porcentaje: <b>' +value+'%</b><br />'+
                'Datos: <b>' +serie.total_indicador+'/'+serie.total_general+'</b><br />'+
                'Hombres: <b>' +serie.hombres+' ('+parseInt(serie.hombres*100/serie.total_general) +'%)</b><br />'+
                'Mujeres: <b>' +serie.mujeres+' ('+parseInt(serie.mujeres*100/serie.total_general) +'%)</b><br />'+
                '</DIV>';
        };
        var setting = {
            title: 'COBERTURA  - <?php echo $TITULO_GRAFICO; ?>',
            description: '<?php echo ''; ?>',
            enableAnimations: true,
            showLegend: true,
            padding: { left: 5, top: 5, right: 5, bottom: 5 },
            titlePadding: { left: 90, top: 0, right: 0, bottom: 10 },
            source: sampleData,
            xAxis:
                {
                    dataField: 'Rango',
                    showGridLines: true
                },
            colorScheme: 'scheme01',
            seriesGroups:
                [
                    {
                        type: 'column',
                        toolTipFormatFunction: toolTips_DNI,
                        valueAxis:
                            {
                                unitInterval: 10,
                                minValue: 0,
                                maxValue: 100,
                                displayValueAxis: true,
                                description: 'Porcentaje',
                                axisSize: 'auto',
                                tickMarksColor: '#888888'
                            },
                        series: [
                            <?php echo $series; ?>
                        ]
                    }
                ]
        };
        // setup the chart
        $('#pscv_cobertura').jqxChart(setting);

        function myEventHandler(event) {
            var eventData = '<div><b>Total General: </b>' + event.args.serie.total_general + '<b>, Total Indicador: </b>' + event.args.serie.total_indicador + "</div>";

            //$('#eventText').html(eventData);
            alertaLateral(eventData);
        };



        //grid
        var data = '[<?php echo $json; ?>]';
        var source =
            {
                datatype: "json",
                datafields: [

                    { name: 'IR', type: 'string' },
                    { name: 'RUT', type: 'string' },
                    { name: 'NOMBRE', type: 'string' },
                    { name: 'EDAD', type: 'string' },
                    { name: 'anios', type: 'string' },
                    { name: 'meses', type: 'string' },
                    { name: 'dias', type: 'string' },
                    { name: 'COMUNAL', type: 'string' },
                    { name: 'ESTABLECIMIENTO', type: 'string' },
                    { name: 'SECTOR_INTERNO', type: 'string' },
                    { name: 'CONTACTO', type: 'string' },
                    { name: 'INDICADOR', type: 'string' },
                ],
                localdata: data
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

        $("#table_grid").jqxGrid(
            {
                width: '95%',
                height:400,
                source: dataAdapter,
                columnsresize: true,
                sortable: true,
                filterable: true,
                autoshowfiltericon: true,
                showfilterrow: true,
                showstatusbar: true,
                editable: true,
                statusbarheight: 30,
                showaggregates: true,
                selectionmode: 'multiplecellsextended',
                columns: [
                    { text: 'IR', dataField: 'IR',
                        cellclassname:cellIrClass,
                        cellsrenderer:cellLinkRegistroTarjetero,
                        cellsalign: 'center', width: 100 },
                    { text: 'RUT', dataField: 'RUT', cellsalign: 'right', width: 150 },
                    { text: 'NOMBRE COMPLETO', dataField: 'NOMBRE' ,
                        width: 350,
                        aggregates: ['count'],aggregatesrenderer: function (aggregates, column, element, summaryData) {
                            var renderstring = "<div  style='float: left; width: 100%; height: 100%;'>";
                            $.each(aggregates, function (key, value) {
                                var name = 'Total Pacientes';
                                renderstring += '<div style="; position: relative; margin: 6px; text-align: right; overflow: hidden;">' + name + ': ' + value + '</div>';
                            });
                            renderstring += "</div>";
                            return renderstring;
                        }},
                    { text: 'AÑO', datafield: 'anios', width: 80 ,filtertype: 'checkedlist', cellsalign: 'center'},
                    { text: 'MES', datafield: 'meses', width: 80 ,filtertype: 'checkedlist', cellsalign: 'center'},
                    { text: 'DIA', datafield: 'dias', width: 80 ,filtertype: 'checkedlist', cellsalign: 'center'},
                    { text: '<?php echo $TITULO_GRAFICO; ?>', dataField: 'INDICADOR', cellsalign: 'left', width: 250,filtertype: 'checkedlist' },
                    { text: 'S. COMUNAL', dataField: 'COMUNAL', cellsalign: 'left', width: 250,filtertype: 'checkedlist' },
                    { text: 'ESTABLECIMIENTO', dataField: 'ESTABLECIMIENTO', cellsalign: 'left', width: 250,filtertype: 'checkedlist' },
                    { text: 'SECTOR_INTERNO', dataField: 'SECTOR_INTERNO', cellsalign: 'left', width: 250,filtertype: 'checkedlist' },
                    { text: 'CONTACTO', dataField: 'CONTACTO', cellsalign: 'left', width: 250},

                ]
            });
        $("#excelExport").click(function () {
            $("#table_grid").jqxGrid('exportdata', 'xls', 'Paciente PSCV', true,null,true, 'excel/save-file.php');
        });
        $("#print").click(function () {
            var content = $('#pscv_cobertura')[0].outerHTML;
            var newWindow = window.open('', '', 'width=900, height=600'),
                document = newWindow.document.open(),
                pageContent =
                    '<!DOCTYPE html>' +
                    '<html>' +
                    '<head>' +
                    '<meta charset="utf-8" />' +
                    '</head>' +
                    '<body>' + content + '</body></html>';
            try
            {
                document.write(pageContent);
                document.close();
                newWindow.print();
                newWindow.close();
            }
            catch (error) {
            }
        });
    });
</script>
<div id="div_imprimir">

    <div class="card-panel">
        <div class="row">
            <div class="col l12 m12 s12">
                <div id='pscv_cobertura' style="width: 100%;height: 500px;"></div>
            </div>
        </div>
        <div class="row right-align">
            <button class="btn" id="print">
                <i class="mdi-action-print left"></i>
                IMPRIMIR GRAFICO
            </button>
        </div>
    </div>
    <div class="card-panel" style="display: none;">
        <div class="row">
            <div class="col l4 m4 s12">
                <label for="desde">DESDE</label>
                <input type="date" name="desde" id="desde" value="<?php echo (date('Y')-1).'-'.date('m').'-'.date('d'); ?>" />
            </div>
            <div class="col l4 m4 s12">
                <label for="hasta">HASTA</label>
                <input type="date" name="hasta" id="hasta" value="<?php echo date('Y-m-d'); ?>" />
            </div>
        </div>
        <div class="row">
            <div class="col l12 m12 s12">
                <div id='pscv_tiempo' style="width: 100%;height: 500px;"></div>
            </div>
        </div>

    </div>
    <div class="card-panel" id="tabla_grilla">
        <div class="row">
            <div class="col l6 m12 s12">
                <button class="btn" id="print_grid">
                    <i class="mdi-action-print left"></i>
                    IMPRIMIR TABLA
                </button>
                <button class="btn" id="excelExport" >
                    <i class="mdi-action-open-in-new left"></i>
                    EXPORTAR EXCEL
                </button>
            </div>
        </div>
        <div class="row">
            <div class="col l12 m12 s12">
                <div id="table_grid"></div>
            </div>
        </div>
    </div>
</div>
<style type="text/css">
    @media only screen
    and (min-device-width : 320px)
    and (max-device-width : 800px) { /* Aquí van los estilos */
        #tabla_grilla{
            display: none;;
        }
    }


</style>
