<?php
include '../../../php/config.php';
session_start();

$id_establecimiento = $_SESSION['id_establecimiento'];
$sql1 = "select * from sector_comunal 
                          where id_establecimiento='$id_establecimiento' 
                          order by nombre_sector_comunal";

?>
<div class="card-panel">
    <header>FILTROS DE BUSQUEDA</header>
    <div class="row">
        <div class="col l3 m6 s12">
            <label>INDICADOR</label>
            <select name="indicador" id="indicador">
                <option VALUE="DNI1">DNI MENORES DE 6 AÑOS</option>
                <option VALUE="DNI2">DNI ENTRE 6 A 9 AÑOS</option>
                <option VALUE="DNI3">DNI TODOS</option>
                <option VALUE="PCINT">PCINT</option>
                <option VALUE="LME">LME</option>
                <option VALUE="SCORE_IRA">SCRORE IRA</option>
                <option VALUE="COBERTURA_DENTAL">COBERTURA DENTAL</option>
                <option VALUE="PSICOMOTOR">PSICOMOTOR</option>
                <option VALUE="presion_arterial">PRESION ARTERIAL</option>
                <option VALUE="perimetro_craneal">PERIMETRO CRANEAL</option>
                <option VALUE="evaluacion_auditiva">EVALUACION AUDITIVA</option>
                <option VALUE="vacunas_paciente">VACUNAS</option>
            </select>
            <script type="text/javascript">
                $(function(){
                    $('#indicador').jqxDropDownList({
                        width: '98%',
                        theme: 'eh-open',
                        height: '25px'
                    });
                })
            </script>
        </div>
        <div class="col l3 m6 s12">
            <label>SECTOR COMUNAL</label>
            <select name="sector_comunal"
                    id="sector_comunal">
                <option selected>TODOS</option>
                <?php

                $res1 = mysql_query($sql1);
                while($row1 = mysql_fetch_array($res1)){
                    ?>
                    <option value="<?php echo strtoupper($row1['id_sector_comunal']); ?>"><?php echo strtoupper($row1['nombre_sector_comunal']); ?></option>
                    <?php
                }
                ?>
            </select>
            <script type="text/javascript">
                $(function(){
                    $('#sector_comunal').jqxDropDownList({
                        width: '98%',
                        height: '25px',
                        theme: 'eh-open',
                        checkboxes: true
                    });
                })
            </script>
        </div>
        <div class="col l3 m6 s12">
            <div class="col l12" id="div_centros_internos">

            </div>
        </div>
        <div class="col l3 m6 s12">
            <div class="col l12" id="div_sectores_interno">


            </div>
        </div>
    </div>

    <div class="row" style="margin-top: 5px;">
        <div class="col l12 m12 s12">
            <input type="button"
                   class="btn col l12 m12 s12"
                   value="CARGAR GRAFICO" onclick="loadIndicador_Grafico()" />
        </div>
    </div>
