<?php
include '../../../php/config.php';
include '../../../php/objetos/establecimiento.php';

session_start();

$id_establecimiento = $_SESSION['id_establecimiento'];

$dsm = new establecimiento($id_establecimiento);


?>
<ul id="dropdown_menu_interno" class="dropdown-content">
    <li onclick="loadForm_sectoresComunales()"><a href="#!">SECTORES COMUNALES</a></li>
    <li onclick=""><a href="#!">ACTUALIZAR PERFIL</a></li>
    <li class="divider"></li>
</ul>
<ul id="dropdown_menu_informes" class="dropdown-content" style="width: 400px;">
    <li onclick="load_estadistica_REMP4()"><a href="#!">REM P4</a></li>
    <li class="divider"></li>
</ul>
<div class="container">
    <div class="col s12 m12 l12" style="margin-top: 10px;">
        <nav class="eh-open_principal">
            <div class="nav-wrapper">
                <div class="col s12">
                    <a href="#!" class="brand-logo"><i class="mdi-action-settings-applications"></i></a>

                    <ul class="right hide-on-med-and-down">
                        <li onclick="load_Info_P4()"><a href="#"><i class="mdi-action-assignment-turned-in left"></i>REM P4</a></li>
                        <!--                        <li onclick="load_infoTraslado_menu()()"><a href="#"><i class="mdi-action-assignment-turned-in left"></i>TRASLADO NIÑO(A)</a></li>-->
                    </ul>
                </div>
            </div>
        </nav>
    </div>
    <div id="contenido_menu">
        <div class="card-panel">
            <div class="row">
                <div class="col l8 m12 s12">
                    <div class="col l12">
                        <label>CENTRO MEDICO
                            <select class="browser-default"
                                    name="centro_interno"
                                    id="centro_interno"
                                    onchange="loadP4()" >
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
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    function exportTable(table,file){
        let export_to_excel = document.getElementById(table);
        let data_to_send = document.getElementById('data_to_send');
        data_to_send.value = export_to_excel.outerHTML;
        $("#file").val(file);
        document.getElementById('formExport').submit();
    }
    function loadP4() {
        var id = $("#centro_interno").val();
        var div = 'contenido_menu';
        loading_div(div);
        $.post('info/P4.php',{
            id:id
        },function(data){
            if(data !=='ERROR_SQL' ){
                $("#"+div).html(data);
            }else{

            }
        });
    }
</script>
<script type="text/javascript">


    function load_Info_P4() {
        var div = 'contenido_menu';
        loading_div(div);
        $.post('info/P4.php',{
        },function(data){
            if(data !=='ERROR_SQL' ){
                $("#"+div).html(data);
            }else{

            }
        });
    }

    function load_infoTraslado_menu() {
        var div = 'contenido_menu';
        loading_div(div);
        $.post('formulario/traslado.php',{
        },function(data){
            if(data !=='ERROR_SQL' ){
                $("#"+div).html(data);
            }else{

            }
        });
    }
</script>
