<?php
require_once __DIR__ . '/../models/Producto.php';
require_once __DIR__ . '/../config/database.php';

class ProductosController {
    private $productoModel;

    public function __construct($db) {
        $this->productoModel = new Producto($db);
    }

    // Mostrar todos los productos
    public function index() {
        $productos = $this->productoModel->obtenerTodos();
        require_once __DIR__ . '/../views/productos/index.php';
    }

    // Mostrar formulario de creación
    public function crear() {
        require_once __DIR__ . '/../views/productos/crear.php';
    }

    // Guardar producto nuevo
    public function guardar($datos) {
        $this->productoModel->agregar($datos['nombre'], $datos['precio'], $datos['stock']);
        header("Location: index.php?r=productos");
    }

    // Mostrar formulario para editar producto
    public function editar($id) {
        $producto = $this->productoModel->obtenerPorId($id);
        require_once __DIR__ . '/../views/productos/editar.php';
    }

    // Actualizar producto
    public function actualizar($datos) {
        $this->productoModel->actualizar($datos['id'], $datos['nombre'], $datos['precio'], $datos['stock']);
        header("Location: index.php?r=productos");
    }

    // Eliminar producto
    public function eliminar($id) {
        $this->productoModel->eliminar($id);
        header("Location: index.php?r=productos");
    }
}
?>
