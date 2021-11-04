<?php
include "../../../../php/config.php";
include "../../../../php/objetos/persona.php";

//session_start();

$id_establecimiento = $_SESSION['id_establecimiento'];


$sector_comunal = explode(",",$_POST['sector_comunal']);
$centro_interno = explode(",",$_POST['centro_interno']);
$sector_interno = explode(",",$_POST['sector_interno']);

$estado = trim($_POST['estado']);

$indicador      = $_POST['indicador'];
$indicador_estado = $indicador;

$indicador = $estado;

if($indicador=='2m'){
    $filtro_edad = ' and persona.edad_total_dias>=((30*2)+29) ';
    $rango_edad_texto = '2 Meses y 29 Dias';
}else{
    if($indicador == '4m'){
        $filtro_edad = ' and persona.edad_total_dias>=((30*4)+29)';
        $rango_edad_texto = '4 Meses y 29 Dias';
    }else{
        if($indicador=='6m'){
            $filtro_edad = ' and persona.edad_total_dias>=((30*6)+29) ';
            $rango_edad_texto = '6 Meses y 29 Dias';
        }else{
            if($indicador=='12m'){
                $filtro_edad = ' and persona.edad_total_dias>=((30*12)+29) ';
                $rango_edad_texto = '12 Meses y 29 Dias';

            }else{
                if($indicador=='18m'){
                    $filtro_edad = ' and persona.edad_total_dias>=((30*18)+29) ';
                    $rango_edad_texto = '18 Meses y 29 Dias';

                }else{
                    if($indicador=='5anios'){
                        $filtro_edad = ' and persona.edad_total_dias>=((5*12*30)+29) ';//mayores de 5 años
                        $rango_edad_texto = '5 años y 29 dias';

                    }
                }
            }
        }

    }
}

$sql_total = "select COUNT(*) as total from paciente_establecimiento inner join persona using(rut) where m_infancia='SI' $filtro_edad; ";
$row_total = mysql_fetch_array(mysql_query($sql_total));
if($row_total){
    $total_pacientes = $row_total['total'];
}



$TITULO_GRAFICO = strtoupper(str_replace("_"," ",$rango_edad_texto));



$sql_column = '';
$sql_column .= ",sum(vacunas_paciente.$indicador='SI') as total_cobertura";
$sql_column .= ",sum(vacunas_paciente.$indicador='SI' and persona.sexo='M' ) as total_hombres";
$sql_column .= ",sum(vacunas_paciente.$indicador='SI' and persona.sexo='F' ) as total_mujeres";



$filtro = '';
$comunal = $establecimientos = $sectores = false;

if(in_array('TODOS',$sector_comunal)){
    $comunal = true;
}else{
    if(in_array('TODOS',$centro_interno)){
        $establecimientos = true;
    }else{
        if(in_array('TODOS',$sector_interno)){
            $sectores = true;
        }
    }
}

$rango = '';
$series = '';

$rango_cobertura = '';
$series_cobertura = '';
$json = '';

//total pacientes
$sql0 = "select *  from paciente_establecimiento inner join persona using(rut) 
              where m_infancia='SI' and persona.rut!='' 
               $filtro_edad ";
$res0  = mysql_query($sql0);
$total_pacientes = 0;
while ($row0 = mysql_fetch_array($res0)){
    $persona = new persona($row0['rut']);
    if($total_pacientes>0){
        $json.=',';
    }
    $sql1 = "select * from vacunas_paciente where rut='$persona->rut' limit 1 ";
    $row1 = mysql_fetch_array(mysql_query($sql1));
    if($row1){
        $valor_vacuna = $row1[$indicador];
    }else{
        $valor_vacuna = 'NO';
    }
    $json .= '{"IR":"'.$persona->rut.'","RUT":"'.$persona->rut.'","NOMBRE":"'.limpiaCadena($persona->nombre).'","EDAD":"'.$persona->edad.'","COMUNAL":"'.$persona->nombre_sector_comunal.'","ESTABLECIMIENTO":"'.$persona->nombre_centro_medico.'","SECTOR_INTERNO":"'.$persona->nombre_sector_interno.'","INDICADOR":"'.$valor_vacuna.'","anios":"'.$persona->edad_anios.'","meses":"'.$persona->edad_meses.'","dias":"'.$persona->edad_dias.'"}';
    $total_pacientes++;
}

