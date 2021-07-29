<?php
include '../config.php';
session_start();

$id_establecimiento = $_SESSION['id_establecimiento'];

$sql = "select count(*) as total from persona 
        inner join paciente_establecimiento using(rut) 
        where id_establecimiento='$id_establecimiento' ";
$row = mysql_fetch_array(mysql_query($sql));

if($row){
    $total_pacientes = $row['total'];
}else{
    $total_pacientes = 0;
}


$sql = "select count(*) as total from persona inner join personal_establecimiento using(rut) 
        where id_establecimiento='$id_establecimiento' ";
$row = mysql_fetch_array(mysql_query($sql));
if($row){
    $total_profesionales = $row['total'];
}else{
    $total_profesionales = 0;
}
$sql = "select count(*) as total from historial_paciente inner join paciente_establecimiento using(rut)
        where paciente_establecimiento.id_establecimiento='$id_establecimiento' 
        and date(historial_paciente.fecha_registro)=current_date() 
        group by date(historial_paciente.fecha_registro);";
$row = mysql_fetch_array(mysql_query($sql));
if($row){
    $total_atenciones = $row['total'];
}else{
    $total_atenciones = 0;
}

$sql = "select count(*) as total from persona inner join paciente_establecimiento using(rut)
        where paciente_establecimiento.id_establecimiento='$id_establecimiento' 
        and sexo='M' ";
$row = mysql_fetch_array(mysql_query($sql));
if($row){
    $total_hombres = $row['total'];
}else{
    $total_hombres = 0;
}
$total_mujeres = $total_pacientes - $total_hombres;

$sql = "select count(*) as total from persona inner join paciente_establecimiento using(rut)
        where paciente_establecimiento.id_establecimiento='$id_establecimiento' 
        and pueblo='SI' ";
$row = mysql_fetch_array(mysql_query($sql));
if($row){
    $total_pueblos = $row['total'];
}else{
    $total_pueblos = 0;
}

$sql = "select count(*) as total from persona inner join paciente_establecimiento using(rut)
        where paciente_establecimiento.id_establecimiento='$id_establecimiento' 
        and naneas!='NO' ";
$row = mysql_fetch_array(mysql_query($sql));
if($row){
    $total_naneas = $row['total'];
}else{
    $total_naneas = 0;
}
?>
<div class="row">
    <div class="col l12 s12 m12" id="escritorio_filtro"></div>
</div>
<div class="row">
    <div class="col l12 s12 m12" id="escritorio_header"></div>
</div>

<hr class="row" />
<div class="row">
    <div class="col l12" id="div_indicador_grafico">

    </div>
</div>

<script type="text/javascript">

    loadheader_Escritorio();

    function loadheader_Escritorio() {
        loadHeader_1();
        loadFormFiltro_Escritorio();
    }
    function loadHeader_1(){
        $.post('php/modal/escritorio/header.php',{
        },function(data){
            $("#escritorio_header").html(data);
        });
    }
    function loadFormFiltro_Escritorio(){
        $.post('php/formulario/escritorio/filtro.php',{
        },function(data){
            $("#escritorio_filtro").html(data);
        });
    }
    function loadSelectSectoresCentro() {
        var centro = $("#id_centro").val();
        $.post('php/ajax/select/sectores_centro.php',{
            id_centro:centro
        },function(data){
            $("#div_sector_interno").html(data);
            $("#id_sector_centro").jqxDropDownList({
                width: '100%', height: 30});
        });
    }
    function init_graficos(){


        //graficos
        loadGrafico_pacientes_edad();
        //loadGrafico_nutricion_infantil();
        loadGrafico_Antropometria();
        loadGrafico_Dental();
    }
    function loadGrafico_Antropometria(){
        loadGrafico_DNI_menor6();
        loadGrafico_DNI_mayor6();
    }
    function loadGrafico_Dental(){
        loadGrafico_DentalCero();
        loadGrafico_DentalGes6();
    }
    function loadGrafico_pacientes_edad(){
        $.post('php/graficos/bar_pacientes_por_edad.php',
            $("#form_FiltrosGraficos").serialize()
            ,function(data){
                $("#grafico1").html(data);
            });
    }
    function loadGrafico_nutricion_infantil(){
        $.post('php/graficos/pie_nutricional_infantil.php',
            $("#form_FiltrosGraficos").serialize()
            ,function(data){
                $("#grafico2").html(data);
            });
    }
    function loadGrafico_DNI_menor6(){
        $.post('php/graficos/barra/nutricional_menores_6_anios.php',
            $("#form_FiltrosGraficos").serialize()
            ,function(data){
                $("#menores6").html(data);

            });
    }
    function loadGrafico_DNI_mayor6(){
        $.post('php/graficos/barra/nutricional_mayores_6_anios.php',
            $("#form_FiltrosGraficos").serialize()
            ,function(data){
                $("#mayores6").html(data);

            });
    }
    function loadGrafico_DentalCero(){
        $.post('php/graficos/dashboard/dental_cero.php',
            $("#form_FiltrosGraficos").serialize()
            ,function(data){
                $("#tabs_dental_cero").html(data);
            });
    }
    function loadGrafico_DentalGes6(){
        $.post('php/graficos/dashboard/dental_ges6.php',
            $("#form_FiltrosGraficos").serialize()
            ,function(data){
                $("#tabs_dental_ges6").html(data);
            });
    }


</script>