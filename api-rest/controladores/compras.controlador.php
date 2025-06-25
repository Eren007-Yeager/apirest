<?php
require_once "modelos/compras.modelo.php";
require_once "modelos/carrito.modelo.php";

class ControladorCompras {

    public function simularPago($idCliente) {
        // OBTENER CURSOS DEL CARRITO
        $carrito = ModeloCarrito::obtenerCarritoPorCliente("carrito_cursos", $idCliente);

        if (empty($carrito)) {
            echo json_encode(["status" => 400, "detalle" => "Carrito vacío"]);
            return;
        }

        // GUARDAR CADA COMPRA
        foreach ($carrito as $curso) {
            ModeloCompras::guardarCompra("compras_cursos", $idCliente, $curso["id"]);
        }

        // VACIAR CARRITO
        ModeloCarrito::vaciarCarritoCliente("carrito_cursos", $idCliente);

        echo json_encode(["status" => 200, "detalle" => "Compra realizada con éxito"]);
    }
}