if($comunal==true){

    //para todos los sectores comunales
    $sql1 = "select 'GENERAL' as nombre_base,count(*) as total 
                                    $sql_column
                                    from persona
                                    inner join paciente_establecimiento using (rut)
                                    inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno
                                    inner join centros_internos on sectores_centros_internos.id_centro_interno=centros_internos.id_centro_interno
                                    inner join sector_comunal on centros_internos.id_sector_comunal=sector_comunal.id_sector_comunal
                                    inner join vacunas_paciente on vacunas_paciente.rut=persona.rut 
                                    where paciente_establecimiento.id_establecimiento='$id_establecimiento'  
                                    and paciente_establecimiento.m_infancia='SI' 
                                    $filtro_edad
                                    ";

//    echo $sql1;

    $row1 = mysql_fetch_array(mysql_query($sql1));


    $total = $total_pacientes;

    $total_hombres   = $row1['total_hombres']!='' ?$row1['total_hombres']:0;
    $total_mujeres   = $row1['total_mujeres']!='' ?$row1['total_mujeres']:0;


    $total_cobertura = $row1['total_cobertura']!='' ?$row1['total_cobertura']:0;

    $porcentaje_cobertura = number_format(($total_cobertura*100/$total),0,'.','');


    $rango_cobertura .= "\n{ Rango:'GENERAL',GENERAL: ".$porcentaje_cobertura."},";
    $series_cobertura .=" \n{ dataField: 'GENERAL', displayText: '$estado',labels: {visible: true,verticalAlignment: 'top',offset: { x: 0, y: -20 } },formatFunction: function (value) {return value + ' %';} ,total_cobertura:$total_cobertura,total_general:$total,hombres:$total_hombres,mujeres:$total_mujeres},";




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
                                    inner join vacunas_paciente on vacunas_paciente.rut=persona.rut  
                                    where paciente_establecimiento.id_establecimiento='$id_establecimiento'
                                    and paciente_establecimiento.m_infancia='SI'  
                                    $filtro_edad    
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
        $rango_cobertura .= "{ Rango:'".$estado."' ";

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

            $total = $total_pacientes; // general de pacientes que califican para el indicador
            $total_hombres   = $row1['total_hombres']!='' ?$row1['total_hombres']:0;
            $total_mujeres   = $row1['total_mujeres']!='' ?$row1['total_mujeres']:0;
            $total_indicador = $row1['total_indicador']!='' ?$row1['total_indicador']:0;
            $total_cobertura = $row1['total_cobertura']!='' ?$row1['total_cobertura']:0;

            $porcentaje_indicador = number_format(($total_indicador*100/$total_cobertura),0,'.','');
            $porcentaje_cobertura = number_format(($total_cobertura*100/$total),0,'.','');

            $series .=" { dataField: '$id', displayText: '$nombre_base',labels: {visible: true,verticalAlignment: 'top',offset: { x: 0, y: -20 } } ,formatFunction: function (value) {return value + ' %';},total_general:$total,total_indicador:$total_indicador,hombres:$total_hombres,mujeres:$total_mujeres},";
            $rango .= ", $id:$porcentaje_indicador";


            $series_cobertura .=" \n{ dataField: '$id', displayText: '$nombre_base',labels: {visible: true,verticalAlignment: 'top',offset: { x: 0, y: -20 } },formatFunction: function (value) {return value + ' %';} ,total_cobertura:$total_cobertura,total_general:$total,hombres:$total_hombres,mujeres:$total_mujeres},";
            $rango_cobertura .= ", $id:$porcentaje_cobertura";
        }
        $rango .= "},";
        $rango_cobertura .= "},";








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
                                    inner join vacunas_paciente on persona.rut=vacunas_paciente.rut  
                                    where paciente_establecimiento.id_establecimiento='$id_establecimiento'
                                    and paciente_establecimiento.m_infancia='SI' 
                                    $filtro_edad
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
            $rango_cobertura .= "{ Rango:'".$estado."'";
            while($row1 = mysql_fetch_array($res1)){
                $nombre_base = $row1['nombre_base']." [".$row1['nombre_establecimiento']."]";
                $id = $row1['id'];

                $total = $row1['total']!='' ?$row1['total']:0; // general de pacientes que califican para el indicador
                $total_hombres   = $row1['total_hombres']!='' ?$row1['total_hombres']:0;
                $total_mujeres   = $row1['total_mujeres']!='' ?$row1['total_mujeres']:0;
                $total_indicador = $row1['total_indicador']!='' ?$row1['total_indicador']:0;
                $total_cobertura = $row1['total_cobertura']!='' ?$row1['total_cobertura']:0;

                $porcentaje_indicador = number_format(($total_indicador*100/$total_cobertura),0,'.','');
                $porcentaje_cobertura = number_format(($total_cobertura*100/$total),0,'.','');

                $series .=" { dataField: '$id', displayText: '$nombre_base',labels: {visible: true,verticalAlignment: 'top',offset: { x: 0, y: -20 } } ,formatFunction: function (value) {return value + ' %';},total_general:$total,total_indicador:$total_indicador,hombres:$total_hombres,mujeres:$total_mujeres},";
                $rango .= ", $id:$porcentaje_indicador";


                $series_cobertura .=" \n{ dataField: '$id', displayText: '$nombre_base',labels: {visible: true,verticalAlignment: 'top',offset: { x: 0, y: -20 } },formatFunction: function (value) {return value + ' %';} ,total_cobertura:$total_cobertura,total_general:$total,hombres:$total_hombres,mujeres:$total_mujeres},";
                $rango_cobertura .= ", $id:$porcentaje_cobertura";
            }
            $rango .= "},";
            $rango_cobertura .= "},";




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
                                    inner join vacunas_paciente on persona.rut=vacunas_paciente.rut  
                                    where paciente_establecimiento.id_establecimiento='$id_establecimiento'
                                    $filtro_edad  
                                    and paciente_establecimiento.m_infancia='SI'   
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
            $rango_cobertura .= "{ Rango:'".$estado."' ";
            while($row1 = mysql_fetch_array($res1)){
                $nombre_base = $row1['nombre_base']." [".$row1['nombre_establecimiento']."]";
                $id = $row1['id'];

                $total = $row1['total']!='' ?$row1['total']:0; // general de pacientes que califican para el indicador
                $total_hombres   = $row1['total_hombres']!='' ?$row1['total_hombres']:0;
                $total_mujeres   = $row1['total_mujeres']!='' ?$row1['total_mujeres']:0;
                $total_indicador = $row1['total_indicador']!='' ?$row1['total_indicador']:0;
                $total_cobertura = $row1['total_cobertura']!='' ?$row1['total_cobertura']:0;

                $porcentaje_indicador = number_format(($total_indicador*100/$total_cobertura),0,'.','');
                $porcentaje_cobertura = number_format(($total_cobertura*100/$total),0,'.','');

                $series .=" { dataField: '$id', displayText: '$nombre_base',labels: {visible: true,verticalAlignment: 'top',offset: { x: 0, y: -20 } } ,formatFunction: function (value) {return value + ' %';},total_general:$total,total_indicador:$total_indicador,hombres:$total_hombres,mujeres:$total_mujeres},";
                $rango .= ", $id:$porcentaje_indicador";


                $series_cobertura .=" \n{ dataField: '$id', displayText: '$nombre_base',labels: {visible: true,verticalAlignment: 'top',offset: { x: 0, y: -20 } },formatFunction: function (value) {return value + ' %';} ,total_cobertura:$total_cobertura,total_general:$total,hombres:$total_hombres,mujeres:$total_mujeres},";
                $rango_cobertura .= ", $id:$porcentaje_cobertura";
            }
            $rango .= "},";
            $rango_cobertura .= "},";

        }
    }
}
//echo $sql1;
$estado = $estado=='' ? 'PENDIENTE':$estado;

