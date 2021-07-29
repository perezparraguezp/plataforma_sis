<?php
include '../../config.php';
include '../../objetos/persona.php';

$myId = $_SESSION['id_usuario'];
$rut = $_POST['rut'];
$p = new persona($rut);

$meses = Array("","Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
?>
<form id="form_agendamiento" class="modal-content">
    <input type="hidden" name="rut" value="<?php echo $rut; ?>" />
    <div class="card-panel" style="padding: 10px;margin: 0px;">
        <div class="row col l12" style="margin-bottom: 0px;">
            <div class="col l8 light-blue-text">AGENDAR PROXIMA ATENCIÓN</div>
        </div>
        <div class="row col l12" style="font-size: 0.8em;margin-bottom: 0px;">
            Paciente: <?php echo $p->nombre; ?>
        </div>
        <hr class="row" />
        <div class="row center-align" style="font-size: 0.8em;margin-bottom: 0px;">
            <div class="col l4 card-panel">
                <div class="row orange center-align" style="padding: 5px;">
                    AÑO
                </div>
                <div class="row">
                    <select name="anio_cita" id="anio_cita">
                        <option><?php echo date('Y'); ?></option>
                        <option><?php echo date('Y')+1; ?></option>
                        <option><?php echo date('Y')+2; ?></option>
                    </select>
                    <script type="text/javascript">
                        $(function(){
                            $('#anio_cita').jqxDropDownList({
                                width: '97%',
                                height: '25px'
                            });
                        })
                    </script>
                </div>
            </div>
            <div class="col l4 card-panel">
                <div class="row orange center-align" style="padding: 5px;">
                    MES
                </div>
                <div class="row">
                    <select name="mes_cita" id="mes_cita">
                        <?php
                        foreach ($meses as $i => $mes){
                            if($i>0){
                                if($i == date('m')){
                                    ?>
                                    <option selected="selected" value="<?php echo $mes; ?>"><?php echo $mes; ?></option>
                                    <?php
                                }else{
                                    ?>
                                    <option value="<?php echo $i; ?>"><?php echo $mes; ?></option>
                                    <?php
                                }
                            }
                        }
                        ?>
                    </select>
                    <script type="text/javascript">
                        $(function(){
                            $('#mes_cita').jqxDropDownList({
                                width: '97%',
                                height: '25px'
                            });
                        })
                    </script>
                </div>
            </div>
            <div class="col l4 card-panel">
                <div class="row orange center-align" style="padding: 5px;">
                    PROFESIONAL
                </div>
                <div class="row">
                    <select name="profesional_cita" id="profesional_cita">
                        <?php
                        $sql = "select * from personal_establecimiento group by tipo_contrato ";
                        $res = mysql_query($sql);
                        while($row = mysql_fetch_array($res)) {
                            ?>
                            <option><?php echo $row['tipo_contrato']; ?></option>
                            <?php
                        }
                        ?>
                    </select>
                    <script type="text/javascript">
                        $(function(){
                            $('#profesional_cita').jqxDropDownList({
                                width: '97%',
                                height: '25px'
                            });
                        })
                    </script>
                </div>
            </div>
        </div>
        <hr class="row" />
        <div class="row">
            <div class="col l6">
                <input type="button"
                       style="width: 100%;"
                       value="--> NO AGENDAR <--"
                       onclick="noAgendar()"
                       class="btn-large red darken-2 white-text"/>
            </div>
            <div class="col l6">
                <input type="button"
                       style="width: 100%;"
                       value="--> AGENDAR <--"
                       onclick="insertAgendamiento()"
                       class="btn-large light-green darken-3"/>
            </div>
        </div>
    </div>
    <div class="card blue lighten-5 black-text" style="padding: 10px;">
        <div class="row">
            <div class="col l12 m12 s12">
                <header>PROXIMOS AGENDAMIENTOS</header>
            </div>
        </div>
        <div class="row deep-purple lighten-4">
            <div class="col l4 m4 s4">FECHA</div>
            <div class="col l8 m8 s8">PROFESIONAL</div>
        </div>
        <div style="height: 100px;overflow-y: scroll;">
            <?php
            $sql = "select * from agendamiento
          where rut='$rut' 
          and anio_proximo_control>='".date('Y')."' 
          and mes_proximo_control>='".date('m')."' 
          order by anio_proximo_control,mes_proximo_control ";
            $res = mysql_query($sql);
            while($row = mysql_fetch_array($res)) {
                ?>
                <div class="row">
                    <div class="col l4 m4 s4"><?php echo $meses[$row['mes_proximo_control']].'/'.$row['anio_proximo_control']; ?></div>
                    <div class="col l8 m8 s8"><?php echo $row['profesional']; ?></div>
                </div>
                <?php
            }
            ?>
        </div>


    </div>
</form>
<div class="modal-footer">
    <a href="#" id="close_modal" class="waves-effect waves-red btn-flat modal-action modal-close">CERRAR</a>
</div>
<script type="text/javascript">
    function insertAgendamiento() {
        $.post('php/db/insert/agendamiento.php',
            $("#form_agendamiento").serialize(),function(data){
                alertaLateral(data);
                if(confirm('QUIERE AGREGAR OTRO AGENDAMIENTO')){
                    boxAgendamiento();
                }else{
                    volverFichaSearch_1();
                    document.getElementById("close_modal").click();
                }


            });
    }
    function noAgendar(){
        volverFichaSearch();
        document.getElementById("close_modal").click();
    }
</script>
