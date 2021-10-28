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

$rango_label_seccion_a = [
    'Menor de 15 Años',
    '15 a 19 Años',
    '20 a 24 Años',
    '25 a 29 Años',
    '30 a 34 Años',
    '35 a 39 Años',
    '40 a 44 Años',
    '45 a 49 Años',
    '50 a 54 Años',
    '55 a 59 Años',
    '60 a 64 Años',
    '65 a 69 Años',
    '70 y mas Años',
];
$filtro_rango_seccion_a = [
    '',//todos
    'and persona.edad_total<15*12',
    'and persona.edad_total>=15*12 and persona.edad_total<19*12',
    'and persona.edad_total>=20*12 and persona.edad_total<24*12',
    'and persona.edad_total>=25*12 and persona.edad_total<29*12',
    'and persona.edad_total>=30*12 and persona.edad_total<34*12',
    'and persona.edad_total>=35*12 and persona.edad_total<39*12',
    'and persona.edad_total>=40*12 and persona.edad_total<44*12',
    'and persona.edad_total>=45*12 and persona.edad_total<49*12',
    'and persona.edad_total>=50*12 and persona.edad_total<54*12',
    'and persona.edad_total>=55*12 and persona.edad_total<59*12',
    'and persona.edad_total>=60*12 and persona.edad_total<64*12',
    'and persona.edad_total>=65*12 and persona.edad_total<69*12',
    'and persona.edad_total>=70*12 ',
    "and persona.pueblo='SI' ",
    "and persona.migrante='SI' ",
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

<div class="card" id="todo_p5">
    <div class="row" style="padding:20px;">
        <div class="col l10">
            <header>CENTRO MEDICO: <?php echo $nombre_centro; ?></header>
            <header>REM-P1. POBLACIÓN EN CONTROL PROGRAMA DE SALUD DE LA MUJER</header>
        </div>
        <div class="col l2">
            <input type="button"
                   class="btn green lighten-2 white-text"
                   value="EXPORTAR A EXCEL" onclick="exportTable('todo_p5','REM P5')" />
        </div>
    </div>
    <hr class="row" style="margin-bottom: 10px;" />

    <section id="seccion_a" style="width: 100%;overflow-y: scroll;">
        <div class="row">
            <div class="col l10">
                <header>SECCION A: POBLACIÓN EN CONTROL SEGÚN MÉTODO DE REGULACIÓN DE FERTILIDAD Y SALUD SEXUAL</header>
            </div>
        </div>
        <table id="table_seccion_a" style="width: 100%;border: solid 1px black;" border="1">
            <tr>
                <td colspan="2" rowspan="2"
                    style="width: 400px;background-color: #fdff8b;position: relative;text-align: center;">
                    MÉTODOS
                </td>
                <td rowspan="2" colspan="1">TOTAL</td>
                <td colspan="13">GRUPO DE EDAD (en años) Y SEXO</td>
                <td rowspan="2" colspan="1">PUEBLOS ORIGINARIOS</td>
                <td rowspan="2" colspan="1">POBLACION MIGRANTE</td>
                <td rowspan="2" colspan="1">PV-VIH (personas viviendo con VIH)</td>
            </tr>
            <tr>
                <?php
                foreach ($rango_label_seccion_a as $i => $value){
                    echo '<td>'.$value.'</td>';
                }
                ?>
            </tr>
            <?php

            $INDICES = [
                "D . I . U T con Cobre",
                "D . I . U con Levonorgestrel",
                "HORMONAL - Oral Combinado",
                "HORMONAL - Oral Progestágeno",
                "HORMONAL - Inyectable Combinado",
                "HORMONAL - Inyectable Progestágeno",
                "HORMONAL - Implante Etonogestrel (3 años)",
                "HORMONAL - Implante Levonorgestrel (5 años)",
                "SÓLO PRESERVATIVO MAC - MUJER",
                "SÓLO PRESERVATIVO MAC - HOMBRE",
                "ESTERILIZACIÓN QUIRURGICA - MUJER",
                "ESTERILIZACIÓN QUIRURGICA - HOMBRE",
                "TOTAL",
            ];
            $filtro_sql = [
                "and (mujer_historial_hormonal.tipo like '%T DE COBRE%' AND estado_hormona='ACTIVA') ",
                "and (mujer_historial_hormonal.tipo like '%CON LEVORGESTREL%' AND estado_hormona='ACTIVA')  ",
                "and (mujer_historial_hormonal.tipo like '%ORAL COMBINADO%' AND estado_hormona='ACTIVA')  ",
                "and (mujer_historial_hormonal.tipo like '%ORAL P%' AND estado_hormona='ACTIVA')  ",
                "and (mujer_historial_hormonal.tipo like '%INYECTABLE COMBINADO%' AND estado_hormona='ACTIVA')  ",
                "and (mujer_historial_hormonal.tipo like '%INYECTABLE PROGEST%' AND estado_hormona='ACTIVA')  ",
                "and (mujer_historial_hormonal.tipo like '%IMPLANTE ETONOGESTREL%' AND estado_hormona='ACTIVA')  ",
                "and (mujer_historial_hormonal.tipo like '%IMPLANTE LEVONORGESTREL%' AND estado_hormona='ACTIVA')  ",
                "and (mujer_historial_hormonal.tipo like '%SOLO PRESERVATIVO MAC%' AND estado_hormona='ACTIVA' and persona.sexo='F')  ",
                "and (mujer_historial_hormonal.tipo like '%SOLO PRESERVATIVO MAC%' AND estado_hormona='ACTIVA' and persona.sexo='M')  ",
                "and (mujer_historial_hormonal.tipo like '%ESTERILIZACION QUIRURGICA%' AND estado_hormona='ACTIVA' and persona.sexo='F')  ",
                "and (mujer_historial_hormonal.tipo like '%ESTERILIZACION QUIRURGICA%' AND estado_hormona='ACTIVA' and persona.sexo='M')  ",

                "and ( (mujer_historial_hormonal.tipo like '%T DE COBRE%' AND estado_hormona='ACTIVA') ".
                "OR (mujer_historial_hormonal.tipo like '%CON LEVORGESTREL%' AND estado_hormona='ACTIVA')  ".
                "OR (mujer_historial_hormonal.tipo like '%ORAL COMBINADO%' AND estado_hormona='ACTIVA')  ".
                "OR (mujer_historial_hormonal.tipo like '%ORAL P%' AND estado_hormona='ACTIVA')  ".
                "OR (mujer_historial_hormonal.tipo like '%INYECTABLE COMBINADO%' AND estado_hormona='ACTIVA')  ".
                "OR (mujer_historial_hormonal.tipo like '%INYECTABLE PROGEST%' AND estado_hormona='ACTIVA')  ".
                "OR (mujer_historial_hormonal.tipo like '%IMPLANTE ETONOGESTREL%' AND estado_hormona='ACTIVA')  ".
                "OR (mujer_historial_hormonal.tipo like '%IMPLANTE LEVONORGESTREL%' AND estado_hormona='ACTIVA')  ".
                "OR (mujer_historial_hormonal.tipo like '%SOLO PRESERVATIVO MAC%' AND estado_hormona='ACTIVA' )  ".
                "OR (mujer_historial_hormonal.tipo like '%ESTERILIZACION QUIRURGICA%' AND estado_hormona='ACTIVA')
                     )"
            ];

            foreach ($INDICES AS $TR => $texto_fila){
                $fila = '';
                foreach ($filtro_rango_seccion_a as $i => $filtro){
                    if($id_centro!=''){
                        $sql = "select count(*) as total from persona 
                                  inner join mujer_historial_hormonal using(rut)
                                  inner join paciente_establecimiento on persona.rut=paciente_establecimiento.rut
                                  inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno
                                  where sectores_centros_internos.id_centro_interno='$id_centro'
                                  and m_mujer='SI' and id_establecimiento='$id_establecimiento' ";
                    }else{
                        $sql = "select count(*) as total from persona 
                                  inner join mujer_historial_hormonal using(rut)
                                  inner join paciente_establecimiento on persona.rut=paciente_establecimiento.rut
                                  inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno
                                  where m_mujer='SI' and id_establecimiento='$id_establecimiento' ";
                    }
                    $sql .= $filtro.' '.$filtro_sql[$TR];
//                    echo $sql;
                    $row = mysql_fetch_array(mysql_query($sql));
                    if($row){
                        $total = $row['total'];
                    }else{
                        $total = 0;
                    }
                    if($TR<8 && ($i>=10 && $i<=12)){
                        $fila .= "<td style='background-color: grey;'></td>";
                    }else{
                        if(($TR==10 || $TR==11)&& ($i>=10 && $i<=12)){
                            $fila .= "<td style='background-color: grey;'></td>";
                        }else{
                            $fila .= "<td>$total</td>";
                        }

                    }


                    //para personas con VIH
                    if($id_centro!=''){
                        $sql_vih = "select count(*) as total from persona 
                                  inner join mujer_historial_hormonal using(rut)
                                  inner join paciente_mujer on persona.rut=paciente_mujer.rut
                                  inner join paciente_establecimiento on persona.rut=paciente_establecimiento.rut
                                  inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno
                                  where sectores_centros_internos.id_centro_interno='$id_centro'
                                  and m_mujer='SI' and id_establecimiento='$id_establecimiento' ";
                    }else{
                        $sql_vih = "select count(*) as total from persona 
                                  inner join mujer_historial_hormonal using(rut)
                                  inner join paciente_mujer on persona.rut=paciente_mujer.rut
                                  inner join paciente_establecimiento on persona.rut=paciente_establecimiento.rut
                                  inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno
                                  where m_mujer='SI' and id_establecimiento='$id_establecimiento' ";
                    }
                    $sql_vih .= $filtro_sql[$TR];

                }
                //personas con vih
                $sql_vih .= " AND patologia_vih='SI' ";
                $row_vih = mysql_fetch_array(mysql_query($sql_vih));

                if($row_vih){
                    $total = $row_vih['total'];
                }else{
                    $total = 0;
                }
                $fila .= "<td>$total</td>";

                //construimos la fila
                $fila_final = '<tr>
                                    <td colspan="2">'.$texto_fila.'</td>';
                $fila_final .= $fila;
                echo $fila_final.'</tr>';

            }
            //
            //            $fila = '';
            //            foreach ($filtro_rango_seccion_a as $i => $filtro) {
            //                if ($id_centro != '') {
            //                    $sql = "select count(distinct persona.rut) as total from persona
            //                                  inner join mujer_historial_hormonal using(rut)
            //                                  inner join paciente_establecimiento on persona.rut=paciente_establecimiento.rut
            //                                  inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno
            //                                  where sectores_centros_internos.id_centro_interno='$id_centro'
            //                                  and m_mujer='SI' and id_establecimiento='$id_establecimiento' ";
            //                } else {
            //                    $sql = "select count(distinct persona.rut) as total from persona
            //                                  inner join mujer_historial_hormonal using(rut)
            //                                  inner join paciente_establecimiento on persona.rut=paciente_establecimiento.rut
            //                                  inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno
            //                                  where m_mujer='SI' and id_establecimiento='$id_establecimiento' ";
            //                }
            //                $sql .= $filtro . ' ' . $filtro_sql[$TR];
            //                //                    echo $sql;
            //                $row = mysql_fetch_array(mysql_query($sql));
            //                if ($row) {
            //                    $total = $row['total'];
            //                } else {
            //                    $total = 0;
            //                }
            //                $fila .= "<td>$total</td>";
            //            }
            //            //VIH
            //            //para personas con VIH
            //            if($id_centro!=''){
            //                $sql_vih = "select count(distinct persona.rut) as total from persona
            //                                  inner join mujer_historial_hormonal using(rut)
            //                                  inner join paciente_mujer on persona.rut=paciente_mujer.rut
            //                                  inner join paciente_establecimiento on persona.rut=paciente_establecimiento.rut
            //                                  inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno
            //                                  where sectores_centros_internos.id_centro_interno='$id_centro'
            //                                  and m_mujer='SI' and id_establecimiento='$id_establecimiento' ";
            //            }else{
            //                $sql_vih = "select count(distinct persona.rut) as total from persona
            //                                  inner join mujer_historial_hormonal using(rut)
            //                                  inner join paciente_mujer on persona.rut=paciente_mujer.rut
            //                                  inner join paciente_establecimiento on persona.rut=paciente_establecimiento.rut
            //                                  inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno
            //                                  where m_mujer='SI' and id_establecimiento='$id_establecimiento' ";
            //            }
            //            $sql_vih .= " AND patologia_vih='SI' ";
            //
            //            $row_vih = mysql_fetch_array(mysql_query($sql_vih));
            //
            //            if($row_vih){
            //                $total = $row_vih['total'];
            //            }else{
            //                $total = 0;
            //            }
            //            $fila .= "<td>$total</td>";
            //f
            //            $fila_final = '<tr>
            //                                    <td colspan="2">TOTAL</td>';
            //            $fila_final .= $fila;
            //            echo $fila_final.'</tr>';

            //segunda fase
            $INDICES = [
                "Mujeres en control con enfermedad cardiovascular (DM-HTA)",
                "Mujeres con Retiro de Implante Anticipado en el semestre (antes de los 3 años)",
                "Mujeres con Retiro de Implante Anticipado en el semestre (antes de los 5 años)",

            ];
            $filtro_sql = [
                " ",
                "and (mujer_historial_hormonal.tipo like '%IMPLANTE ETONO%' AND estado_hormona='SUSPENDIDA' and TIMESTAMPDIFF(MONTH,fecha_retiro_hormonal,CURRENT_DATE)<7)  ",
                "and (mujer_historial_hormonal.tipo like '%IMPLANTE LEVONOR%' AND estado_hormona='SUSPENDIDA' and TIMESTAMPDIFF(MONTH,fecha_retiro_hormonal,CURRENT_DATE)<7)  ",

            ];

            foreach ($INDICES AS $TR => $texto_fila){
                $fila = '';
                foreach ($filtro_rango_seccion_a as $i => $filtro){
                    if($id_centro!=''){
                        $sql = "select count(*) as total from persona 
                                  inner join mujer_historial_hormonal using(rut)
                                  inner join paciente_establecimiento on persona.rut=paciente_establecimiento.rut
                                  inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno
                                  where sectores_centros_internos.id_centro_interno='$id_centro'
                                  and m_mujer='SI' and id_establecimiento='$id_establecimiento' ";
                    }else{
                        $sql = "select count(*) as total from persona 
                                  inner join mujer_historial_hormonal using(rut)
                                  inner join paciente_establecimiento on persona.rut=paciente_establecimiento.rut
                                  inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno
                                  where m_mujer='SI' and id_establecimiento='$id_establecimiento' ";
                    }
                    $sql .= $filtro.' '.$filtro_sql[$TR];
//                    echo $sql;
                    $row = mysql_fetch_array(mysql_query($sql));
                    if($row){
                        $total = $row['total'];
                    }else{
                        $total = 0;
                    }
                    if($TR>0 && ($i>=10 && $i<=12)){
                        $fila .= "<td style='background-color: grey;'></td>";
                    }else{
                        $fila .= "<td>$total</td>";

                    }


                    //para personas con VIH
                    if($id_centro!=''){
                        $sql_vih = "select count(*) as total from persona 
                                  inner join mujer_historial_hormonal using(rut)
                                  inner join paciente_mujer on persona.rut=paciente_mujer.rut
                                  inner join paciente_establecimiento on persona.rut=paciente_establecimiento.rut
                                  inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno
                                  where sectores_centros_internos.id_centro_interno='$id_centro'
                                  and m_mujer='SI' and id_establecimiento='$id_establecimiento' ";
                    }else{
                        $sql_vih = "select count(*) as total from persona 
                                  inner join mujer_historial_hormonal using(rut)
                                  inner join paciente_mujer on persona.rut=paciente_mujer.rut
                                  inner join paciente_establecimiento on persona.rut=paciente_establecimiento.rut
                                  inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno
                                  where m_mujer='SI' and id_establecimiento='$id_establecimiento' ";
                    }
                    $sql_vih .= $filtro_sql[$TR];

                }
                //personas con vih
                $sql_vih .= " AND patologia_vih='SI' ";
                $row_vih = mysql_fetch_array(mysql_query($sql_vih));

                if($row_vih){
                    $total = $row_vih['total'];
                }else{
                    $total = 0;
                }
                $fila .= "<td>$total</td>";

                //construimos la fila
                $fila_final = '<tr>
                                    <td colspan="2">'.$texto_fila.'</td>';
                $fila_final .= $fila;
                echo $fila_final.'</tr>';

            }


            //PRACTICA SEXUAL SEGURA
            $INDICES = [
                "Método de Regulación de Fertilidad más Preservativo",

            ];
            $filtro_sql = [
                "and regulacion_mas_preservativo='SI' ",
            ];

            foreach ($INDICES AS $TR => $texto_fila){
                $fila = '';
                foreach ($filtro_rango_seccion_a as $i => $filtro){
                    if($id_centro!=''){
                        $sql = "select count(*) as total from persona 
                                  inner join practica_sexual_mujer using(rut)
                                  inner join paciente_establecimiento on persona.rut=paciente_establecimiento.rut
                                  inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno
                                  where sectores_centros_internos.id_centro_interno='$id_centro'
                                  and m_mujer='SI' and id_establecimiento='$id_establecimiento' ";
                    }else{
                        $sql = "select count(*) as total from persona 
                                  inner join practica_sexual_mujer using(rut)
                                  inner join paciente_establecimiento on persona.rut=paciente_establecimiento.rut
                                  inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno
                                  where m_mujer='SI' and id_establecimiento='$id_establecimiento' ";
                    }
                    $sql .= $filtro.' '.$filtro_sql[$TR];
//                    echo $sql;
                    $row = mysql_fetch_array(mysql_query($sql));
                    if($row){
                        $total = $row['total'];
                    }else{
                        $total = 0;
                    }
                    $fila .= "<td>$total</td>";

                    //para personas con VIH
                    if($id_centro!=''){
                        $sql_vih = "select count(*) as total from persona 
                                  inner join mujer_historial_hormonal using(rut)
                                  inner join paciente_mujer on persona.rut=paciente_mujer.rut
                                  inner join paciente_establecimiento on persona.rut=paciente_establecimiento.rut
                                  inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno
                                  where sectores_centros_internos.id_centro_interno='$id_centro'
                                  and m_mujer='SI' and id_establecimiento='$id_establecimiento' ";
                    }else{
                        $sql_vih = "select count(*) as total from persona 
                                  inner join mujer_historial_hormonal using(rut)
                                  inner join paciente_mujer on persona.rut=paciente_mujer.rut
                                  inner join paciente_establecimiento on persona.rut=paciente_establecimiento.rut
                                  inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno
                                  where m_mujer='SI' and id_establecimiento='$id_establecimiento' ";
                    }
                    $sql_vih .= $filtro_sql[$TR];

                }
                //personas con vih
                $sql_vih .= " AND patologia_vih='SI' ";
                $row_vih = mysql_fetch_array(mysql_query($sql_vih));

                if($row_vih){
                    $total = $row_vih['total'];
                }else{
                    $total = 0;
                }
                $fila .= "<td>$total</td>";

                //construimos la fila
                $fila_final = '<tr>
                                    <td colspan="2">'.$texto_fila.'</td>';
                $fila_final .= $fila;
                echo $fila_final.'</tr>';

            }

            //GESTANTES CON PRESERVATIVO
            $INDICES = [
                "Gestantes que reciben preservativo",

            ];
            $filtro_sql = [
                "and gestante='SI' and regulacion_mas_preservativo='SI' ",
            ];

            foreach ($INDICES AS $TR => $texto_fila){
                $fila = '';
                foreach ($filtro_rango_seccion_a as $i => $filtro){
                    if($id_centro!=''){
                        $sql = "select count(*) as total from persona 
                                  inner join practica_sexual_mujer using(rut)
                                  inner join paciente_mujer on persona.rut=paciente_mujer.rut
                                  inner join paciente_establecimiento on persona.rut=paciente_establecimiento.rut
                                  inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno
                                  where sectores_centros_internos.id_centro_interno='$id_centro'
                                  and m_mujer='SI' and id_establecimiento='$id_establecimiento' ";
                    }else{
                        $sql = "select count(*) as total from persona 
                                  inner join practica_sexual_mujer using(rut)
                                  inner join paciente_mujer on persona.rut=paciente_mujer.rut
                                  inner join paciente_establecimiento on persona.rut=paciente_establecimiento.rut
                                  inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno
                                  where m_mujer='SI' and id_establecimiento='$id_establecimiento' ";
                    }
                    $sql .= $filtro.' '.$filtro_sql[$TR];
//                    echo $sql;
                    $row = mysql_fetch_array(mysql_query($sql));
                    if($row){
                        $total = $row['total'];
                    }else{
                        $total = 0;
                    }
                    if($TR==0 && ($i>=10 && $i<=12)){
                        $fila .= "<td style='background-color: grey;'></td>";
                    }else{
                        $fila .= "<td>$total</td>";
                    }


                    //para personas con VIH
                    if($id_centro!=''){
                        $sql_vih = "select count(*) as total from persona 
                                  inner join mujer_historial_hormonal using(rut)
                                  inner join paciente_mujer on persona.rut=paciente_mujer.rut
                                  inner join paciente_establecimiento on persona.rut=paciente_establecimiento.rut
                                  inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno
                                  where sectores_centros_internos.id_centro_interno='$id_centro'
                                  and m_mujer='SI' and id_establecimiento='$id_establecimiento' ";
                    }else{
                        $sql_vih = "select count(*) as total from persona 
                                  inner join mujer_historial_hormonal using(rut)
                                  inner join paciente_mujer on persona.rut=paciente_mujer.rut
                                  inner join paciente_establecimiento on persona.rut=paciente_establecimiento.rut
                                  inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno
                                  where m_mujer='SI' and id_establecimiento='$id_establecimiento' ";
                    }
                    $sql_vih .= $filtro_sql[$TR];

                }
                //personas con vih
                $sql_vih .= " AND patologia_vih='SI' ";
                $row_vih = mysql_fetch_array(mysql_query($sql_vih));

                if($row_vih){
                    $total = $row_vih['total'];
                }else{
                    $total = 0;
                }
                $fila .= "<td>$total</td>";

                //construimos la fila
                $fila_final = '<tr>
                                    <td colspan="2">'.$texto_fila.'</td>';
                $fila_final .= $fila;
                echo $fila_final.'</tr>';

            }


            //PRACTICA SEXUAL SEGURA
            $INDICES = [
                "PRESERVATIVO/PRACTICA SEXUAL SEGURA - MUJER",
                "PRESERVATIVO/PRACTICA SEXUAL SEGURA - HOMBRE",
                "LUBRICANTES - MUJER",
                "LUBRICANTES - HOMBRE",
                "CONDÓN FEMENINO",

            ];
            $filtro_sql = [
                "and (regulacion_mas_preservativo='SI' or preservativo_masculino='SI') and persona.sexo='F'",
                "and (regulacion_mas_preservativo='SI' or preservativo_masculino='SI') and persona.sexo='M'",
                "and lubricante='SI' and persona.sexo='F'",
                "and lubricante='SI' and persona.sexo='M'",
                "and condon_femenino='SI' ",

            ];

            foreach ($INDICES AS $TR => $texto_fila){
                $fila = '';
                foreach ($filtro_rango_seccion_a as $i => $filtro){
                    if($id_centro!=''){
                        $sql = "select count(*) as total from persona 
                                  inner join practica_sexual_mujer using(rut)
                                  inner join paciente_establecimiento on persona.rut=paciente_establecimiento.rut
                                  inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno
                                  where sectores_centros_internos.id_centro_interno='$id_centro'
                                  and m_mujer='SI' and id_establecimiento='$id_establecimiento' ";
                    }else{
                        $sql = "select count(*) as total from persona 
                                  inner join practica_sexual_mujer using(rut)
                                  inner join paciente_establecimiento on persona.rut=paciente_establecimiento.rut
                                  inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno
                                  where m_mujer='SI' and id_establecimiento='$id_establecimiento' ";
                    }
                    $sql .= $filtro.' '.$filtro_sql[$TR];
//                    echo $sql;
                    $row = mysql_fetch_array(mysql_query($sql));
                    if($row){
                        $total = $row['total'];
                    }else{
                        $total = 0;
                    }
                    $fila .= "<td>$total</td>";


                    //para personas con VIH
                    if($id_centro!=''){
                        $sql_vih = "select count(*) as total from persona 
                                  inner join mujer_historial_hormonal using(rut)
                                  inner join paciente_mujer on persona.rut=paciente_mujer.rut
                                  inner join paciente_establecimiento on persona.rut=paciente_establecimiento.rut
                                  inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno
                                  where sectores_centros_internos.id_centro_interno='$id_centro'
                                  and m_mujer='SI' and id_establecimiento='$id_establecimiento' ";
                    }else{
                        $sql_vih = "select count(*) as total from persona 
                                  inner join mujer_historial_hormonal using(rut)
                                  inner join paciente_mujer on persona.rut=paciente_mujer.rut
                                  inner join paciente_establecimiento on persona.rut=paciente_establecimiento.rut
                                  inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno
                                  where m_mujer='SI' and id_establecimiento='$id_establecimiento' ";
                    }
                    $sql_vih .= $filtro_sql[$TR];

                }
                //personas con vih
                $sql_vih .= " AND patologia_vih='SI' ";
                $row_vih = mysql_fetch_array(mysql_query($sql_vih));

                if($row_vih){
                    $total = $row_vih['total'];
                }else{
                    $total = 0;
                }
                $fila .= "<td>$total</td>";

                //construimos la fila
                $fila_final = '<tr>
                                    <td colspan="2">'.$texto_fila.'</td>';
                $fila_final .= $fila;
                echo $fila_final.'</tr>';

            }


            ?>



        </table>
    </section>

    <section id="seccion_b" style="width: 100%;overflow-y: scroll;">
        <div class="row">
            <div class="col l10">
                <header>SECCION B: GESTANTES EN CONTROL CON EVALUACIÓN RIESGO BIOPSICOSOCIAL</header>
            </div>
        </div>
        <table id="table_seccion_b" style="width: 100%;border: solid 1px black;" border="1">
            <tr>
                <td style="width: 400px;background-color: #fdff8b;position: relative;text-align: center;">
                    GRUPOS DE EDAD
                </td>
                <td>TOTAL DE GESTANTES EN CONTROL</td>
                <td>EN RIESGO PSICO-SOCIAL</td>
                <td>QUE PRESENTAN VIOLENCIA DE GÉNERO</td>
                <td>GESTANTES QUE PRESENTAN ARO</td>
                <td>POBLACION MIGRANTES</td>
            </tr>

            <?php

            $INDICES = [
                "Menos de 15 años",
                "15 a 19 años",
                "20 a 24 años",
                "25 a 29 años",
                "30 a 34 años",
                "35 a 39 años",
                "40 a 44 años",
                "45 a 49 años",
                "50 a 54 años",
                "TOTAL",
            ];
            $filtro_sql = [
                'and persona.edad_total<15*12',
                'and persona.edad_total>=15*12 and persona.edad_total<19*12',
                'and persona.edad_total>=20*12 and persona.edad_total<24*12',
                'and persona.edad_total>=25*12 and persona.edad_total<29*12',
                'and persona.edad_total>=30*12 and persona.edad_total<34*12',
                'and persona.edad_total>=35*12 and persona.edad_total<39*12',
                'and persona.edad_total>=40*12 and persona.edad_total<44*12',
                'and persona.edad_total>=45*12 and persona.edad_total<49*12',
                'and persona.edad_total>=50*12 and persona.edad_total<54*12',
                'and persona.edad_total>0',
            ];

            $filtro_rango_seccion_a = [
                '',
                "AND riesgo_biopsicosocial='CON RIESGO BIOPSICOSOCIAL' ",
                "AND riesgo_biopsicosocial='PRESENTA VIOLENCIA DE GENERO' ",
                "AND riesgo_biopsicosocial like 'PRESENTA ARO%' ",
                "AND persona.migrante='SI' ",
            ];

            foreach ($INDICES AS $TR => $texto_fila) {
                $fila = '';
                foreach ($filtro_rango_seccion_a as $i => $filtro) {
                    if ($id_centro != '') {
                        $sql = "select count(*) as total from persona 
                                  inner join gestacion_mujer using(rut)
                                  inner join paciente_establecimiento on persona.rut=paciente_establecimiento.rut
                                  inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno
                                  where sectores_centros_internos.id_centro_interno='$id_centro'
                                  and m_mujer='SI' and id_establecimiento='$id_establecimiento' 
                                  and estado_gestacion='ACTIVA' ";
                    } else {
                        $sql = "select count(*) as total from persona 
                                  inner join gestacion_mujer using(rut)
                                  inner join paciente_establecimiento on persona.rut=paciente_establecimiento.rut
                                  inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno
                                  where m_mujer='SI' and id_establecimiento='$id_establecimiento' 
                                  and estado_gestacion='ACTIVA' ";
                    }
                    $sql .= $filtro . ' ' . $filtro_sql[$TR];
//                    echo $sql;
                    $row = mysql_fetch_array(mysql_query($sql));
                    if ($row) {
                        $total = $row['total'];
                    } else {
                        $total = 0;
                    }
                    $fila .= "<td>$total</td>";


                }
                $fila_final = '<tr>
                                    <td>'.$texto_fila.'</td>';
                $fila_final .= $fila;
                echo $fila_final.'</tr>';
            }
            ?>
        </table>
    </section>

    <section id="seccion_c" style="width: 100%;overflow-y: scroll;">
        <div class="row">
            <div class="col l10">
                <header>SECCION C: GESTANTES EN RIESGO PSICOSOCIAL CON VISITA DOMICILIARIA INTEGRAL REALIZADA</header>
            </div>
        </div>
        <table id="table_seccion_c" style="width: 100%;border: solid 1px black;" border="1">
            <tr>
                <td style="width: 400px;background-color: #fdff8b;position: relative;text-align: center;">
                    Nº VDI
                </td>
                <td>Nº Gestantes con VDI</td>
                <td>TOTAL de Visitas</td>
            </tr>

            <?php

            $INDICES = [
                "1 Visita",
                "2 Visitas",
                "3 Visitas",
                "4 Visitas Y Más",
                "TOTAL",
            ];
            $filtro_sql = [
                '=1',
                '=2',
                '=3',
                '>=4',
                '>=1',
            ];

            $filtro_rango_seccion_a = [
                '',
            ];

            foreach ($INDICES AS $TR => $texto_fila) {
                $fila = '';
                foreach ($filtro_rango_seccion_a as $i => $filtro) {
                    if ($id_centro != '') {
                        $sql = "select count(distinct visita_vdi.rut) as total,count(id_visita) as total_visitas from persona 
                                  inner join gestacion_mujer using(rut)
                                  inner join visita_vdi on visita_vdi.id_gestacion=gestacion_mujer.id_gestacion
                                  inner join paciente_establecimiento on persona.rut=paciente_establecimiento.rut
                                  inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno
                                  where sectores_centros_internos.id_centro_interno='$id_centro'
                                  and m_mujer='SI' and id_establecimiento='$id_establecimiento' 
                                  and estado_gestacion='ACTIVA' ";
                    } else {
                        $sql = "select count(distinct visita_vdi.rut) as total,count(id_visita) as total_visitas from persona 
                                  inner join gestacion_mujer using(rut)
                                  inner join visita_vdi on visita_vdi.id_gestacion=gestacion_mujer.id_gestacion 
                                  inner join paciente_establecimiento on persona.rut=paciente_establecimiento.rut
                                  inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno
                                  where m_mujer='SI' and id_establecimiento='$id_establecimiento' 
                                  and estado_gestacion='ACTIVA' ";
                    }
                    $sql .= "group by visita_vdi.rut
                             having count(*) ".$filtro_sql[$TR];
                    $res = mysql_query($sql);
                    $total = 0;
                    $total_visitas = 0;
                    while($row = mysql_fetch_array($res)){
                        $total++;
                        $total_visitas += $row['total_visitas'];
                    }
                    $fila .= "<td>$total</td>";
                    $fila .= "<td>$total_visitas</td>";


                }
                $fila_final = '<tr>
                                    <td>'.$texto_fila.'</td>';
                $fila_final .= $fila;
                echo $fila_final.'</tr>';
            }
            ?>
        </table>
    </section>

    <section id="seccion_d" style="width: 100%;overflow-y: scroll;">
        <div class="row">
            <div class="col l10">
                <header>SECCION D: GESTANTES Y MUJERES DE 8° MES POST-PARTO EN CONTROL, SEGÚN ESTADO NUTRICIONAL</header>
            </div>
        </div>
        <table id="table_seccion_d" style="width: 100%;border: solid 1px black;" border="1">
            <tr>
                <td style="width: 400px;background-color: #fdff8b;position: relative;text-align: center;" rowspan="2">
                    POBLACION
                </td>
                <td>ESTADO NUTRICIONAL</td>
                <td>TOTAL</td>
                <td>Menos 15 años</td>
                <td>15 a 19 AÑOS</td>
                <td>20 a 24 AÑOS</td>
                <td>25 a 29 AÑOS</td>
                <td>30 a 34 AÑOS</td>
                <td>35 a 39 AÑOS</td>
                <td>40 a 44 AÑOS</td>
                <td>45 a 49 AÑOS</td>
                <td>50 a 54 AÑOS</td>
            </tr>

            <?php

            $INDICES = [
                "1 Visita",
                "2 Visitas",
                "3 Visitas",
                "4 Visitas Y Más",
                "TOTAL",
            ];
            $filtro_sql = [
                '=1',
                '=2',
                '=3',
                '>=4',
                '>=1',
            ];

            $filtro_rango_seccion_a = [
                '',
            ];

            foreach ($INDICES AS $TR => $texto_fila) {
                $fila = '';
                foreach ($filtro_rango_seccion_a as $i => $filtro) {
                    if ($id_centro != '') {
                        $sql = "select count(distinct visita_vdi.rut) as total,count(id_visita) as total_visitas from persona 
                                  inner join gestacion_mujer using(rut)
                                  inner join visita_vdi on visita_vdi.id_gestacion=gestacion_mujer.id_gestacion
                                  inner join paciente_establecimiento on persona.rut=paciente_establecimiento.rut
                                  inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno
                                  where sectores_centros_internos.id_centro_interno='$id_centro'
                                  and m_mujer='SI' and id_establecimiento='$id_establecimiento' 
                                  and estado_gestacion='ACTIVA' ";
                    } else {
                        $sql = "select count(distinct visita_vdi.rut) as total,count(id_visita) as total_visitas from persona 
                                  inner join gestacion_mujer using(rut)
                                  inner join visita_vdi on visita_vdi.id_gestacion=gestacion_mujer.id_gestacion 
                                  inner join paciente_establecimiento on persona.rut=paciente_establecimiento.rut
                                  inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno
                                  where m_mujer='SI' and id_establecimiento='$id_establecimiento' 
                                  and estado_gestacion='ACTIVA' ";
                    }
                    $sql .= "group by visita_vdi.rut
                             having count(*) ".$filtro_sql[$TR];
                    $res = mysql_query($sql);
                    $total = 0;
                    $total_visitas = 0;
                    while($row = mysql_fetch_array($res)){
                        $total++;
                        $total_visitas += $row['total_visitas'];
                    }
                    $fila .= "<td>$total</td>";
                    $fila .= "<td>$total_visitas</td>";


                }
                $fila_final = '<tr>
                                    <td>'.$texto_fila.'</td>';
                $fila_final .= $fila;
                echo $fila_final.'</tr>';
            }
            ?>
        </table>
    </section>

    <section id="seccion_f" style="width: 100%;overflow-y: scroll;">
        <div class="row">
            <div class="col l10">
                <header>SECCION F: MUJERES EN CONTROL DE CLIMATERIO </header>
            </div>
        </div>
        <table id="table_seccion_f" style="width: 100%;border: solid 1px black;" border="1">
            <tr>
                <td style="width: 400px;background-color: #fdff8b;position: relative;text-align: center;">
                    Población
                </td>
                <td>45 a 64 años</td>
            </tr>

            <?php

            $INDICES = [
                "Población en Control",
            ];
            $filtro_sql = [
                '',
            ];

            $filtro_rango_seccion_a = [
                'and persona.edad_total>=45*12 and persona.edad_total<64*12',
            ];

            foreach ($INDICES AS $TR => $texto_fila) {
                $fila = '';
                foreach ($filtro_rango_seccion_a as $i => $filtro) {
                    if ($id_centro != '') {
                        $sql = "select count(*) as total from persona 
                                  inner join paciente_mujer using(rut)
                                  inner join paciente_establecimiento on persona.rut=paciente_establecimiento.rut
                                  inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno
                                  where sectores_centros_internos.id_centro_interno='$id_centro'
                                  and m_mujer='SI' and id_establecimiento='$id_establecimiento' 
                                  and paciente_mujer.climaterio='SI' ";
                    } else {
                        $sql = "select count(*) as total from persona 
                                  inner join paciente_mujer using(rut) 
                                  inner join paciente_establecimiento on persona.rut=paciente_establecimiento.rut
                                  inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno
                                  where m_mujer='SI' and id_establecimiento='$id_establecimiento' 
                                  and paciente_mujer.climaterio='SI' ";
                    }
                    $sql .= $filtro.$filtro_sql[$TR];

                    $row = mysql_fetch_array(mysql_query($sql));
                    if($row){
                        $total = $row['total'];
                    }else{
                        $total = 0;
                    }

                    $fila .= "<td>$total</td>";



                }
                $fila_final = '<tr>
                                    <td>'.$texto_fila.'</td>';
                $fila_final .= $fila;
                echo $fila_final.'</tr>';
            }


            //con pauta mrs
            $INDICES = [
                "Mujeres con pauta aplicada MRS*",
                "Mujeres con MRS elevado*",
            ];
            $filtro_sql = [
                "and pauta_mrs!=''",
                "and pauta_mrs='ALTERADO' ",
            ];

            $filtro_rango_seccion_a = [
                'and persona.edad_total>=45*12 and persona.edad_total<64*12 ',
            ];

            foreach ($INDICES AS $TR => $texto_fila) {
                $fila = '';
                foreach ($filtro_rango_seccion_a as $i => $filtro) {
                    if ($id_centro != '') {
                        $sql = "select count(distinct pauta_mrs.rut) as total from persona 
                                  inner join paciente_mujer using(rut)
                                  inner join pauta_mrs on persona.rut=pauta_mrs.rut
                                  inner join paciente_establecimiento on persona.rut=paciente_establecimiento.rut
                                  inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno
                                  where sectores_centros_internos.id_centro_interno='$id_centro'
                                  and m_mujer='SI' and id_establecimiento='$id_establecimiento' 
                                  and paciente_mujer.climaterio='SI' ";
                    } else {
                        $sql = "select count(distinct pauta_mrs.rut) as total from persona 
                                  inner join paciente_mujer using(rut)
                                  inner join pauta_mrs on persona.rut=pauta_mrs.rut
                                  inner join paciente_establecimiento on persona.rut=paciente_establecimiento.rut
                                  inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno
                                  where m_mujer='SI' and id_establecimiento='$id_establecimiento' 
                                  and paciente_mujer.climaterio='SI' ";
                    }
                    $sql .= $filtro.$filtro_sql[$TR];


                    $row = mysql_fetch_array(mysql_query($sql));
                    if($row){
                        $total = $row['total'];
                    }else{
                        $total = 0;
                    }

                    $fila .= "<td>$total</td>";



                }
                $fila_final = '<tr>
                                    <td>'.$texto_fila.'</td>';
                $fila_final .= $fila;
                echo $fila_final.'</tr>';
            }

            //con REEMPLAZO HORMONAL
            $INDICES = [
                "Mujeres con aplicación de terapia hormonal de reemplazo según MRS*",
            ];
            $filtro_sql = [
                "and reemplazo_hormonal='SI' ",
            ];

            $filtro_rango_seccion_a = [
                'and persona.edad_total>=45*12 and persona.edad_total<64*12 ',
            ];

            foreach ($INDICES AS $TR => $texto_fila) {
                $fila = '';
                foreach ($filtro_rango_seccion_a as $i => $filtro) {
                    if ($id_centro != '') {
                        $sql = "select count(*) as total from persona 
                                  inner join paciente_mujer using(rut)
                                  inner join paciente_establecimiento on persona.rut=paciente_establecimiento.rut
                                  inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno
                                  where sectores_centros_internos.id_centro_interno='$id_centro'
                                  and m_mujer='SI' and id_establecimiento='$id_establecimiento' 
                                  and paciente_mujer.climaterio='SI' ";
                    } else {
                        $sql = "select count(*) as total from persona 
                                  inner join paciente_mujer using(rut)
                                  inner join paciente_establecimiento on persona.rut=paciente_establecimiento.rut
                                  inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno
                                  where m_mujer='SI' and id_establecimiento='$id_establecimiento' 
                                  and paciente_mujer.climaterio='SI' ";
                    }
                    $sql .= $filtro.$filtro_sql[$TR];

                    $row = mysql_fetch_array(mysql_query($sql));
                    if($row){
                        $total = $row['total'];
                    }else{
                        $total = 0;
                    }

                    $fila .= "<td>$total</td>";



                }
                $fila_final = '<tr>
                                    <td>'.$texto_fila.'</td>';
                $fila_final .= $fila;
                echo $fila_final.'</tr>';
            }

            //con TALLERES EDUCATIVOS
            $INDICES = [
                "Talleres educativos",
            ];
            $filtro_sql = [
                " ",
            ];

            $filtro_rango_seccion_a = [
                'and persona.edad_total>=45*12 and persona.edad_total<64*12 ',
            ];

            foreach ($INDICES AS $TR => $texto_fila) {
                $fila = '';
                foreach ($filtro_rango_seccion_a as $i => $filtro) {
                    if ($id_centro != '') {
                        $sql = "select count(*) as total from persona 
                                  inner join paciente_mujer using(rut)
                                  inner join talleres_climaterio on paciente_mujer.rut=talleres_climaterio.rut
                                  inner join paciente_establecimiento on persona.rut=paciente_establecimiento.rut
                                  inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno
                                  where sectores_centros_internos.id_centro_interno='$id_centro'
                                  and m_mujer='SI' and id_establecimiento='$id_establecimiento' 
                                  and paciente_mujer.climaterio='SI' ";
                    } else {
                        $sql = "select count(*) as total from persona 
                                  inner join paciente_mujer using(rut)
                                  inner join talleres_climaterio on paciente_mujer.rut=talleres_climaterio.rut
                                  inner join paciente_establecimiento on persona.rut=paciente_establecimiento.rut
                                  inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno
                                  where m_mujer='SI' and id_establecimiento='$id_establecimiento' 
                                  and paciente_mujer.climaterio='SI' ";
                    }
                    $sql .= $filtro.$filtro_sql[$TR];

                    $row = mysql_fetch_array(mysql_query($sql));
                    if($row){
                        $total = $row['total'];
                    }else{
                        $total = 0;
                    }

                    $fila .= "<td>$total</td>";



                }
                $fila_final = '<tr>
                                    <td>'.$texto_fila.'</td>';
                $fila_final .= $fila;
                echo $fila_final.'</tr>';
            }
            ?>
        </table>
    </section>

    <section id="seccion_g" style="width: 100%;overflow-y: scroll;">
        <div class="row">
            <div class="col l10">
                <header>SECCION G: GESTANTES EN CONTROL CON ECOGRAFÍA POR TRIMESTRE DE GESTACION (EN EL SEMESTRE)</header>
            </div>
        </div>
        <table id="table_seccion_g" style="width: 100%;border: solid 1px black;" border="1">
            <tr>
                <td style="width: 400px;background-color: #fdff8b;position: relative;text-align: center;">
                    POBLACION
                </td>
                <td>PRIMER TRIMESTRE</td>
                <td>SEGUNDO TRIMESTRE</td>
                <td>TERCER TRIMESTRE</td>
                <td>TOTAL DE GESTANTES CON ECOGRAFIAS DEL EXTRASISTEMA</td>
            </tr>

            <?php

            $INDICES = [
                "Menor de 15",
                "15 - 19 ",
                "20 - 24",
                "25 - 29",
                "30 - 34",
                "35 - 39",
                "40 - 44",
                "45 - 49",
                "50 - 54",
                "TOTAL",
            ];
            $filtro_sql = [
                "and gestacion_mujer.estado_gestacion='ACTIVA' and  ecografias_mujer.trimestre='PRIMER' ",
                "and gestacion_mujer.estado_gestacion='ACTIVA' and  ecografias_mujer.trimestre='SEGUNDO' ",
                "and gestacion_mujer.estado_gestacion='ACTIVA' and  ecografias_mujer.trimestre='TERCERO' ",
                "and gestacion_mujer.estado_gestacion='ACTIVA' 
                and  (ecografias_mujer.trimestre='TERCERO' OR ecografias_mujer.trimestre='SEGUNDO' OR ecografias_mujer.trimestre='PRIMER') 
                and tipo_eco='EXTRASISTEMA' ",
            ];

            $filtro_rango_seccion_a = [
                'and persona.edad_total<15*12 ',
                'and persona.edad_total>=15*12 and persona.edad_total<20*12 ',
                'and persona.edad_total>=20*12 and persona.edad_total<25*12 ',
                'and persona.edad_total>=25*12 and persona.edad_total<30*12 ',
                'and persona.edad_total>=30*12 and persona.edad_total<35*12 ',
                'and persona.edad_total>=35*12 and persona.edad_total<40*12 ',
                'and persona.edad_total>=40*12 and persona.edad_total<45*12 ',
                'and persona.edad_total>=45*12 and persona.edad_total<50*12 ',
                'and persona.edad_total>=50*12 and persona.edad_total<55*12 ',
                'and persona.edad_total>=1*12 ',
            ];

            foreach ($filtro_rango_seccion_a as $i => $filtro_fila) {
                $texto_fila = $INDICES[$i];
                $fila = '';
                foreach ($filtro_sql as $column => $filtro_column){
                    if ($id_centro != '') {
                        $sql = "select count(*) as total from persona 
                                  inner join paciente_mujer on persona.rut=paciente_mujer.rut
                                  inner join gestacion_mujer on persona.rut=gestacion_mujer.rut
                                  inner join ecografias_mujer on ecografias_mujer.id_gestacion=gestacion_mujer.id_gestacion 
                                  inner join paciente_establecimiento on persona.rut=paciente_establecimiento.rut
                                  inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno,
                                  where sectores_centros_internos.id_centro_interno='$id_centro'
                                  and m_mujer='SI' and id_establecimiento='$id_establecimiento' 
                                  and paciente_mujer.gestacion='SI' ";
                    } else {
                        $sql = "select count(*) as total from persona 
                                  inner join paciente_mujer on persona.rut=paciente_mujer.rut
                                  inner join gestacion_mujer on persona.rut=gestacion_mujer.rut
                                  inner join ecografias_mujer on ecografias_mujer.id_gestacion=gestacion_mujer.id_gestacion
                                  inner join paciente_establecimiento on persona.rut=paciente_establecimiento.rut
                                  inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno
                                  where m_mujer='SI' and id_establecimiento='$id_establecimiento' 
                                  and paciente_mujer.gestacion='SI' ";
                    }
                    $sql .= $filtro_fila.$filtro_column;
//                    echo $sql;
                    $row = mysql_fetch_array(mysql_query($sql));
                    if($row){
                        $total = $row['total'];
                    }else{
                        $total = 0;
                    }

                    $fila .= "<td>$total</td>";
                }
                $fila_final = '<tr>
                                    <td>'.$texto_fila.'</td>';
                $fila_final .= $fila;
                echo $fila_final.'</tr>';
            }
            ?>
        </table>
    </section>

    <section id="seccion_h" style="width: 100%;overflow-y: scroll;">
        <div class="row">
            <div class="col l10">
                <header>SECCION H: MUJERES BAJO CONTROL DE REGULACIÓN DE FERTILIDAD SEGÚN ESTADO NUTRICIONAL</header>
            </div>
        </div>
        <table id="table_seccion_h" style="width: 100%;border: solid 1px black;" border="1">
            <tr>
                <td style="width: 400px;background-color: #fdff8b;position: relative;text-align: center;" rowspan="6">
                    POBLACION <br />MUJERES BAJO CONTROL DE REGULACIÓN DE FERTILIDAD SEGÚN ESTADO NUTRICIONAL
                </td>
                <td>ESTADO NUTRICIONAL</td>
                <td>TOTAL</td>
                <td>Menos 15 años</td>
                <td>15 a 19 AÑOS</td>
                <td>20 a 24 AÑOS</td>
                <td>25 a 29 AÑOS</td>
                <td>30 a 34 AÑOS</td>
                <td>35 a 39 AÑOS</td>
                <td>40 a 44 AÑOS</td>
                <td>45 a 49 AÑOS</td>
                <td>50 a 54 AÑOS</td>
            </tr>

            <?php

            $FILA = [
                "OBESA",
                "SOBREPESO",
                "NORMAL",
                "BAJO PESO",
                "TOTAL",
            ];
            $filtro_fila = [
                "and paciente_mujer.imc='OB' ",
                "and paciente_mujer.imc='SP' ",
                "and paciente_mujer.imc='N' ",
                "and paciente_mujer.imc='BP' ",
                "and (paciente_mujer.imc='BP' OR paciente_mujer.imc='N' OR paciente_mujer.imc='SP' paciente_mujer.imc='OB') ",

            ];

            $filtro_column = [
                'and persona.edad_total>=1*12 ',
                'and persona.edad_total<15*12 ',
                'and persona.edad_total>=15*12 and persona.edad_total<20*12 ',
                'and persona.edad_total>=20*12 and persona.edad_total<25*12 ',
                'and persona.edad_total>=25*12 and persona.edad_total<30*12 ',
                'and persona.edad_total>=30*12 and persona.edad_total<35*12 ',
                'and persona.edad_total>=35*12 and persona.edad_total<40*12 ',
                'and persona.edad_total>=40*12 and persona.edad_total<45*12 ',
                'and persona.edad_total>=45*12 and persona.edad_total<50*12 ',
                'and persona.edad_total>=50*12 and persona.edad_total<55*12 ',
            ];

            foreach ($filtro_fila as $f => $sql_fila) {
                $texto_fila = $FILA[$f];//detalle fila
                $fila = '';
                foreach ($filtro_column as $c => $sql_column){
                    if ($id_centro != '') {
                        $sql = "select count(*) as total from persona 
                                  inner join paciente_mujer on persona.rut=paciente_mujer.rut 
                                  inner join paciente_establecimiento on persona.rut=paciente_establecimiento.rut
                                  inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno,
                                  where sectores_centros_internos.id_centro_interno='$id_centro'
                                  and m_mujer='SI' and id_establecimiento='$id_establecimiento' 
                                  and paciente_mujer.regulacion_fertilidad='SI' ";
                    } else {
                        $sql = "select count(*) as total from persona 
                                  inner join paciente_mujer on persona.rut=paciente_mujer.rut
                                  inner join paciente_establecimiento on persona.rut=paciente_establecimiento.rut
                                  inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno
                                  where m_mujer='SI' and id_establecimiento='$id_establecimiento' 
                                  and paciente_mujer.regulacion_fertilidad='SI' ";
                    }
                    $sql .= $sql_fila.$sql_column;
                    $row = mysql_fetch_array(mysql_query($sql));
                    if($row){
                        $total = $row['total'];
                    }else{
                        $total = 0;
                    }

                    $fila .= "<td>$total</td>";
                }
                $fila_final = '<tr>
                                    <td>'.$texto_fila.'</td>';
                $fila_final .= $fila;
                echo $fila_final.'</tr>';
            }
            ?>
        </table>
    </section>

    <!--
    SOLO PARA HOSPITALES
    <section id="seccion_i" style="width: 100%;overflow-y: scroll;">
        <div class="row">
            <div class="col l10">
                <header>SECCIÓN I POBLACIÓN EN CONTROL POR PATOLOGÍAS DE ALTO RIESGO OBSTÉTRICO	</header>
            </div>
        </div>
        <table id="table_seccion_i" style="width: 100%;border: solid 1px black;" border="1">
            <tr>
                <td style="width: 400px;background-color: #fdff8b;position: relative;text-align: center;">
                    PATOLOGÍA
                </td>
                <td>TOTAL</td>
            </tr>

            <?php

            $FILA = [
                "Preeclampsia (PE) ",
                "Sindrome Hipertensivo del Embarazo (SHE)",
                "Factores de riesgo y condicionantes de Parto Prematuro",
                "Retardo Crecimiento Intrauterino (RCIU )",
                "SÍFILIS",
                "VIH",
                "Diabetes Pre Gestacional",
                "Diabetes Gestacional",
                "Cesárea anterior",
                "Malformación Congénita",
                "Anemia",
                "Cardiopatías",
                "Pielonefritis",
                "Rh(-) sensibilizada",
                "Placenta previa",
                "Chagas",
                "Colestasia Intrahépatica de Embarazo",
                "Otras patologías del embarazo",
                "TOTAL",
            ];
            $filtro_fila = [
                "",
                "",
                "",
                "",
                "",
                "",
                "",
                "",
                "",
                "",
                "",
                "",
                "",
                "",
                "",
                "",
                "",
                "",
                "",
            ];

            $filtro_column = [
                'and persona.edad_total>=1*12 '
            ];

            foreach ($filtro_fila as $f => $sql_fila) {
                $texto_fila = $FILA[$f];//detalle fila
                $fila = '';
                foreach ($filtro_column as $c => $sql_column){
                    if ($id_centro != '') {
                        $sql = "select count(*) as total from persona 
                                  inner join paciente_mujer on persona.rut=paciente_mujer.rut 
                                  inner join paciente_establecimiento on persona.rut=paciente_establecimiento.rut
                                  inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno,
                                  where sectores_centros_internos.id_centro_interno='$id_centro'
                                  and m_mujer='SI' and id_establecimiento='$id_establecimiento' 
                                  and paciente_mujer.regulacion_fertilidad='SI' ";
                    } else {
                        $sql = "select count(*) as total from persona 
                                  inner join paciente_mujer on persona.rut=paciente_mujer.rut
                                  inner join paciente_establecimiento on persona.rut=paciente_establecimiento.rut
                                  inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno
                                  where m_mujer='SI' and id_establecimiento='$id_establecimiento' 
                                  and paciente_mujer.regulacion_fertilidad='SI' ";
                    }
                    $sql .= $sql_fila.$sql_column;
                    $row = mysql_fetch_array(mysql_query($sql));
                    if($row){
                        $total = $row['total'];
                    }else{
                        $total = 0;
                    }

                    $fila .= "<td>$total</td>";
                }
                $fila_final = '<tr>
                                    <td>'.$texto_fila.'</td>';
                $fila_final .= $fila;
                echo $fila_final.'</tr>';
            }
            ?>
        </table>
    </section>
    -->

</div>
