<?php
class mysql {
    public $validar;
    public $result;
    public $myId;
    public $id_establecimiento;

    function __construct($id){
        $this->myId = $id;
        $this->id_establecimiento = $_SESSION['id_establecimiento'];
    }
    function getId($tabla,$id_sql){
        $sql = "select * from ".$tabla." where id_usuario='".$this->myId."' order by ".$id_sql." desc limit 1;";
        $row = mysql_fetch_array(mysql_query($sql));
        if($row){
            return $row[$id_sql];
        }
    }
    function insert_centro_interno($sector_comunal,$nombre,$direccion,$telefono,$email){
        $sql = "insert into centros_internos(id_establecimiento,nombre_centro_interno,direccion_centro_interno,telefono_centro_interno,email_centro_interno,id_sector_comunal) 
              values('$this->id_establecimiento',upper('$nombre'),upper('$direccion'),upper('$telefono'),upper('$email'),'$sector_comunal')";
        mysql_query($sql)or die($this->result=false);
        $this->result = true;
    }
    function insert_sector_interno($nombre,$id_centro_interno){
        $sql = "insert into sectores_centros_internos(id_centro_interno,nombre_sector_interno) 
              values(upper('$id_centro_interno'),upper('$nombre'))";

        mysql_query($sql)or die($this->result=false);
        $this->result = true;
    }
    function insert_tipo_documento($nombre,$texto){
        $sql = "insert into tipo_documento(nombre_tipo_doc,texto_tipo_doc,id_usuario) 
              values(upper('$nombre'),upper('$texto'),'".$this->myId."')";
        mysql_query($sql)or die($this->result=false);
        $this->result = true;
    }

