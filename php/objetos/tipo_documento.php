<?php
class tipo_documento {
    public $id;
    public $nombre_tipo,$texto_tipo;
    public $existe,$fecha_creacion;

    function __construct($id){
        $this->id= $id;
        $sql = "select * from tipo_documento WHERE id_tipo_doc='$id' limit 1";
        $row = mysql_fetch_array(mysql_query($sql));
        if($row){
            $this->existe=true;
            $this->nombre_tipo = $row['nombre_tipo_doc'];
            $this->texto_tipo = $row['texto_tipo_doc'];
            $this->fecha_creacion = $row['fecha_creacion'];
        }else{
            $this->existe=false;
        }

    }
    function update($nombre,$texto){
        mysql_query("update tipo_documento set 
                  nombre_tipo_doc=upper('$nombre'),
                  texto_tipo_doc=upper('$texto') 
                  where id_tipo_doc='".$this->id."'");
    }
    function delete(){
        mysql_query("delete from tipo_documento where id_tipo_doc='".$this->id."'")or die(false);
        return true;
    }
}

?>
