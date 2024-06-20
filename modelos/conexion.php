<?php

class Conexion{

    static public function conectar(){

        $link = new PDO('mysql:host=localhost;dbname=wendysfood', 
                        'root', 
                        '');
        
        $link -> exec("set names utf8");
        
        $link -> exec("SET time_zone = '-05:00'");

        return $link;

    }
    
}

