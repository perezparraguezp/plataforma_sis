<?php
class tipo_agrupacion{
    public $id,$estado;
    public $nombre_tipo,$texto_tipo;
    public $existe,$fecha_creacion;

    function __construct($id){
        $this->id= $id;
        $sql = "select * from tipo_agrupacion WHERE id_tipo_agrupacion='$id' limit 1";
        $row = mysql_fetch_array(mysql_query($sql));
        if($row){
            $this->existe=true;
            $this->nombre_tipo = $row['nombre_tipo_agrupacion'];
            $this->texto_tipo = $row['texto_tipo_agrupacion'];
            $this->fecha_creacion = $row['fecha_creacion'];
            $this->estado = $row['estado_agrupacion'];
        }else{
            $this->existe=false;
        }

    }
    function update($nombre,$texto){
        mysql_query("update tipo_agrupacion set 
                  nombre_tipo_agrupacion=upper('$nombre'),
                  texto_tipo_agrupacion=upper('$texto') 
                  where id_tipo_agrupacion='".$this->id."'");
    }
    function delete(){
        mysql_query("delete from tipo_agrupacion where id_tipo_agrupacion='".$this->id."'")or die(false);
        return true;
    }
}

?>
