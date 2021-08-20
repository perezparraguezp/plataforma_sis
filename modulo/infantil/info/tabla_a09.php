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
$rango_seccion_c42 = [
    'persona.edad_total_dias>=0 and persona.edad_total_dias<365', //menor 1 año
    'persona.edad_total>=(1) and persona.edad_total<=(12)',//1 año
    'persona.edad_total>=(12 + 1) and persona.edad_total<=(12*2)',//2 año
    'persona.edad_total>=((12*2)+1) and persona.edad_total<=(12*3)',//3 año
    'persona.edad_total>=((12*3)+1) and persona.edad_total<=(12*4)',//4 año
    'persona.edad_total>=((12*4)+1) and persona.edad_total<=(12*5)',//5 año
    'persona.edad_total>=((12*5)+1) and persona.edad_total<=(12*6)',//6 año
    'persona.edad_total>=((12*6)+1) and persona.edad_total<=(12*7)',//7 año
    'persona.edad_total>=((12*7)+1) and persona.edad_total<=(12*9)',//8 a 9 año
    'persona.edad_total>=((12*10)) and persona.edad_total<=(12*14)',//10 a 14 años
    'persona.edad_total>=((12*14)+1) and persona.edad_total<=(12*19)',//15 a 19 años
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
    '8 a 9 AÑOS',//8 a 9 año
    '10 A 14 AÑOS',//10 a 14 años
    '15 A 19 AÑOS',//15 a 19 años
];

$sexo = [
    "persona.sexo='M' ",
    "persona.sexo='F' "
];