?>
<script type="text/javascript">
    $(document).ready(function () {
        //COBERTURA OBSERVADA
        var  data_cobertura = [
            <?php echo $rango_cobertura; ?>
        ];
        var toolTips_COBERTURA_VACUNAS = function (value, itemIndex, serie, group, categoryValue, categoryAxis) {
            // var dataItem = sampleData[itemIndex];

            return '<DIV style="text-align:left">' +
                '<b>' +serie.displayText+'</b><br />'+
                'Porcentaje: <b>' +value+'%</b><br />'+
                'Datos: <b>' +serie.total_cobertura+'/'+serie.total_general+'</b><br />'+
                'Hombres: <b>' +serie.hombres+' ('+parseInt(serie.hombres*100/serie.total_general) +'%)</b><br />'+
                'Mujeres: <b>' +serie.mujeres+' ('+parseInt(serie.mujeres*100/serie.total_general) +'%)</b><br />'+
                '</DIV>';
        };
        var setting_cobertura = {
            title: 'COBERTURA - <?php echo $TITULO_GRAFICO; ?>',
            description: '<?php echo $rango_edad_texto; ?>',
            enableAnimations: true,
            showLegend: true,
            padding: { left: 5, top: 5, right: 5, bottom: 5 },
            titlePadding: { left: 90, top: 0, right: 0, bottom: 10 },
            source: data_cobertura,
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
                        toolTipFormatFunction: toolTips_COBERTURA_VACUNAS,
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
                            <?php echo $series_cobertura; ?>
                        ]
                    }
                ]
        };

        // setup the chart
        $('#vacunas_cobertura').jqxChart(setting_cobertura);


        var data = '[<?php echo $json; ?>]';

        //grid
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
                    { name: 'INDICADOR', type: 'string' },

                ],
                localdata: data
            };
        var cellLinkRegistroTarjetero = function(row, columnfield, value, defaulthtml, columnproperties, rowdata) {
            return ''+
                '<a onclick="loadMenu_Infantil(\'menu_1\',\'registro_tarjetero\',\''+value+'\')"  style="color: white;" >' +
                '<i class="mdi-hardware-keyboard-return"></i> IR' +
                '</a>';
        }
        var cellIrClass = function(row, columnfield, value, defaulthtml, columnproperties, rowdata) {
            return  "eh-open_principal white-text cursor_cell_link center";

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

        var dataAdapter = new $.jqx.dataAdapter(source);

        $("#table_grid").jqxGrid(
            {
                width: '95%',
                height:400,
                theme: 'eh-open',
                source: dataAdapter,
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
                    // { text: 'EDAD', dataField: 'EDAD', cellsalign: 'left', width: 250,cellsrenderer:cellEdadAnios},
                    { text: 'AÑO', datafield: 'anios', width: 80 ,filtertype: 'checkedlist', cellsalign: 'center'},
                    { text: 'MES', datafield: 'meses', width: 80 ,filtertype: 'checkedlist', cellsalign: 'center'},
                    { text: 'DIA', datafield: 'dias', width: 80 ,filtertype: 'checkedlist', cellsalign: 'center'},
                    { text: '<?php echo $TITULO_GRAFICO; ?>', dataField: 'INDICADOR', cellsalign: 'left', width: 250,filtertype: 'checkedlist' },
                    { text: 'S. COMUNAL', dataField: 'COMUNAL', cellsalign: 'left', width: 250,filtertype: 'checkedlist' },
                    { text: 'ESTABLECIMIENTO', dataField: 'ESTABLECIMIENTO', cellsalign: 'left', width: 250,filtertype: 'checkedlist' },
                    { text: 'SECTOR_INTERNO', dataField: 'SECTOR_INTERNO', cellsalign: 'left', width: 250,filtertype: 'checkedlist' },

                ]
            });
        $("#excelExport").click(function () {

            $("#table_grid").jqxGrid('exportdata', 'xls', 'vacunas', true,null,true, 'excel/save-file.php');


        });
        $("#print").click(function () {
            var content = $('#div_imprimir')[0].outerHTML;
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
    <div class="row right-align">
        <button id="print">IMPRIMIR</button>
    </div>
    <div class="card-panel">
        <div class="row">
            <div class="col l12 m12 s12">
                <label for="estado">VACUNAS</label>
                <select id="estado" name="estado">
                    <option><?php echo $estado; ?></option>
                    <option disabled>-----------------</option>
                    <?php
                    $sql3 = "show columns from vacunas_paciente where Field!='rut' ";
                    $res3 = mysql_query($sql3);
                    while($row3 = mysql_fetch_array($res3)){
                        ?>
                        <option><?php echo $row3['Field']; ?></option>
                        <?php
                    }
                    ?>
                </select>
                <script type="text/javascript">
                    $(function(){
                        $('#estado').jqxDropDownList({
                            width: '100%',
                            theme: 'eh-open',
                            height: '25px'
                        });
                        $('#estado').on('select', function (event) {
                            loadGraficoVacunas_tipo();
                        });

                    });
                    function loadGraficoVacunas_tipo() {
                        var estado = $("#estado").val();

                        $.post('graficos/barra/VACUNAS.php',{
                            sector_comunal:sector_comunal,
                            centro_interno:centro_interno,
                            sector_interno:sector_interno,
                            estado:estado,
                        },function(data){
                            $("#div_indicador_grafico").html(data);

                        });

                    }
                </script>
            </div>
        </div>
        <div class="row">
            <div class="col l12 m12 s12">
                <div id='vacunas_cobertura' style="width: 100%;height: 500px;"></div>
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
            button{
                display: none;
            }
        }
        a{
            border: none;
            text-decoration: none;
        }
        a:hover{
            background-color: #438eb9;
        }

    </style>
    <div class="card-panel" id="tabla_grilla">
        <div class="row">
            <div class="col l12 m12 s12">
                <input type="button" class="btn light-green darken-3 white-text right" value="EXPORTAR TABLA" id="excelExport" />
            </div>
        </div>
        <div class="row">
            <div class="col l12 m12 s12">
                <div id="table_grid"></div>
            </div>
        </div>
    </div>
</div>