    function insert_tipo_agrupacion($nombre,$texto){
        $sql = "insert into tipo_agrupacion(nombre_tipo_agrupacion,texto_tipo_agrupacion,id_usuario) 
              values(upper('$nombre'),upper('$texto'),'".$this->myId."')";
        mysql_query($sql)or die($this->result=false);
        $this->result = true;
    }
    function insert_establecimiento($rut,$tipo,$comuna,$nombre,$direccion,$telefono,$email){
        $rut = str_replace(".","",$rut);
        $sql = "insert into establecimiento(rut,tipo_establecimiento,comuna,nombre_establecimiento,direccion_establecimiento,telefono_establecimiento,email_establecimiento,id_usuario) 
              values('$rut','$tipo',UPPER('$comuna'),upper('$nombre'),UPPER('$direccion'),upper('$telefono'),upper('$email'),'".$this->myId."')";
        echo $sql;
        mysql_query($sql)or die($this->result=false);
        $this->insert_persona($rut,$nombre,$telefono,$email);
        $this->update_persona_column($rut,'direccion',$direccion);
        $this->crearUsuario($rut);
        $this->result = true;
    }
    function update_establecimiento($id_centro_interno,$nombre,$direccion,$telefono,$email,$sector_comunal){
        $sql = "update centros_internos set 
                      nombre_centro_interno=upper('$nombre'),
                      direccion_centro_interno=upper('$direccion'),
                      telefono_centro_interno=upper('$telefono'),
                      email_centro_interno=upper('$email'),
                      id_sector_comunal='$sector_comunal' 
                      where id_centro_interno='$id_centro_interno' ";
        mysql_query($sql)or die($this->result=false);
        $this->result = true;
    }
    function crearUsuario($rut){
        $str = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890";
        $password = "";
        //Reconstruimos la contraseña segun la longitud que se quiera
        for($i=0;$i<$_POST['longitud'];$i++) {
            //obtenemos un caracter aleatorio escogido de la cadena de caracteres
            $password .= substr($str,rand(0,62),1);
        }
        $sql = "insert into usuarios(rut,clave,tipo_usuario) 
              values('$rut','$password','ESTABLECIMIENTO')";

        mysql_query($sql)or die($this->result=false);
        $this->result = true;
    }
    function existe_persona($rut){
        mysql_query("delete from persona where rut=''");
        $sql = "select * from persona where upper(rut)=upper('$rut') limit 1";
        //echo $sql;
        $row = mysql_fetch_array(mysql_query($sql));
        if($row){
            return true;
        }else{
            return false;
        }
    }
    function update_persona($rut,$nombre_completo,$telefono,$email){
        $sql = "update persona set 
                  nombre_completo=upper('$nombre_completo'),
                  telefono='$telefono',
                  email='$email' 
                  where upper(rut)=upper('$rut')";

        mysql_query($sql)or die($this->result=false);
        $this->result = true;
    }
    function insert_persona($rut,$nombre,$telefono,$email){
        $rut = str_replace(".","",$rut);
        if($this->existe_persona($rut)){
            //actualizar Persona
            $this->update_persona($rut,$nombre,$telefono,$email);
        }else{
            $sql = "insert into persona(rut,nombre_completo,telefono,email) 
              values(upper('$rut'),upper('$nombre'),UPPER('$telefono'),upper('$email'))";
            mysql_query($sql)or die($this->result=false);
            $this->result = true;
        }
    }
    function insert_persona_sinRUT($nombre,$telefono,$email){
        $rut = substr(md5(rand(1982,$telefono)),0,10);
        $sql = "insert into persona(rut,nombre_completo,telefono,email,estado_rut) 
              values('$rut',upper('$nombre'),UPPER('$telefono'),upper('$email'),'PENDIENTE')";

        mysql_query($sql)or die($this->result=false);
        return $rut;
    }
    function update_persona_column($rut,$column,$value){
        $rut = str_replace(".","",$rut);
        $sql = "update persona set ".$column."='$value' where rut='$rut'";
        mysql_query($sql)or die($this->result=false);
        $this->result = true;
    }
    function insert_paciente_establecimiento($rut,$id_sector){
        $rut = str_replace(".","",$rut);
        $sql = "select * from paciente_establecimiento where id_establecimiento='$this->id_establecimiento' and rut='$rut' limit 1";
        $row = mysql_fetch_array(mysql_query($sql));
        if($row){
            $sql = "update paciente_establecimiento set id_sector='$id_sector'
                    where id_establecimiento='$this->id_establecimiento' 
                    and rut='$rut'";
        }else{
            $sql = "insert into paciente_establecimiento(id_establecimiento,rut,id_sector) 
              values('$this->id_establecimiento','$rut','$id_sector')";
        }

        mysql_query($sql);
    }
    function updateRUTPaciente($rut_old,$rut_new){
        $sql = "update persona set rut='$rut_new' where rut='$rut_old'";
        mysql_query($sql)or die($this->result=false);
        $sql = "update paciente_establecimiento set rut='$rut_new' where rut='$rut_old'";
        mysql_query($sql)or die($this->result=false);
        $sql = "update antropometria set rut='$rut_new' where rut='$rut_old'";
        mysql_query($sql)or die($this->result=false);
        $sql = "update paciente_psicomotor set rut='$rut_new' where rut='$rut_old'";
        mysql_query($sql)or die($this->result=false);
        $sql = "update datos_nacimiento set rut='$rut_new' where rut='$rut_old'";
        mysql_query($sql)or die($this->result=false);
        $sql = "update vacunas_paciente set rut='$rut_new' where rut='$rut_old'";
        mysql_query($sql)or die($this->result=false);
        $sql = "update paciente_dental set rut='$rut_new' where rut='$rut_old'";
        mysql_query($sql)or die($this->result=false);
        $this->result = true;
    }
    function limpiarModulos($rut){
        $rut = str_replace(".","",$rut);
        $sql = "update paciente_establecimiento 
                    set m_cardiovascular='NO',
                        m_infancia='NO',
                        m_mujer='NO',
                        m_adulto_mayor='NO',
                        m_adolescente='NO'
                    where id_establecimiento='$this->id_establecimiento' 
                    and rut='$rut' 
                    limit 1";
        mysql_query($sql);
    }
    function updateModuloPaciente($rut,$modulo,$estado){
        $rut = str_replace(".","",$rut);
        $sql = "update paciente_establecimiento 
                    set $modulo='$estado' 
                    where id_establecimiento='$this->id_establecimiento' 
                    and rut='$rut' 
                    limit 1";
        mysql_query($sql);

    }
    function updatePapaMamaPaciente($rut,$mama,$papa){
        $rut = str_replace(".","",$rut);
        $sql = "update paciente_establecimiento 
                      set rut_mama=upper('$mama'),
                      rut_papa=upper('$papa') 
                      where rut='$rut'";

        mysql_query($sql)or die($this->result=false);
        $this->result = true;
    }
    function insert_agrupacion_escolar($id_tipo,$desde,$hasta,$presidente,$tesorero,$secretario,$id_establecimiento){
        $sql = "insert into agrupacion_escolar(id_tipo_agrupacion,fecha_desde,fecha_hasta,presidente,tesorero,secretaria,id_establecimiento) 
              values('$id_tipo','$desde','$hasta','$presidente','$tesorero','$secretario','$id_establecimiento' )";
        mysql_query($sql)or die($this->result=false);
        $this->result = true;
    }
    function insert_documento($tipo,$ruta,$obs,$establecimiento){
        $sql = "insert into documento_establecimiento(id_tipo_doc,ruta_documento,observaciones,id_usuario,id_establecimiento) 
              values('$tipo','$ruta','$obs','".$this->myId."','$establecimiento' )";
        mysql_query($sql)or die($this->result=false);
        $this->result = true;
    }
    function insert_atributo_establecimiento($tipo,$nombre,$texto){
        $sql = "insert into atributo_establecimiento(tipo_atributo,nombre_atributo,descripcion_atributo) 
              values(upper('$tipo'),upper('$nombre'),upper('$texto'))";
        mysql_query($sql)or die($this->result=false);
        $this->result = true;
    }

