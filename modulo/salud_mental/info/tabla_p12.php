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

<div class="card" id="todo_p12">
    <div class="row" style="padding:20px;">
        <div class="col l10">
            <header>CENTRO MEDICO: <?php echo $nombre_centro; ?></header>
            <header>REM-P12. PERSONAS CON PAP – MAMOGRAFIA - EXAMEN FISICO DE MAMA VIGENTES Y PRODUCCION DE PAP Y VPH (SEMESTRAL)</header>
        </div>
        <div class="col l2">
            <input type="button"
                   class="btn green lighten-2 white-text"
                   value="EXPORTAR A EXCEL" onclick="exportTable('todo_p12','REM P12')" />
        </div>
    </div>
    <hr class="row" style="margin-bottom: 10px;" />

    <section id="seccion_a" style="width: 100%;overflow-y: scroll;">
        <div class="row">
            <div class="col l10">
                <header>SECCION A: PROGRAMA DE CANCER DE CUELLO UTERINO: POBLACIÓN CON PAP VIGENTE</header>
            </div>
        </div>
        <table id="table_seccion_a" style="width: 100%;border: solid 1px black;" border="1">
            <tr>
                <td style="width: 400px;background-color: #fdff8b;position: relative;text-align: center;">
                    FRUPOS DE EDAD (EN AÑOS)
                </td>
                <td>MUJERES con PAP Vigente en los ultimos 3 años</td>
                <td>TRANS MASCULINO con PAP Vigente en los ultimos 3 años</td>
            </tr>
            <?php

            $INDICES = [
                'Menor de 25 Años',
                '25 a 29 Años',
                '30 a 34 Años',
                '35 a 39 Años',
                '40 a 44 Años',
                '45 a 49 Años',
                '50 a 54 Años',
                '55 a 59 Años',
                '60 a 64 Años',
                '65 a 69 Años',
                '70 y 74 Años',
                '75 y 79 Años',
                '80 y Más años',
                'TOTAL',
            ];
            $filtro_fila = [
                'and persona.edad_total<25*12',
                'and persona.edad_total>=25*12 and persona.edad_total<29*12',
                'and persona.edad_total>=30*12 and persona.edad_total<34*12',
                'and persona.edad_total>=35*12 and persona.edad_total<39*12',
                'and persona.edad_total>=40*12 and persona.edad_total<44*12',
                'and persona.edad_total>=45*12 and persona.edad_total<49*12',
                'and persona.edad_total>=50*12 and persona.edad_total<54*12',
                'and persona.edad_total>=55*12 and persona.edad_total<59*12',
                'and persona.edad_total>=60*12 and persona.edad_total<64*12',
                'and persona.edad_total>=65*12 and persona.edad_total<69*12',
                'and persona.edad_total>=70*12 and persona.edad_total<74*12',
                'and persona.edad_total>=75*12 and persona.edad_total<79*12',
                'and persona.edad_total>=80*12 ',
                "AND persona.rut!=''",
            ];

            $filtro_columna = [
                "and persona.sexo='F' and tipo_examen='PAP'  ",
                "and persona.sexo='M' and tipo_examen='PAP'"
            ];

            foreach ($INDICES AS $TR => $texto_fila){
                $fila = '';
                foreach ($filtro_columna as $i => $filtro_colmun){
                    if($id_centro!=''){
                        $sql = "select count(distinct examen_mujer.rut) as total from persona 
                                  inner join examen_mujer using(rut)
                                  inner join paciente_establecimiento on persona.rut=paciente_establecimiento.rut
                                  inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno
                                  where sectores_centros_internos.id_centro_interno='$id_centro'
                                  and m_mujer='SI' and TIMESTAMPDIFF(DAY,examen_mujer.fecha_examen,CURRENT_DATE)<(365*3)
                                  and id_establecimiento='$id_establecimiento' ";
                    }else{
                        $sql = "select count(distinct examen_mujer.rut) as total from persona 
                                  inner join examen_mujer using(rut)
                                  inner join paciente_establecimiento on persona.rut=paciente_establecimiento.rut
                                  inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno
                                  where m_mujer='SI' and m_mujer='SI' and TIMESTAMPDIFF(DAY,examen_mujer.fecha_examen,CURRENT_DATE)<(365*3) 
                                  and id_establecimiento='$id_establecimiento' ";
                    }
                    $sql .= $filtro_colmun.' '.$filtro_fila[$TR].' group by examen_mujer.rut';
//                    echo $sql.'<br />';
                    $row = mysql_fetch_array(mysql_query($sql));
                    if($row){
                        $total = $row['total'];
                    }else{
                        $total = 0;
                    }
                    $fila .= "<td>$total</td>";


                }
                echo '<td>'.$texto_fila.'</td>'.$fila.'</tr>';
            }



            ?>



        </table>
    </section>

    <section id="seccion_b1" style="width: 100%;overflow-y: scroll;">
        <div class="row">
            <div class="col l10">
                <header>SECCIÓN B.1- PROGRAMA DE CANCER DE CUELLO UTERINO: PAP REALIZADOS E INFORMADOS SEGÚN RESULTADOS Y GRUPOS DE EDAD (Examen realizados en la red pública)</header>
            </div>
        </div>
        <table id="table_seccion_b1" style="width: 100%;border: solid 1px black;" border="1">
            <tr>
                <td rowspan="3" style="width: 200px;background-color: #fdff8b;position: relative;text-align: center;">
                    FRUPOS DE EDAD (EN AÑOS)
                </td>
                <td colspan="11">PAP INFORMADOS SEGUN RESULTADOS</td>
            </tr>
            <tr>
                <td colspan="1" rowspan="2">TOTAL</td>
                <td colspan="1" rowspan="2">NORMALES</td>
                <td colspan="2">INADECUADOS Y ATIPICOS</td>
                <td colspan="6">POSITIVOS</td>
                <td colspan="1" rowspan="2">TOTAL PAP INFORMADOS EN TRANS MASCULINO</td>
            </tr>
            <tr>
                <td>INADECUADOS</td>
                <td>ATIPICOS</td>
                <td> HPV</td>
                <td> NIE I</td>
                <td> NIE II</td>
                <td> NIE III</td>
                <td> Ca. Inv. (Epidermoide)</td>
                <td>Ca. Inv. (Adenocarcinoma)</td>
            </tr>
            <?php

            $INDICES = [
                'Menor de 25 Años',
                '25 a 29 Años',
                '30 a 34 Años',
                '35 a 39 Años',
                '40 a 44 Años',
                '45 a 49 Años',
                '50 a 54 Años',
                '55 a 59 Años',
                '60 a 64 Años',
                '65 a 69 Años',
                '70 y 74 Años',
                '75 y 79 Años',
                '80 y Más años',
                'TOTAL',
            ];
            $filtro_fila = [
                'and persona.edad_total<25*12',
                'and persona.edad_total>=25*12 and persona.edad_total<29*12',
                'and persona.edad_total>=30*12 and persona.edad_total<34*12',
                'and persona.edad_total>=35*12 and persona.edad_total<39*12',
                'and persona.edad_total>=40*12 and persona.edad_total<44*12',
                'and persona.edad_total>=45*12 and persona.edad_total<49*12',
                'and persona.edad_total>=50*12 and persona.edad_total<54*12',
                'and persona.edad_total>=55*12 and persona.edad_total<59*12',
                'and persona.edad_total>=60*12 and persona.edad_total<64*12',
                'and persona.edad_total>=65*12 and persona.edad_total<69*12',
                'and persona.edad_total>=70*12 and persona.edad_total<74*12',
                'and persona.edad_total>=75*12 and persona.edad_total<79*12',
                'and persona.edad_total>=80*12 ',
                "AND persona.rut!=''",
            ];

            $filtro_columna = [
                "and tipo_examen='PAP' ",
                "and tipo_examen='PAP' and valor_examen='NORMAL'  ",
                "and tipo_examen='PAP' and valor_examen='INADECUADOS'  ",
                "and tipo_examen='PAP' and valor_examen='ATIPICOS'  ",
                "and tipo_examen='PAP' and valor_examen='HPV'  ",
                "and tipo_examen='PAP' and valor_examen='NIE I'  ",
                "and tipo_examen='PAP' and valor_examen='NIE II'  ",
                "and tipo_examen='PAP' and valor_examen='NIE III'  ",
                "and tipo_examen='PAP' and valor_examen='CA EPIDERMOIDE'  ",
                "and tipo_examen='PAP' and valor_examen='CA ADENOCARCINOMA'  ",
                "and tipo_examen='PAP' and persona.sexo='M'  ",
            ];

            foreach ($INDICES AS $TR => $texto_fila){
                $fila = '';
                foreach ($filtro_columna as $i => $filtro_colmun){
                    if($id_centro!=''){
                        $sql = "select count(*) as total from persona 
                                  inner join examen_mujer using(rut)
                                  inner join paciente_establecimiento on persona.rut=paciente_establecimiento.rut
                                  inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno
                                  where sectores_centros_internos.id_centro_interno='$id_centro'
                                  and m_mujer='SI' and origen_examen='INTRASISTEMA'
                                    and TIMESTAMPDIFF(DAY,examen_mujer.fecha_examen,CURRENT_DATE)<365
                                  and id_establecimiento='$id_establecimiento' ";
                    }else{
                        $sql = "select count(*) as total from persona 
                                  inner join examen_mujer using(rut)
                                  inner join paciente_establecimiento on persona.rut=paciente_establecimiento.rut
                                  inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno
                                  where m_mujer='SI' and origen_examen='INTRASISTEMA' 
                                    and TIMESTAMPDIFF(DAY,examen_mujer.fecha_examen,CURRENT_DATE)<365
                                  and id_establecimiento='$id_establecimiento' ";
                    }
                    $sql .= $filtro_colmun.' '.$filtro_fila[$TR];

                    $row = mysql_fetch_array(mysql_query($sql));
                    if($row){
                        $total = $row['total'];
                    }else{
                        $total = 0;
                    }
                    $fila .= "<td>$total</td>";


                }
                echo '<td>'.$texto_fila.'</td>'.$fila.'</tr>';
            }



            ?>



        </table>
    </section>

    <section id="seccion_b2" style="width: 100%;overflow-y: scroll;">
        <div class="row">
            <div class="col l10">
                <header>SECCIÓN B.2- PROGRAMA DE CANCER DE CUELLO UTERINO: PAP REALIZADOS E INFORMADOS SEGÚN RESULTADOS Y GRUPOS DE EDAD (Examen realizados en extrasistema)</header>
            </div>
        </div>
        <table id="table_seccion_b2" style="width: 100%;border: solid 1px black;" border="1">
            <tr>
                <td rowspan="3" style="width: 200px;background-color: #fdff8b;position: relative;text-align: center;">
                    FRUPOS DE EDAD (EN AÑOS)
                </td>
                <td colspan="11">PAP INFORMADOS SEGUN RESULTADOS</td>
            </tr>
            <tr>
                <td colspan="1" rowspan="2">TOTAL</td>
                <td colspan="1" rowspan="2">NORMALES</td>
                <td colspan="2">INADECUADOS Y ATIPICOS</td>
                <td colspan="6">POSITIVOS</td>
                <td colspan="1" rowspan="2">TOTAL PAP INFORMADOS EN TRANS MASCULINO</td>
            </tr>
            <tr>
                <td>INADECUADOS</td>
                <td>ATIPICOS</td>
                <td> HPV</td>
                <td> NIE I</td>
                <td> NIE II</td>
                <td> NIE III</td>
                <td> Ca. Inv. (Epidermoide)</td>
                <td>Ca. Inv. (Adenocarcinoma)</td>
            </tr>
            <?php

            $INDICES = [
                'Menor de 25 Años',
                '25 a 29 Años',
                '30 a 34 Años',
                '35 a 39 Años',
                '40 a 44 Años',
                '45 a 49 Años',
                '50 a 54 Años',
                '55 a 59 Años',
                '60 a 64 Años',
                '65 a 69 Años',
                '70 y 74 Años',
                '75 y 79 Años',
                '80 y Más años',
                'TOTAL',
            ];
            $filtro_fila = [
                'and persona.edad_total<25*12',
                'and persona.edad_total>=25*12 and persona.edad_total<29*12',
                'and persona.edad_total>=30*12 and persona.edad_total<34*12',
                'and persona.edad_total>=35*12 and persona.edad_total<39*12',
                'and persona.edad_total>=40*12 and persona.edad_total<44*12',
                'and persona.edad_total>=45*12 and persona.edad_total<49*12',
                'and persona.edad_total>=50*12 and persona.edad_total<54*12',
                'and persona.edad_total>=55*12 and persona.edad_total<59*12',
                'and persona.edad_total>=60*12 and persona.edad_total<64*12',
                'and persona.edad_total>=65*12 and persona.edad_total<69*12',
                'and persona.edad_total>=70*12 and persona.edad_total<74*12',
                'and persona.edad_total>=75*12 and persona.edad_total<79*12',
                'and persona.edad_total>=80*12 ',
                "AND persona.rut!=''",
            ];

            $filtro_columna = [
                "and tipo_examen='PAP' ",
                "and tipo_examen='PAP' and valor_examen='NORMAL'  ",
                "and tipo_examen='PAP' and valor_examen='INADECUADOS'  ",
                "and tipo_examen='PAP' and valor_examen='ATIPICOS'  ",
                "and tipo_examen='PAP' and valor_examen='HPV'  ",
                "and tipo_examen='PAP' and valor_examen='NIE I'  ",
                "and tipo_examen='PAP' and valor_examen='NIE II'  ",
                "and tipo_examen='PAP' and valor_examen='NIE III'  ",
                "and tipo_examen='PAP' and valor_examen='CA EPIDERMOIDE'  ",
                "and tipo_examen='PAP' and valor_examen='CA ADENOCARCINOMA'  ",
                "and tipo_examen='PAP' and persona.sexo='M'  ",
            ];

            foreach ($INDICES AS $TR => $texto_fila){
                $fila = '';
                foreach ($filtro_columna as $i => $filtro_colmun){
                    if($id_centro!=''){
                        $sql = "select count(*) as total from persona 
                                  inner join examen_mujer using(rut)
                                  inner join paciente_establecimiento on persona.rut=paciente_establecimiento.rut
                                  inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno
                                  where sectores_centros_internos.id_centro_interno='$id_centro'
                                  and m_mujer='SI' and origen_examen='EXTRASISTEMA'
                                    and TIMESTAMPDIFF(DAY,examen_mujer.fecha_examen,CURRENT_DATE)<365
                                  and id_establecimiento='$id_establecimiento' ";
                    }else{
                        $sql = "select count(*) as total from persona 
                                  inner join examen_mujer using(rut)
                                  inner join paciente_establecimiento on persona.rut=paciente_establecimiento.rut
                                  inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno
                                  where m_mujer='SI' 
                                  and origen_examen='EXTRASISTEMA'
                                    and TIMESTAMPDIFF(DAY,examen_mujer.fecha_examen,CURRENT_DATE)<365
                                  and id_establecimiento='$id_establecimiento' ";
                    }
                    $sql .= $filtro_colmun.' '.$filtro_fila[$TR];

                    $row = mysql_fetch_array(mysql_query($sql));
                    if($row){
                        $total = $row['total'];
                    }else{
                        $total = 0;
                    }
                    $fila .= "<td>$total</td>";


                }
                echo '<td>'.$texto_fila.'</td>'.$fila.'</tr>';
            }



            ?>



        </table>
    </section>

    <section id="seccion_c" style="width: 100%;overflow-y: scroll;">
        <div class="row">
            <div class="col l10">
                <header>SECCION C: PROGRAMA DE CANCER DE MAMA: MUJERES CON MAMOGRAFÍA VIGENTE EN LOS ULTIMOS 3 AÑOS</header>
            </div>
        </div>
        <table id="table_seccion_c" style="width: 100%;border: solid 1px black;" border="1">
            <tr>
                <td style="width: 400px;background-color: #fdff8b;position: relative;text-align: center;">
                    FRUPOS DE EDAD (EN AÑOS)
                </td>
                <td>Mujeres con mamografia vigente  (Menor o igual a 3 años)</td>
            </tr>
            <?php

            $INDICES = [
                'Menor de 35 Años',
                '35 a 49 Años',
                '50 a 54 Años',
                '55 a 59 Años',
                '60 a 64 Años',
                '65 a 69 Años',
                '70 y 74 Años',
                '75 y 79 Años',
                '80 y Más años',
                'TOTAL',
            ];
            $filtro_fila = [
                'and persona.edad_total<35*12',
                'and persona.edad_total>=35*12 and persona.edad_total<49*12',
                'and persona.edad_total>=50*12 and persona.edad_total<54*12',
                'and persona.edad_total>=55*12 and persona.edad_total<59*12',
                'and persona.edad_total>=60*12 and persona.edad_total<64*12',
                'and persona.edad_total>=65*12 and persona.edad_total<69*12',
                'and persona.edad_total>=70*12 and persona.edad_total<74*12',
                'and persona.edad_total>=75*12 and persona.edad_total<79*12',
                'and persona.edad_total>=80*12 ',
                "AND persona.rut!=''",
            ];

            $filtro_columna = [
                "and persona.sexo='F' and tipo_examen='MAMOGRAFIA'  ",
            ];

            foreach ($INDICES AS $TR => $texto_fila){
                $fila = '';
                foreach ($filtro_columna as $i => $filtro_colmun){
                    if($id_centro!=''){
                        $sql = "select count(distinct examen_mujer.rut) as total from persona 
                                  inner join examen_mujer using(rut)
                                  inner join paciente_establecimiento on persona.rut=paciente_establecimiento.rut
                                  inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno
                                  where sectores_centros_internos.id_centro_interno='$id_centro'
                                  and m_mujer='SI' and TIMESTAMPDIFF(DAY,examen_mujer.fecha_examen,CURRENT_DATE)<(365*3)
                                  and id_establecimiento='$id_establecimiento' ";
                    }else{
                        $sql = "select count(distinct examen_mujer.rut) as total from persona 
                                  inner join examen_mujer using(rut)
                                  inner join paciente_establecimiento on persona.rut=paciente_establecimiento.rut
                                  inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno
                                  where m_mujer='SI' and m_mujer='SI' and TIMESTAMPDIFF(DAY,examen_mujer.fecha_examen,CURRENT_DATE)<(365*3) 
                                  and id_establecimiento='$id_establecimiento' ";
                    }
                    $sql .= $filtro_colmun.' '.$filtro_fila[$TR].' group by examen_mujer.rut';
//                    echo $sql.'<br />';
                    $row = mysql_fetch_array(mysql_query($sql));
                    if($row){
                        $total = $row['total'];
                    }else{
                        $total = 0;
                    }
                    $fila .= "<td>$total</td>";


                }
                echo '<td>'.$texto_fila.'</td>'.$fila.'</tr>';
            }



            ?>



        </table>
    </section>

    <section id="seccion_d" style="width: 100%;overflow-y: scroll;">
        <div class="row">
            <div class="col l10">
                <header>SECCION D: PROGRAMA DE CANCER DE MAMA: NÚMERO DE MUJERES CON EXAMEN FÍSICO DE MAMA (VIGENTE)</header>
            </div>
        </div>
        <table id="table_seccion_d" style="width: 100%;border: solid 1px black;" border="1">
            <tr>
                <td style="width: 400px;background-color: #fdff8b;position: relative;text-align: center;">
                    FRUPOS DE EDAD (EN AÑOS)
                </td>
                <td>Mujeres con mamografia vigente  (Menor o igual a 3 años)</td>
            </tr>
            <?php

            $INDICES = [
                'Menor de 35 Años',
                '35 a 49 Años',
                '50 a 54 Años',
                '55 a 59 Años',
                '60 a 64 Años',
                '65 a 69 Años',
                '70 y 74 Años',
                '75 y 79 Años',
                '80 y Más años',
                'TOTAL',
            ];
            $filtro_fila = [
                'and persona.edad_total<35*12',
                'and persona.edad_total>=35*12 and persona.edad_total<49*12',
                'and persona.edad_total>=50*12 and persona.edad_total<54*12',
                'and persona.edad_total>=55*12 and persona.edad_total<59*12',
                'and persona.edad_total>=60*12 and persona.edad_total<64*12',
                'and persona.edad_total>=65*12 and persona.edad_total<69*12',
                'and persona.edad_total>=70*12 and persona.edad_total<74*12',
                'and persona.edad_total>=75*12 and persona.edad_total<79*12',
                'and persona.edad_total>=80*12 ',
                "AND persona.rut!=''",
            ];

            $filtro_columna = [
                "and persona.sexo='F' and tipo_examen='EXAMEN FISICO MAMAS'  ",
            ];

            foreach ($INDICES AS $TR => $texto_fila){
                $fila = '';
                foreach ($filtro_columna as $i => $filtro_colmun){
                    if($id_centro!=''){
                        $sql = "select count(distinct examen_mujer.rut) as total from persona 
                                  inner join examen_mujer using(rut)
                                  inner join paciente_establecimiento on persona.rut=paciente_establecimiento.rut
                                  inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno
                                  where sectores_centros_internos.id_centro_interno='$id_centro'
                                  and m_mujer='SI' and TIMESTAMPDIFF(DAY,examen_mujer.fecha_examen,CURRENT_DATE)<(365*3)
                                  and id_establecimiento='$id_establecimiento' ";
                    }else{
                        $sql = "select count(distinct examen_mujer.rut) as total from persona 
                                  inner join examen_mujer using(rut)
                                  inner join paciente_establecimiento on persona.rut=paciente_establecimiento.rut
                                  inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno
                                  where m_mujer='SI' and m_mujer='SI' and TIMESTAMPDIFF(DAY,examen_mujer.fecha_examen,CURRENT_DATE)<(365*3) 
                                  and id_establecimiento='$id_establecimiento' ";
                    }
                    $sql .= $filtro_colmun.' '.$filtro_fila[$TR].' group by examen_mujer.rut';
//                    echo $sql.'<br />';
                    $row = mysql_fetch_array(mysql_query($sql));
                    if($row){
                        $total = $row['total'];
                    }else{
                        $total = 0;
                    }
                    $fila .= "<td>$total</td>";


                }
                echo '<td>'.$texto_fila.'</td>'.$fila.'</tr>';
            }



            ?>



        </table>
    </section>

    <section id="seccion_e" style="width: 100%;overflow-y: scroll;">
        <div class="row">
            <div class="col l10">
                <header>SECCION E: PROGRAMA DE CANCER DE CUELLO UTERINO: POBLACIÓN CON VPH VIGENTE</header>
            </div>
        </div>
        <table id="table_seccion_e" style="width: 100%;border: solid 1px black;" border="1">
            <tr>
                <td style="width: 400px;background-color: #fdff8b;position: relative;text-align: center;">
                    FRUPOS DE EDAD (EN AÑOS)
                </td>
                <td>MUJERES con Examen VPH Vigente en los ultimos 5 años</td>
                <td>TRANS MASCULINO con Examen VPH Vigente en los ultimos 5 años</td>
            </tr>
            <?php

            $INDICES = [
                '30 a 34 Años',
                '35 a 39 Años',
                '40 a 44 Años',
                '45 a 49 Años',
                '50 a 54 Años',
                '55 a 59 Años',
                '60 a 64 Años',
                'TOTAL',
            ];
            $filtro_fila = [
                'and persona.edad_total>=30*12 and persona.edad_total<34*12',
                'and persona.edad_total>=35*12 and persona.edad_total<39*12',
                'and persona.edad_total>=40*12 and persona.edad_total<44*12',
                'and persona.edad_total>=45*12 and persona.edad_total<49*12',
                'and persona.edad_total>=50*12 and persona.edad_total<54*12',
                'and persona.edad_total>=55*12 and persona.edad_total<59*12',
                'and persona.edad_total>=60*12 and persona.edad_total<64*12',
                'and persona.edad_total>=30*12 and persona.edad_total<64*12',
            ];

            $filtro_columna = [
                "and persona.sexo='F' and tipo_examen='VPH'  ",
                "and persona.sexo='M' and tipo_examen='VPH'  ",
            ];

            foreach ($INDICES AS $TR => $texto_fila){
                $fila = '';
                foreach ($filtro_columna as $i => $filtro_colmun){
                    if($id_centro!=''){
                        $sql = "select count(distinct examen_mujer.rut) as total from persona 
                                  inner join examen_mujer using(rut)
                                  inner join paciente_establecimiento on persona.rut=paciente_establecimiento.rut
                                  inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno
                                  where sectores_centros_internos.id_centro_interno='$id_centro'
                                  and m_mujer='SI' and TIMESTAMPDIFF(DAY,examen_mujer.fecha_examen,CURRENT_DATE)<(365*5)
                                  and id_establecimiento='$id_establecimiento' ";
                    }else{
                        $sql = "select count(distinct examen_mujer.rut) as total from persona 
                                  inner join examen_mujer using(rut)
                                  inner join paciente_establecimiento on persona.rut=paciente_establecimiento.rut
                                  inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno
                                  where m_mujer='SI' and m_mujer='SI' and TIMESTAMPDIFF(DAY,examen_mujer.fecha_examen,CURRENT_DATE)<(365*5) 
                                  and id_establecimiento='$id_establecimiento' ";
                    }
                    $sql .= $filtro_colmun.' '.$filtro_fila[$TR].' group by examen_mujer.rut';
//                    echo $sql.'<br />';
                    $row = mysql_fetch_array(mysql_query($sql));
                    if($row){
                        $total = $row['total'];
                    }else{
                        $total = 0;
                    }
                    $fila .= "<td>$total</td>";


                }
                echo '<td>'.$texto_fila.'</td>'.$fila.'</tr>';
            }



            ?>



        </table>
    </section>

    <section id="seccion_f" style="width: 100%;overflow-y: scroll;">
        <div class="row">
            <div class="col l10">
                <header>SECCIÓN F: PROGRAMA DE CANCER DE CUELLO UTERINO: RESULTADOS DE VPH  REALIZADOS EN EL SISTEMA PUBLICO SEGÚN GRUPOS DE EDAD (Examen realizados en la red pública)</header>
            </div>
        </div>
        <table id="table_seccion_f" style="width: 100%;border: solid 1px black;" border="1">
            <tr>
                <td rowspan="3" style="width: 200px;background-color: #fdff8b;position: relative;text-align: center;">
                    FRUPOS DE EDAD (EN AÑOS)
                </td>
                <td colspan="6">VPH INFORMADOS SEGÚN RESULTADO</td>
            </tr>
            <tr>
                <td colspan="1" rowspan="2">TOTAL</td>
                <td colspan="1" rowspan="2">VPH (-)</td>
                <td colspan="3">POSITIVOS</td>
                <td colspan="1" rowspan="2">TOTAL VPH INFORMADOS EN TRANS MASCULINO</td>
            </tr>
            <tr>
                <td>VPH 16 (+)</td>
                <td>VPH 18 (+)</td>
                <td>VPH AR* (+)</td>
            </tr>
            <?php

            $INDICES = [
                '30 a 34 Años',
                '35 a 39 Años',
                '40 a 44 Años',
                '45 a 49 Años',
                '50 a 54 Años',
                '55 a 59 Años',
                '60 a 64 Años',
                '65 a 69 Años',
                '70 y 74 Años',
                '75 y 79 Años',
                '80 y Más años',
                'TOTAL',
            ];
            $filtro_fila = [
                'and persona.edad_total>=30*12 and persona.edad_total<34*12',
                'and persona.edad_total>=35*12 and persona.edad_total<39*12',
                'and persona.edad_total>=40*12 and persona.edad_total<44*12',
                'and persona.edad_total>=45*12 and persona.edad_total<49*12',
                'and persona.edad_total>=50*12 and persona.edad_total<54*12',
                'and persona.edad_total>=55*12 and persona.edad_total<59*12',
                'and persona.edad_total>=60*12 and persona.edad_total<64*12',
                'and persona.edad_total>=65*12 and persona.edad_total<69*12',
                'and persona.edad_total>=70*12 and persona.edad_total<74*12',
                'and persona.edad_total>=75*12 and persona.edad_total<79*12',
                'and persona.edad_total>=80*12 ',
                "AND persona.rut!=''",
            ];

            $filtro_columna = [
                "and tipo_examen='VPH' ",
                "and tipo_examen='VPH' and valor_examen='VPH (-)'  ",
                "and tipo_examen='VPH' and valor_examen='VPH 16(+)'  ",
                "and tipo_examen='VPH' and valor_examen='VPH 18(+)'  ",
                "and tipo_examen='VPH' and valor_examen='VPH ALTO RIESGO(+)'  ",
                "and tipo_examen='VPH' and persona.sexo='M'  "
            ];

            foreach ($INDICES AS $TR => $texto_fila){
                $fila = '';
                foreach ($filtro_columna as $i => $filtro_colmun){
                    if($id_centro!=''){
                        $sql = "select count(distinct examen_mujer.rut) as total from persona 
                                  inner join examen_mujer using(rut)
                                  inner join paciente_establecimiento on persona.rut=paciente_establecimiento.rut
                                  inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno
                                  where sectores_centros_internos.id_centro_interno='$id_centro'
                                  and m_mujer='SI' and origen_examen='INTRASISTEMA'
                                    and TIMESTAMPDIFF(DAY,examen_mujer.fecha_examen,CURRENT_DATE)<365
                                  and id_establecimiento='$id_establecimiento' ";
                    }else{
                        $sql = "select count(distinct examen_mujer.rut) as total from persona 
                                  inner join examen_mujer using(rut)
                                  inner join paciente_establecimiento on persona.rut=paciente_establecimiento.rut
                                  inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno
                                  where m_mujer='SI' and origen_examen='INTRASISTEMA' 
                                    and TIMESTAMPDIFF(DAY,examen_mujer.fecha_examen,CURRENT_DATE)<365
                                  and id_establecimiento='$id_establecimiento' ";
                    }
                    $sql .= $filtro_colmun.' '.$filtro_fila[$TR];

                    $row = mysql_fetch_array(mysql_query($sql));
                    if($row){
                        $total = $row['total'];
                    }else{
                        $total = 0;
                    }
                    $fila .= "<td>$total</td>";


                }
                echo '<td>'.$texto_fila.'</td>'.$fila.'</tr>';
            }



            ?>



        </table>
    </section>

    <section id="seccion_f1" style="width: 100%;overflow-y: scroll;">
        <div class="row">
            <div class="col l10">
                <header>SECCIÓN F: PROGRAMA DE CANCER DE CUELLO UTERINO: RESULTADOS DE VPH  REALIZADOS EN EL SISTEMA PUBLICO SEGÚN GRUPOS DE EDAD (Examen realizados en extrasistema)</header>
            </div>
        </div>
        <table id="table_seccion_f1" style="width: 100%;border: solid 1px black;" border="1">
            <tr>
                <td rowspan="3" style="width: 200px;background-color: #fdff8b;position: relative;text-align: center;">
                    FRUPOS DE EDAD (EN AÑOS)
                </td>
                <td colspan="6">VPH INFORMADOS SEGÚN RESULTADO</td>
            </tr>
            <tr>
                <td colspan="1" rowspan="2">TOTAL</td>
                <td colspan="1" rowspan="2">VPH (-)</td>
                <td colspan="3">POSITIVOS</td>
                <td colspan="1" rowspan="2">TOTAL VPH INFORMADOS EN TRANS MASCULINO</td>
            </tr>
            <tr>
                <td>VPH 16 (+)</td>
                <td>VPH 18 (+)</td>
                <td>VPH AR* (+)</td>
            </tr>
            <?php

            $INDICES = [
                '30 a 34 Años',
                '35 a 39 Años',
                '40 a 44 Años',
                '45 a 49 Años',
                '50 a 54 Años',
                '55 a 59 Años',
                '60 a 64 Años',
                '65 a 69 Años',
                '70 y 74 Años',
                '75 y 79 Años',
                '80 y Más años',
                'TOTAL',
            ];
            $filtro_fila = [
                'and persona.edad_total>=30*12 and persona.edad_total<34*12',
                'and persona.edad_total>=35*12 and persona.edad_total<39*12',
                'and persona.edad_total>=40*12 and persona.edad_total<44*12',
                'and persona.edad_total>=45*12 and persona.edad_total<49*12',
                'and persona.edad_total>=50*12 and persona.edad_total<54*12',
                'and persona.edad_total>=55*12 and persona.edad_total<59*12',
                'and persona.edad_total>=60*12 and persona.edad_total<64*12',
                'and persona.edad_total>=65*12 and persona.edad_total<69*12',
                'and persona.edad_total>=70*12 and persona.edad_total<74*12',
                'and persona.edad_total>=75*12 and persona.edad_total<79*12',
                'and persona.edad_total>=80*12 ',
                "AND persona.rut!=''",
            ];

            $filtro_columna = [
                "and tipo_examen='VPH' ",
                "and tipo_examen='VPH' and valor_examen='VPH (-)'  ",
                "and tipo_examen='VPH' and valor_examen='VPH 16(+)'  ",
                "and tipo_examen='VPH' and valor_examen='VPH 18(+)'  ",
                "and tipo_examen='VPH' and valor_examen='VPH ALTO RIESGO(+)'  ",
                "and tipo_examen='VPH' and persona.sexo='M'  "
            ];

            foreach ($INDICES AS $TR => $texto_fila){
                $fila = '';
                foreach ($filtro_columna as $i => $filtro_colmun){
                    if($id_centro!=''){
                        $sql = "select count(*) as total from persona 
                                  inner join examen_mujer using(rut)
                                  inner join paciente_establecimiento on persona.rut=paciente_establecimiento.rut
                                  inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno
                                  where sectores_centros_internos.id_centro_interno='$id_centro'
                                  and m_mujer='SI' and origen_examen='EXTRASISTEMA'
                                    and TIMESTAMPDIFF(DAY,examen_mujer.fecha_examen,CURRENT_DATE)<365
                                  and id_establecimiento='$id_establecimiento' ";
                    }else{
                        $sql = "select count(*) as total from persona 
                                  inner join examen_mujer using(rut)
                                  inner join paciente_establecimiento on persona.rut=paciente_establecimiento.rut
                                  inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno
                                  where m_mujer='SI' and origen_examen='EXTRASISTEMA'
                                   and TIMESTAMPDIFF(DAY,examen_mujer.fecha_examen,CURRENT_DATE)<365
                                  and id_establecimiento='$id_establecimiento' ";
                    }
                    $sql .= $filtro_colmun.' '.$filtro_fila[$TR];

                    $row = mysql_fetch_array(mysql_query($sql));
                    if($row){
                        $total = $row['total'];
                    }else{
                        $total = 0;
                    }
                    $fila .= "<td>$total</td>";


                }
                echo '<td>'.$texto_fila.'</td>'.$fila.'</tr>';
            }



            ?>



        </table>
    </section>

</div>
