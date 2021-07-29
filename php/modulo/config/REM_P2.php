<?php
include '../../config.php';
include '../../objetos/mysql.php';

$mysql = new mysql($_SESSION['id_usuario']);

$sql_1 = "UPDATE persona set edad_total_dias=TIMESTAMPDIFF(DAY, fecha_nacimiento, current_date) 
            where fecha_update_dias!=CURRENT_DATE() ";
//mysql_query($sql_1);

$id_establecimiento = $_SESSION['id_establecimiento'];

$id_centro = $_POST['id'];
if($id_centro!=''){
    $filtro_centro = " and id_centro_interno='$id_centro' ";
}


//rango de meses en dias
$rango_seccion_a = [
    'persona.edad_total_dias>=0 and persona.edad_total_dias<30', //menor 1 mes
    'persona.edad_total_dias>=(30*1) and persona.edad_total_dias<(30*2)',//un mes
    'persona.edad_total_dias>=(30*2) and persona.edad_total_dias<(30*3)',//dos meses
    'persona.edad_total_dias>=(30*3) and persona.edad_total_dias<(30*4)',//tres meses
    'persona.edad_total_dias>=(30*4) and persona.edad_total_dias<(30*5)',//cuatro meses
    'persona.edad_total_dias>=(30*5) and persona.edad_total_dias<(30*6)',//cinco meses
    'persona.edad_total_dias>=(30*6) and persona.edad_total_dias<(30*7)',//cinco meses
    'persona.edad_total_dias>=(30*7) and persona.edad_total_dias<(30*12)',//7 a 11 meses
    'persona.edad_total_dias>=(30*12) and persona.edad_total_dias<(30*18)',//12 a 17 meses
    'persona.edad_total_dias>=(30*18) and persona.edad_total_dias<(30*24)',//18 a 23 meses
    'persona.edad_total_dias>=(30*24) and persona.edad_total_dias<(30*36)',//24 a 35 meses
    'persona.edad_total_dias>=(30*36) and persona.edad_total_dias<(30*42)',//36 a 41 meses
    'persona.edad_total_dias>=(30*42) and persona.edad_total_dias<(30*48)',//42 a 47 meses
    'persona.edad_total_dias>=(30*48) and persona.edad_total_dias<(30*60)',//48 a 59 meses

    "persona.edad_total_dias>=0 and persona.edad_total_dias<(30*60) and persona.pueblo!='NO'",//PUEBLOS ORIGINARIOS
    "persona.edad_total_dias>=0 and persona.edad_total_dias<(30*60) and persona.migrante!='NO'",//MIGRANTES
];
$rango_seccion_a1 = [
    'persona.edad_total_dias>=(60*30) and persona.edad_total_dias<(72*30)', //entre 60 meses a 71 meses
    'persona.edad_total_dias>=(30*12*6) and persona.edad_total_dias<(30*12*10)',//desde los 6 años y menores de 10 años

    "persona.edad_total_dias>=(60*30) and persona.edad_total_dias<(30*12*10) and persona.pueblo!='NO'",//PUEBLOS ORIGINARIOS
    "persona.edad_total_dias>=(60*30) and persona.edad_total_dias<(30*12*10) and persona.migrante!='NO'",//MIGRANTES
];

$rango_seccion_b = [
    'persona.edad_total<12', //menor 12 meses
    'persona.edad_total>=12 and persona.edad_total<=17',
    'persona.edad_total>=18 and persona.edad_total<=23',
    'persona.edad_total>=24 and persona.edad_total<=47',
    'persona.edad_total>=48 and persona.edad_total<=59',
];
$label_rango_seccion_b = [
        'menores 12 meses',
        'desde 12 meses a 17 meses',
        'desde 18 meses a 23 meses',
        'desde 24 meses a 47 meses',
        'desde 48 meses a 59 meses',

];
$rango_seccion_c = [
    'persona.edad_total_dias>0', //menor 10 dias
    'persona.edad_total_dias<10', //menor 10 dias
    'persona.edad_total=1',
    'persona.edad_total=2',
    'persona.edad_total=3',
    'persona.edad_total=4',
    'persona.edad_total=5',
    'persona.edad_total=6',
    'persona.edad_total<=6',
];
$label_rango_seccion_c = [
        'TOTAL',
        'menor de 10 dias',
        '1 mes',
        '2 meses',
        '3 meses',
        '4 meses',
        '5 meses',
        '6 meses',
        'Total de Niños y Niñas que han Recibido VDI',
];
$rango_seccion_h = [
    'persona.edad_total_dias>=0 and persona.edad_total_dias<30', //menor 1 mes
    'persona.edad_total_dias>=(30*1) and persona.edad_total_dias<(30*2)',//un mes
    'persona.edad_total_dias>=(30*2) and persona.edad_total_dias<(30*3)',//dos meses
    'persona.edad_total_dias>=(30*3) and persona.edad_total_dias<(30*4)',//tres meses
    'persona.edad_total_dias>=(30*4) and persona.edad_total_dias<(30*5)',//cuatro meses
    'persona.edad_total_dias>=(30*5) and persona.edad_total_dias<(30*6)',//cinco meses
    'persona.edad_total_dias>=(30*6) and persona.edad_total_dias<(30*7)',//cinco meses
    'persona.edad_total_dias>=(30*7) and persona.edad_total_dias<(30*12)',//7 a 11 meses
    'persona.edad_total_dias>=(30*12) and persona.edad_total_dias<(30*18)',//12 a 17 meses
    'persona.edad_total_dias>=(30*18) and persona.edad_total_dias<(30*24)',//18 a 23 meses
    'persona.edad_total_dias>=(30*24) and persona.edad_total_dias<(30*36)',//24 a 35 meses
    'persona.edad_total_dias>=(30*36) and persona.edad_total_dias<(30*42)',//36 a 41 meses
    'persona.edad_total_dias>=(30*42) and persona.edad_total_dias<(30*48)',//42 a 47 meses
    'persona.edad_total_dias>=(30*48) and persona.edad_total_dias<(30*60)',//48 a 59 meses

    'persona.edad_total_dias>=(60*30) and persona.edad_total_dias<(72*30)', //entre 60 meses a 71 meses
    'persona.edad_total_dias>=(30*12*6) and persona.edad_total_dias<=(30*12*7)',//desde los 6 a 7 años
    'persona.edad_total_dias>=(30*12*8) and persona.edad_total_dias<=(30*12*9)',//desde los 8 a 9 años

    "persona.edad_total_dias>=0 and persona.edad_total_dias<(30*60) and persona.pueblo!='NO'",//PUEBLOS ORIGINARIOS
    "persona.edad_total_dias>=0 and persona.edad_total_dias<(30*60) and persona.migrante!='NO'",//MIGRANTES
];

$label_rango_seccion_h = [
    'menor de 1 mes', //menor 1 mes
    '1 mes',//un mes
    '2 meses',//dos meses
    '3 meses',//dos meses
    '4 meses',//dos meses
    '5 meses',//dos meses
    '6 meses',//dos meses
    '7 a 11 meses',//7 a 11 meses
    '12 a 17 meses',//12 a 17 meses
    '18 a 23 meses',//18 a 23 meses
    '24 a 35 meses',//24 a 35 meses
    '36 a 41 meses',//36 a 41 meses
    '42 a 47 meses',//42 a 47 meses
    '48 a 59 meses',//48 a 59 meses

    '60 a 71 meses', //entre 60 meses a 71 meses
    '6 a 7 años',//desde los 6 a 7 años
    '8 a 9 años',//desde los 6 a 7 años
];

$sexo = [
    "persona.sexo='M' ",
    "persona.sexo='F' "
];
$label_rango_seccion_e = [
    'Menor de 6 meses',
    'de 6 a 11 meses',
    'de 12 a 17 meses',
    'de 18 a 23 meses',
    '24 a 35 meses',//24 a 35 meses
    '36 a 41 meses',//36 a 41 meses
    '42 a 47 meses',//42 a 47 meses
    '48 a 59 meses',//48 a 59 meses
    '60 a 71 meses', //entre 60 meses a 71 meses
    '6 a 9 años',//desde los 6 a 9 años

];

$label_rango_seccion_f = [
    '36 a 47 meses',
    '48 6 a 71 meses',
    '6 a 9 años',
];
$rango_seccion_f = [
    'persona.edad_total>=36 and persona.edad_total<=47',//36 a 47 meses
    'persona.edad_total>=48 and persona.edad_total<=71',//48 6 a 71 meses
    'persona.edad_total>=(12*6) and persona.edad_total<=(12*9)',//desde los 8 a 9 años
];


