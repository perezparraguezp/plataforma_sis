<?php
include '../../config.php';
include '../../objetos/persona.php';

//session_start();

$id_establecimiento = $_SESSION['id_establecimiento'];


$sector_comunal = explode(",",$_POST['sector_comunal']);
$centro_interno = explode(",",$_POST['centro_interno']);
$sector_interno = explode(",",$_POST['sector_interno']);

$estado = trim($_POST['estado']);

$indicador      = $_POST['indicador'];
$indicador_estado = $indicador;

if($indicador=='IMCE'){
    $filtro_edad = 'persona.edad_total>=((5*12)+1) ';//5 años y un mes
    $rango_edad_texto = 'Mayores de 5 años y 1 Mes';
}else{
    if($indicador == 'PCINT'){
        $filtro_edad = 'persona.edad_total>=(5*12) ';//Dede los 5 años
        $rango_edad_texto = 'Desde los 3 Años';
    }else{
        if($indicador=='presion_arterial'){
            $rango_edad_texto = 'Desde los 3 Años';
            $filtro_edad = 'persona.edad_total>=(3*12) ';//desde los 3 años
        }else{
            if($indicador=='DNI1'){
                //MENORES DE 6 AÑOS
                $filtro_edad = 'persona.edad_total<(6*12) ';//Dede los 5 años
                $rango_edad_texto = 'Menores de 6 Años';
                $indicador = 'DNI';
            }else{
                if($indicador=='DNI2'){
                    //ENTRE 6 AÑOS A 9 AÑOS
                    $filtro_edad = 'persona.edad_total>=(6*12) AND persona.edad_totaL<(9*12)';//Dede los 5 años
                    $rango_edad_texto = 'Desde los 6 hasta los 9 Años';
                    $indicador = 'DNI';
                }else{
                    if($indicador=='DNI3'){
                        $filtro_edad = 'persona.edad_total<(10*12) ';//menores de 10 años
                        $rango_edad_texto = 'Todos los niños menores de 10 años';
                        $indicador = 'DNI';
                    }else{
                        if($indicador=='SCORE_IRA'){
                            $filtro_edad = 'persona.edad_total<8 ';//menores de 8 meses
                            $rango_edad_texto = 'Menores de 8 meses';
                        }else{
                            if($indicador=='LME'){
                                $filtro_edad = 'persona.edad_total<8 ';//menores de 8 meses
                                $rango_edad_texto = 'Menores de 8 meses';
                            }else{
                                if($indicador=='perimetro_craneal'){
                                    $filtro_edad = 'persona.edad_total<8 ';//menores de 8 meses
                                    $rango_edad_texto = 'Menores de 8 meses';
                                }
                            }
                        }
                    }
                }
            }
        }

    }
}


$TITULO_GRAFICO = strtoupper(str_replace("_"," ",$indicador));



$sql_column = '';
$sql_column .= ",sum(antropometria.$indicador='$estado' and $filtro_edad) as total_indicador";
$sql_column .= ",sum(antropometria.$indicador='$estado' and persona.sexo='M' and  $filtro_edad) as total_hombres";
$sql_column .= ",sum(antropometria.$indicador='$estado' and persona.sexo='F' and $filtro_edad) as total_mujeres";
$sql_column .= ",sum(antropometria.$indicador!='' and $filtro_edad) as total_cobertura";


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

