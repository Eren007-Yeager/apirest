<?php
require_once "modelos/carrito.modelo.php";

class ControladorCarrito {

    public function create($datos) {
        $respuesta = ModeloCarrito::agregarAlCarrito("carrito_cursos", $datos);
        echo json_encode(["status" => 200, "detalle" => $respuesta]);
    }

    public function obtenerPorCliente($idCliente) {
        $carrito = ModeloCarrito::obtenerCarritoPorCliente("carrito_cursos", $idCliente);
        echo json_encode($carrito);
    }

    public function eliminar($idCarrito) {
        $respuesta = ModeloCarrito::eliminarDelCarrito("carrito_cursos", $idCarrito);
        echo json_encode(["status" => 200, "detalle" => $respuesta]);
    }

     public function vaciarCarrito($idCliente) {
        $respuesta = ModeloCarrito::vaciarCarritoCliente("carrito_cursos", $idCliente);
        if($respuesta) {
            echo json_encode(["status" => 200, "detalle" => "Carrito vaciado con éxito"]);
        } else {
            echo json_encode(["status" => 500, "detalle" => "Error al vaciar carrito"]);
        }
    }
}