    function delete_centro_interno($id){
        $sql = "delete from centros_internos where id_centro_interno='$id' limit 1";
        mysql_query($sql)or die($this->result=false);
        $this->result = true;
    }
    function delete_agendamiento($id){
        $sql = "delete from agendamiento where id_agendamiento='$id' limit 1";
        mysql_query($sql)or die($this->result=false);
        $this->result = true;
    }
    function insert_sector_comunal($nombre){
        $sql = "insert into sector_comunal(id_establecimiento,nombre_sector_comunal) 
              values(upper('$this->id_establecimiento'),upper('$nombre'))";
        mysql_query($sql)or die($this->result=false);
        $this->result = true;
    }
    function delete_sector_comunal($id){
        $sql = "delete from sector_comunal where id_sector_comunal='$id' limit 1";
        mysql_query($sql)or die($this->result=false);
        $this->result = true;
    }
    function delete_sector_interno($id){
        $sql = "delete from sectores_centros_internos where id_sector_centro_interno='$id' limit 1";
        mysql_query($sql)or die($this->result=false);
        $this->result = true;
    }

    //estadisticas
    function getTotal($tabla,$indicador,$valor,$rango,$sexo,$id_centro){

        if($id_centro!=''){

            $sql = "select 
                    sum($indicador='$valor' and $sexo and $rango) as total 
                from $tabla
                    inner join persona using(rut)
                    inner join paciente_establecimiento using (rut) 
                    inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno 
                    inner join centros_internos on sectores_centros_internos.id_centro_interno=centros_internos.id_centro_interno 
                    inner join sector_comunal on centros_internos.id_sector_comunal=sector_comunal.id_sector_comunal  
                where m_cardiovascular='SI' and persona.rut!='' and $indicador!=''
                and sectores_centros_internos.id_centro_interno='$id_centro' 
                and paciente_establecimiento.id_establecimiento='1' ";

        }else{
            $sql = "select 
                    sum($indicador='$valor' and $sexo and $rango) as total 
                    from $tabla
                    inner join persona using(rut)
                    inner join paciente_establecimiento using (rut) 
                    inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno 
                    inner join centros_internos on sectores_centros_internos.id_centro_interno=centros_internos.id_centro_interno 
                    inner join sector_comunal on centros_internos.id_sector_comunal=sector_comunal.id_sector_comunal  
                where m_cardiovascular='SI' and persona.rut!='' and id_sector!=0 and $indicador!=''
                and paciente_establecimiento.id_establecimiento='1' ";

        }
        $row = mysql_fetch_array(mysql_query($sql));
        if($row){
            return $row['total'];
        }else{
            return 0;
        }
    }

