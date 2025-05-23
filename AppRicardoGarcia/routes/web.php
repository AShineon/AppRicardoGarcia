<?php
// Conexión a la base de datos
require_once __DIR__ . '/../config/database.php';

// Controladores disponibles
require_once __DIR__ . '/../controllers/ProductosController.php';

// Obtener ruta desde la URL
$ruta = $_GET['r'] ?? 'productos'; // si no hay parámetro, por defecto va a 'productos'

// Instanciar controlador
$productosController = new ProductosController($db);

// Ruteo básico
switch ($ruta) {
    case 'productos':
        $productosController->index();
        break;

    case 'productos_crear':
        $productosController->crear();
        break;

    case 'productos_guardar':
        $productosController->guardar($_POST);
        break;

    case 'productos_editar':
        $productosController->editar($_GET['id']);
        break;

    case 'productos_actualizar':
        $productosController->actualizar($_POST);
        break;

    case 'productos_eliminar':
        $productosController->eliminar($_GET['id']);
        break;

    default:
        echo "Ruta no válida: " . htmlspecialchars($ruta);
        break;
}
