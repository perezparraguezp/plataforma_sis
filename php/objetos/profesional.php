<?php

/**
 * Created by PhpStorm.
 * User: iPapo
 * Date: 07-10-18
 * Time: 22:41
 */
class profesional{
    public $rut,$nombre,$telefono,$direccion,$email;
    public $tipo_profesional,$existe,$id_profesional;
    public $vigencia,$clave;
    public $fecha_termino,$fecha_inicio;



    function __construct($id){
        $sql = "select * from usuarios 
                inner join persona using(rut)
                where usuarios.id_profesional='$id' limit 1";

        $row = mysql_fetch_array(mysql_query($sql));
        if($row){
            $this->id_profesional = $row['id_profesional'];
            $this->nombre = $row['nombre_completo'];
            $this->rut = $row['rut'];
            $this->telefono = $row['telefono'];
            $this->email = $row['email'];
            $this->direccion = $row['direccion'];
            $this->tipo_profesional = $row['tipo_usuario'];
            $this->clave = $row['clave'];
            $this->fecha_inicio = $row['fecha_inicio'];
            $this->fecha_termino = $row['fecha_termino'];
            $this->existe = true;
            $this->updateVigencia();
        }else{
            $this->existe = false;
        }
    }
    function updateVigencia(){
        $sql = "select * from personal_establecimiento where id_profesional='$this->id_profesional' limit 1";
        $row = mysql_fetch_array(mysql_query($sql));
        if($row){
            $indefinido = $row['indefinido'];
            if($indefinido=='SI'){
                $this->vigencia = 'INDEFINIDO';
            }else{
                $this->vigencia = fechaNormal($row['fecha_termino']);
            }

        }else{
            $this->vigencia = 'NO EXISTE';
        }
    }
    function updateClave($clave){
        $sql2 = "update usuarios set clave=upper('$clave')  
                      where id_profesional='$this->id_profesional'  limit 1";
        mysql_query($sql2);
    }



}