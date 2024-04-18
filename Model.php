<?php
include "utils.php";
include "config.php";

class ProductoModelo {
    private $dbConn;

    const TABLE = 'productos';
    const ID_FIELD = 'codprod';

    public function __construct() {
        $this->dbConn = connect($GLOBALS['db']);
    }

    public function buscar($id) { // busqueda por id
        $stmt = $this->dbConn->prepare("SELECT * FROM " . self::TABLE . " WHERE " . self::ID_FIELD . " = :id");
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAll() { // listado de productos
        $stmt = $this->dbConn->prepare("SELECT * FROM " . self::TABLE);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////
    public function getPaginaProducto($limit, $offset) { // lista la página x
        $stmt = $this->dbConn->prepare("SELECT p.*, c.nombre AS nombre_categoria
                                        FROM " . self::TABLE . " p
                                        JOIN categoria c ON p.categoria_id = c.id
                                        ORDER BY c.nombre
                                        LIMIT :limit OFFSET :offset");
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getTotalProductoCount() { // da la cantidad total de productos
        $stmt = $this->dbConn->prepare("SELECT COUNT(*) FROM " . self::TABLE);
        $stmt->execute();
        return $stmt->fetchColumn();
    }
    
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////

    // crear un producto
    public function create($data) {
        $sql = "INSERT INTO " . self::TABLE . " (nombre, categoria_id, pvp, stock, imagen, observaciones) VALUES (:nombre, :categoria_id, :pvp, :stock, :imagen, :observaciones)";
        $stmt = $this->dbConn->prepare($sql);
        $stmt = bindAllValues($stmt, $data);

        $stmt->execute();
        return $this->dbConn->lastInsertId();
    }

    //actualizar / editar
    public function editar($data) {
        $sql = "UPDATE " . self::TABLE . " SET nombre = :nombre, categoria_id = :categoria_id, pvp = :pvp, stock = :stock, imagen = :imagen, observaciones = :observaciones WHERE " . self::ID_FIELD . " = :id";
        $stmt = $this->dbConn->prepare($sql);
        $stmt = bindAllValues($stmt, $data);

        $stmt->execute();
        return $stmt->rowCount();
    }

    //borrar
    public function borrar($id) {
        $stmt = $this->dbConn->prepare("DELETE FROM " . self::TABLE . " WHERE " . self::ID_FIELD . " = :id");
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        return $stmt->rowCount();
    }
}
