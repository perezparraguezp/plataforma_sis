<?php
include "../../../php/config.php";
include '../../../php/objetos/mysql.php';


$mysql = new mysql($_SESSION['id_usuario']);

$sql_1 = "UPDATE persona set edad_total_dias=TIMESTAMPDIFF(DAY, fecha_nacimiento, current_date) 
            where fecha_update_dias!=CURRENT_DATE() ";



$id_establecimiento = $_SESSION['id_establecimiento'];

$id_centro = $_POST['id'];
if($id_centro!=''){
    $filtro_centro = " and id_centro_interno='$id_centro' ";
    $sql0 = "select * from centros_internos 
                              WHERE id_centro_interno='$id_centro' limit 1";
    $row0 = mysql_fetch_array(mysql_query($sql0));
    $nombre_centro = $row0['nombre_centro_interno'];
}else{
    $nombre_centro = 'TODOS LOS CENTROS';
}


//rango de meses en dias
$rango_seccion_d7 = [
    'persona.edad_total_dias>=0 and persona.edad_total_dias<365', //menor 1 año
    'persona.edad_total>=(1) and persona.edad_total<=(12)',//1 año
    'persona.edad_total>(12) and persona.edad_total<=(12*2)',//2 año
    'persona.edad_total>((12*2)) and persona.edad_total<=(12*3)',//3 año
    'persona.edad_total>((12*3)) and persona.edad_total<=(12*4)',//4 año
    'persona.edad_total>((12*4)) and persona.edad_total<=(12*5)',//5 año
    'persona.edad_total>((12*5)) and persona.edad_total<=(12*6)',//6 año
    'persona.edad_total>((12*6)) and persona.edad_total<=(12*7)',//7 año
    'persona.edad_total>((12*7)) and persona.edad_total<=(12*8)',//8 año
    'persona.edad_total>((12*8)) and persona.edad_total<=(12*9)',//9 año
    'persona.edad_total>((12*9)) and persona.edad_total<=(12*14)',//10 a 14 años
    'persona.edad_total>((12*14)) and persona.edad_total<=(12*19)',//15 a 19 años
];
$rango_seccion_d7_text = [
    '< 1 AÑO', //menor 1 año
    '1 AÑO',//1 año
    '2 AÑOS',//2 año
    '3 AÑOS',//3 año
    '4 AÑOS',//4 año
    '5 AÑOS',//5 año
    '6 AÑOS',//6 año
    '7 AÑOS',//7 año
    '8 AÑOS',//8 año
    '9 AÑOS',//9 año
    '10 A 14 AÑOS',//10 a 14 años
    '15 A 19 AÑOS',//15 a 19 años
];

$sexo = [
    "persona.sexo='M' ",
    "persona.sexo='F' "
];


?>
<style type="text/css">
    table, tr, td {
        padding: 10px;
        border: 1px solid black;
        border-collapse: collapse;
        font-size: 0.8em;
        text-align: center;
    }
    section{
        padding-top: 10px;
        padding-left: 10px;
    }
    header{
        font-weight: bold;;
    }
