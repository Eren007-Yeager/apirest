<?php
require_once "conexion.php";

class ModeloCarrito {

    static public function agregarAlCarrito($tabla, $datos) {
        $stmt = Conexion::conectar()->prepare("INSERT INTO $tabla (id_cliente, id_curso) VALUES (:id_cliente, :id_curso)");
        $stmt->bindParam(":id_cliente", $datos["id_cliente"], PDO::PARAM_INT);
        $stmt->bindParam(":id_curso", $datos["id_curso"], PDO::PARAM_INT);
        return $stmt->execute() ? "ok" : "error";
    }

    static public function obtenerCarritoPorCliente($tabla, $idCliente) {
        $stmt = Conexion::conectar()->prepare("
            SELECT carrito.id, cursos.* FROM $tabla AS carrito
            INNER JOIN cursos ON carrito.id_curso = cursos.id
            WHERE carrito.id_cliente = :id_cliente
        ");
        $stmt->bindParam(":id_cliente", $idCliente, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    static public function eliminarDelCarrito($tabla, $idCarrito) {
        $stmt = Conexion::conectar()->prepare("DELETE FROM $tabla WHERE id = :id");
        $stmt->bindParam(":id", $idCarrito, PDO::PARAM_INT);
        return $stmt->execute() ? "ok" : "error";
    }

    static public function vaciarCarritoCliente($tabla, $idCliente) {
        $stmt = Conexion::conectar()->prepare("DELETE FROM $tabla WHERE id_cliente = :id_cliente");
        $stmt->bindParam(":id_cliente", $idCliente, PDO::PARAM_INT);
        return $stmt->execute();
    }

    
}   
