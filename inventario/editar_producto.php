<?php
require 'DB.php';

if (!isset($_GET['id'])) {
    header('Location: index.php');
    exit;
}

$id = intval($_GET['id']);

$producto = $mysqli->query("SELECT * FROM producto WHERE producto_id = $id")->fetch_assoc();
if (!$producto) {
    die("Producto no encontrado.");
}

$categorias = $mysqli->query("SELECT * FROM categoria ORDER BY nombre");
$proveedores = $mysqli->query("SELECT * FROM proveedor ORDER BY nombre");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $mysqli->real_escape_string($_POST['nombre']);
    $descripcion = $mysqli->real_escape_string($_POST['descripcion']);
    $precio_base = floatval($_POST['precio_base']);
    $categoria_id = intval($_POST['categoria_id']);
    $proveedor_id = intval($_POST['proveedor_id']);

    $query = "UPDATE producto SET 
                nombre = '$nombre',
                descripcion = '$descripcion',
                precio_base = $precio_base,
                categoria_id = $categoria_id,
                proveedor_id = $proveedor_id
              WHERE producto_id = $id";

    if ($mysqli->query($query)) {
        header('Location: index.php');
        exit;
    } else {
        $error = "Error al actualizar el producto: " . $mysqli->error;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8" />
<title>Editar Producto</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body class="p-3">
<div class="container">
    <h1>Editar Producto</h1>
    <?php if(!empty($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <form method="post">
        <div class="mb-3">
            <label>Nombre</label>
            <input type="text" name="nombre" class="form-control" value="<?= htmlspecialchars($producto['nombre']) ?>" required />
        </div>
        <div class="mb-3">
            <label>Descripción</label>
            <input type="text" name="descripcion" class="form-control" value="<?= htmlspecialchars($producto['descripcion']) ?>" />
        </div>
        <div class="mb-3">
            <label>Precio Base</label>
            <input type="number" step="0.01" name="precio_base" class="form-control" value="<?= $producto['precio_base'] ?>" required />
        </div>
        <div class="mb-3">
            <label>Categoría</label>
            <select name="categoria_id" class="form-select" required>
                <?php while($cat = $categorias->fetch_assoc()): ?>
                    <option value="<?= $cat['categoria_id'] ?>" <?= ($cat['categoria_id'] == $producto['categoria_id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($cat['nombre']) ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="mb-3">
            <label>Proveedor</label>
            <select name="proveedor_id" class="form-select" required>
                <?php while($prov = $proveedores->fetch_assoc()): ?>
                    <option value="<?= $prov['proveedor_id'] ?>" <?= ($prov['proveedor_id'] == $producto['proveedor_id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($prov['nombre']) ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Actualizar</button>
        <a href="index.php" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
</body>
</html>