</style>
<div class="card" id="todo_p3">
    <div class="row" style="padding:20px;">
        <div class="col l10">
            <header>CENTRO MEDICO: <?php echo $nombre_centro; ?></header>
        </div>
        <div class="col l2">
            <input type="button"
                   class="btn green lighten-2 white-text"
                   value="EXPORTAR A EXCEL" onclick="exportTable('todo_p3','P3')" />
        </div>
    </div>
    <hr class="row" style="margin-bottom: 10px;" />
    <section id="seccion_d" style="width: 100%;overflow-y: scroll;">
        <div class="row">
            <div class="col l10">
                <header>SECCION D: OTRAS EVALUACIONES, APLICACIONES Y RESULTADOS DE ESCALAS EN TODAS LAS EDADES</header>
            </div>
        </div>
        <div class="row">
            <div class="col l10">
                <header>SECCION D.7: APLICACIÓN Y RESULTADOS DE PAUTA DE EVALUACION CON ENFOQUE DE RIESGO ODONTÓLOGICO (CERO)</header>
            </div>
        </div>
        <table id="table_seccion_d7" style="width: 100%;border: solid 1px black;" border="1">
            <tr>
                <td colspan="2" rowspan="3"
                    style="width: 400px;background-color: #fdff8b;position: relative;text-align: center;">
                    PAUTA CERO
                </td>
                <td rowspan="2" colspan="3">
                    TOTAL
                </td>
                <td colspan="24">
                    POR EDAD
                </td>
                <td colspan="2" rowspan="2">
                    NIÑOS, NIÑAS Y ADOLESCENTES RED SENAME
                </td>
            </tr>
            <tr>
                <?php
                foreach ($rango_seccion_d7_text as $i => $item){
                    echo '<td colspan="2">'.$item.'</td>';
                }
                ?>
            </tr>
            <tr>
                <td>AMBOS SEXOS</td>
                <td>HOMBRES</td>
                <td>MUJERES</td>
                <?php
                foreach ($rango_seccion_d7 as $i => $item){
                    echo '<td>HOMBRES</td>
                          <td>MUJERES</td>';
                }
                ?>
                <td>HOMBRES</td>
                <td>MUJERES</td>
            </tr>
            <tr>
                <td rowspan="2">EVALUACION DE RIESGO</td>
                <td>BAJO RIESGO</td>
                <?php
                $thph = 0;
                $thpm = 0;
                $thm = 0;
                $tmm = 0;
                $tabla = 'historial_dental';
                $indicador = 'riesgo';
                $valor = 'BAJO';
                $PE[$valor]['MUJERES'] =0;
                $PE[$valor]['HOMBRES'] =0;
                $PE[$valor]['AMBOS'] =0;
                $fila = '';
                foreach ($rango_seccion_d7 as $i => $rango){
                    $sex = $sexo[0];//hombres
                    if($id_centro!=''){
                        $sql = "select sum(upper(indicador)=upper('riesgo') and valor='$valor' and $rango and $sex) as total 
                        from historial_dental 
                        inner join persona using(rut) 
                        inner join paciente_establecimiento on persona.rut=paciente_establecimiento.rut
                        inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno 
                        where sectores_centros_internos.id_centro_interno='$id_centro'
                        limit 1";
                    }else{
                        $sql = "select sum(upper(indicador)=upper('riesgo') and valor='$valor' and $rango and $sex) as total 
                        from historial_dental 
                        inner join persona using(rut) 
                        inner join paciente_establecimiento on persona.rut=paciente_establecimiento.rut
                        inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno 
                        limit 1";
                    }
                    $row = mysql_fetch_array(mysql_query($sql));
                    if($row){
                        $total_hombres = $row['total'];
                    }else{
                        $total_hombres = 0;
                    }

                    $sex = $sexo[1];//mujeres
                    if($id_centro!=''){
                        $sql = "select sum(upper(indicador)=upper('riesgo') and valor='$valor' and $rango and $sex) as total 
                        from historial_dental 
                        inner join persona using(rut) 
                        inner join paciente_establecimiento on persona.rut=paciente_establecimiento.rut
                        inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno 
                        where sectores_centros_internos.id_centro_interno='$id_centro'
                        limit 1";
                    }else{
                        $sql = "select sum(upper(indicador)=upper('riesgo') and valor='$valor' and $rango and $sex) as total 
                        from historial_dental 
                        inner join persona using(rut) 
                        inner join paciente_establecimiento on persona.rut=paciente_establecimiento.rut
                        inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno 
                        limit 1";
                    }
                    $row = mysql_fetch_array(mysql_query($sql));
                    if($row){
                        $total_mujeres = $row['total'];
                    }else{
                        $total_mujeres = 0;
                    }


                    $PE[$valor]['HOMBRES'] += $total_hombres;
                    $PE[$valor]['MUJERES'] += $total_mujeres;
                    $PE[$valor]['AMBOS']   += $total_mujeres + $total_hombres;

                    $fila.= '<td>'.$total_hombres.'</td>';//hombre
                    $fila.= '<td>'.$total_mujeres.'</td>';//mujer

                    //TOTAL POR COLUMNA
                    $TOTAL[$valor][$rango]['HOMBRES'] += $total_hombres;
                    $TOTAL[$valor][$rango]['MUJERES'] += $total_mujeres;
                }
                ?>
                <td><?php echo $PE[$valor]['AMBOS'] ?></td>
                <td><?php echo $PE[$valor]['HOMBRES'] ?></td>
                <td><?php echo $PE[$valor]['MUJERES'] ?></td>
                <?php echo $fila; ?>
            </tr>
            <tr>
                <td>ALTO RIESGO</td>
                <?php
                $thph = 0;
                $thpm = 0;
                $thm = 0;
                $tmm = 0;
                $tabla = 'historial_dental';
                $indicador = 'riesgo';
                $valor = 'ALTO';
                $PE[$valor]['MUJERES'] =0;
                $PE[$valor]['HOMBRES'] =0;
                $PE[$valor]['AMBOS'] =0;
                $fila = '';
                foreach ($rango_seccion_d7 as $i => $rango){
                    $sex = $sexo[0];//hombres
                    if($id_centro!=''){
                        $sql = "select sum(upper(indicador)=upper('riesgo') and valor='$valor' and $rango and $sex) as total 
                        from historial_dental 
                        inner join persona using(rut) 
                        inner join paciente_establecimiento on persona.rut=paciente_establecimiento.rut
                        inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno 
                        where sectores_centros_internos.id_centro_interno='$id_centro'
                        limit 1";
                    }else{
                        $sql = "select sum(upper(indicador)=upper('riesgo') and valor='$valor' and $rango and $sex) as total 
                        from historial_dental 
                        inner join persona using(rut) 
                        inner join paciente_establecimiento on persona.rut=paciente_establecimiento.rut
                        inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno 
                        limit 1";
                    }
                    $row = mysql_fetch_array(mysql_query($sql));
                    if($row){
                        $total_hombres = $row['total'];
                    }else{
                        $total_hombres = 0;
                    }

                    $sex = $sexo[1];//mujeres
                    if($id_centro!=''){
                        $sql = "select sum(upper(indicador)=upper('riesgo') and valor='$valor' and $rango and $sex) as total 
                        from historial_dental 
                        inner join persona using(rut) 
                        inner join paciente_establecimiento on persona.rut=paciente_establecimiento.rut
                        inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno 
                        where sectores_centros_internos.id_centro_interno='$id_centro'
                        limit 1";
                    }else{
                        $sql = "select sum(upper(indicador)=upper('riesgo') and valor='$valor' and $rango and $sex) as total 
                        from historial_dental 
                        inner join persona using(rut) 
                        inner join paciente_establecimiento on persona.rut=paciente_establecimiento.rut
                        inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno 
                        limit 1";
                    }
                    $row = mysql_fetch_array(mysql_query($sql));
                    if($row){
                        $total_mujeres = $row['total'];
                    }else{
                        $total_mujeres = 0;
                    }
                    $PE[$valor]['HOMBRES'] += $total_hombres;
                    $PE[$valor]['MUJERES'] += $total_mujeres;
                    $PE[$valor]['AMBOS']   += $total_mujeres + $total_hombres;

                    $fila.= '<td>'.$total_hombres.'</td>';//hombre
                    $fila.= '<td>'.$total_mujeres.'</td>';//mujer

                    //TOTAL POR COLUMNA
                    $TOTAL[$valor][$rango]['HOMBRES'] += $total_hombres;
                    $TOTAL[$valor][$rango]['MUJERES'] += $total_mujeres;

                }
                ?>
                <td><?php echo $PE[$valor]['AMBOS'] ?></td>
                <td><?php echo $PE[$valor]['HOMBRES'] ?></td>
                <td><?php echo $PE[$valor]['MUJERES'] ?></td>
                <?php echo $fila; ?>

            </tr>
            <tr style="font-weight: bold;background-color: #d7efff;">
                <td colspan="2">TOTAL</td>
                <?php
                $fila = '';
                foreach ($rango_seccion_d7 as $i => $rango){

                    $total_hombres = $TOTAL['ALTO'][$rango]['HOMBRES'] + $TOTAL['BAJO'][$rango]['HOMBRES'];
                    $total_mujeres = $TOTAL['ALTO'][$rango]['MUJERES'] + $TOTAL['BAJO'][$rango]['MUJERES'];

                    $fila.= '<td>'.$total_hombres.'</td>';//hombre
                    $fila.= '<td>'.$total_mujeres.'</td>';//mujer

                    $TOTAL_GENERAL['AMBOS'] += ($total_hombres + $total_mujeres);
                    $TOTAL_GENERAL['HOMBRES'] += ($total_hombres);
                    $TOTAL_GENERAL['MUJERES'] += ($total_mujeres);
                }
                ?>
                <td><?php echo $TOTAL_GENERAL['AMBOS'] ?></td>
                <td><?php echo $TOTAL_GENERAL['HOMBRES'] ?></td>
                <td><?php echo $TOTAL_GENERAL['MUJERES'] ?></td>

                <?php echo $fila; ?>
            </tr>
        </table>
    </section>

</div>
