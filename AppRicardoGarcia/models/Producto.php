<?php
class Producto {
    private $db;

    public function __construct($dbConnection) {
        $this->db = $dbConnection;
    }

    // Obtener todos los productos
    public function obtenerTodos() {
        $query = "SELECT * FROM productos";
        $stmt = $this->db->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Agregar un producto nuevo
    public function agregar($nombre, $precio, $stock) {
        $query = "INSERT INTO productos (nombre, precio, stock) VALUES (?, ?, ?)";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$nombre, $precio, $stock]);
    }

    // Obtener un producto por ID
    public function obtenerPorId($id) {
        $query = "SELECT * FROM productos WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Actualizar producto
    public function actualizar($id, $nombre, $precio, $stock) {
        $query = "UPDATE productos SET nombre = ?, precio = ?, stock = ? WHERE id = ?";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$nombre, $precio, $stock, $id]);
    }

    // Eliminar producto
    public function eliminar($id) {
        $query = "DELETE FROM productos WHERE id = ?";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$id]);
    }
}
?>
