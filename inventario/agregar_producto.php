<?php
require 'DB.php';

$categorias = $mysqli->query("SELECT * FROM categoria ORDER BY nombre");
$proveedores = $mysqli->query("SELECT * FROM proveedor ORDER BY nombre");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $mysqli->real_escape_string($_POST['nombre']);
    $descripcion = $mysqli->real_escape_string($_POST['descripcion']);
    $precio_base = floatval($_POST['precio_base']);
    $categoria_id = intval($_POST['categoria_id']);
    $proveedor_id = intval($_POST['proveedor_id']);

    $query = "INSERT INTO producto (nombre, descripcion, precio_base, categoria_id, proveedor_id)
              VALUES ('$nombre', '$descripcion', $precio_base, $categoria_id, $proveedor_id)";
    
    if ($mysqli->query($query)) {
        header('Location: index.php');
        exit;
    } else {
        $error = "Error al guardar el producto: " . $mysqli->error;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8" />
<title>Agregar Producto</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body class="p-3">
<div class="container">
    <h1>Agregar Producto</h1>
    <?php if(!empty($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <form method="post">
        <div class="mb-3">
            <label>Nombre</label>
            <input type="text" name="nombre" class="form-control" required />
        </div>
        <div class="mb-3">
            <label>Descripción</label>
            <input type="text" name="descripcion" class="form-control" />
        </div>
        <div class="mb-3">
            <label>Precio Base</label>
            <input type="number" step="0.01" name="precio_base" class="form-control" required />
        </div>
        <div class="mb-3">
            <label>Categoría</label>
            <select name="categoria_id" class="form-select" required>
                <option value="">Selecciona una categoría</option>
                <?php while($cat = $categorias->fetch_assoc()): ?>
                    <option value="<?= $cat['categoria_id'] ?>"><?= htmlspecialchars($cat['nombre']) ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="mb-3">
            <label>Proveedor</label>
            <select name="proveedor_id" class="form-select" required>
                <option value="">Selecciona un proveedor</option>
                <?php while($prov = $proveedores->fetch_assoc()): ?>
                    <option value="<?= $prov['proveedor_id'] ?>"><?= htmlspecialchars($prov['nombre']) ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <button type="submit" class="btn btn-success">Guardar</button>
        <a href="index.php" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
</body>
</html>
