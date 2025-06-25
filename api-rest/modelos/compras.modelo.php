<?php
require_once "conexion.php";

class ModeloCompras {

    static public function guardarCompra($tabla, $idCliente, $idCurso) {
        $stmt = Conexion::conectar()->prepare("INSERT INTO $tabla (id_cliente, id_curso) VALUES (:id_cliente, :id_curso)");
        $stmt->bindParam(":id_cliente", $idCliente, PDO::PARAM_INT);
        $stmt->bindParam(":id_curso", $idCurso, PDO::PARAM_INT);
        return $stmt->execute() ? "ok" : "error";
    }

    static public function obtenerComprasCliente($tabla, $idCliente) {
        $stmt = Conexion::conectar()->prepare("
            SELECT compras.*, cursos.titulo, cursos.descripcion, cursos.precio 
            FROM $tabla AS compras 
            INNER JOIN cursos ON compras.id_curso = cursos.id 
            WHERE compras.id_cliente = :id_cliente
        ");
        $stmt->bindParam(":id_cliente", $idCliente, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
