<?php
class establecimiento {

    public $id,$nombre,$comuna,$tipo,$direccion,$telefono,$email;
    public $id_sector_comunal;


    function __construct($id){
        $this->id = $id;
        $sql = "select *,comunas.comuna as nombre_comuna 
                      from establecimiento inner join comunas on establecimiento.comuna=comunas.id  
                      where id_establecimiento='$id' limit 1;";

        $row = mysql_fetch_array(mysql_query($sql));
        if($row){
            $this->nombre = $row['nombre_establecimiento'];
            $this->comuna = $row['nombre_comuna'];
            $this->tipo = $row['tipo_establecimiento'];
            $this->direccion = $row['direccion_establecimiento'];
            $this->telefono = $row['telefono_establecimiento'];
            $this->email = $row['email_establecimiento'];
        }
    }

    function getNombreCentroInterno($id_centro_interno){
        $sql = "select * from centros_internos 
                  where id_centro_interno='$id_centro_interno' limit 1";;
        $row = mysql_fetch_array(mysql_query($sql));
        if($row){
            $nombre = $row['nombre_centro_interno'];
        }else{
            $nombre = 'NO EXISTE EN NUESTROS REGISTROS';
        }
        return $nombre;
    }
    function getNombreSectorComunal($id_centro_interno){
        $sql = "select * from centros_internos inner join sector_comunal using(id_sector_comunal)
                  where id_centro_interno='$id_centro_interno' limit 1";;
        $row = mysql_fetch_array(mysql_query($sql));
        if($row){
            $nombre = $row['nombre_sector_comunal'];
        }else{
            $nombre = 'NO EXISTE EN NUESTROS REGISTROS';
        }
        return $nombre;
    }
    function getTelefonoCentroInterno($id_centro_interno){
        $sql = "select * from centros_internos 
                  where id_centro_interno='$id_centro_interno' limit 1";;
        $row = mysql_fetch_array(mysql_query($sql));
        if($row){
            $nombre = $row['telefono_centro_interno'];
        }else{
            $nombre = 'NO REGISTRA';
        }
        return $nombre;
    }
    function getEmailCentroInterno($id_centro_interno){
        $sql = "select * from centros_internos 
                  where id_centro_interno='$id_centro_interno' limit 1";;
        $row = mysql_fetch_array(mysql_query($sql));
        if($row){
            $nombre = $row['email_centro_interno'];
        }else{
            $nombre = 'NO REGISTRA';
        }
        return $nombre;
    }
    function getDireccionCentroInterno($id_centro_interno){
        $sql = "select * from centros_internos 
                  where id_centro_interno='$id_centro_interno' limit 1";;
        $row = mysql_fetch_array(mysql_query($sql));
        if($row){
            $nombre = $row['direccion_centro_interno'];
        }else{
            $nombre = 'NO REGISTRA';
        }
        return $nombre;
    }
    function getData($col){
        $sql = "select * from establecimiento 
                where id_establecimiento='".$this->id."' 
                limit 1";
        $row = mysql_fetch_array(mysql_query($sql));
        if($row){
            return $row[$col];
        }

    }

    function getAtributo($id_atributo){
        $sql = "select * from mis_atributos_establecimientos 
                where id_atributo='$id_atributo' and id_establecimiento='".$this->id."' limit 1";
        $row = mysql_fetch_array(mysql_query($sql));
        if($row){
            $atributo = Array('ID' => $id_atributo, 'VALOR' => $row['valor'], 'OBS' => $row['observaciones'], 'EXISTE' => 'SI');
        }else{
            $atributo = Array('ID' => '', 'VALOR' => '', 'OBS' => '', 'EXISTE' => 'NO');
        }
        return $atributo;
    }
    function deleteAtributo($id_atributo){
        $sql = "delete from mis_atributos_establecimientos 
                where id_atributo='$id_atributo' and id_establecimiento='".$this->id."' ";
        mysql_query($sql) or die (false);
        return true;
    }
    function updateAtributo($id_atributo,$valor,$obs,$usuario){
        $atributo = $this->getAtributo($id_atributo);
        if($atributo['EXISTE']=='SI'){
            //eliminamos el valor del atributo para proceder a crearlo nuevamente
            $this->deleteAtributo($id_atributo);
        }

        $sql = "insert into mis_atributos_establecimientos(id_atributo,id_establecimiento,valor,observaciones,fecha_modificacion,id_usuario) 
            values('$id_atributo','".$this->id."','$valor',upper('$obs'),now(),'$usuario')";
        mysql_query($sql)or die(false);
        return true;
    }
    function getIdSectorComunal($id_centro_interno){
        $sql = "select * from centros_internos 
                  where id_centro_interno='$id_centro_interno' limit 1";;
        $row = mysql_fetch_array(mysql_query($sql));
        if($row){
            $id = $row['id_sector_comunal'];
        }else{
            $id = '0';
        }
        return $id;
    }



}

?>
