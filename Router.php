<?php
//listado paginado y ordenada por categoria en orden creciente
//buscar por id

//agregar

//edicion

//borrado

include "Controller.php";

header("Content-Type: application/json");

// Parseamos la URL para obtener la ruta y el método HTTP.
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode('/', $uri);

$method = $_SERVER['REQUEST_METHOD'];

// Instancia del controlador.
$controller = new Controller();

// Enrutamiento básico según el método HTTP y la presencia del ID de tarea.
switch ($method) {
    case 'GET':
        $productoId = sacarId($uri);
        //buscar por id
        if ($productoId) { // http://localhost/api/producto/1
            $controller->getProducto($productoId);

        //listado paginado y ordenada por categoria en orden creciente
        }if (isset($_GET['page']) && isset($_GET['perPage'])) { // http://localhost/api/producto?page=2&perPage=2
            $page = (int) $_GET['page'];
            $perPage = (int) $_GET['perPage'];
            $controller->getProductosPaginados($page, $perPage);

        }
        // Listar todo
        else { // http://localhost/api/producto
            $controller->getAllTasks(); 
        }
        break;

    case 'POST':
        // Para usarlo la url sera: http://localhost/api/producto
        // y en body, raw, lo enviaremos con formato json
        /*
        {
            "nombre": "Naranja",
            "categoria_id": 1,
            "pvp": 1,
            "stock": 16,
            "imagen": "images/naranja.png",
            "observaciones": "Fruta mu rica, de los campos de mi abuelo."
        }
        */
        $controller->crearProducto();
        break;

    case 'PUT':
        // http://localhost/api/producto
        // y luego un raw
        $taskId = sacarId($uri);
        $controller->editarProducto($taskId);
        break;

    case 'DELETE':
        // http://localhost/api/producto/5
        $taskId = sacarId($uri);
        $controller->borrarProducto($taskId);
        break;

    default:
        header("HTTP/1.1 405 Method Not Allowed");
        echo json_encode(['message' => 'El Método no esta permitido o no existe']);
        break;
}

?>