</div>
<div id="header_graficos"></div>
<div class="card-panel" id="div_indicador_grafico"></div>
<script type="text/javascript">

    var sector_comunal = 'TODOS';
    var centro_interno = 'TODOS';

    var sector_interno = 'TODOS';

    var indicador = '';

    $("#sector_comunal").on('checkChange', function (event1){
        if (event1.args) {
            var item1 = event1.args.item;
            var label1 = item1.label;
            if(label1!='TODOS'){
                $("#sector_comunal").jqxDropDownList('uncheckItem', 'TODOS');
            }else{
                // $("#centro_interno").jqxDropDownList('checkAll');
            }
        }
        sector_comunal = "";
        var items1 = $("#sector_comunal").jqxDropDownList('getCheckedItems');

        $.each(items1, function (index) {
            sector_comunal += this.value + ", ";
        });


        if(sector_comunal !=='TODOS,'){
            $.post('../../php/ajax/select/centro_interno.php',{
                sector_municipal:$("#sector_comunal").val()
            },function(data){
                $("#div_centros_internos").html('');
                $("#div_centros_internos").html('<label>ESTABLECIMIENTO</label><select name="centro_interno" id="centro_interno"></select>');
                $("#centro_interno").html(data);

                $('#centro_interno').jqxDropDownList({
                    width: '98%',
                    height: '25px',
                    theme: 'eh-open',
                    checkboxes: true
                });
                $("#centro_interno").on('checkChange', function (event2){

                    if (event2.args) {
                        var item2 = event2.args.item;
                        var label2 = item2.label;
                        if(label2!='TODOS'){
                            $("#centro_interno").jqxDropDownList('uncheckItem', 'TODOS');
                        }else{
                            // $("#centro_interno").jqxDropDownList('checkAll');
                        }
                    }
                    centro_interno = "";
                    var items2 = $("#centro_interno").jqxDropDownList('getCheckedItems');
                    $.each(items2, function (index) {
                        centro_interno += this.value + ", ";

                    });
                    if(centro_interno !=='TODOS,'){

                        $.post('../../php/ajax/select/sectores_centro_option.php',{
                            id_centro:centro_interno
                        },function(data){
                            $("#div_sectores_interno").html('');
                            $("#div_sectores_interno").html('<label>SECTOR INTERNO</label><select name="sector_interno" id="sector_interno"></select>');
                            $("#sector_interno").html(data);
                            $('#sector_interno').jqxDropDownList({
                                width: '98%',
                                height: '25px',
                                theme: 'eh-open',
                                checkboxes: true
                            });
                            $("#sector_interno").on('checkChange', function (event3){
                                if (event3.args) {
                                    var item3 = event3.args.item;
                                    var label3 = item3.label;
                                    if(label3!='TODOS'){
                                        $("#sector_interno").jqxDropDownList('uncheckItem', 'TODOS');
                                    }else{
                                        //  $("#sector_interno").jqxDropDownList('checkAll');
                                    }
                                }
                                sector_interno = "";
                                var items3 = $("#sector_interno").jqxDropDownList('getCheckedItems');
                                $.each(items3, function (index) {
                                    sector_interno += this.value + ", ";
                                });
                            });
                        });
                    }else{
                        $("#div_centros_internos").html('');
                        $("#div_sectores_interno").html('');
                    }

                });


            });
        }else{
            $("#div_centros_internos").html('');
            $("#div_sectores_interno").html('');
        }
    });


    function loadIndicador_Grafico() {
        loadGif_graficos('div_indicador_grafico');
        var indicador = $("#indicador").val();
        if(indicador==='NORMAL'){
            $.post('graficos/barra/DNI_NORMALIDAD.php',{
                sector_comunal:sector_comunal,
                centro_interno:centro_interno,
                sector_interno:sector_interno,
                indicador:indicador
            },function(data){
                $("#div_indicador_grafico").html(data);
                //updateHeadEscritorio(sector_comunal,centro_interno,sector_interno);
            });
        }else{
            if(indicador==='COBERTURA_DENTAL') {
                $.post('graficos/barra/COBERTURA_DENTAL.php', {
                    sector_comunal: sector_comunal,
                    centro_interno: centro_interno,
                    sector_interno: sector_interno,
                    indicador: indicador
                }, function (data) {
                    $("#div_indicador_grafico").html(data);
                    //updateHeadEscritorio(sector_comunal,centro_interno,sector_interno);
                });
            }else{
                if(indicador==='PSICOMOTOR'){
                    $.post('graficos/barra/PSICOMOTOR.php', {
                        sector_comunal: sector_comunal,
                        centro_interno: centro_interno,
                        sector_interno: sector_interno,
                        indicador: 'EV NEUROSENSORIAL'
                    }, function (data) {
                        $("#div_indicador_grafico").html(data);
                        //updateHeadEscritorio(sector_comunal,centro_interno,sector_interno);
                    });
                }else{
                    if(indicador==='presion_arterial'){
                        $.post('graficos/barra/ANTROPOMETRIA_GENERAL.php', {
                            sector_comunal: sector_comunal,
                            centro_interno: centro_interno,
                            sector_interno: sector_interno,
                            indicador: 'presion_arterial',
                            estado:'NORMAL'
                        }, function (data) {
                            $("#div_indicador_grafico").html(data);
                            //updateHeadEscritorio(sector_comunal,centro_interno,sector_interno);
                        });
                    }else{
                        if(indicador==='perimetro_craneal'){
                            $.post('graficos/barra/ANTROPOMETRIA_GENERAL.php', {
                                sector_comunal: sector_comunal,
                                centro_interno: centro_interno,
                                sector_interno: sector_interno,
                                indicador: 'perimetro_craneal',
                                estado:'NORMAL'
                            }, function (data) {
                                $("#div_indicador_grafico").html(data);
                                //updateHeadEscritorio(sector_comunal,centro_interno,sector_interno);
                            });
                        }else{
                            if(indicador==='evaluacion_auditiva'){
                                $.post('graficos/barra/ANTROPOMETRIA_GENERAL.php', {
                                    sector_comunal: sector_comunal,
                                    centro_interno: centro_interno,
                                    sector_interno: sector_interno,
                                    indicador: 'evaluacion_auditiva',
                                    estado:'NORMAL'
                                }, function (data) {
                                    $("#div_indicador_grafico").html(data);

                                });
                            }else{
                                if(indicador==='DNI'){
                                    $.post('graficos/barra/ANTROPOMETRIA_GENERAL.php', {
                                        sector_comunal: sector_comunal,
                                        centro_interno: centro_interno,
                                        sector_interno: sector_interno,
                                        indicador: 'DNI',
                                        estado:'NORMAL'
                                    }, function (data) {
                                        $("#div_indicador_grafico").html(data);

                                    });
                                }else{
                                    if(indicador==='SCORE_IRA' || indicador==='PCINT'||
                                        indicador==='IMCE'|| indicador==='LME'|| indicador==='DNI1'
                                        || indicador==='DNI2'|| indicador==='DNI3'){
                                        $.post('graficos/barra/ANTROPOMETRIA_GENERAL.php', {
                                            sector_comunal: sector_comunal,
                                            centro_interno: centro_interno,
                                            sector_interno: sector_interno,
                                            indicador: indicador,
                                            estado:'NORMAL'
                                        }, function (data) {
                                            $("#div_indicador_grafico").html(data);

                                        });
                                    }else{
                                        if(indicador === 'vacunas_paciente'){
                                            $.post('graficos/barra/VACUNAS.php', {
                                                sector_comunal: sector_comunal,
                                                centro_interno: centro_interno,
                                                sector_interno: sector_interno,
                                                indicador: indicador,
                                                estado:'2m'
                                            }, function (data) {
                                                $("#div_indicador_grafico").html(data);

                                            });
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    function loadGif_graficos(div) {
        $("#"+div).html('<div class="card-panel row">' +
            '<div class="col l12 m12 s12 center">' +
            '<img src="../../graficando.gif" widht="100%" /><br /> '+
            '<strong>GRAFICANDO DATOS</strong> '+
            '</div>'+
            '</div>');
    }





</script>
