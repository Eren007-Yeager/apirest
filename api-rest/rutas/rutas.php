<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Authorization, Content-Type, id_cliente, llave_secreta");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Content-Type: application/json; charset=UTF-8");

// Manejar solicitudes preflight de Angular (OPTIONS)
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}
$ruta = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$arrayRutas = explode("/", trim($ruta, "/"));

// Verificar si llega 'pagina' como query string y es numérico
if (isset($_GET['pagina']) && is_numeric($_GET['pagina'])) {
    $pagina = (int) $_GET['pagina'];

    $cursos = new ControladorCursos();
    $cursos->index($pagina);
    return; // Termina para no seguir procesando rutas
}

// Si la ruta es vacía o sólo tiene un segmento 'api-rest'
if (count($arrayRutas) == 0 || (count($arrayRutas) == 1 && $arrayRutas[0] === 'api-rest')) {
    $json = array(
        "detalle" => "No encontrado"
    );
    echo json_encode($json, true);
    return;
}

// Cuando la URI es del tipo /api-rest/cursos
if (count($arrayRutas) == 2 && $arrayRutas[0] == "api-rest" && $arrayRutas[1] == "cursos") {
    $cursos = new ControladorCursos();

    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        $cursos->index(null);
        return;

    } elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Leer el contenido JSON
        $input = file_get_contents("php://input");
        $datos = json_decode($input, true);

        if (!$datos) {
            echo json_encode([
                "status" => 400,
                "detalle" => "JSON inválido"
            ]);
            return;
        }

        // Validar campos requeridos
        $camposRequeridos = ['titulo', 'descripcion', 'instructor', 'imagen', 'precio'];
        foreach ($camposRequeridos as $campo) {
            if (empty($datos[$campo])) {
                echo json_encode([
                    "status" => 400,
                    "detalle" => "El campo '$campo' es obligatorio"
                ]);
                return;
            }
        }

        $cursos->create($datos);
        return;
    }
}
if (count($arrayRutas) == 3 && $arrayRutas[0] == "api-rest" && $arrayRutas[1] == "cursos" && $arrayRutas[2] == "catalogo") {
    $cursos = new ControladorCursos();

    if ($_SERVER["REQUEST_METHOD"] == "GET") {
        $cursos->catalogoCliente();
        return;
    }
}

// Cuando la URI es del tipo /api-rest/cursos/{id}
if (count($arrayRutas) == 3 && $arrayRutas[0] == "api-rest" && $arrayRutas[1] == "cursos") {
    $id = $arrayRutas[2];
    $cursos = new ControladorCursos();

    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        $cursos->show($id);
        return;

    } elseif ($_SERVER['REQUEST_METHOD'] == 'PUT') {
        // Leer contenido JSON para PUT
        $input = file_get_contents("php://input");
        $putData = json_decode($input, true);

        if (!$putData) {
            echo json_encode([
                "status" => 400,
                "detalle" => "JSON inválido"
            ]);
            return;
        }

        // Opcional: validar campos si quieres asegurarte que no estén vacíos
        // por ejemplo, si el PUT debe enviar todos los campos:
        /*
        $camposRequeridos = ['titulo', 'descripcion', 'instructor', 'imagen', 'precio'];
        foreach ($camposRequeridos as $campo) {
            if (!isset($putData[$campo]) || empty($putData[$campo])) {
                echo json_encode([
                    "status" => 400,
                    "detalle" => "El campo '$campo' es obligatorio"
                ]);
                return;
            }
        }
        */

        $cursos->update($id, $putData);
        return;

    } elseif ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
        $cursos->delete($id);
        return;
    }
}

// Cuando la URI es del tipo /api-rest/clientes
if (count($arrayRutas) == 2 && $arrayRutas[0] == "api-rest" && $arrayRutas[1] == "clientes") {
    $clientes = new ControladorClientes();

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Leer contenido JSON
        $input = file_get_contents("php://input");
        $datos = json_decode($input, true);

        if (!$datos) {
            echo json_encode([
                "status" => 400,
                "detalle" => "JSON inválido"
            ]);
            return;
        }

        // Validar campos requeridos
        $camposRequeridos = ['nombre', 'apellido', 'email'];
        foreach ($camposRequeridos as $campo) {
            if (empty($datos[$campo])) {
                echo json_encode([
                    "status" => 400,
                    "detalle" => "El campo '$campo' es obligatorio"
                ]);
                return;
            }
        }

        $clientes->create($datos);
        return;
    }

    // Si más adelante quieres listar todos los clientes con GET:
    // if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    //     $clientes->index();
    //     return;
    // }
}

// === RUTAS PARA CARRITO ===

// Agregar al carrito
if (count($arrayRutas) == 2 && $arrayRutas[0] == "api-rest" && $arrayRutas[1] == "carrito") {
    $carrito = new ControladorCarrito();

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $input = file_get_contents("php://input");
        $datos = json_decode($input, true);

        if (!$datos) {
            echo json_encode(["status" => 400, "detalle" => "JSON inválido"]);
            return;
        }

        $carrito->create($datos);
        return;
    }
}

// Obtener carrito por cliente
if (count($arrayRutas) == 3 && $arrayRutas[0] == "api-rest" && $arrayRutas[1] == "carrito") {
    $carrito = new ControladorCarrito();
    $idCliente = $arrayRutas[2];

    if ($_SERVER["REQUEST_METHOD"] == "GET") {
        $carrito->obtenerPorCliente($idCliente);
        return;
    }
}

// Eliminar un producto del carrito por ID del carrito con DELETE y ruta /carrito/{id}
if (count($arrayRutas) == 3 && $arrayRutas[0] == "api-rest" && $arrayRutas[1] == "carrito") {
    $carrito = new ControladorCarrito();
    $idCarrito = $arrayRutas[2];

    if ($_SERVER["REQUEST_METHOD"] == "DELETE") {
        $carrito->eliminar($idCarrito);
        return;
    }
}


// === RUTA PARA SIMULAR COMPRA ===
if (count($arrayRutas) == 3 && $arrayRutas[0] == "api-rest" && $arrayRutas[1] == "pago") {
    $compras = new ControladorCompras();
    $idCliente = $arrayRutas[2];

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $compras->simularPago($idCliente);
        return;
    }
}

// Vaciar carrito completo de un cliente
if (count($arrayRutas) == 4 && $arrayRutas[0] == "api-rest" && $arrayRutas[1] == "carrito" && $arrayRutas[2] == "vaciar") {
    $carrito = new ControladorCarrito();
    $idCliente = $arrayRutas[3];

    if ($_SERVER["REQUEST_METHOD"] == "DELETE") {
        $carrito->vaciarCarrito($idCliente);
        return;
    }
}

// Simular compra
if (count($arrayRutas) == 3 && $arrayRutas[0] == "api-rest" && $arrayRutas[1] == "pago") {
    $compras = new ControladorCompras();
    $idCliente = $arrayRutas[2];

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $compras->simularPago($idCliente);
        return;
    }
}

// Obtener compras por cliente
if (count($arrayRutas) == 3 && $arrayRutas[0] == "api-rest" && $arrayRutas[1] == "compras") {
    $compras = new ControladorCompras();
    $idCliente = $arrayRutas[2];

    if ($_SERVER["REQUEST_METHOD"] == "GET") {
        $compras->obtenerComprasPorCliente($idCliente);
        return;
    }
}


?>