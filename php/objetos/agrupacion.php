<?php

/**
 * Created by PhpStorm.
 * User: iPapo
 * Date: 07-10-18
 * Time: 22:13
 */
class agrupacion{
    public $id;
    public $estado,$desde,$hasta;
    public $presidente,$tesorero,$secretario;
    public $existe,$nombre_agrupacion;

    function __construct($id){
        $this->id = $id;
        $sql = "select * from agrupacion_escolar 
                inner join tipo_agrupacion using(id_tipo_agrupacion) 
                where id_agrupacion='$id' limit 1";

        $row = mysql_fetch_array(mysql_query($sql));
        if($row){
            $this->estado = $row['estado_agrupacion'];
            $this->desde = $row['fecha_desde'];
            $this->hasta = $row['fecha_hasta'];
            $this->nombre_agrupacion = $row['nombre_tipo_agrupacion'];

            $this->presidente = $row['presidente'];
            $this->tesorero = $row['tesorero'];
            $this->secretario = $row['secretaria'];

            $this->validar_estado();

            $this->existe = true;
        }else{
            $this->existe = false;
        }
    }
    function validar_estado(){
        if($this->hasta > date('Y-m-d')){
            $estado = 'VIGENTE';
        }else{
            $estado = 'NO VIGENTE';
        }
        $this->estado = $estado;
        $this->update('estado_agrupacion',$estado);
    }
    function update($col,$val){
        $sql = "update agrupacion_escolar set 
                    $col='$val' 
                    where id_agrupacion='".$this->id."'";
        mysql_query($sql);
    }

}