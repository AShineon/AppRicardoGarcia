<?php
require 'DB.php';

$categorias = $mysqli->query("SELECT * FROM categoria ORDER BY nombre");

?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8" />
<title>Productos por Categoría</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body class="p-3">
<div class="container">
    <h1>Productos por Categoría</h1>
    <a href="agregar_producto.php" class="btn btn-success mb-3">Agregar Producto</a>

    <?php while($cat = $categorias->fetch_assoc()): ?>
        <h3><?= htmlspecialchars($cat['nombre']) ?></h3>
        <p><?= htmlspecialchars($cat['descripcion']) ?></p>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Descripción</th>
                    <th>Precio Base</th>
                    <th>Proveedor</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $cat_id = (int)$cat['categoria_id'];
            $productos = $mysqli->query("SELECT p.*, pr.nombre as proveedor_nombre 
                                         FROM producto p 
                                         LEFT JOIN proveedor pr ON p.proveedor_id = pr.proveedor_id
                                         WHERE categoria_id = $cat_id");
            if ($productos->num_rows == 0) {
                echo "<tr><td colspan='5'>No hay productos en esta categoría</td></tr>";
            } else {
                while($prod = $productos->fetch_assoc()):
            ?>
                <tr>
                    <td><?= htmlspecialchars($prod['nombre']) ?></td>
                    <td><?= htmlspecialchars($prod['descripcion']) ?></td>
                    <td>$<?= number_format($prod['precio_base'], 2) ?></td>
                    <td><?= htmlspecialchars($prod['proveedor_nombre']) ?></td>
                    <td>
                        <a href="editar_producto.php?id=<?= $prod['producto_id'] ?>" class="btn btn-primary btn-sm">Editar</a>
                        <a href="eliminar                                                                                                                                                                                                               .php?id=<?= $prod['producto_id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de eliminar este producto?')">Eliminar</a>
                    </td>
                </tr>
            <?php
                endwhile;
            }
            ?>
            </tbody>
        </table>
    <?php endwhile; ?>
</div>
</body>
</html>