//INDICE
$indice_ceod_sql = [
    '0%', //0
    '1%', //0
    '3%', //0
    '5%', //0
    '7%', //0
    '9%', // 9
];
$indice_ceod_label = [
    '0', //0
    '1 a 2', //0
    '3 a 4', //0
    '5 a 6', //0
    '7 a 8', //0
    '9 o Más', // 9
];
//dientes
$indice_dientes_sql = [
    '0%', //0
    '1 a%', //0
    '10 a%', //0
    '20 a%', //0
    '28 y%', //0
];
$indice_dientes_label = [
    '0', //0
    '1 a 9', //0
    '10 a 19', //0
    '20 a 27', //0
    '28 y Más', //0
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

<div class="card" id="todo_p9">
    <div class="row" style="padding:20px;">
        <div class="col l10">
            <header>CENTRO MEDICO: <?php echo $nombre_centro; ?></header>
        </div>
        <div class="col l2">
            <input type="button"
                   class="btn green lighten-2 white-text"
                   value="EXPORTAR A EXCEL" onclick="exportTable('todo_p9','REM A09')" />
        </div>
    </div>
    <hr class="row" style="margin-bottom: 10px;" />
    <section id="seccion_d" style="width: 100%;overflow-y: scroll;">
        <div class="row">
            <div class="col l10">
                <header>SECCION C: INGRESOS Y EGRESOS EN APS</header>
            </div>
        </div>
        <table id="table_seccion_d7" style="width: 100%;border: solid 1px black;" border="1">
            <tr>
                <td colspan="2" rowspan="3"
                    style="width: 400px;background-color: #fdff8b;position: relative;text-align: center;">
                    TIPO DE INGRESO O EGRESO
                </td>
                <td rowspan="2" colspan="3">
                    TOTAL
                </td>
                <td colspan="24">
                    POR EDAD
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
                foreach ($rango_seccion_c42 as $i => $item){
                    echo '<td>HOMBRES</td>
                          <td>MUJERES</td>';
                }
                ?>
            </tr>
            <!--            SECCION C FILA 42-->
            <tr>
                <td rowspan="1" colspan="2">INGRESOS CONTROL CON ENFOQUE RIESGO ODONTOLÓGICO (CERO)</td>
                <?php
                $thph = 0;
                $thpm = 0;
                $thm = 0;
                $tmm = 0;
                $tabla = 'historial_dental';
                $indicador = 'cero';
                $valor = 'SI';
                $PE[$valor]['MUJERES'] =0;
                $PE[$valor]['HOMBRES'] =0;
                $PE[$valor]['AMBOS'] =0;
                $fila = '';
                foreach ($rango_seccion_c42 as $i => $rango){
                    $sex = $sexo[0];//hombres
                    if($id_centro!=''){
                        $sql = "select sum(upper(indicador)=upper('$indicador') and valor='$valor' and $rango and $sex) as total 
                        from historial_dental 
                        inner join persona using(rut) 
                        inner join paciente_establecimiento on persona.rut=paciente_establecimiento.rut
                        inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno 
                        where sectores_centros_internos.id_centro_interno='$id_centro'
                        and (historial_dental.fecha_registro > current_date() - interval  30 day)
                        limit 1";
                    }else{
                        $sql = "select sum(upper(indicador)=upper('$indicador') and valor='$valor' and $rango and $sex) as total 
                        from historial_dental 
                        inner join persona on historial_dental.rut=persona.rut
                        inner join paciente_establecimiento on persona.rut=paciente_establecimiento.rut
                        where (historial_dental.fecha_registro > current_date() - interval  30 day)
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
                        $sql = "select sum(upper(indicador)=upper('$indicador') and valor='$valor' and $rango and $sex) as total 
                        from historial_dental 
                        inner join persona using(rut) 
                        inner join paciente_establecimiento on persona.rut=paciente_establecimiento.rut
                        inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno 
                        where sectores_centros_internos.id_centro_interno='$id_centro'
                        and (historial_dental.fecha_registro > current_date() - interval  30 day)
                        limit 1";
                    }else{
                        $sql = "select sum(upper(indicador)=upper('$indicador') and valor='$valor' and $rango and $sex) as total 
                        from historial_dental 
                        inner join persona on historial_dental.rut=persona.rut
                        inner join paciente_establecimiento on persona.rut=paciente_establecimiento.rut
                        where (historial_dental.fecha_registro > current_date() - interval  30 day)
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
                }
                ?>
                <td><?php echo $PE[$valor]['AMBOS'] ?></td>
                <td><?php echo $PE[$valor]['HOMBRES'] ?></td>
                <td><?php echo $PE[$valor]['MUJERES'] ?></td>
                <?php echo $fila; ?>
            </tr>
            <!--            SECCION C FILA 48 AL 53-->
            <tr>
                <td rowspan="6">INDICE CEOD O COPD EN PACIENTES INGRESADOS</td>
                <?php

                $tabla = 'historial_dental';
                $indicador = 'indice';
                foreach ($indice_ceod_label as $fila_item => $valor_label){

                    $valor = $indice_ceod_sql[$fila_item];

                    $INDICE[$valor]['MUJERES'] =0;
                    $INDICE[$valor]['HOMBRES'] =0;
                    $INDICE[$valor]['AMBOS'] =0;
                    if($fila_item>0){
                        echo "</tr><tr>";
                    }
                    echo '<td>'.$valor_label.'</td>';
                    $fila = '';
                    foreach ($rango_seccion_c42 as $i => $rango){

                        $sex = $sexo[0];//hombres
                        if($id_centro!=''){
                            $sql = "select sum(upper(indicador)=upper('$indicador') and valor like '$valor' and $rango and $sex) as total 
                        from historial_dental 
                        inner join persona using(rut) 
                        inner join paciente_establecimiento on persona.rut=paciente_establecimiento.rut
                        inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno 
                        where sectores_centros_internos.id_centro_interno='$id_centro'
                        and (historial_dental.fecha_registro > current_date() - interval  30 day)
                        limit 1";
                        }else{
                            $sql = "select sum(upper(indicador)=upper('$indicador') and valor like '$valor' and $rango and $sex) as total 
                        from historial_dental 
                        inner join persona on historial_dental.rut=persona.rut
                        inner join paciente_establecimiento on persona.rut=paciente_establecimiento.rut
                        where (historial_dental.fecha_registro > current_date() - interval  30 day)
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
                            $sql = "select sum(upper(indicador)=upper('$indicador') and valor like '$valor' and $rango and $sex) as total 
                        from historial_dental 
                        inner join persona using(rut) 
                        inner join paciente_establecimiento on persona.rut=paciente_establecimiento.rut
                        inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno 
                        where sectores_centros_internos.id_centro_interno='$id_centro'
                        and (historial_dental.fecha_registro > current_date() - interval  30 day)
                        limit 1";
                        }else{
                            $sql = "select sum(upper(indicador)=upper('$indicador') and valor like '$valor' and $rango and $sex) as total 
                        from historial_dental 
                        inner join persona on historial_dental.rut=persona.rut
                        inner join paciente_establecimiento on persona.rut=paciente_establecimiento.rut
                        where (historial_dental.fecha_registro > current_date() - interval  30 day)
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

                    }
                    ?>
                    <td><?php echo $PE[$valor]['AMBOS'] ?></td>
                    <td><?php echo $PE[$valor]['HOMBRES'] ?></td>
                    <td><?php echo $PE[$valor]['MUJERES'] ?></td>
                    <?php echo $fila; ?>
                    <?php
                }
                ?>

            </tr>
            <!--            SECCION C FILA 55 AL 59-->
            <tr>
                <td rowspan="5">Nº DE DIENTES EN BOCA.</td>
                <?php

                $tabla = 'historial_dental';
                $indicador = 'dientes';
                foreach ($indice_dientes_label as $fila_item => $valor_label){

                    $valor = $indice_dientes_sql[$fila_item];

                    $INDICE[$valor]['MUJERES'] =0;
                    $INDICE[$valor]['HOMBRES'] =0;
                    $INDICE[$valor]['AMBOS'] =0;
                    if($fila_item>0){
                        echo "</tr><tr>";
                    }
                    echo '<td>'.$valor_label.'</td>';
                    $fila = '';
                    foreach ($rango_seccion_c42 as $i => $rango){

                        $sex = $sexo[0];//hombres
                        if($id_centro!=''){
                            $sql = "select sum(upper(indicador)=upper('$indicador') and valor like '$valor' and $rango and $sex) as total 
                        from historial_dental 
                        inner join persona using(rut) 
                        inner join paciente_establecimiento on persona.rut=paciente_establecimiento.rut
                        inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno 
                        where sectores_centros_internos.id_centro_interno='$id_centro'
                        and (historial_dental.fecha_registro > current_date() - interval  30 day)
                        limit 1";
                        }else{
                            $sql = "select sum(upper(indicador)=upper('$indicador') and valor like '$valor' and $rango and $sex) as total 
                        from historial_dental 
                        inner join persona on historial_dental.rut=persona.rut
                        inner join paciente_establecimiento on persona.rut=paciente_establecimiento.rut
                        where (historial_dental.fecha_registro > current_date() - interval  30 day)
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
                            $sql = "select sum(upper(indicador)=upper('$indicador') and valor like '$valor' and $rango and $sex) as total 
                        from historial_dental 
                        inner join persona using(rut) 
                        inner join paciente_establecimiento on persona.rut=paciente_establecimiento.rut
                        inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno 
                        where sectores_centros_internos.id_centro_interno='$id_centro'
                        and (historial_dental.fecha_registro > current_date() - interval  30 day)
                        limit 1";
                        }else{
                            $sql = "select sum(upper(indicador)=upper('$indicador') and valor like '$valor' and $rango and $sex) as total 
                        from historial_dental 
                        inner join persona on historial_dental.rut=persona.rut
                        inner join paciente_establecimiento on persona.rut=paciente_establecimiento.rut
                        where (historial_dental.fecha_registro > current_date() - interval  30 day)
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

                    }
                    ?>
                    <td><?php echo $PE[$valor]['AMBOS'] ?></td>
                    <td><?php echo $PE[$valor]['HOMBRES'] ?></td>
                    <td><?php echo $PE[$valor]['MUJERES'] ?></td>
                    <?php echo $fila; ?>
                    <?php
                }
                ?>

            </tr>
        </table>
    </section>

</div>