if($comunal==true){
    //para todos los sectores comunales
    $sql1 = "select 'GENERAL' as nombre_base,count(*) as total 
                                    $sql_column
                                    from persona
                                    inner join paciente_establecimiento using (rut)
                                    inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno
                                    inner join centros_internos on sectores_centros_internos.id_centro_interno=centros_internos.id_centro_interno
                                    inner join sector_comunal on centros_internos.id_sector_comunal=sector_comunal.id_sector_comunal
                                    inner join antropometria on antropometria.rut=persona.rut 
                                    where $filtro_edad 
                                    and paciente_establecimiento.id_establecimiento='$id_establecimiento' 
                                    ";
    $row1 = mysql_fetch_array(mysql_query($sql1));

    $total = $row1['total']!='' ?$row1['total']:0; // general de pacientes que califican para el indicador
    $total_hombres   = $row1['total_hombres']!='' ?$row1['total_hombres']:0;
    $total_mujeres   = $row1['total_mujeres']!='' ?$row1['total_mujeres']:0;
    $total_indicador = $row1['total_indicador']!='' ?$row1['total_indicador']:0;
    $total_cobertura = $row1['total_cobertura']!='' ?$row1['total_cobertura']:0;

    $porcentaje_indicador = number_format(($total_indicador*100/$total),1,'.','');
    $porcentaje_cobertura = number_format(($total_cobertura*100/$total),1,'.','');

    $rango .= "\n{ Rango:'GENERAL',GENERAL: ".$porcentaje_indicador."},";
    $series .=" \n{ dataField: 'GENERAL', displayText: '$estado',labels: {visible: true,verticalAlignment: 'top',offset: { x: 1, y: -20 } },formatFunction: function (value) {return value + ' %';} ,total_general:$total,total_indicador:$porcentaje_indicador,hombres:$total_hombres,mujeres:$total_mujeres},";

    //cobertura
    $sql2 = "select * from persona
                        inner join paciente_establecimiento using (rut)
                        inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno
                        inner join centros_internos on sectores_centros_internos.id_centro_interno=centros_internos.id_centro_interno
                        inner join sector_comunal on centros_internos.id_sector_comunal=sector_comunal.id_sector_comunal 
                        where $filtro_edad    
                                    and paciente_establecimiento.id_establecimiento='$id_establecimiento'
                                    group by persona.rut ";
    $res2 = mysql_query($sql2);
    $total_pacientes = 0;
    $total_cobertura = 0;
    while($row2 = mysql_fetch_array($res2)){

        $persona = new persona($row2['rut']);
        if($persona->getAntropometria($indicador)!='PENDIENTE'){
            $total_cobertura++;
        }
        if($total_pacientes>0){
            $json.=',';
        }
        $json .= '{"RUT":"'.$persona->rut.'","NOMBRE":"'.$persona->nombre.'","COMUNAL":"'.$row2['nombre_sector_comunal'].'","ESTABLECIMIENTO":"'.$row2['nombre_centro_interno'].'","SECTOR_INTERNO":"'.$row2['nombre_sector_interno'].'","INDICADOR":"'.$persona->getAntropometria($indicador).'"}';

        $total_pacientes++;
    }

    $porcentaje_cobertura = number_format(($total_cobertura*100/$total_pacientes),1,'.','');


    $rango_cobertura .= "\n{ Estado: 'APLICADO', Porcentaje: $porcentaje_cobertura },";
    $rango_cobertura .= "\n{ Estado: 'PENDIENTE', Porcentaje: ".(100-$porcentaje_cobertura)." },";

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
                                    inner join antropometria on antropometria.rut=persona.rut  
                                    where $filtro_edad    
                                    and paciente_establecimiento.id_establecimiento='$id_establecimiento' 
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
            $total_cobertura = $row1['total_cobertura']!='' ?$row1['total_cobertura']:0;

            $porcentaje = number_format(($total_indicador*100/$total),1,'.','');

            $series .=" { dataField: '$id', displayText: '$nombre_base',labels: {visible: true,verticalAlignment: 'top',offset: { x: 1, y: -20 } } ,formatFunction: function (value) {return value + ' %';},total_general:$total,total_indicador:$total_indicador,hombres:$total_hombres,mujeres:$total_mujeres},";
            $rango .= ", $id:$porcentaje";
        }
        $rango .= "},";

        //cobertura
        $sql2 = "select * from persona
                        inner join paciente_establecimiento using (rut)
                        inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno
                        inner join centros_internos on sectores_centros_internos.id_centro_interno=centros_internos.id_centro_interno
                        inner join sector_comunal on centros_internos.id_sector_comunal=sector_comunal.id_sector_comunal 
                        where $filtro_edad    
                                    and paciente_establecimiento.id_establecimiento='$id_establecimiento' 
                                    AND (";
        $a = 0;
        foreach ($sector_comunal as $i => $id_sector_comunal){
            $id_sector_comunal = trim($id_sector_comunal);
            if($id_sector_comunal!='' && $id_sector_comunal != null){
                if($a>0){
                    $sql2.=' or ';
                }
                $sql2 .= "centros_internos.id_sector_comunal='$id_sector_comunal' ";
                $a++;
            }

        }
        $sql2.=') group by persona.rut';
        $res2 = mysql_query($sql2);
        $total_pacientes = 0;
        $total_cobertura = 0;
        while($row2 = mysql_fetch_array($res2)){

            $persona = new persona($row2['rut']);
            if($persona->getAntropometria($indicador)!='PENDIENTE'){
                $total_cobertura++;
            }
            if($total_pacientes>0){
                $json.=',';
            }
            $json .= '{"RUT":"'.$persona->rut.'","NOMBRE":"'.$persona->nombre.'","COMUNAL":"'.$row2['nombre_sector_comunal'].'","ESTABLECIMIENTO":"'.$row2['nombre_centro_interno'].'","SECTOR_INTERNO":"'.$row2['nombre_sector_interno'].'","INDICADOR":"'.$persona->getAntropometria($indicador).'"}';

            $total_pacientes++;
        }

        $porcentaje_cobertura = number_format(($total_cobertura*100/$total_pacientes),1,'.','');


        $rango_cobertura .= "\n{ Estado: 'APLICADO', Porcentaje: $porcentaje_cobertura },";
        $rango_cobertura .= "\n{ Estado: 'PENDIENTE', Porcentaje: ".(100-$porcentaje_cobertura)." },";



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
                                    inner join antropometria on persona.rut=antropometria.rut  
                                    where $filtro_edad
                                    and paciente_establecimiento.id_establecimiento='$id_establecimiento'
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
                $total_cobertura = $row1['total_cobertura']!='' ?$row1['total_cobertura']:0;

                $porcentaje = number_format(($total_indicador*100/$total),1,'.','');

                $series .=" { dataField: '$id', displayText: '$nombre_base',labels: {visible: true,verticalAlignment: 'top',offset: { x: 1, y: -20 } } ,formatFunction: function (value) {return value + ' %';},total_general:$total,total_indicador:$total_indicador,hombres:$total_hombres,mujeres:$total_mujeres},";
                $rango .= ", $id:$porcentaje";
            }
            $rango .= "},";
            //cobertura
            $sql2 = "select * from persona
                        inner join paciente_establecimiento using (rut)
                        inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno
                        inner join centros_internos on sectores_centros_internos.id_centro_interno=centros_internos.id_centro_interno
                        inner join sector_comunal on centros_internos.id_sector_comunal=sector_comunal.id_sector_comunal 
                        where $filtro_edad    
                                    and paciente_establecimiento.id_establecimiento='$id_establecimiento' 
                                    AND (";
            $a = 0;
            foreach ($centro_interno as $i => $id_centro_interno){
                $id_centro_interno = trim($id_centro_interno);
                if($id_centro_interno!='' && $id_centro_interno != null){
                    if($a>0){
                        $sql2.=' or ';
                    }
                    $sql2 .= "centros_internos.id_centro_interno='$id_centro_interno' ";
                    $a++;
                }

            }
            $sql2.=') group by persona.rut';
            $res2 = mysql_query($sql2);
            $total_pacientes = 0;
            $total_cobertura = 0;
            while($row2 = mysql_fetch_array($res2)){

                $persona = new persona($row2['rut']);
                if($persona->getAntropometria($indicador)!='PENDIENTE'){
                    $total_cobertura++;
                }
                if($total_pacientes>0){
                    $json.=',';
                }
                $json .= '{"RUT":"'.$persona->rut.'","NOMBRE":"'.$persona->nombre.'","COMUNAL":"'.$row2['nombre_sector_comunal'].'","ESTABLECIMIENTO":"'.$row2['nombre_centro_interno'].'","SECTOR_INTERNO":"'.$row2['nombre_sector_interno'].'","INDICADOR":"'.$persona->getAntropometria($indicador).'"}';

                $total_pacientes++;
            }

            $porcentaje_cobertura = number_format(($total_cobertura*100/$total_pacientes),1,'.','');


            $rango_cobertura .= "\n{ Estado: 'APLICADO', Porcentaje: $porcentaje_cobertura },";
            $rango_cobertura .= "\n{ Estado: 'PENDIENTE', Porcentaje: ".(100-$porcentaje_cobertura)." },";




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
                                    inner join antropometria on persona.rut=antropometria.rut  
                                    where $filtro_edad 
                                    and paciente_establecimiento.id_establecimiento='$id_establecimiento'  
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

                $total = $row1['total']!='' ?$row1['total']:0; // general de pacientes que califican para el indicador
                $total_hombres   = $row1['total_hombres']!='' ?$row1['total_hombres']:0;
                $total_mujeres   = $row1['total_mujeres']!='' ?$row1['total_mujeres']:0;
                $total_indicador = $row1['total_indicador']!='' ?$row1['total_indicador']:0;
                $total_cobertura = $row1['total_cobertura']!='' ?$row1['total_cobertura']:0;

                $porcentaje = number_format(($total_indicador*100/$total),1,'.','');

                $series .=" { dataField: '$id', displayText: '$nombre_base',labels: {visible: true,verticalAlignment: 'top',offset: { x: 1, y: -20 } } ,formatFunction: function (value) {return value + ' %';},total_general:$total,total_indicador:$total_indicador,hombres:$total_hombres,mujeres:$total_mujeres},";
                $rango .= ", $id:$porcentaje";
            }
            $rango .= "},";

            //cobertura
            $sql2 = "select * from persona
                        inner join paciente_establecimiento using (rut)
                        inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno
                        inner join centros_internos on sectores_centros_internos.id_centro_interno=centros_internos.id_centro_interno
                        inner join sector_comunal on centros_internos.id_sector_comunal=sector_comunal.id_sector_comunal 
                        where $filtro_edad    
                                    and paciente_establecimiento.id_establecimiento='$id_establecimiento' 
                                    AND (";
            $a = 0;
            foreach ($sector_interno as $i => $id_sector_interno){
                $id_sector_interno = trim($id_sector_interno);
                if($id_sector_interno!='' && $id_sector_interno != null){
                    if($a>0){
                        $sql2.=' or ';
                    }
                    $sql2 .= "sectores_centros_internos.id_sector_centro_interno='$id_sector_interno' ";
                    $a++;
                }

            }
            $sql2.=') group by persona.rut';
            $res2 = mysql_query($sql2);
            $total_pacientes = 0;
            $total_cobertura = 0;
            while($row2 = mysql_fetch_array($res2)){

                $persona = new persona($row2['rut']);
                if($persona->getAntropometria($indicador)!='PENDIENTE'){
                    $total_cobertura++;
                }
                if($total_pacientes>0){
                    $json.=',';
                }
                $json .= '{"RUT":"'.$persona->rut.'","NOMBRE":"'.$persona->nombre.'","COMUNAL":"'.$row2['nombre_sector_comunal'].'","ESTABLECIMIENTO":"'.$row2['nombre_centro_interno'].'","SECTOR_INTERNO":"'.$row2['nombre_sector_interno'].'","INDICADOR":"'.$persona->getAntropometria($indicador).'"}';

                $total_pacientes++;
            }

            $porcentaje_cobertura = number_format(($total_cobertura*100/$total_pacientes),1,'.','');


            $rango_cobertura .= "\n{ Estado: 'APLICADO', Porcentaje: $porcentaje_cobertura },";
            $rango_cobertura .= "\n{ Estado: 'PENDIENTE', Porcentaje: ".(100-$porcentaje_cobertura)." },";

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


        // prepare jqxChart settings
        var settings1 = {
            title: '<?php echo $TITULO_GRAFICO; ?>',
            description: '<?php echo $rango_edad_texto; ?>',
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
        $('#dni_menor_6anios').jqxChart(settings1);

        function myEventHandler(event) {
            var eventData = '<div><b>Total General: </b>' + event.args.serie.total_general + '<b>, Total Indicador: </b>' + event.args.serie.total_indicador + "</div>";

            //$('#eventText').html(eventData);
            alertaLateral(eventData);
        };
        // select the chartContainer DIV element and render the chart.
        var  data_cobertura = [
            <?php echo $rango_cobertura; ?>
        ];
        var toolTips_COBERTURA = function (value, itemIndex, serie, group, categoryValue, categoryAxis) {
            var dataItem = sampleData[itemIndex];

            return '<DIV style="text-align:left">' +
                '<b>' +dataItem['Estado']+'aa</b><br />'+
                'Porcentaje: <b>' +value+'%</b><br />'+
                'TOTAL: <b>' +serie.total+'</b><br />'+
                '</DIV>';
        };
        var settings = {
            title: "COBERTURA TOTAL",
            description: "<?php echo $TITULO_GRAFICO; ?>",
            enableAnimations: false,
            showLegend: true,
            showBorderLine: true,
            legendPosition: { left: 520, top: 140, width: 100, height: 100 },
            padding: { left: 5, top: 5, right: 5, bottom: 5 },
            titlePadding: { left: 0, top: 0, right: 0, bottom: 10 },
            source: data_cobertura,
            colorScheme: 'scheme01',
            seriesGroups:
                [
                    {
                        type: 'pie',
                        showLabels: true,
                        series:
                            [
                                {
                                    dataField: 'Porcentaje',
                                    displayText: 'Estado',
                                    labelRadius: 90,
                                    initialAngle: 15,
                                    radius: 80,

                                    centerOffset: 0,
                                    formatFunction: function (value, itemIdx, serieIndex, groupIndex) {
                                        if (isNaN(value))
                                            return value;
                                        return value + '%';
                                    }
                                }
                            ]
                    }
                ]
        };
        // setup the chart
        $('#chart_cobertura').jqxChart(settings);

        var data = '[<?php echo $json; ?>]';
        //grid
        var source =
            {
                datatype: "json",
                datafields: [
                    { name: 'RUT', type: 'string' },
                    { name: 'NOMBRE', type: 'string' },
                    { name: 'COMUNAL', type: 'string' },
                    { name: 'ESTABLECIMIENTO', type: 'string' },
                    { name: 'SECTOR_INTERNO', type: 'string' },
                    { name: 'INDICADOR', type: 'string' },

                ],
                localdata: data
            };

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
                    { text: 'RUT', dataField: 'RUT', cellsalign: 'right', width: 150 },
                    { text: 'NOMBRE COMPLETO', dataField: 'NOMBRE' ,aggregates: ['count'],aggregatesrenderer: function (aggregates, column, element, summaryData) {
                            var renderstring = "<div  style='float: left; width: 100%; height: 100%;'>";
                            $.each(aggregates, function (key, value) {
                                var name = 'Total Pacientes';
                                renderstring += '<div style="; position: relative; margin: 6px; text-align: right; overflow: hidden;">' + name + ': ' + value + '</div>';
                            });
                            renderstring += "</div>";
                            return renderstring;
                        }},
                    { text: '<?php echo $indicador; ?>', dataField: 'INDICADOR', cellsalign: 'left', width: 150,filtertype: 'checkedlist' },
                    { text: 'S. COMUNAL', dataField: 'COMUNAL', cellsalign: 'left', width: 150,filtertype: 'checkedlist' },
                    { text: 'ESTABLECIMIENTO', dataField: 'ESTABLECIMIENTO', cellsalign: 'left', width: 150,filtertype: 'checkedlist' },
                    { text: 'SECTOR_INTERNO', dataField: 'SECTOR_INTERNO', cellsalign: 'left', width: 150,filtertype: 'checkedlist' },

                ]
            });
        $("#excelExport").click(function () {
            $("#table_grid").jqxGrid('exportdata', 'xls', 'jqxGrid');
        });
    });
