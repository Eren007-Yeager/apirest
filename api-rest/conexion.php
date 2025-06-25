 <?php

 class Conexion{
    static public function conectar(){
        $link= new PDO("mysql:host=localhost;dbname=apirest","admin", "54321");
        $link->exec("set names utf8");/*config de php*/


        return $link;
    }

 }