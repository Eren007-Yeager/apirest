<?php 

require_once "controladores/rutas.controlador.php";
require_once "controladores/cursos.controlador.php";
require_once "controladores/clientes.controlador.php";
require_once "controladores/carrito.controlador.php";
require_once "controladores/compras.controlador.php";
require_once "modelos/carrito.modelo.php";
require_once "modelos/clientes.modelo.php";
require_once "modelos/cursos.modelo.php";
require_once "modelos/compras.modelo.php";

$rutas= new ControladorRutas();
$rutas->inicio();




?>



