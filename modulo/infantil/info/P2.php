<?php
include "../../../php/config.php";
include '../../../php/objetos/mysql.php';

$id_centro = $_POST['id'];
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
        var div = 'tabla_p2';
        loading_div(div);
        $.post('info/tabla_p2.php',{
            id:id
        },function(data){
            if(data !=='ERROR_SQL' ){
                $("#"+div).html(data);
            }else{

            }
        });
    }
</script>
<form action="../../exportar/table.php" method="post" target="_blank" id="formExport">
    <input type="hidden" id="data_to_send" name="data_to_send" />
    <input type="hidden" id="file" name="file" value="archivo" />
</form>
<div class="row">
    <div class="col l12 m12 s12">
        <div class="col l10">
            <label>CENTRO MEDICO
                <select class="browser-default"
                        name="centro_interno"
                        id="centro_interno"
                        onchange="loadP2()" >
                    <option value="" disabled="disabled" selected="selected">SELECCIONAR CENTRO MEDICO</option>
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
                    <option value="">TODOS</option>
                </select>
            </label>
        </div>
    </div>
    <div class="col l4 m12 s12 right-align">
        <div class="col l12">

        </div>
    </div>
</div>
<div id="tabla_p2">

</div>