</script>

<div>
    <div class="col l12">
        <div class="col l4">
            <!-- GRAFICO DE COBERTURA -->
            <div id='chart_cobertura'
                 style="width:95%; height:400px; position: relative; left: 0px; top: 0px;"></div>
        </div>
        <div class="col l8">
            <!-- GRAFICO DE BARRAS -->
            <div class="row">
                <div class="col l12">
                    <div class="col l4">
                        <label for="estado">ESTADO</label>
                        <select id="estado" name="estado" >
                            <option><?php echo $estado; ?></option>
                            <option disabled>-----------------</option>
                            <?php
                            $sql3 = "select * from antropometria 
                                        where $indicador!='' 
                                        group by $indicador";
                            $res3 = mysql_query($sql3);
                            while($row3 = mysql_fetch_array($res3)){
                                ?>
                                <option><?php echo $row3[$indicador]; ?></option>
                                <?php
                            }
                            ?>
                            <option value="">PENDIENTE</option>
                        </select>
                        <script type="text/javascript">
                            $(function(){
                                $('#estado').jqxDropDownList({
                                    width: '100%',
                                    height: '25px'
                                });
                                $('#estado').on('select', function (event) {
                                    loadIndicador_Grafico_PRESIONARTERIAL();
                                });

                            });
                            function loadIndicador_Grafico_PRESIONARTERIAL() {
                                var estado = $("#estado").val();
                                var indicador = '<?php echo $indicador_estado ?>';


                                $.post('php/graficos/barra/ANTROPOMETRIA_GENERAL.php',{
                                    sector_comunal:sector_comunal,
                                    centro_interno:centro_interno,
                                    sector_interno:sector_interno,
                                    indicador:indicador,
                                    estado:estado,
                                },function(data){
                                    $("#div_indicador_grafico").html(data);
                                    //updateHeadEscritorio(sector_comunal,centro_interno,sector_interno);
                                });
                                updateHeadEscritorio();
                            }
                        </script>
                    </div>
                </div>
                <div class="col l12">
                    <div id='dni_menor_6anios' style="width:95%; height:400px; position: relative; left: 0px; top: 0px;"></div>
                </div>
            </div>

        </div>
    </div>
    <hr class="row" />
    <div class="row">
        <div class="card">
            <div class="row">
                <div class="col l12">
                    <input type="button" class="btn light-green darken-3 white-text right" value="EXPORTAR TABLA" id="excelExport" />
                </div>
            </div>
            <hr class="row" />
            <div class="row">
                <div class="col l12" style="padding: 10px;padding-left: 20px;">
                    <div id="table_grid"></div>
                </div>
            </div>
        </div>
    </div>
</div>