$label_rango_seccion_g = [
    '4 a 11 meses',
    '12 a 23 meses',
    '24 a 35 meses',
    '36 a 47 meses',
    '48 a 59 meses',
    '60 a 71 meses'
];
$rango_seccion_g = [
    'persona.edad_total>=4 and persona.edad_total<=11',//36 a 47 meses
    'persona.edad_total>=12 and persona.edad_total<=23',//48 6 a 71 meses
    'persona.edad_total>=24 and persona.edad_total<=35',//48 6 a 71 meses
    'persona.edad_total>=36 and persona.edad_total<=47',//48 6 a 71 meses
    'persona.edad_total>=48 and persona.edad_total<=59',//48 6 a 71 meses
    'persona.edad_total>=60 and persona.edad_total<=71',//48 6 a 71 meses
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
<script type="text/javascript">
    function exportTable(table,file){
        let export_to_excel = document.getElementById(table);
        let data_to_send = document.getElementById('data_to_send');
        data_to_send.value = export_to_excel.outerHTML;
        $("#file").val(file);
        document.getElementById('formExport').submit();
    }
    function loadP2() {
        var id = $("#centro_interno").val();
        var div = 'contenido_menu';
        loading(div);
        $.post('php/modulo/config/REM_P2.php',{
            id:id
        },function(data){
            if(data !=='ERROR_SQL' ){
                $("#"+div).html(data);
            }else{

            }
        });
    }
</script>
<form action="exportar/table.php" method="post" target="_blank" id="formExport">
    <input type="hidden" id="data_to_send" name="data_to_send" />
    <input type="hidden" id="file" name="file" value="archivo" />
</form>
<div class="card" id="todo_p2">
    <div class="row">
        <div class="col l8 m12 s12">
            <div class="col l12">
                <label>CENTRO MEDICO
                    <select class="browser-default"
                            name="centro_interno"
                            id="centro_interno"
                            onchange="loadP2()" >
                        <option value="">TODOS</option>
                        <?php
                        $sql0 = "select * from centros_internos 
                              order by nombre_centro_interno ";
                        $res0 = mysql_query($sql0);
                        while($row0 = mysql_fetch_array($res0)){
                            if($id_centro==$row0['id_centro_interno']){
                                ?>
                                <option selected value="<?php echo $row0['id_centro_interno']; ?>"><?php echo $row0['nombre_centro_interno']; ?></option>
                                <?php
                            }else{
                                ?>
                                <option value="<?php echo $row0['id_centro_interno']; ?>"><?php echo $row0['nombre_centro_interno']; ?></option>
                                <?php
                            }

                        }
                        ?>
                    </select>
                </label>
            </div>
        </div>
        <div class="col l4 m12 s12 right-align">
            <div class="col l12">
                <input type="button"
                       class="btn green lighten-2 white-text"
                       value="EXPORTAR A EXCEL" onclick="exportTable('todo_p2','P2')" />
            </div>
        </div>
    </div>
    <hr class="row" style="margin-bottom: 10px;" />

    <section id="seccion_a" style="width: 100%;overflow-y: scroll;">
        <div class="row">
            <div class="col l10">
                <header>SECCION A: POBLACIÓN EN CONTROL, SEGÚN ESTADO NUTRICIONAL PARA NIÑOS MENOR DE UN MES-59 MESES</header>
            </div>
        </div>
        <table id="table_seccion_a" style="width: 100%;border: solid 1px black;" border="1">
            <tr>
                <td colspan="2" rowspan="3"
                    style="width: 400px;background-color: #fdff8b;position: relative;text-align: center;">
                  INDICADOR NUTRICIONAL Y PARAMETROS DE MEDICIÓN
                </td>
                <td rowspan="2" colspan="3">
                    TOTAL
                </td>
                    <td colspan="28">
                    GRUPOS DE EDAD (EN MESES - AÑOS) Y SEXO
                </td>
                    <td colspan="2" rowspan="2">
                    PUEBLOS ORIGINARIOS
                </td>
                    <td colspan="2" rowspan="2">
                    POBLACION MIGRANTE
                </td>
            </tr>
            <tr>
                <td colspan="2">MENOR DE 1 MES</td>
                <td colspan="2">1 MES</td>
                <td colspan="2">2 MESES</td>
                <td colspan="2">3 MESES</td>
                <td colspan="2">4 MESES</td>
                <td colspan="2">5 MESES</td>
                <td colspan="2">6 MESES</td>
                <td colspan="2">7 A 11 MESES</td>
                <td colspan="2">12 A 17 MESES</td>
                <td colspan="2">18 A 23 MESES</td>
                <td colspan="2">24 A 35 MESES</td>
                <td colspan="2">36 A 41 MESES</td>
                <td colspan="2">42 A 47 MESES</td>
                <td colspan="2">48 A 59 MESES</td>
            </tr>
            <tr>
                <td>AMBOS SEXOS</td>
                <td>HOMBRES</td>
                <td>MUJERES</td>

                <td>HOMBRES</td>
                <td>MUJERES</td>

                <td>HOMBRES</td>
                <td>MUJERES</td>

                <td>HOMBRES</td>
                <td>MUJERES</td>

                <td>HOMBRES</td>
                <td>MUJERES</td>

                <td>HOMBRES</td>
                <td>MUJERES</td>

                <td>HOMBRES</td>
                <td>MUJERES</td>

                <td>HOMBRES</td>
                <td>MUJERES</td>

                <td>HOMBRES</td>
                <td>MUJERES</td>

                <td>HOMBRES</td>
                <td>MUJERES</td>

                <td>HOMBRES</td>
                <td>MUJERES</td>

                <td>HOMBRES</td>
                <td>MUJERES</td>

                <td>HOMBRES</td>
                <td>MUJERES</td>

                <td>HOMBRES</td>
                <td>MUJERES</td>

                <td>HOMBRES</td>
                <td>MUJERES</td>

                <td>HOMBRES</td>
                <td>MUJERES</td>

                <td>HOMBRES</td>
                <td>MUJERES</td>
            </tr>
            <tr>
                <td colspan="2">TOTAL DE NIÑOS EN CONTROL</td>
            </tr>
            <tr>
                <td rowspan="6">INDICADOR PESO/EDAD</td>
                <td>+ 2 D.S. (>= +2.0)</td>
                <?php
                $thph = 0;
                $thpm = 0;
                $thm = 0;
                $tmm = 0;
                $tabla = 'antropometria';
                $indicador = 'PE';
                $valor = '2';
                $PE['2']['MUJERES'] =0;
                $PE['2']['HOMBRES'] =0;
                $PE['2']['AMBOS'] =0;
                $fila = '';
                foreach ($rango_seccion_a as $i => $rango){
                    $total_hombres = $mysql->getTotal($tabla,$indicador,$valor,$rango,$sexo[0],$id_centro);
                    $total_mujeres = $mysql->getTotal($tabla,$indicador,$valor,$rango,$sexo[1],$id_centro);
                    $PE['2']['HOMBRES'] = $PE['2']['HOMBRES'] + $total_hombres;
                    $PE['2']['MUJERES'] = $PE['2']['MUJERES'] + $total_mujeres;
                    $PE['2']['HOMBRES'] = $PE['2']['AMBOS'] + $total_mujeres + $total_hombres;

                    $fila.= '<td>'.$total_hombres.'</td>';//hombre
                    $fila.= '<td>'.$total_mujeres.'</td>';//mujer
                }
                ?>
                <td><?php echo $PE['2']['AMBOS'] ?></td>
                <td><?php echo $PE['2']['HOMBRES'] ?></td>
                <td><?php echo $PE['2']['MUJERES'] ?></td>
                <?php echo $fila; ?>


            </tr>
            <tr>
                <td>+ 1 D.S. (+1.0 a +1.9)</td>
                <?php
                $tabla = 'antropometria';
                $indicador = 'PE';
                $valor = '1';
                $PE['1']['MUJERES'] =0;
                $PE['1']['HOMBRES'] =0;
                $PE['1']['AMBOS'] =0;
                $fila = '';
                foreach ($rango_seccion_a as $i => $rango){
                    $total_hombres = $mysql->getTotal($tabla,$indicador,$valor,$rango,$sexo[0],$id_centro);
                    $total_mujeres = $mysql->getTotal($tabla,$indicador,$valor,$rango,$sexo[1],$id_centro);
                    $PE['1']['HOMBRES'] = $PE['1']['HOMBRES'] + $total_hombres;
                    $PE['1']['MUJERES'] = $PE['1']['MUJERES'] + $total_mujeres;
                    $PE['1']['HOMBRES'] = $PE['1']['AMBOS'] + $total_mujeres + $total_hombres;

                    $fila.= '<td>'.$total_hombres.'</td>';//hombre
                    $fila.= '<td>'.$total_mujeres.'</td>';//mujer
                }
                ?>
                <td><?php echo $PE['1']['AMBOS'] ?></td>
                <td><?php echo $PE['1']['HOMBRES'] ?></td>
                <td><?php echo $PE['1']['MUJERES'] ?></td>
                <?php echo $fila; ?>

            </tr>
            <tr style="font-weight: bold;background-color: #d7efff;">
                <td>TOTAL</td>
                <?php
                $tabla = 'antropometria';
                $indicador = 'PE';
                $total_mujeres = 0;
                $total_hombres= 0;
                $fila='';
                foreach ($rango_seccion_a as $i => $rango){

                    $total_hombres = $mysql->getTotal($tabla,$indicador,'1',$rango,$sexo[0],$id_centro);
                    $total_hombres += $mysql->getTotal($tabla,$indicador,'2',$rango,$sexo[0],$id_centro);

                    $total_mujeres = $mysql->getTotal($tabla,$indicador,'1',$rango,$sexo[1],$id_centro);
                    $total_mujeres += $mysql->getTotal($tabla,$indicador,'2',$rango,$sexo[1],$id_centro);


                    $fila.= '<td>'.$total_hombres.'</td>';//hombre
                    $fila.= '<td>'.$total_mujeres.'</td>';//mujer
                }
                ?>
                <td><?php echo $PE['1']['AMBOS']+$PE['2']['AMBOS'] ?></td>
                <td><?php echo $PE['1']['HOMBRES']+$PE['2']['HOMBRES'] ?></td>
                <td><?php echo $PE['1']['MUJERES']+$PE['2']['MUJERES'] ?></td>
                <?php echo $fila; ?>


            </tr>
            <tr>
                <td>- 1 D.S. (-1.0 a -1.9)</td>
                <?php
                $tabla = 'antropometria';
                $indicador = 'PE';
                $valor = '-1';
                $PE['-1']['MUJERES'] =0;
                $PE['-1']['HOMBRES'] =0;
                $PE['-1']['AMBOS'] =0;
                $fila = '';
                foreach ($rango_seccion_a as $i => $rango){
                    $total_hombres = $mysql->getTotal($tabla,$indicador,$valor,$rango,$sexo[0],$id_centro);
                    $total_mujeres = $mysql->getTotal($tabla,$indicador,$valor,$rango,$sexo[1],$id_centro);
                    $PE['-1']['HOMBRES'] = $PE['-1']['HOMBRES'] + $total_hombres;
                    $PE['-1']['MUJERES'] = $PE['-1']['MUJERES'] + $total_mujeres;
                    $PE['-1']['HOMBRES'] = $PE['-1']['AMBOS'] + $total_mujeres + $total_hombres;

                    $fila.= '<td>'.$total_hombres.'</td>';//hombre
                    $fila.= '<td>'.$total_mujeres.'</td>';//mujer
                }
                ?>
                <td><?php echo $PE['-1']['AMBOS'] ?></td>
                <td><?php echo $PE['-1']['HOMBRES'] ?></td>
                <td><?php echo $PE['-1']['MUJERES'] ?></td>
                <?php echo $fila; ?>

            </tr>
            <tr>
                <td>- 2 D.S. (<= -2.0)</td>
                <?php
                $tabla = 'antropometria';
                $indicador = 'PE';
                $valor = '-2';
                $PE['-2']['MUJERES'] =0;
                $PE['-2']['HOMBRES'] =0;
                $PE['-2']['AMBOS'] =0;
                $fila = '';
                foreach ($rango_seccion_a as $i => $rango){
                    $total_hombres = $mysql->getTotal($tabla,$indicador,$valor,$rango,$sexo[0],$id_centro);
                    $total_mujeres = $mysql->getTotal($tabla,$indicador,$valor,$rango,$sexo[1],$id_centro);
                    $PE['-2']['HOMBRES'] = $PE['-2']['HOMBRES'] + $total_hombres;
                    $PE['-2']['MUJERES'] = $PE['-2']['MUJERES'] + $total_mujeres;
                    $PE['-2']['HOMBRES'] = $PE['-2']['AMBOS'] + $total_mujeres + $total_hombres;

                    $fila.= '<td>'.$total_hombres.'</td>';//hombre
                    $fila.= '<td>'.$total_mujeres.'</td>';//mujer
                }
                ?>
                <td><?php echo $PE['-2']['AMBOS'] ?></td>
                <td><?php echo $PE['-2']['HOMBRES'] ?></td>
                <td><?php echo $PE['-2']['MUJERES'] ?></td>
                <?php echo $fila; ?>

            </tr>
            <tr style="font-weight: bold;background-color: #d7efff;">
                <td>TOTAL</td>
                <?php
                $tabla = 'antropometria';
                $indicador = 'PE';
                $total_mujeres = 0;
                $total_hombres= 0;
                $fila='';
                foreach ($rango_seccion_a as $i => $rango){

                    $total_hombres = $mysql->getTotal($tabla,$indicador,'-1',$rango,$sexo[0],$id_centro);
                    $total_hombres += $mysql->getTotal($tabla,$indicador,'-2',$rango,$sexo[0],$id_centro);

                    $total_mujeres = $mysql->getTotal($tabla,$indicador,'-1',$rango,$sexo[1],$id_centro);
                    $total_mujeres += $mysql->getTotal($tabla,$indicador,'-2',$rango,$sexo[1],$id_centro);


                    $fila.= '<td>'.$total_hombres.'</td>';//hombre
                    $fila.= '<td>'.$total_mujeres.'</td>';//mujer
                }
                ?>
                <td><?php echo $PE['-1']['AMBOS']+$PE['-2']['AMBOS'] ?></td>
                <td><?php echo $PE['-1']['HOMBRES']+$PE['-2']['HOMBRES'] ?></td>
                <td><?php echo $PE['-1']['MUJERES']+$PE['-2']['MUJERES'] ?></td>
                <?php echo $fila; ?>

            </tr>
            <!--PT -->
            <tr>
                <td rowspan="6">INDICADOR PESO/TALLA</td>
                <td>+ 2 D.S. (>= +2.0)</td>
                <?php
                $tabla = 'antropometria';
                $indicador = 'PT';
                $valor = '2';
                $PE['2']['MUJERES'] =0;
                $PE['2']['HOMBRES'] =0;
                $PE['2']['AMBOS'] =0;
                $fila = '';
                foreach ($rango_seccion_a as $i => $rango){
                    $total_hombres = $mysql->getTotal($tabla,$indicador,$valor,$rango,$sexo[0],$id_centro);
                    $total_mujeres = $mysql->getTotal($tabla,$indicador,$valor,$rango,$sexo[1],$id_centro);
                    $PE['2']['HOMBRES'] = $PE['2']['HOMBRES'] + $total_hombres;
                    $PE['2']['MUJERES'] = $PE['2']['MUJERES'] + $total_mujeres;
                    $PE['2']['HOMBRES'] = $PE['2']['AMBOS'] + $total_mujeres + $total_hombres;

                    $fila.= '<td>'.$total_hombres.'</td>';//hombre
                    $fila.= '<td>'.$total_mujeres.'</td>';//mujer
                }
                ?>
                <td><?php echo $PE['2']['AMBOS'] ?></td>
                <td><?php echo $PE['2']['HOMBRES'] ?></td>
                <td><?php echo $PE['2']['MUJERES'] ?></td>
                <?php echo $fila; ?>


            </tr>
            <tr>
                <td>+ 1 D.S. (+1.0 a +1.9)</td>
                <?php
                $tabla = 'antropometria';
                $indicador = 'PT';
                $valor = '1';
                $PE['1']['MUJERES'] =0;
                $PE['1']['HOMBRES'] =0;
                $PE['1']['AMBOS'] =0;
                $fila = '';
                foreach ($rango_seccion_a as $i => $rango){
                    $total_hombres = $mysql->getTotal($tabla,$indicador,$valor,$rango,$sexo[0],$id_centro);
                    $total_mujeres = $mysql->getTotal($tabla,$indicador,$valor,$rango,$sexo[1],$id_centro);
                    $PE['1']['HOMBRES'] = $PE['1']['HOMBRES'] + $total_hombres;
                    $PE['1']['MUJERES'] = $PE['1']['MUJERES'] + $total_mujeres;
                    $PE['1']['HOMBRES'] = $PE['1']['AMBOS'] + $total_mujeres + $total_hombres;

                    $fila.= '<td>'.$total_hombres.'</td>';//hombre
                    $fila.= '<td>'.$total_mujeres.'</td>';//mujer
                }
                ?>
                <td><?php echo $PE['1']['AMBOS'] ?></td>
                <td><?php echo $PE['1']['HOMBRES'] ?></td>
                <td><?php echo $PE['1']['MUJERES'] ?></td>
                <?php echo $fila; ?>

            </tr>
            <tr style="font-weight: bold;background-color: #d7efff;">
                <td>TOTAL</td>
                <?php
                $tabla = 'antropometria';
                $indicador = 'PT';
                $total_mujeres = 0;
                $total_hombres= 0;
                $fila='';
                foreach ($rango_seccion_a as $i => $rango){

                    $total_hombres = $mysql->getTotal($tabla,$indicador,'1',$rango,$sexo[0],$id_centro);
                    $total_hombres += $mysql->getTotal($tabla,$indicador,'2',$rango,$sexo[0],$id_centro);

                    $total_mujeres = $mysql->getTotal($tabla,$indicador,'1',$rango,$sexo[1],$id_centro);
                    $total_mujeres += $mysql->getTotal($tabla,$indicador,'2',$rango,$sexo[1],$id_centro);


                    $fila.= '<td>'.$total_hombres.'</td>';//hombre
                    $fila.= '<td>'.$total_mujeres.'</td>';//mujer
                }
                ?>
                <td><?php echo $PE['1']['AMBOS']+$PE['2']['AMBOS'] ?></td>
                <td><?php echo $PE['1']['HOMBRES']+$PE['2']['HOMBRES'] ?></td>
                <td><?php echo $PE['1']['MUJERES']+$PE['2']['MUJERES'] ?></td>
                <?php echo $fila; ?>

            </tr>
            <tr>
                <td>- 1 D.S. (-1.0 a -1.9)</td>
                <?php
                $tabla = 'antropometria';
                $indicador = 'PT';
                $valor = '-1';
                $PE['-1']['MUJERES'] =0;
                $PE['-1']['HOMBRES'] =0;
                $PE['-1']['AMBOS'] =0;
                $fila = '';
                foreach ($rango_seccion_a as $i => $rango){
                    $total_hombres = $mysql->getTotal($tabla,$indicador,$valor,$rango,$sexo[0],$id_centro);
                    $total_mujeres = $mysql->getTotal($tabla,$indicador,$valor,$rango,$sexo[1],$id_centro);
                    $PE['-1']['HOMBRES'] = $PE['-1']['HOMBRES'] + $total_hombres;
                    $PE['-1']['MUJERES'] = $PE['-1']['MUJERES'] + $total_mujeres;
                    $PE['-1']['HOMBRES'] = $PE['-1']['AMBOS'] + $total_mujeres + $total_hombres;

                    $fila.= '<td>'.$total_hombres.'</td>';//hombre
                    $fila.= '<td>'.$total_mujeres.'</td>';//mujer
                }
                ?>
                <td><?php echo $PE['-1']['AMBOS'] ?></td>
                <td><?php echo $PE['-1']['HOMBRES'] ?></td>
                <td><?php echo $PE['-1']['MUJERES'] ?></td>
                <?php echo $fila; ?>

            </tr>
            <tr>
                <td>- 2 D.S. (<= -2.0)</td>
                <?php
                $tabla = 'antropometria';
                $indicador = 'PT';
                $valor = '-2';
                $PE['-2']['MUJERES'] =0;
                $PE['-2']['HOMBRES'] =0;
                $PE['-2']['AMBOS'] =0;
                $fila = '';
                foreach ($rango_seccion_a as $i => $rango){
                    $total_hombres = $mysql->getTotal($tabla,$indicador,$valor,$rango,$sexo[0],$id_centro);
                    $total_mujeres = $mysql->getTotal($tabla,$indicador,$valor,$rango,$sexo[1],$id_centro);
                    $PE['-2']['HOMBRES'] = $PE['-2']['HOMBRES'] + $total_hombres;
                    $PE['-2']['MUJERES'] = $PE['-2']['MUJERES'] + $total_mujeres;
                    $PE['-2']['HOMBRES'] = $PE['-2']['AMBOS'] + $total_mujeres + $total_hombres;

                    $fila.= '<td>'.$total_hombres.'</td>';//hombre
                    $fila.= '<td>'.$total_mujeres.'</td>';//mujer
                }
                ?>
                <td><?php echo $PE['-2']['AMBOS'] ?></td>
                <td><?php echo $PE['-2']['HOMBRES'] ?></td>
                <td><?php echo $PE['-2']['MUJERES'] ?></td>
                <?php echo $fila; ?>

            </tr>
            <tr style="font-weight: bold;background-color: #d7efff;">
                <td>TOTAL</td>
                <?php
                $tabla = 'antropometria';
                $indicador = 'PT';
                $total_mujeres = 0;
                $total_hombres= 0;
                $fila='';
                foreach ($rango_seccion_a as $i => $rango){

                    $total_hombres = $mysql->getTotal($tabla,$indicador,'-1',$rango,$sexo[0],$id_centro);
                    $total_hombres += $mysql->getTotal($tabla,$indicador,'-2',$rango,$sexo[0],$id_centro);

                    $total_mujeres = $mysql->getTotal($tabla,$indicador,'-1',$rango,$sexo[1],$id_centro);
                    $total_mujeres += $mysql->getTotal($tabla,$indicador,'-2',$rango,$sexo[1],$id_centro);


                    $fila.= '<td>'.$total_hombres.'</td>';//hombre
                    $fila.= '<td>'.$total_mujeres.'</td>';//mujer
                }
                ?>
                <td><?php echo $PE['-1']['AMBOS']+$PE['-2']['AMBOS'] ?></td>
                <td><?php echo $PE['-1']['HOMBRES']+$PE['-2']['HOMBRES'] ?></td>
                <td><?php echo $PE['-1']['MUJERES']+$PE['-2']['MUJERES'] ?></td>
                <?php echo $fila; ?>

            </tr>
            <!--TE -->
            <tr>
                <td rowspan="6">INDICADOR TALLA/EDAD</td>
                <td>+ 2 D.S. (>= +2.0)</td>
                <?php
                $tabla = 'antropometria';
                $indicador = 'TE';
                $valor = '2';
                $PE['2']['MUJERES'] =0;
                $PE['2']['HOMBRES'] =0;
                $PE['2']['AMBOS'] =0;
                $fila = '';
                foreach ($rango_seccion_a as $i => $rango){
                    $total_hombres = $mysql->getTotal($tabla,$indicador,$valor,$rango,$sexo[0],$id_centro);
                    $total_mujeres = $mysql->getTotal($tabla,$indicador,$valor,$rango,$sexo[1],$id_centro);
                    $PE['2']['HOMBRES'] = $PE['2']['HOMBRES'] + $total_hombres;
                    $PE['2']['MUJERES'] = $PE['2']['MUJERES'] + $total_mujeres;
                    $PE['2']['HOMBRES'] = $PE['2']['AMBOS'] + $total_mujeres + $total_hombres;

                    $fila.= '<td>'.$total_hombres.'</td>';//hombre
                    $fila.= '<td>'.$total_mujeres.'</td>';//mujer
                }
                ?>
                <td><?php echo $PE['2']['AMBOS'] ?></td>
                <td><?php echo $PE['2']['HOMBRES'] ?></td>
                <td><?php echo $PE['2']['MUJERES'] ?></td>
                <?php echo $fila; ?>


            </tr>
            <tr>
                <td>+ 1 D.S. (+1.0 a +1.9)</td>
                <?php
                $tabla = 'antropometria';
                $indicador = 'TE';
                $valor = '1';
                $PE['1']['MUJERES'] =0;
                $PE['1']['HOMBRES'] =0;
                $PE['1']['AMBOS'] =0;
                $fila = '';
                foreach ($rango_seccion_a as $i => $rango){
                    $total_hombres = $mysql->getTotal($tabla,$indicador,$valor,$rango,$sexo[0],$id_centro);
                    $total_mujeres = $mysql->getTotal($tabla,$indicador,$valor,$rango,$sexo[1],$id_centro);
                    $PE['1']['HOMBRES'] = $PE['1']['HOMBRES'] + $total_hombres;
                    $PE['1']['MUJERES'] = $PE['1']['MUJERES'] + $total_mujeres;
                    $PE['1']['HOMBRES'] = $PE['1']['AMBOS'] + $total_mujeres + $total_hombres;

                    $fila.= '<td>'.$total_hombres.'</td>';//hombre
                    $fila.= '<td>'.$total_mujeres.'</td>';//mujer
                }
                ?>
                <td><?php echo $PE['1']['AMBOS'] ?></td>
                <td><?php echo $PE['1']['HOMBRES'] ?></td>
                <td><?php echo $PE['1']['MUJERES'] ?></td>
                <?php echo $fila; ?>
            </tr>
            <tr style="font-weight: bold;background-color: #d7efff;">
                <td>TOTAL</td>
                <?php
                $tabla = 'antropometria';
                $indicador = 'TE';
                $total_mujeres = 0;
                $total_hombres= 0;
                $fila='';
                foreach ($rango_seccion_a as $i => $rango){

                    $total_hombres = $mysql->getTotal($tabla,$indicador,'1',$rango,$sexo[0],$id_centro);
                    $total_hombres += $mysql->getTotal($tabla,$indicador,'2',$rango,$sexo[0],$id_centro);

                    $total_mujeres = $mysql->getTotal($tabla,$indicador,'1',$rango,$sexo[1],$id_centro);
                    $total_mujeres += $mysql->getTotal($tabla,$indicador,'2',$rango,$sexo[1],$id_centro);


                    $fila.= '<td>'.$total_hombres.'</td>';//hombre
                    $fila.= '<td>'.$total_mujeres.'</td>';//mujer
                }
                ?>
                <td><?php echo $PE['1']['AMBOS']+$PE['2']['AMBOS'] ?></td>
                <td><?php echo $PE['1']['HOMBRES']+$PE['2']['HOMBRES'] ?></td>
                <td><?php echo $PE['1']['MUJERES']+$PE['2']['MUJERES'] ?></td>
                <?php echo $fila; ?>

            </tr>
            <tr>
                <td>- 1 D.S. (-1.0 a -1.9)</td>
                <?php
                $tabla = 'antropometria';
                $indicador = 'TE';
                $valor = '-1';
                $PE['-1']['MUJERES'] =0;
                $PE['-1']['HOMBRES'] =0;
                $PE['-1']['AMBOS'] =0;
                $fila = '';
                foreach ($rango_seccion_a as $i => $rango){
                    $total_hombres = $mysql->getTotal($tabla,$indicador,$valor,$rango,$sexo[0],$id_centro);
                    $total_mujeres = $mysql->getTotal($tabla,$indicador,$valor,$rango,$sexo[1],$id_centro);
                    $PE['-1']['HOMBRES'] = $PE['-1']['HOMBRES'] + $total_hombres;
                    $PE['-1']['MUJERES'] = $PE['-1']['MUJERES'] + $total_mujeres;
                    $PE['-1']['HOMBRES'] = $PE['-1']['AMBOS'] + $total_mujeres + $total_hombres;

                    $fila.= '<td>'.$total_hombres.'</td>';//hombre
                    $fila.= '<td>'.$total_mujeres.'</td>';//mujer
                }
                ?>
                <td><?php echo $PE['-1']['AMBOS'] ?></td>
                <td><?php echo $PE['-1']['HOMBRES'] ?></td>
                <td><?php echo $PE['-1']['MUJERES'] ?></td>
                <?php echo $fila; ?>

            </tr>
            <tr>
                <td>- 2 D.S. (<= -2.0)</td>
                <?php
                $tabla = 'antropometria';
                $indicador = 'TE';
                $valor = '-2';
                $PE['-2']['MUJERES'] =0;
                $PE['-2']['HOMBRES'] =0;
                $PE['-2']['AMBOS'] =0;
                $fila = '';
                foreach ($rango_seccion_a as $i => $rango){
                    $total_hombres = $mysql->getTotal($tabla,$indicador,$valor,$rango,$sexo[0],$id_centro);
                    $total_mujeres = $mysql->getTotal($tabla,$indicador,$valor,$rango,$sexo[1],$id_centro);
                    $PE['-2']['HOMBRES'] = $PE['-2']['HOMBRES'] + $total_hombres;
                    $PE['-2']['MUJERES'] = $PE['-2']['MUJERES'] + $total_mujeres;
                    $PE['-2']['HOMBRES'] = $PE['-2']['AMBOS'] + $total_mujeres + $total_hombres;

                    $fila.= '<td>'.$total_hombres.'</td>';//hombre
                    $fila.= '<td>'.$total_mujeres.'</td>';//mujer
                }
                ?>
                <td><?php echo $PE['-2']['AMBOS'] ?></td>
                <td><?php echo $PE['-2']['HOMBRES'] ?></td>
                <td><?php echo $PE['-2']['MUJERES'] ?></td>
                <?php echo $fila; ?>

            </tr>
            <tr style="font-weight: bold;background-color: #d7efff;">
                <td>TOTAL</td>
                <?php
                $tabla = 'antropometria';
                $indicador = 'TE';
                $total_mujeres = 0;
                $total_hombres= 0;
                $fila='';
                foreach ($rango_seccion_a as $i => $rango){

                    $total_hombres = $mysql->getTotal($tabla,$indicador,'-1',$rango,$sexo[0],$id_centro);
                    $total_hombres += $mysql->getTotal($tabla,$indicador,'-2',$rango,$sexo[0],$id_centro);

                    $total_mujeres = $mysql->getTotal($tabla,$indicador,'-1',$rango,$sexo[1],$id_centro);
                    $total_mujeres += $mysql->getTotal($tabla,$indicador,'-2',$rango,$sexo[1],$id_centro);


                    $fila.= '<td>'.$total_hombres.'</td>';//hombre
                    $fila.= '<td>'.$total_mujeres.'</td>';//mujer
                }
                ?>
                <td><?php echo $PE['-1']['AMBOS']+$PE['-2']['AMBOS'] ?></td>
                <td><?php echo $PE['-1']['HOMBRES']+$PE['-2']['HOMBRES'] ?></td>
                <td><?php echo $PE['-1']['MUJERES']+$PE['-2']['MUJERES'] ?></td>
                <?php echo $fila; ?>
            </tr>
            <!--DNI - RI DESNUTRICION -->
            <tr>
                <td rowspan="7">DIAGNOSTICO NUTRICIONAL INTEGRADO</td>
                <td>RIESGO DE DESNUTRIR/ DEFICIT PONDERAL</td>
                <?php
                //
                $DNI['AMBOS'] = 0;
                $DNI['HOMBRES'] = 0;
                $DNI['MUJERES'] = 0;

                //
                $tabla = 'antropometria';
                $indicador = 'DNI';
                $valor = 'RI DESNUTRICION';
                $DNI[$valor]['MUJERES'] =0;
                $DNI[$valor]['HOMBRES'] =0;
                $DNI[$valor]['AMBOS'] =0;
                $fila = '';
                foreach ($rango_seccion_a as $i => $rango){
                    $total_hombres = $mysql->getTotal($tabla,$indicador,$valor,$rango,$sexo[0],$id_centro);
                    $total_mujeres = $mysql->getTotal($tabla,$indicador,$valor,$rango,$sexo[1],$id_centro);

                    $total_dni[$i]['HOMBRES'] += $total_hombres;
                    $total_dni[$i]['MUJERES'] += $total_mujeres;

                    $DNI[$valor]['HOMBRES'] = $DNI[$valor]['HOMBRES'] + $total_hombres;
                    $DNI[$valor]['MUJERES'] = $DNI[$valor]['MUJERES'] + $total_mujeres;
                    $DNI[$valor]['AMBOS'] = $DNI[$valor]['AMBOS'] + $total_mujeres + $total_hombres;

                    $fila.= '<td>'.$total_hombres.'</td>';//hombre
                    $fila.= '<td>'.$total_mujeres.'</td>';//mujer

                }
                $DNI['AMBOS'] += $DNI[$valor]['AMBOS'];
                $DNI['HOMBRES'] += $DNI[$valor]['HOMBRES'];
                $DNI['MUJERES'] += $DNI[$valor]['MUJERES'];
                ?>
                <td><?php echo $DNI[$valor]['AMBOS'] ?></td>
                <td><?php echo $DNI[$valor]['HOMBRES'] ?></td>
                <td><?php echo $DNI[$valor]['MUJERES'] ?></td>
                <?php echo $fila; ?>

            </tr>
            <tr>
                <td>DESNUTRICIÓN</td>
                <?php
                $tabla = 'antropometria';
                $indicador = 'DNI';
                $valor = 'DESNUTRICION';
                $DNI[$valor]['MUJERES'] =0;
                $DNI[$valor]['HOMBRES'] =0;
                $DNI[$valor]['AMBOS'] =0;
                $fila = '';
                foreach ($rango_seccion_a as $i => $rango){
                    $total_hombres = $mysql->getTotal($tabla,$indicador,$valor,$rango,$sexo[0],$id_centro);
                    $total_mujeres = $mysql->getTotal($tabla,$indicador,$valor,$rango,$sexo[1],$id_centro);
                    $DNI[$valor]['HOMBRES'] = $DNI[$valor]['HOMBRES'] + $total_hombres;
                    $DNI[$valor]['MUJERES'] = $DNI[$valor]['MUJERES'] + $total_mujeres;
                    $DNI[$valor]['AMBOS'] = $DNI[$valor]['AMBOS'] + $total_mujeres + $total_hombres;

                    $total_dni[$i]['HOMBRES'] += $total_hombres;
                    $total_dni[$i]['MUJERES'] += $total_mujeres;

                    $fila.= '<td>'.$total_hombres.'</td>';//hombre
                    $fila.= '<td>'.$total_mujeres.'</td>';//mujer
                }

                $DNI['AMBOS'] += $DNI[$valor]['AMBOS'];
                $DNI['HOMBRES'] += $DNI[$valor]['HOMBRES'];
                $DNI['MUJERES'] += $DNI[$valor]['MUJERES'];
                ?>
                <td><?php echo $DNI[$valor]['AMBOS'] ?></td>
                <td><?php echo $DNI[$valor]['HOMBRES'] ?></td>
                <td><?php echo $DNI[$valor]['MUJERES'] ?></td>
                <?php echo $fila; ?>
            </tr>
            <tr>
                <td>SOBRE PESO / RIESGO OBESIDAD</td>
                <?php
                $tabla = 'antropometria';
                $indicador = 'DNI';
                $valor = 'SOBREPESO';
                $DNI[$valor]['MUJERES'] =0;
                $DNI[$valor]['HOMBRES'] =0;
                $DNI[$valor]['AMBOS'] =0;
                $fila = '';
                foreach ($rango_seccion_a as $i => $rango){
                    $total_hombres = $mysql->getTotal($tabla,$indicador,$valor,$rango,$sexo[0],$id_centro);
                    $total_mujeres = $mysql->getTotal($tabla,$indicador,$valor,$rango,$sexo[1],$id_centro);
                    $DNI[$valor]['HOMBRES'] = $DNI[$valor]['HOMBRES'] + $total_hombres;
                    $DNI[$valor]['MUJERES'] = $DNI[$valor]['MUJERES'] + $total_mujeres;
                    $DNI[$valor]['AMBOS'] = $DNI[$valor]['AMBOS'] + $total_mujeres + $total_hombres;

                    $total_dni[$i]['HOMBRES'] += $total_hombres;
                    $total_dni[$i]['MUJERES'] += $total_mujeres;

                    $fila.= '<td>'.$total_hombres.'</td>';//hombre
                    $fila.= '<td>'.$total_mujeres.'</td>';//mujer
                }
                $DNI['AMBOS'] += $DNI[$valor]['AMBOS'];
                $DNI['HOMBRES'] += $DNI[$valor]['HOMBRES'];
                $DNI['MUJERES'] += $DNI[$valor]['MUJERES'];
                ?>
                <td><?php echo $DNI[$valor]['AMBOS'] ?></td>
                <td><?php echo $DNI[$valor]['HOMBRES'] ?></td>
                <td><?php echo $DNI[$valor]['MUJERES'] ?></td>
                <?php echo $fila; ?>
            </tr>
            <tr>
                <td>OBESIDAD</td>
                <?php
                $tabla = 'antropometria';
                $indicador = 'DNI';
                $valor = 'OBESIDAD';
                $DNI[$valor]['MUJERES'] =0;
                $DNI[$valor]['HOMBRES'] =0;
                $DNI[$valor]['AMBOS'] =0;
                $fila = '';
                foreach ($rango_seccion_a as $i => $rango){
                    $total_hombres = $mysql->getTotal($tabla,$indicador,$valor,$rango,$sexo[0],$id_centro);
                    $total_mujeres = $mysql->getTotal($tabla,$indicador,$valor,$rango,$sexo[1],$id_centro);
                    $DNI[$valor]['HOMBRES'] = $DNI[$valor]['HOMBRES'] + $total_hombres;
                    $DNI[$valor]['MUJERES'] = $DNI[$valor]['MUJERES'] + $total_mujeres;
                    $DNI[$valor]['AMBOS'] = $DNI[$valor]['AMBOS'] + $total_mujeres + $total_hombres;

                    $total_dni[$i]['HOMBRES'] += $total_hombres;
                    $total_dni[$i]['MUJERES'] += $total_mujeres;

                    $fila.= '<td>'.$total_hombres.'</td>';//hombre
                    $fila.= '<td>'.$total_mujeres.'</td>';//mujer
                }
                $DNI['AMBOS'] += $DNI[$valor]['AMBOS'];
                $DNI['HOMBRES'] += $DNI[$valor]['HOMBRES'];
                $DNI['MUJERES'] += $DNI[$valor]['MUJERES'];
                ?>
                <td><?php echo $DNI[$valor]['AMBOS'] ?></td>
                <td><?php echo $DNI[$valor]['HOMBRES'] ?></td>
                <td><?php echo $DNI[$valor]['MUJERES'] ?></td>
                <?php echo $fila; ?>
            </tr>
            <tr>
                <td>OBESIDAD SEVERA</td>
                <?php
                $tabla = 'antropometria';
                $indicador = 'DNI';
                $valor = 'OB SEVERA';
                $DNI[$valor]['MUJERES'] =0;
                $DNI[$valor]['HOMBRES'] =0;
                $DNI[$valor]['AMBOS'] =0;
                $fila = '';
                foreach ($rango_seccion_a as $i => $rango){
                    $total_hombres = $mysql->getTotal($tabla,$indicador,$valor,$rango,$sexo[0],$id_centro);
                    $total_mujeres = $mysql->getTotal($tabla,$indicador,$valor,$rango,$sexo[1],$id_centro);
                    $DNI[$valor]['HOMBRES'] = $DNI[$valor]['HOMBRES'] + $total_hombres;
                    $DNI[$valor]['MUJERES'] = $DNI[$valor]['MUJERES'] + $total_mujeres;
                    $DNI[$valor]['AMBOS'] = $DNI[$valor]['AMBOS'] + $total_mujeres + $total_hombres;

                    $total_dni[$i]['HOMBRES'] += $total_hombres;
                    $total_dni[$i]['MUJERES'] += $total_mujeres;

                    $fila.= '<td>'.$total_hombres.'</td>';//hombre
                    $fila.= '<td>'.$total_mujeres.'</td>';//mujer
                }
                $DNI['AMBOS'] += $DNI[$valor]['AMBOS'];
                $DNI['HOMBRES'] += $DNI[$valor]['HOMBRES'];
                $DNI['MUJERES'] += $DNI[$valor]['MUJERES'];
                ?>
                <td><?php echo $DNI[$valor]['AMBOS'] ?></td>
                <td><?php echo $DNI[$valor]['HOMBRES'] ?></td>
                <td><?php echo $DNI[$valor]['MUJERES'] ?></td>
                <?php echo $fila; ?>
            </tr>
            <tr>
                <td>NORMAL</td>
                <?php
                $tabla = 'antropometria';
                $indicador = 'DNI';
                $valor = 'NORMAL';
                $DNI[$valor]['MUJERES'] =0;
                $DNI[$valor]['HOMBRES'] =0;
                $DNI[$valor]['AMBOS'] =0;
                $fila = '';
                foreach ($rango_seccion_a as $i => $rango){

                    $total_hombres = $mysql->getTotal($tabla,$indicador,$valor,$rango,$sexo[0],$id_centro);
                    $total_mujeres = $mysql->getTotal($tabla,$indicador,$valor,$rango,$sexo[1],$id_centro);

                    $DNI[$valor]['HOMBRES'] = $DNI[$valor]['HOMBRES'] + $total_hombres;
                    $DNI[$valor]['MUJERES'] = $DNI[$valor]['MUJERES'] + $total_mujeres;
                    $DNI[$valor]['AMBOS'] = $DNI[$valor]['AMBOS'] + $total_mujeres + $total_hombres;


                    $total_dni[$i]['HOMBRES'] += $total_hombres;
                    $total_dni[$i]['MUJERES'] += $total_mujeres;



                    $fila.= '<td>'.$total_hombres.'</td>';//hombre
                    $fila.= '<td>'.$total_mujeres.'</td>';//mujer


                }
                $DNI['AMBOS'] += $DNI[$valor]['AMBOS'];
                $DNI['HOMBRES'] += $DNI[$valor]['HOMBRES'];
                $DNI['MUJERES'] += $DNI[$valor]['MUJERES'];
                ?>
                <td><?php echo $DNI[$valor]['AMBOS'] ?></td>
                <td><?php echo $DNI[$valor]['HOMBRES'] ?></td>
                <td><?php echo $DNI[$valor]['MUJERES'] ?></td>
                <?php echo $fila; ?>
            </tr>
            <tr style="font-weight: bold;background-color: #d7efff;">
                <td>TOTAL</td>
                <?php
                $fila='';
                foreach ($rango_seccion_a as $i => $rango){
                    $total_hombres = $total_dni[$i]['HOMBRES'];
                    $total_mujeres = $total_dni[$i]['MUJERES'];

                    $fila.= '<td>'.$total_hombres.'</td>';//hombre
                    $fila.= '<td>'.$total_mujeres.'</td>';//mujer
                }
                ?>
                <td><?php echo $DNI['AMBOS'] ?></td>
                <td><?php echo $DNI['HOMBRES'] ?></td>
                <td><?php echo $DNI['MUJERES'] ?></td>
                <?php echo $fila; ?>
            </tr>
        </table>
    </section>
    <section id="seccion_a1" style="width: 100%;overflow-y: scroll;">
        <header>SECCION A.1: POBLACIÓN EN CONTROL, SEGÚN ESTADO NUTRICIONAL PARA NIÑOS DE 60 MESES-9 AÑOS 11 MESES</header>
        <table id="table_seccion_a1" style="width: 100%;border: solid 1px black;" border="1">
            <tr>
                <td colspan="2" rowspan="3">INDICADOR NUTRICIONAL Y PARÁMETROS DE MEDICIÓN</td>
                <td colspan="3" rowspan="2">TOTAL</td>
                <td colspan="8">GRUPOS DE EDAD (MESES Y AÑOS) Y SEXO</td>
            </tr>
            <tr>
                <td colspan="2">60 A 71 meses</td>
                <td colspan="2">6 A 9 años 11 meses</td>
                <td colspan="2">PUEBLOS ORIGINARIOS</td>
                <td colspan="2">POBLACION MIGRANTES</td>
            </tr>
            <tr>
                <td>AMBOS SEXOS</td>
                <td>HOMBRES</td>
                <td>MUJERES</td>

                <td>HOMBRES</td>
                <td>MUJERES</td>

                <td>HOMBRES</td>
                <td>MUJERES</td>

                <td>HOMBRES</td>
                <td>MUJERES</td>

                <td>HOMBRES</td>
                <td>MUJERES</td>
            </tr>
            <tr>
                <td rowspan="8">INDICADOR IMC / EDAD</td>
                <td>+ 3 D.S. (>= 3 )</td>
                <?php
                $tabla = 'antropometria';
                $indicador = 'IMCE';
                $valor = '3';
                $IMCE[$valor]['MUJERES'] =0;
                $IMCE[$valor]['HOMBRES'] =0;
                $IMCE[$valor]['AMBOS'] =0;
                $fila = '';
                foreach ($rango_seccion_a1 as $i => $rango){
                    $total_hombres = $mysql->getTotal($tabla,$indicador,$valor,$rango,$sexo[0],$id_centro);
                    $total_mujeres = $mysql->getTotal($tabla,$indicador,$valor,$rango,$sexo[1],$id_centro);

                    $IMCE[$valor]['HOMBRES'] = $IMCE[$valor]['HOMBRES'] + $total_hombres;
                    $IMCE[$valor]['MUJERES'] = $IMCE[$valor]['MUJERES'] + $total_mujeres;
                    $IMCE[$valor]['AMBOS'] = $IMCE[$valor]['AMBOS'] + $total_mujeres + $total_hombres;

                    $fila.= '<td>'.$total_hombres.'</td>';//hombre
                    $fila.= '<td>'.$total_mujeres.'</td>';//mujer
                }
                ?>
                <td><?php echo $IMCE[$valor]['AMBOS'] ?></td>
                <td><?php echo $IMCE[$valor]['HOMBRES'] ?></td>
                <td><?php echo $IMCE[$valor]['MUJERES'] ?></td>
                <?php echo $fila; ?>
            </tr>
            <tr>
                <td>+ 2 D.S. (>= +2.0 a +2.9)</td>
                <?php
                $tabla = 'antropometria';
                $indicador = 'IMCE';
                $valor = '2';
                $IMCE[$valor]['MUJERES'] =0;
                $IMCE[$valor]['HOMBRES'] =0;
                $IMCE[$valor]['AMBOS'] =0;
                $fila = '';
                foreach ($rango_seccion_a1 as $i => $rango){
                    $total_hombres = $mysql->getTotal($tabla,$indicador,$valor,$rango,$sexo[0],$id_centro);
                    $total_mujeres = $mysql->getTotal($tabla,$indicador,$valor,$rango,$sexo[1],$id_centro);

                    $IMCE[$valor]['HOMBRES'] = $IMCE[$valor]['HOMBRES'] + $total_hombres;
                    $IMCE[$valor]['MUJERES'] = $IMCE[$valor]['MUJERES'] + $total_mujeres;
                    $IMCE[$valor]['AMBOS'] = $IMCE[$valor]['AMBOS'] + $total_mujeres + $total_hombres;

                    $fila.= '<td>'.$total_hombres.'</td>';//hombre
                    $fila.= '<td>'.$total_mujeres.'</td>';//mujer
                }
                ?>
                <td><?php echo $IMCE[$valor]['AMBOS'] ?></td>
                <td><?php echo $IMCE[$valor]['HOMBRES'] ?></td>
                <td><?php echo $IMCE[$valor]['MUJERES'] ?></td>
                <?php echo $fila; ?>

            </tr>
            <tr>
                <td>+ 2 D.S. (>= +2.0 a +2.9)</td>
                <?php
                $tabla = 'antropometria';
                $indicador = 'IMCE';
                $valor = '1';
                $IMCE[$valor]['MUJERES'] =0;
                $IMCE[$valor]['HOMBRES'] =0;
                $IMCE[$valor]['AMBOS'] =0;
                $fila = '';
                foreach ($rango_seccion_a1 as $i => $rango){
                    $total_hombres = $mysql->getTotal($tabla,$indicador,$valor,$rango,$sexo[0],$id_centro);
                    $total_mujeres = $mysql->getTotal($tabla,$indicador,$valor,$rango,$sexo[1],$id_centro);

                    $IMCE[$valor]['HOMBRES'] = $IMCE[$valor]['HOMBRES'] + $total_hombres;
                    $IMCE[$valor]['MUJERES'] = $IMCE[$valor]['MUJERES'] + $total_mujeres;
                    $IMCE[$valor]['AMBOS'] = $IMCE[$valor]['AMBOS'] + $total_mujeres + $total_hombres;

                    $fila.= '<td>'.$total_hombres.'</td>';//hombre
                    $fila.= '<td>'.$total_mujeres.'</td>';//mujer
                }
                ?>
                <td><?php echo $IMCE[$valor]['AMBOS'] ?></td>
                <td><?php echo $IMCE[$valor]['HOMBRES'] ?></td>
                <td><?php echo $IMCE[$valor]['MUJERES'] ?></td>
                <?php echo $fila; ?>

            </tr>
            <tr style="font-weight: bold;background-color: #d7efff;">
                <td>TOTAL</td>
                <?php
                $tabla = 'antropometria';
                $fila = '';
                $total_hombres = 0;
                $total_mujeres = 0;
                foreach ($rango_seccion_a1 as $i => $rango){
                    $total_hombres =
                        +$mysql->getTotal($tabla,$indicador,'3',$rango,$sexo[0])
                        +$mysql->getTotal($tabla,$indicador,'2',$rango,$sexo[0])
                        +$mysql->getTotal($tabla,$indicador,'1',$rango,$sexo[0],$id_centro);
                    $total_mujeres =
                        +$mysql->getTotal($tabla,$indicador,'3',$rango,$sexo[1])
                        +$mysql->getTotal($tabla,$indicador,'2',$rango,$sexo[1])
                        +$mysql->getTotal($tabla,$indicador,'1',$rango,$sexo[1],$id_centro);

                    $fila.= '<td>'.$total_hombres.'</td>';//hombre
                    $fila.= '<td>'.$total_mujeres.'</td>';//mujer


                }


                ?>
                <td><?php echo $IMCE['3']['AMBOS']+$IMCE['2']['AMBOS']+$IMCE['1']['AMBOS'] ?></td>
                <td><?php echo $IMCE['3']['HOMBRES']+$IMCE['2']['HOMBRES']+$IMCE['1']['HOMBRES'] ?></td>
                <td><?php echo $IMCE['3']['MUJERES']+$IMCE['2']['MUJERES']+$IMCE['1']['MUJERES'] ?></td>
                <?php echo $fila; ?>

            </tr>
            <tr>
                <td>- 1 D.S. (<= -1.0 a -1.9)</td>
                <?php
                $tabla = 'antropometria';
                $indicador = 'IMCE';
                $valor = '-1';
                $IMCE[$valor]['MUJERES'] =0;
                $IMCE[$valor]['HOMBRES'] =0;
                $IMCE[$valor]['AMBOS'] =0;
                $fila = '';
                foreach ($rango_seccion_a1 as $i => $rango){
                    $total_hombres = $mysql->getTotal($tabla,$indicador,$valor,$rango,$sexo[0],$id_centro);
                    $total_mujeres = $mysql->getTotal($tabla,$indicador,$valor,$rango,$sexo[1],$id_centro);

                    $IMCE[$valor]['HOMBRES'] = $IMCE[$valor]['HOMBRES'] + $total_hombres;
                    $IMCE[$valor]['MUJERES'] = $IMCE[$valor]['MUJERES'] + $total_mujeres;
                    $IMCE[$valor]['AMBOS'] = $IMCE[$valor]['AMBOS'] + $total_mujeres + $total_hombres;

                    $fila.= '<td>'.$total_hombres.'</td>';//hombre
                    $fila.= '<td>'.$total_mujeres.'</td>';//mujer
                }
                ?>
                <td><?php echo $IMCE[$valor]['AMBOS'] ?></td>
                <td><?php echo $IMCE[$valor]['HOMBRES'] ?></td>
                <td><?php echo $IMCE[$valor]['MUJERES'] ?></td>
                <?php echo $fila; ?>

            </tr>
            <tr>
                <td>- 2 D.S. (<= -2.0)</td>
                <?php
                $tabla = 'antropometria';
                $indicador = 'IMCE';
                $valor = '-2';
                $IMCE[$valor]['MUJERES'] =0;
                $IMCE[$valor]['HOMBRES'] =0;
                $IMCE[$valor]['AMBOS'] =0;
                $fila = '';
                foreach ($rango_seccion_a1 as $i => $rango){
                    $total_hombres = $mysql->getTotal($tabla,$indicador,$valor,$rango,$sexo[0],$id_centro);
                    $total_mujeres = $mysql->getTotal($tabla,$indicador,$valor,$rango,$sexo[1],$id_centro);

                    $IMCE[$valor]['HOMBRES'] = $IMCE[$valor]['HOMBRES'] + $total_hombres;
                    $IMCE[$valor]['MUJERES'] = $IMCE[$valor]['MUJERES'] + $total_mujeres;
                    $IMCE[$valor]['AMBOS'] = $IMCE[$valor]['AMBOS'] + $total_mujeres + $total_hombres;

                    $fila.= '<td>'.$total_hombres.'</td>';//hombre
                    $fila.= '<td>'.$total_mujeres.'</td>';//mujer
                }
                ?>
                <td><?php echo $IMCE[$valor]['AMBOS'] ?></td>
                <td><?php echo $IMCE[$valor]['HOMBRES'] ?></td>
                <td><?php echo $IMCE[$valor]['MUJERES'] ?></td>
                <?php echo $fila; ?>

            </tr>
            <tr style="font-weight: bold;background-color: #d7efff;">
                <td>TOTAL</td>
                <?php
                $tabla = 'antropometria';
                $fila = '';
                $total_hombres = 0;
                $total_mujeres = 0;
                foreach ($rango_seccion_a1 as $i => $rango){
                    $total_hombres =
                        +$mysql->getTotal($tabla,$indicador,'-2',$rango,$sexo[0])
                        +$mysql->getTotal($tabla,$indicador,'-1',$rango,$sexo[0],$id_centro);
                    $total_mujeres =
                        +$mysql->getTotal($tabla,$indicador,'-2',$rango,$sexo[1])
                        +$mysql->getTotal($tabla,$indicador,'-1',$rango,$sexo[1],$id_centro);

                    $fila.= '<td>'.$total_hombres.'</td>';//hombre
                    $fila.= '<td>'.$total_mujeres.'</td>';//mujer


                }


                ?>
                <td><?php echo $IMCE['-1']['AMBOS']+$IMCE['-2']['AMBOS']; ?></td>
                <td><?php echo $IMCE['-1']['HOMBRES']+$IMCE['-2']['HOMBRES']; ?></td>
                <td><?php echo $IMCE['-1']['MUJERES']+$IMCE['-2']['MUJERES']; ?></td>
                <?php echo $fila; ?>

            </tr>
            <tr>
                <td>PROMEDIO (-0,9 A + 0,9)</td>
            </tr>
            <tr>
                <td rowspan="7">INDICADOR TALLA/EDAD</td>
                <td>+ 2 D.S. (>= +2.0)</td>
                <?php
                $tabla = 'antropometria';
                $indicador = 'TE';
                $valor = '2';
                $IMCE[$valor]['MUJERES'] =0;
                $IMCE[$valor]['HOMBRES'] =0;
                $IMCE[$valor]['AMBOS'] =0;
                $fila = '';
                foreach ($rango_seccion_a1 as $i => $rango){
                    $total_hombres = $mysql->getTotal($tabla,$indicador,$valor,$rango,$sexo[0],$id_centro);
                    $total_mujeres = $mysql->getTotal($tabla,$indicador,$valor,$rango,$sexo[1],$id_centro);

                    $IMCE[$valor]['HOMBRES'] = $IMCE[$valor]['HOMBRES'] + $total_hombres;
                    $IMCE[$valor]['MUJERES'] = $IMCE[$valor]['MUJERES'] + $total_mujeres;
                    $IMCE[$valor]['AMBOS'] = $IMCE[$valor]['AMBOS'] + $total_mujeres + $total_hombres;

                    $fila.= '<td>'.$total_hombres.'</td>';//hombre
                    $fila.= '<td>'.$total_mujeres.'</td>';//mujer
                }
                ?>
                <td><?php echo $IMCE[$valor]['AMBOS'] ?></td>
                <td><?php echo $IMCE[$valor]['HOMBRES'] ?></td>
                <td><?php echo $IMCE[$valor]['MUJERES'] ?></td>
                <?php echo $fila; ?>

            </tr>
            <tr>
                <td>+ 1 D.S. (+1.0 a +1.9)</td>
                <?php
                $tabla = 'antropometria';
                $indicador = 'TE';
                $valor = '1';
                $IMCE[$valor]['MUJERES'] =0;
                $IMCE[$valor]['HOMBRES'] =0;
                $IMCE[$valor]['AMBOS'] =0;
                $fila = '';
                foreach ($rango_seccion_a1 as $i => $rango){
                    $total_hombres = $mysql->getTotal($tabla,$indicador,$valor,$rango,$sexo[0],$id_centro);
                    $total_mujeres = $mysql->getTotal($tabla,$indicador,$valor,$rango,$sexo[1],$id_centro);

                    $IMCE[$valor]['HOMBRES'] = $IMCE[$valor]['HOMBRES'] + $total_hombres;
                    $IMCE[$valor]['MUJERES'] = $IMCE[$valor]['MUJERES'] + $total_mujeres;
                    $IMCE[$valor]['AMBOS'] = $IMCE[$valor]['AMBOS'] + $total_mujeres + $total_hombres;

                    $fila.= '<td>'.$total_hombres.'</td>';//hombre
                    $fila.= '<td>'.$total_mujeres.'</td>';//mujer
                }
                ?>
                <td><?php echo $IMCE[$valor]['AMBOS'] ?></td>
                <td><?php echo $IMCE[$valor]['HOMBRES'] ?></td>
                <td><?php echo $IMCE[$valor]['MUJERES'] ?></td>
                <?php echo $fila; ?>

            </tr>
            <tr style="font-weight: bold;background-color: #d7efff;">
                <td>TOTAL</td>
                <?php
                $tabla = 'antropometria';
                $indicador = 'TE';
                $fila = '';
                $total_hombres = 0;
                $total_mujeres = 0;
                foreach ($rango_seccion_a1 as $i => $rango){
                    $total_hombres =
                        +$mysql->getTotal($tabla,$indicador,'2',$rango,$sexo[0])
                        +$mysql->getTotal($tabla,$indicador,'1',$rango,$sexo[0],$id_centro);
                    $total_mujeres =
                        +$mysql->getTotal($tabla,$indicador,'2',$rango,$sexo[1])
                        +$mysql->getTotal($tabla,$indicador,'1',$rango,$sexo[1],$id_centro);

                    $fila.= '<td>'.$total_hombres.'</td>';//hombre
                    $fila.= '<td>'.$total_mujeres.'</td>';//mujer


                }


                ?>
                <td><?php echo $IMCE['1']['AMBOS']+$IMCE['2']['AMBOS']; ?></td>
                <td><?php echo $IMCE['1']['HOMBRES']+$IMCE['2']['HOMBRES']; ?></td>
                <td><?php echo $IMCE['1']['MUJERES']+$IMCE['2']['MUJERES']; ?></td>
                <?php echo $fila; ?>

            </tr>
            <tr>
                <td>- 1 D.S. (-1.0 a -1.9)</td>
                <?php
                $tabla = 'antropometria';
                $indicador = 'TE';
                $valor = '-1';
                $IMCE[$valor]['MUJERES'] =0;
                $IMCE[$valor]['HOMBRES'] =0;
                $IMCE[$valor]['AMBOS'] =0;
                $fila = '';
                foreach ($rango_seccion_a1 as $i => $rango){
                    $total_hombres = $mysql->getTotal($tabla,$indicador,$valor,$rango,$sexo[0],$id_centro);
                    $total_mujeres = $mysql->getTotal($tabla,$indicador,$valor,$rango,$sexo[1],$id_centro);

                    $IMCE[$valor]['HOMBRES'] = $IMCE[$valor]['HOMBRES'] + $total_hombres;
                    $IMCE[$valor]['MUJERES'] = $IMCE[$valor]['MUJERES'] + $total_mujeres;
                    $IMCE[$valor]['AMBOS'] = $IMCE[$valor]['AMBOS'] + $total_mujeres + $total_hombres;

                    $fila.= '<td>'.$total_hombres.'</td>';//hombre
                    $fila.= '<td>'.$total_mujeres.'</td>';//mujer
                }
                ?>
                <td><?php echo $IMCE[$valor]['AMBOS'] ?></td>
                <td><?php echo $IMCE[$valor]['HOMBRES'] ?></td>
                <td><?php echo $IMCE[$valor]['MUJERES'] ?></td>
                <?php echo $fila; ?>

            </tr>
            <tr>
                <td>- 2 D.S. (<= -2.0)</td>
                <?php
                $tabla = 'antropometria';
                $indicador = 'TE';
                $valor = '-2';
                $IMCE[$valor]['MUJERES'] =0;
                $IMCE[$valor]['HOMBRES'] =0;
                $IMCE[$valor]['AMBOS'] =0;
                $fila = '';
                foreach ($rango_seccion_a1 as $i => $rango){
                    $total_hombres = $mysql->getTotal($tabla,$indicador,$valor,$rango,$sexo[0],$id_centro);
                    $total_mujeres = $mysql->getTotal($tabla,$indicador,$valor,$rango,$sexo[1],$id_centro);

                    $IMCE[$valor]['HOMBRES'] = $IMCE[$valor]['HOMBRES'] + $total_hombres;
                    $IMCE[$valor]['MUJERES'] = $IMCE[$valor]['MUJERES'] + $total_mujeres;
                    $IMCE[$valor]['AMBOS'] = $IMCE[$valor]['AMBOS'] + $total_mujeres + $total_hombres;

                    $fila.= '<td>'.$total_hombres.'</td>';//hombre
                    $fila.= '<td>'.$total_mujeres.'</td>';//mujer
                }
                ?>
                <td><?php echo $IMCE[$valor]['AMBOS'] ?></td>
                <td><?php echo $IMCE[$valor]['HOMBRES'] ?></td>
                <td><?php echo $IMCE[$valor]['MUJERES'] ?></td>
                <?php echo $fila; ?>

            </tr>
            <tr style="font-weight: bold;background-color: #d7efff;">
                <td>TOTAL</td>
                <?php
                $tabla = 'antropometria';
                $indicador = 'TE';
                $fila = '';
                $total_hombres = 0;
                $total_mujeres = 0;
                foreach ($rango_seccion_a1 as $i => $rango){
                    $total_hombres =
                        +$mysql->getTotal($tabla,$indicador,'-2',$rango,$sexo[0])
                        +$mysql->getTotal($tabla,$indicador,'-1',$rango,$sexo[0],$id_centro);
                    $total_mujeres =
                        +$mysql->getTotal($tabla,$indicador,'-2',$rango,$sexo[1])
                        +$mysql->getTotal($tabla,$indicador,'-1',$rango,$sexo[1],$id_centro);

                    $fila.= '<td>'.$total_hombres.'</td>';//hombre
                    $fila.= '<td>'.$total_mujeres.'</td>';//mujer


                }


                ?>
                <td><?php echo $IMCE['-1']['AMBOS']+$IMCE['-2']['AMBOS']; ?></td>
                <td><?php echo $IMCE['-1']['HOMBRES']+$IMCE['-2']['HOMBRES']; ?></td>
                <td><?php echo $IMCE['-1']['MUJERES']+$IMCE['-2']['MUJERES']; ?></td>
                <?php echo $fila; ?>

            </tr>
            <tr>
                <td>PROMEDIO (-0,9 A + 0,9)</td>
            </tr>
            <tr>
                <td rowspan="4">INDICADOR PERIMETRO DE CINTURA / EDAD</td>
                <td>NORMAL (<p75)</td>
                <?php
                $tabla = 'antropometria';
                $indicador = 'PCINT';
                $valor = 'NORMAL';
                $IMCE[$valor]['MUJERES'] =0;
                $IMCE[$valor]['HOMBRES'] =0;
                $IMCE[$valor]['AMBOS'] =0;
                $fila = '';
                foreach ($rango_seccion_a1 as $i => $rango){
                    $total_hombres = $mysql->getTotal($tabla,$indicador,$valor,$rango,$sexo[0],$id_centro);
                    $total_mujeres = $mysql->getTotal($tabla,$indicador,$valor,$rango,$sexo[1],$id_centro);

                    $IMCE[$valor]['HOMBRES'] = $IMCE[$valor]['HOMBRES'] + $total_hombres;
                    $IMCE[$valor]['MUJERES'] = $IMCE[$valor]['MUJERES'] + $total_mujeres;
                    $IMCE[$valor]['AMBOS'] = $IMCE[$valor]['AMBOS'] + $total_mujeres + $total_hombres;

                    $fila.= '<td>'.$total_hombres.'</td>';//hombre
                    $fila.= '<td>'.$total_mujeres.'</td>';//mujer
                }
                ?>
                <td><?php echo $IMCE[$valor]['AMBOS'] ?></td>
                <td><?php echo $IMCE[$valor]['HOMBRES'] ?></td>
                <td><?php echo $IMCE[$valor]['MUJERES'] ?></td>
                <?php echo $fila; ?>

            </tr>
            <tr>
                <td>RIESGO DE OBESIDAD ABDOMINAL (75<p<90)</td>
                <?php
                $tabla = 'antropometria';
                $indicador = 'PCINT';
                $valor = 'RIESGO OBESIDAD';
                $IMCE[$valor]['MUJERES'] =0;
                $IMCE[$valor]['HOMBRES'] =0;
                $IMCE[$valor]['AMBOS'] =0;
                $fila = '';
                foreach ($rango_seccion_a1 as $i => $rango){
                    $total_hombres = $mysql->getTotal($tabla,$indicador,$valor,$rango,$sexo[0],$id_centro);
                    $total_mujeres = $mysql->getTotal($tabla,$indicador,$valor,$rango,$sexo[1],$id_centro);

                    $IMCE[$valor]['HOMBRES'] = $IMCE[$valor]['HOMBRES'] + $total_hombres;
                    $IMCE[$valor]['MUJERES'] = $IMCE[$valor]['MUJERES'] + $total_mujeres;
                    $IMCE[$valor]['AMBOS'] = $IMCE[$valor]['AMBOS'] + $total_mujeres + $total_hombres;

                    $fila.= '<td>'.$total_hombres.'</td>';//hombre
                    $fila.= '<td>'.$total_mujeres.'</td>';//mujer
                }
                ?>
                <td><?php echo $IMCE[$valor]['AMBOS'] ?></td>
                <td><?php echo $IMCE[$valor]['HOMBRES'] ?></td>
                <td><?php echo $IMCE[$valor]['MUJERES'] ?></td>
                <?php echo $fila; ?>

            </tr>
            <tr>
                <td>OBESIDAD ABDOMINAL (>p90)</td>
                <?php
                $tabla = 'antropometria';
                $indicador = 'PCINT';
                $valor = 'OBESIDAD ABDOMINAL';
                $IMCE[$valor]['MUJERES'] =0;
                $IMCE[$valor]['HOMBRES'] =0;
                $IMCE[$valor]['AMBOS'] =0;
                $fila = '';
                foreach ($rango_seccion_a1 as $i => $rango){
                    $total_hombres = $mysql->getTotal($tabla,$indicador,$valor,$rango,$sexo[0],$id_centro);
                    $total_mujeres = $mysql->getTotal($tabla,$indicador,$valor,$rango,$sexo[1],$id_centro);

                    $IMCE[$valor]['HOMBRES'] = $IMCE[$valor]['HOMBRES'] + $total_hombres;
                    $IMCE[$valor]['MUJERES'] = $IMCE[$valor]['MUJERES'] + $total_mujeres;
                    $IMCE[$valor]['AMBOS'] = $IMCE[$valor]['AMBOS'] + $total_mujeres + $total_hombres;

                    $fila.= '<td>'.$total_hombres.'</td>';//hombre
                    $fila.= '<td>'.$total_mujeres.'</td>';//mujer
                }
                ?>
                <td><?php echo $IMCE[$valor]['AMBOS'] ?></td>
                <td><?php echo $IMCE[$valor]['HOMBRES'] ?></td>
                <td><?php echo $IMCE[$valor]['MUJERES'] ?></td>
                <?php echo $fila; ?>

            </tr>
            <tr style="font-weight: bold;background-color: #d7efff;">
                <td>TOTAL</td>
                <?php
                $tabla = 'antropometria';
                $indicador = 'PCINT';
                $fila = '';
                $total_hombres = 0;
                $total_mujeres = 0;
                foreach ($rango_seccion_a1 as $i => $rango){
                    $total_hombres =
                        +$mysql->getTotal($tabla,$indicador,'OBESIDAD ABDOMINAL',$rango,$sexo[0])
                        +$mysql->getTotal($tabla,$indicador,'RIESGO OBESIDAD',$rango,$sexo[0])
                        +$mysql->getTotal($tabla,$indicador,'NORMAL',$rango,$sexo[0],$id_centro);
                    $total_mujeres =
                        +$mysql->getTotal($tabla,$indicador,'OBESIDAD ABDOMINAL',$rango,$sexo[1])
                        +$mysql->getTotal($tabla,$indicador,'RIESGO OBESIDAD',$rango,$sexo[1])
                        +$mysql->getTotal($tabla,$indicador,'NORMAL',$rango,$sexo[1]) ;

                    $fila.= '<td>'.$total_hombres.'</td>';//hombre
                    $fila.= '<td>'.$total_mujeres.'</td>';//mujer


                }


                ?>
                <td><?php echo $IMCE['OBESIDAD ABDOMINAL']['AMBOS']+$IMCE['RIESGO OBESIDAD']['AMBOS']+$IMCE['NORMAL']['AMBOS']; ?></td>
                <td><?php echo $IMCE['OBESIDAD ABDOMINAL']['HOMBRES']+$IMCE['RIESGO OBESIDAD']['HOMBRES']+$IMCE['NORMAL']['HOMBRES']; ?></td>
                <td><?php echo $IMCE['OBESIDAD ABDOMINAL']['MUJERES']+$IMCE['RIESGO OBESIDAD']['MUJERES']+$IMCE['NORMAL']['MUJERES']; ?></td>

                <?php echo $fila; ?>

            </tr>
            <!-- DNI -->
            <tr>
                <td rowspan="7">DIAGNOSTICO NUTRICIONAL INTEGRADO</td>
                <td>RIESGO DE DESNUTRIR/ DEFICIT PONDERAL</td>
                <?php
                //
                $DNI['AMBOS'] = 0;
                $DNI['HOMBRES'] = 0;
                $DNI['MUJERES'] = 0;

                //
                $tabla = 'antropometria';
                $indicador = 'DNI';
                $valor = 'RI DESNUTRICION';
                $DNI[$valor]['MUJERES'] =0;
                $DNI[$valor]['HOMBRES'] =0;
                $DNI[$valor]['AMBOS'] =0;
                $fila = '';
                foreach ($rango_seccion_a1 as $i => $rango){
                    $total_hombres = $mysql->getTotal($tabla,$indicador,$valor,$rango,$sexo[0],$id_centro);
                    $total_mujeres = $mysql->getTotal($tabla,$indicador,$valor,$rango,$sexo[1],$id_centro);

                    $total_dni[$i]['HOMBRES'] += $total_hombres;
                    $total_dni[$i]['MUJERES'] += $total_mujeres;

                    $DNI[$valor]['HOMBRES'] = $DNI[$valor]['HOMBRES'] + $total_hombres;
                    $DNI[$valor]['MUJERES'] = $DNI[$valor]['MUJERES'] + $total_mujeres;
                    $DNI[$valor]['AMBOS'] = $DNI[$valor]['AMBOS'] + $total_mujeres + $total_hombres;

                    $fila.= '<td>'.$total_hombres.'</td>';//hombre
                    $fila.= '<td>'.$total_mujeres.'</td>';//mujer

                }
                $DNI['AMBOS'] += $DNI[$valor]['AMBOS'];
                $DNI['HOMBRES'] += $DNI[$valor]['HOMBRES'];
                $DNI['MUJERES'] += $DNI[$valor]['MUJERES'];
                ?>
                <td><?php echo $DNI[$valor]['AMBOS'] ?></td>
                <td><?php echo $DNI[$valor]['HOMBRES'] ?></td>
                <td><?php echo $DNI[$valor]['MUJERES'] ?></td>
                <?php echo $fila; ?>

            </tr>
            <tr>
                <td>DESNUTRICIÓN</td>
                <?php
                $tabla = 'antropometria';
                $indicador = 'DNI';
                $valor = 'DESNUTRICION';
                $DNI[$valor]['MUJERES'] =0;
                $DNI[$valor]['HOMBRES'] =0;
                $DNI[$valor]['AMBOS'] =0;
                $fila = '';
                foreach ($rango_seccion_a1 as $i => $rango){
                    $total_hombres = $mysql->getTotal($tabla,$indicador,$valor,$rango,$sexo[0],$id_centro);
                    $total_mujeres = $mysql->getTotal($tabla,$indicador,$valor,$rango,$sexo[1],$id_centro);
                    $DNI[$valor]['HOMBRES'] = $DNI[$valor]['HOMBRES'] + $total_hombres;
                    $DNI[$valor]['MUJERES'] = $DNI[$valor]['MUJERES'] + $total_mujeres;
                    $DNI[$valor]['AMBOS'] = $DNI[$valor]['AMBOS'] + $total_mujeres + $total_hombres;

                    $total_dni[$i]['HOMBRES'] += $total_hombres;
                    $total_dni[$i]['MUJERES'] += $total_mujeres;

                    $fila.= '<td>'.$total_hombres.'</td>';//hombre
                    $fila.= '<td>'.$total_mujeres.'</td>';//mujer
                }

                $DNI['AMBOS'] += $DNI[$valor]['AMBOS'];
                $DNI['HOMBRES'] += $DNI[$valor]['HOMBRES'];
                $DNI['MUJERES'] += $DNI[$valor]['MUJERES'];
                ?>
                <td><?php echo $DNI[$valor]['AMBOS'] ?></td>
                <td><?php echo $DNI[$valor]['HOMBRES'] ?></td>
                <td><?php echo $DNI[$valor]['MUJERES'] ?></td>
                <?php echo $fila; ?>
            </tr>
            <tr>
                <td>SOBRE PESO / RIESGO OBESIDAD</td>
                <?php
                $tabla = 'antropometria';
                $indicador = 'DNI';
                $valor = 'SOBREPESO';
                $DNI[$valor]['MUJERES'] =0;
                $DNI[$valor]['HOMBRES'] =0;
                $DNI[$valor]['AMBOS'] =0;
                $fila = '';
                foreach ($rango_seccion_a1 as $i => $rango){
                    $total_hombres = $mysql->getTotal($tabla,$indicador,$valor,$rango,$sexo[0],$id_centro);
                    $total_mujeres = $mysql->getTotal($tabla,$indicador,$valor,$rango,$sexo[1],$id_centro);
                    $DNI[$valor]['HOMBRES'] = $DNI[$valor]['HOMBRES'] + $total_hombres;
                    $DNI[$valor]['MUJERES'] = $DNI[$valor]['MUJERES'] + $total_mujeres;
                    $DNI[$valor]['AMBOS'] = $DNI[$valor]['AMBOS'] + $total_mujeres + $total_hombres;

                    $total_dni[$i]['HOMBRES'] += $total_hombres;
                    $total_dni[$i]['MUJERES'] += $total_mujeres;

                    $fila.= '<td>'.$total_hombres.'</td>';//hombre
                    $fila.= '<td>'.$total_mujeres.'</td>';//mujer
                }
                $DNI['AMBOS'] += $DNI[$valor]['AMBOS'];
                $DNI['HOMBRES'] += $DNI[$valor]['HOMBRES'];
                $DNI['MUJERES'] += $DNI[$valor]['MUJERES'];
                ?>
                <td><?php echo $DNI[$valor]['AMBOS'] ?></td>
                <td><?php echo $DNI[$valor]['HOMBRES'] ?></td>
                <td><?php echo $DNI[$valor]['MUJERES'] ?></td>
                <?php echo $fila; ?>
            </tr>
            <tr>
                <td>OBESIDAD</td>
                <?php
                $tabla = 'antropometria';
                $indicador = 'DNI';
                $valor = 'OBESIDAD';
                $DNI[$valor]['MUJERES'] =0;
                $DNI[$valor]['HOMBRES'] =0;
                $DNI[$valor]['AMBOS'] =0;
                $fila = '';
                foreach ($rango_seccion_a1 as $i => $rango){
                    $total_hombres = $mysql->getTotal($tabla,$indicador,$valor,$rango,$sexo[0],$id_centro);
                    $total_mujeres = $mysql->getTotal($tabla,$indicador,$valor,$rango,$sexo[1],$id_centro);
                    $DNI[$valor]['HOMBRES'] = $DNI[$valor]['HOMBRES'] + $total_hombres;
                    $DNI[$valor]['MUJERES'] = $DNI[$valor]['MUJERES'] + $total_mujeres;
                    $DNI[$valor]['AMBOS'] = $DNI[$valor]['AMBOS'] + $total_mujeres + $total_hombres;

                    $total_dni[$i]['HOMBRES'] += $total_hombres;
                    $total_dni[$i]['MUJERES'] += $total_mujeres;

                    $fila.= '<td>'.$total_hombres.'</td>';//hombre
                    $fila.= '<td>'.$total_mujeres.'</td>';//mujer
                }
                $DNI['AMBOS'] += $DNI[$valor]['AMBOS'];
                $DNI['HOMBRES'] += $DNI[$valor]['HOMBRES'];
                $DNI['MUJERES'] += $DNI[$valor]['MUJERES'];
                ?>
                <td><?php echo $DNI[$valor]['AMBOS'] ?></td>
                <td><?php echo $DNI[$valor]['HOMBRES'] ?></td>
                <td><?php echo $DNI[$valor]['MUJERES'] ?></td>
                <?php echo $fila; ?>
            </tr>
            <tr>
                <td>OBESIDAD SEVERA</td>
                <?php
                $tabla = 'antropometria';
                $indicador = 'DNI';
                $valor = 'OB SEVERA';
                $DNI[$valor]['MUJERES'] =0;
                $DNI[$valor]['HOMBRES'] =0;
                $DNI[$valor]['AMBOS'] =0;
                $fila = '';
                foreach ($rango_seccion_a1 as $i => $rango){
                    $total_hombres = $mysql->getTotal($tabla,$indicador,$valor,$rango,$sexo[0],$id_centro);
                    $total_mujeres = $mysql->getTotal($tabla,$indicador,$valor,$rango,$sexo[1],$id_centro);
                    $DNI[$valor]['HOMBRES'] = $DNI[$valor]['HOMBRES'] + $total_hombres;
                    $DNI[$valor]['MUJERES'] = $DNI[$valor]['MUJERES'] + $total_mujeres;
                    $DNI[$valor]['AMBOS'] = $DNI[$valor]['AMBOS'] + $total_mujeres + $total_hombres;

                    $total_dni[$i]['HOMBRES'] += $total_hombres;
                    $total_dni[$i]['MUJERES'] += $total_mujeres;

                    $fila.= '<td>'.$total_hombres.'</td>';//hombre
                    $fila.= '<td>'.$total_mujeres.'</td>';//mujer
                }
                $DNI['AMBOS'] += $DNI[$valor]['AMBOS'];
                $DNI['HOMBRES'] += $DNI[$valor]['HOMBRES'];
                $DNI['MUJERES'] += $DNI[$valor]['MUJERES'];
                ?>
                <td><?php echo $DNI[$valor]['AMBOS'] ?></td>
                <td><?php echo $DNI[$valor]['HOMBRES'] ?></td>
                <td><?php echo $DNI[$valor]['MUJERES'] ?></td>
                <?php echo $fila; ?>
            </tr>
            <tr>
                <td>NORMAL</td>
                <?php
                $tabla = 'antropometria';
                $indicador = 'DNI';
                $valor = 'NORMAL';
                $DNI[$valor]['MUJERES'] =0;
                $DNI[$valor]['HOMBRES'] =0;
                $DNI[$valor]['AMBOS'] =0;
                $fila = '';
                foreach ($rango_seccion_a1 as $i => $rango){

                    $total_hombres = $mysql->getTotal($tabla,$indicador,$valor,$rango,$sexo[0],$id_centro);
                    $total_mujeres = $mysql->getTotal($tabla,$indicador,$valor,$rango,$sexo[1],$id_centro);

                    $DNI[$valor]['HOMBRES'] = $DNI[$valor]['HOMBRES'] + $total_hombres;
                    $DNI[$valor]['MUJERES'] = $DNI[$valor]['MUJERES'] + $total_mujeres;
                    $DNI[$valor]['AMBOS'] = $DNI[$valor]['AMBOS'] + $total_mujeres + $total_hombres;


                    $total_dni[$i]['HOMBRES'] += $total_hombres;
                    $total_dni[$i]['MUJERES'] += $total_mujeres;



                    $fila.= '<td>'.$total_hombres.'</td>';//hombre
                    $fila.= '<td>'.$total_mujeres.'</td>';//mujer


                }
                $DNI['AMBOS'] += $DNI[$valor]['AMBOS'];
                $DNI['HOMBRES'] += $DNI[$valor]['HOMBRES'];
                $DNI['MUJERES'] += $DNI[$valor]['MUJERES'];
                ?>
                <td><?php echo $DNI[$valor]['AMBOS'] ?></td>
                <td><?php echo $DNI[$valor]['HOMBRES'] ?></td>
                <td><?php echo $DNI[$valor]['MUJERES'] ?></td>
                <?php echo $fila; ?>
            </tr>
            <tr style="font-weight: bold;background-color: #d7efff;">
                <td>TOTAL</td>
                <?php
                $fila='';
                foreach ($rango_seccion_a1 as $i => $rango){
                    $total_hombres = $total_dni[$i]['HOMBRES'];
                    $total_mujeres = $total_dni[$i]['MUJERES'];

                    $fila.= '<td>'.$total_hombres.'</td>';//hombre
                    $fila.= '<td>'.$total_mujeres.'</td>';//mujer
                }
                ?>
                <td><?php echo $DNI['AMBOS'] ?></td>
                <td><?php echo $DNI['HOMBRES'] ?></td>
                <td><?php echo $DNI['MUJERES'] ?></td>
                <?php echo $fila; ?>
            </tr>
        </table>
    </section>
    <section id="seccion_b" style="width: 100%;overflow-y: scroll;">
        <div class="row">
            <div class="col l10">
                <header>SECCION B: POBLACION EN CONTROL SEGÚN RESULTADO DE EVALUACIÓN DEL DESARROLLO PSICOMOTOR</header>
            </div>
        </div>
        <div class="row">
            <div class="col l12">
                <table id="table_seccion_b" style="width: 100%;border: solid 1px black;" border="1">
                    <tr>
                        <td colspan="2">RESULTADO Y GRUPOS DE EDAD</td>
                        <td>TOTAL</td>
                        <td>HOMBRES</td>
                        <td>MUJERES</td>
                    </tr>
                    <?php
                    $estados = ['RIESGO','RETRASO'];
                    foreach ($estados as $i => $estado){
                            $i_esatdo = 0;

                            foreach ($rango_seccion_b as $j => $rango){
                                ?>
                                <tr>
                                <?php

                                if($i_esatdo==0){
                                    ?>
                                    <td rowspan="5"><?php echo $estado; ?></td>
                                    <?php
                                }

                                $sql = "select COUNT(*) as total,
                                           sum(persona.sexo='M') AS HOMBRES,
                                           sum(persona.sexo='F') AS MUJERES
                                    from paciente_psicomotor inner join persona using(rut)
                                    inner join paciente_establecimiento on persona.rut=paciente_establecimiento.rut
                                    inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno 
                                    where tepsi='$estado' AND $rango 
                                    $filtro_centro;";
                                $total_hombres = $total_mujeres = 0;
                                $row = mysql_fetch_array(mysql_query($sql));
                                if($row){
                                    $total_hombres = $row['HOMBRES'];
                                    $total_mujeres = $row['MUJERES'];
                                }

                                $sql = "select COUNT(*) as total,
                                           sum(persona.sexo='M') AS HOMBRES,
                                           sum(persona.sexo='F') AS MUJERES
                                    from paciente_psicomotor inner join persona using(rut)
                                    inner join paciente_establecimiento on persona.rut=paciente_establecimiento.rut
                                    inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno
                                    where eedp='$estado' AND $rango 
                                    $filtro_centro;";

                                $row = mysql_fetch_array(mysql_query($sql));
                                if($row){
                                    $total_hombres += $row['HOMBRES'];
                                    $total_mujeres += $row['MUJERES'];
                                }
                                ?>
                                <td><?php echo $label_rango_seccion_b[$j]; ?></td>
                                <td><?php echo ($total_hombres+$total_mujeres); ?></td>
                                <td><?php echo ($total_hombres); ?></td>
                                <td><?php echo ($total_mujeres); ?></td>
                                </tr>
                                <?php
                                $i_esatdo++;
                            }
                    }
                    ?>
                </table>
            </div>
        </div>
    </section>
    <section id="seccion_c" style="width: 100%;overflow-y: scroll;">
        <div class="row">
            <div class="col l12">
                <header>SECCION C: POBLACIÓN MENOR DE 1 AÑO EN CONTROL, SEGÚN SCORE RIESGO EN IRA Y VISITA DOMICILIARIA INTEGRAL EN EL SEMESTRE</header>
            </div>
        </div>
        <div class="row">
            <div class="col l12">
                <table id="table_seccion_c" style="width: 100%;border: solid 1px black;" border="1">
                    <tr>
                        <td colspan="2">RESULTADO</td>
                        <?php
                        foreach ($rango_seccion_c as $i => $rango){
                            ?>
                            <td><?php echo $label_rango_seccion_c[$i]; ?></td>
                        <?php
                        }
                        ?>
                    </tr>
                    <?php
                    $indicadores = ['SCORE DE RIESGO'];
                    $estados = ["SCORE_IRA='LEVE'","SCORE_IRA='MODERADO'","SCORE_IRA='GRAVE'","SCORE_IRA!=''"];
                    $label_estados = ["LEVE","MODERADO","GRAVE","TOTAL"];
                    $i_esatdo = 0;
                    foreach ($estados as $i => $estado){
                        ?>
                        <tr>
                        <?php
                        if($i_esatdo==0){
                            ?>
                            <td rowspan="4"><?php echo $indicadores[$i_esatdo]; ?></td>
                            <?php
                            $i_esatdo++;
                        }
                        ?>
                        <td><?php echo $label_estados[$i]; ?></td>

                        <?php
                        foreach ($rango_seccion_c as $j => $rango){
                                $sql = "select COUNT(*) as total,
                                    from antropometria inner join persona using(rut)
                                    inner join paciente_establecimiento on persona.rut=paciente_establecimiento.rut
                                    inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno
                                    where $estado AND $rango 
                                    $filtro_centro;";
                                $total = 0;
                                $row = mysql_fetch_array(mysql_query($sql));
                                if($row){
                                    $total = $row['total'];
                                }
                                ?>
                                <td><?php echo $total; ?></td>
                            <?php
                        }
                        ?>
                        </tr>
                            <?php
                    }
                    ?>
                </table>
            </div>
        </div>
    </section>
    <section id="seccion_d" style="width: 100%;overflow-y: scroll;">
    <div class="row">
        <div class="col l12">
            <header>SECCION D: POBLACIÓN EN CONTROL EN EL SEMESTRE CON CONSULTA NUTRICIONAL, SEGÚN ESTRATEGIA</header>
        </div>
    </div>
    <div class="row">
        <div class="col l12">
            <table id="table_seccion_d" style="width: 50%;border: solid 1px black;" border="1">
                <tr>
                    <td>NIÑO/A CON CONSULTA NUTRICIONAL EN </td>
                    <td>TOTAL</td>
                </tr>
                <tr>
                    <td>DEL 5TO MES </td>
                    <td></td>
                </tr>
                <tr>
                    <td>DE LOS 3 AÑOS Y 6 MESES </td>
                    <td></td>
                </tr>
            </table>
        </div>
    </div>
</section>
    <section id="seccion_e" style="width: 100%;overflow-y: scroll;">
        <div class="row">
            <div class="col l12">
                <header>SECCION E: POBLACIÓN INASISTENTE A CONTROL DEL NIÑO SANO (AL CORTE)</header>
            </div>
        </div>
        <div class="row">
            <div class="col l12">
                <table id="table_seccion_e" style="width: 50%;border: solid 1px black;" border="1">
                    <tr>
                        <td>EDAD </td>
                        <td>TOTAL</td>
                    </tr>
                    <?php
                    foreach ($label_rango_seccion_e as $i => $value){
                        ?>
                        <tr>
                            <td><?PHP ECHO $value ?></td>
                            <td></td>
                        </tr>
                        <?php
                    }
                    ?>
                </table>
            </div>
        </div>
    </section>
    <section id="seccion_f" style="width: 100%;overflow-y: scroll;">
        <div class="row">
            <div class="col l12">
                <header>SECCION F: POBLACIÓN INFANTIL SEGÚN DIAGNÓSTICO DE PRESIÓN ARTERIAL (Incluida en Seccion A y A1)</header>
            </div>
        </div>
        <div class="row">
            <div class="col l12">
                <table id="table_seccion_f" style="width: 50%;border: solid 1px black;" border="1">
                    <tr>
                        <td rowspan="2">CLASIFICACION </td>
                        <td rowspan="2">TOTAL</td>
                        <td colspan="3">GRUPO DE EDAD</td>
                    </tr>
                    <tr>
                        <?php
                        foreach ($label_rango_seccion_f as $i => $value){
                            echo '<td>'.$value.'</td>';
                        }
                        ?>
                    </tr>
                    <?php
                    $estados = ['NORMAL','PRE-HIPERTENSION','ETAPA 1','ETAPA 2'];

                    foreach ($estados as $estado){
                        $total_estado = 0;
                        $tr = '<tr>';
                        $td = '';
                        foreach ($rango_seccion_f as $i => $rango){
                            $sql = "select
                                          sum(presion_arterial='$estado' and $rango) as total
                                        from persona
                                        inner join antropometria on persona.rut=antropometria.rut 
                                        inner join paciente_establecimiento on persona.rut=paciente_establecimiento.rut
                                        inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno 
                                        where paciente_establecimiento.id_establecimiento='$id_establecimiento' 
                                        $filtro_centro ";
                            $row = mysql_fetch_array(mysql_query($sql));
                            if($row){
                                $total = $row['total'];
                            }else{
                                $total = 0;
                            }
                            $total_estado+=$total;
                            $td .= '<td>'.$total.'</td>';

                        }
                        $tr .= '<td>'.$estado.'</td><td>'.$total_estado.'</td>'.$td;
                        $tr .= '</tr>';
                        echo $tr;
                    }
                    ?>
                </table>
            </div>
        </div>
    </section>
    <section id="seccion_g" style="width: 100%;overflow-y: scroll;">
        <div class="row">
            <div class="col l12">
                <header>SECCION G: POBLACIÓN INFANTIL EUTRÓFICA, SEGÚN RIESGO DE MALNUTRICIÓN POR EXCESO (Incluida en Seccion A y A1)</header>
            </div>
        </div>
        <div class="row">
            <div class="col l12">
                <table id="table_seccion_g" style="width: 70%;border: solid 1px black;" border="1">
                    <tr>
                        <td rowspan="2">RESULTADO </td>
                        <td rowspan="2">TOTAL</td>
                        <td colspan="6">GRUPO DE EDAD</td>
                    </tr>
                    <tr>
                        <?php
                        foreach ($label_rango_seccion_g as $i => $value){
                            echo '<td>'.$value.'</td>';
                        }
                        ?>
                    </tr>
                    <?php
                    $estados = ['SIN RIESGO','CON RIESGO'];

                    foreach ($estados as $estado){
                        $total_estado = 0;
                        $tr = '<tr>';
                        $td = '';
                        foreach ($rango_seccion_g as $i => $rango){
                            $sql = "select
                                          sum(RIMALN='$estado' and $rango) as total
                                        from persona
                                        inner join antropometria on persona.rut=antropometria.rut 
                                        inner join paciente_establecimiento on persona.rut=paciente_establecimiento.rut
                                        inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno
                                        where paciente_establecimiento.id_establecimiento='$id_establecimiento' 
                                        $filtro_centro
                                        ";
                            $row = mysql_fetch_array(mysql_query($sql));
                            if($row){
                                $total = $row['total'];
                            }else{
                                $total = 0;
                            }
                            $total_estado+=$total;
                            $td .= '<td>'.$total.'</td>';

                        }
                        $tr .= '<td>'.$estado.'</td><td>'.$total_estado.'</td>'.$td;
                        $tr .= '</tr>';
                        echo $tr;
                    }
                    ?>
                </table>
            </div>
        </div>
    </section>
    <section id="seccion_h" style="width: 100%;overflow-y: scroll;">
        <div class="row">
            <div class="col l12">
                <header>SECCION H: POBLACIÓN INFANTIL SEGÚN DIAGNÓSTICO DE NANEAS (incluidas en sección A y A.1)</header>
            </div>
        </div>
        <div class="row">
            <div class="col l12">
                <table id="table_seccion_c" style="width: 100%;border: solid 1px black;" border="1">
                    <tr>
                        <td rowspan="3">DIAGNOSTICOS</td>
                        <td rowspan="2" colspan="3">TOTAL</td>
                        <td colspan="<?php echo (count($rango_seccion_h)-2)*2; ?>">GRUPOS DE EDAD Y SEXO</td>
                        <td colspan="2" rowspan="2" >PUEBLOS ORIGINARIOS</td>
                        <td colspan="2" rowspan="2">POBLACION MIGRANTE</td>
                    </tr>
                    <tr>
                        <?php
                        foreach ($label_rango_seccion_h as $i => $label){
                            ?>
                            <td COLSPAN="2"><?php echo $label; ?></td>
                            <?php
                        }
                        ?>
                    </tr>
                    <tr>
                        <td style="background-color: #fdff8b">AMBOS</td>
                        <td style="background-color: #fdff8b">HOMBRES</td>
                        <td style="background-color: #fdff8b">HOMBRES</td>
                        <?php
                        $label_sexo = ['HOMBRE','MUJER'];
                        foreach ($rango_seccion_h as $i => $rango){

                            foreach ($label_sexo as $s => $value){
                                ?>
                                <td><?php echo $value; ?></td>
                                <?php
                            }

                        }
                        ?>
                    </tr>
                    <?php
                    $sql1 = "select * from tipos_nanea 
                              order by id_nanea";
                    $res1 = mysql_query($sql1);
                    $FILA = ARRAY();
                    $filtro_total = '';
                    while($row = mysql_fetch_array($res1)){
                        $indicador = trim($row['nanea']);
                        $TOTAL = ARRAY();
                        $tr = '<tr>
                                        <td>'.$indicador.'</td>';
                        $fila = '';
                        foreach ($rango_seccion_h as $i => $rango){
                            foreach ($sexo as $i => $s){
                                $sql2 = "select count(*) as total from persona 
                                            inner join paciente_establecimiento using (rut) 
                                            inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno
                                            where id_establecimiento='$id_establecimiento' 
                                            and $s and $rango 
                                            $filtro_centro 
                                            AND upper(nanea) like '%$indicador%'
                                            limit 1";
                                $row2 = mysql_fetch_array(mysql_query($sql2));
                                if($row2){
                                    $TOTAL[$label_sexo[$i]] += $row2['total'];
                                }else{
                                    $TOTAL[$label_sexo[$i]] += 0;
                                }

                                $fila .= '<td>'.$row2['total'].'</td>';
                            }

                        }
                        $filtro_total .= " OR upper(nanea) like '%$indicador%' ";
                        $fila = ' <td style="background-color: #fdff8b">'.($TOTAL['HOMBRE']+$TOTAL['MUJER']).'</td>
                                  <td style="background-color: #fdff8b">'.($TOTAL['HOMBRE']).'</td>
                                  <td style="background-color: #fdff8b">'.($TOTAL['MUJER']).'</td>'
                                .$fila;

                        $tr = $tr.$fila.'</tr>';
                        echo $tr;
                    }

                    ?>
                </table>
            </div>
        </div>
    </section>

</div>