    function getTotal_infancia($tabla,$indicador,$valor,$rango,$sexo,$id_centro){

        if($id_centro!=''){

            $sql = "select 
                    sum($indicador='$valor' and $sexo and $rango) as total 
                from $tabla
                    inner join persona using(rut)
                    inner join paciente_establecimiento using (rut) 
                    inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno 
                    inner join centros_internos on sectores_centros_internos.id_centro_interno=centros_internos.id_centro_interno 
                    inner join sector_comunal on centros_internos.id_sector_comunal=sector_comunal.id_sector_comunal  
                where m_infancia='SI' and persona.rut!='' and $indicador!=''
                and sectores_centros_internos.id_centro_interno='$id_centro' 
                and paciente_establecimiento.id_establecimiento='1' ";

        }else{
            $sql = "select 
                    sum($indicador='$valor' and $sexo and $rango) as total 
                    from $tabla
                    inner join persona using(rut)
                    inner join paciente_establecimiento using (rut) 
                    inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno 
                    inner join centros_internos on sectores_centros_internos.id_centro_interno=centros_internos.id_centro_interno 
                    inner join sector_comunal on centros_internos.id_sector_comunal=sector_comunal.id_sector_comunal  
                where m_infancia='SI' and persona.rut!='' and id_sector!=0 and $indicador!=''
                and paciente_establecimiento.id_establecimiento='1' ";

        }
        $row = mysql_fetch_array(mysql_query($sql));
        if($row){
            return $row['total'];
        }else{
            return 0;
        }
    }
    function getTotalHistorial($tabla,$column_indicador,$valor_indicador,$column_valor,$valor_valor,$rango,$sexo,$id_centro,$dias){

        if($id_centro!=''){
            $sql = "select sum($column_indicador='$valor_indicador'
                            and $column_valor='$valor_valor' and $sexo) as total 
                        from $tabla 
                        inner join persona using(rut) 
                        inner join paciente_establecimiento on persona.rut=paciente_establecimiento.rut
                        inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno 
                        where sectores_centros_internos.id_centro_interno='$id_centro'
                        and $rango
                        and ($tabla.fecha_registro > current_date() - interval  $dias day)
                        group by $tabla.rut
                        limit 1";
        }else{
            $sql = "select sum($column_indicador='$valor_indicador'
                            and $column_valor='$valor_valor' and  $rango and $sexo) as total 
                        from $tabla 
                        inner join persona using(rut) 
                        inner join paciente_establecimiento on persona.rut=paciente_establecimiento.rut
                        inner join sectores_centros_internos on paciente_establecimiento.id_sector=sectores_centros_internos.id_sector_centro_interno
                        where $rango  
                        and ($tabla.fecha_registro > current_date() - interval  $dias day)
                        group by $tabla.rut
                        limit 1";
        }
//        echo $sql.'<br />';


        $row = mysql_fetch_array(mysql_query($sql));
        if($row){
            return $row['total'];
        }else{
            return 0;
        }
    }
    function getTotal1($tabla,$indicador,$valor,$rango,$sexo,$id_centro){

        $sql = "select sum($indicador='$valor' and $rango and $sexo) as total 
                        from $tabla 
                        inner join persona using(rut)  
                        limit 1";

        $row = mysql_fetch_array(mysql_query($sql));
        if($row){
            return $row['total'];
        }else{
            return 0;
        }
    }
    function getTotalPersona($tabla,$indicador,$valor,$sexo,$filtro_suma,$filtro_where){
        $sql = "select sum($indicador='$valor' and $sexo $filtro_suma) as total 
                        from $tabla inner join persona using(rut) 
                        $filtro_where
                        limit 1";

        $row = mysql_fetch_array(mysql_query($sql));
        if($row){
            return $row['total'];
        }else{
            return 0;
        }
    }
    //mujer
    function insert_hormona($rut,$tipo,$vencimiento,$fecha_registro,$obs){
        $sql = "insert into mujer_historial_hormonal(fecha_registro,id_profesional,tipo,vencimiento,rut,observacion) 
              values('$fecha_registro','$this->idId','$tipo','$vencimiento','$rut',upper('$obs'))";
        mysql_query($sql)or die($this->result=false);
        $this->result = true;
    }
    function insert_ecografia($id_gestacion,$rut,$tipo,$fecha,$trimestre,$obs){
        $sql = "insert into ecografias_mujer(tipo_eco,id_profesional,fecha_eco,trimestre,rut,id_gestacion,observacion) 
              values('$tipo','$this->myId','$fecha','$trimestre','$rut','$id_gestacion',upper('$obs'))";

        mysql_query($sql)or die($this->result=false);
        $this->result = true;
    }
    function insert_VDI($id_gestacion,$fecha,$obs,$rut){
        $sql = "insert into visita_vdi(id_gestacion,rut,fecha_vdi,obs_vdi,id_profesional) 
              values('$id_gestacion','$rut','$fecha','$obs','$this->myId')";

        mysql_query($sql)or die($this->result=false);
        $this->result = true;
    }
    function deleteHormona($rut,$id_historial){
        $sql = "delete from mujer_historial_hormonal 
                    where rut='$rut' and id_historial='$id_historial' ";
        mysql_query($sql)or die($this->result=false);
        $this->result = true;
    }

}

?>
