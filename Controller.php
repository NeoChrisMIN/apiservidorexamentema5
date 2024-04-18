<?php
include "Model.php";

class Controller {
    private $model;

    public function __construct() {
        $this->model = new ProductoModelo();
    }

    //Busqueda por Ip
    public function getProducto($id) {
        $result = $this->model->buscar($id);
        if ($result) {
            header("HTTP/1.1 200 OK");
            echo json_encode($result);
        } else {
            header("HTTP/1.1 404 Not Found");
            echo json_encode(['message' => 'No encontrado']);
        }
        exit();
    }

    //listado
    public function getAllTasks() {
        $result = $this->model->getAll();
        header("HTTP/1.1 200 OK");
        echo json_encode($result);
        exit();
    }

    // Listado paginado de productos
    public function getProductosPaginados($pagina, $porPagina) {
        $offset = ($pagina - 1) * $porPagina;
        $productos = $this->model->getPaginaProducto($porPagina, $offset);
        $totalProductos = $this->model->getTotalProductoCount();

        $resultado = [
            'pagina' => $pagina,
            'por_pagina' => $porPagina,
            'total_paginas' => ceil($totalProductos / $porPagina),
            'total_productos' => $totalProductos,
            'productos' => $productos
        ];

        header("HTTP/1.1 200 OK");
        echo json_encode($resultado);
        exit();
    }

/////////////////////////////////////////////////////////////////////////////////////////////////////

    // Crear un nuevo producto
    public function crearProducto() {
        $data = json_decode(file_get_contents('php://input'), true);
        $result = $this->model->create($data);
        if ($result) {
            header("HTTP/1.1 201 Created");
            echo json_encode(['id' => $result]);
        } else {
            header("HTTP/1.1 400 Bad Request");
            echo json_encode(['message' => 'no funciono']);
        }
        exit();
    }

////////////////////////////////////////////////////////////////////////////////////

    // actualizar/editar
    public function editarProducto($id) {
        $data = json_decode(file_get_contents('php://input'), true);
        $data['id'] = $id;
        $result = $this->model->editar($data);
        if ($result) {
            header("HTTP/1.1 200 OK");
            echo json_encode(['message' => 'Producto editado']);
        } else {
            header("HTTP/1.1 400 Bad Request");
            echo json_encode(['message' => 'no funciono']);
        }
        exit();
    }

////////////////////////////////////////////////////////////////////////////////////

    // Borrar
    public function borrarProducto($id) {
        $result = $this->model->borrar($id);
        if ($result) {
            header("HTTP/1.1 200 OK");
            echo json_encode(['message' => 'Borrado']);
        } else {
            header("HTTP/1.1 404 Not Found");
            echo json_encode(['message' => 'no funciono']);
        }
        exit();
    }
}
