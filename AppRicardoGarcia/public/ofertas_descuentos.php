<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: index.html");
    exit();
}

require_once "../config/database.php";

// Insertar oferta
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'agregar') {
    $producto_id = $_POST['producto_id'];
    $porcentaje_descuento = floatval($_POST['porcentaje_descuento']);
    $fecha_inicio = $_POST['fecha_inicio'];
    $fecha_fin = $_POST['fecha_fin'];
    $activa = isset($_POST['activa']) ? 1 : 0;

    $stmt = $conn->prepare("INSERT INTO Oferta (
                producto_id, 
                porcentaje_descuento, 
                fecha_inicio, 
                fecha_fin, 
                activa
            ) VALUES (?, ?, ?, ?, ?)");
    
    $stmt->bind_param("idssi", 
        $producto_id, 
        $porcentaje_descuento,
        $fecha_inicio,
        $fecha_fin,
        $activa
    );
    
    if ($stmt->execute()) {
        header("Location: ofertas_descuentos.php?exito=1");
        exit();
    } else {
        $error = "Error al crear la oferta: " . $stmt->error;
    }
}

// Cambiar estado de oferta
if (isset($_GET['cambiar_estado'])) {
    $oferta_id = $_GET['cambiar_estado'];
    $nuevo_estado = $_GET['estado'];
    
    $stmt = $conn->prepare("UPDATE Oferta SET activa = ? WHERE oferta_id = ?");
    $stmt->bind_param("ii", $nuevo_estado, $oferta_id);
    $stmt->execute();
    
    header("Location: ofertas_descuentos.php");
    exit();
}

// Eliminar oferta
if (isset($_GET['eliminar'])) {
    $id = $_GET['eliminar'];
    $conn->query("DELETE FROM Oferta WHERE oferta_id = $id");
    header("Location: ofertas_descuentos.php");
    exit();
}

// Obtener todas las ofertas con información del producto
$ofertas = $conn->query("
    SELECT o.*, p.nombre AS producto_nombre
    FROM Oferta o
    JOIN Producto p ON o.producto_id = p.producto_id
    ORDER BY o.fecha_inicio DESC
");

// Obtener productos para el dropdown
$productos = $conn->query("SELECT producto_id, nombre FROM Producto ORDER BY nombre");
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Ofertas y Descuentos</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f4f6f9;
      padding: 30px;
    }
    .header-container {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 20px;
    }
    h2 {
      margin-bottom: 0;
    }
    .btn-volver {
      background-color: #3498db;
      color: white;
      padding: 8px 15px;
      border-radius: 5px;
      text-decoration: none;
      font-weight: bold;
      transition: background-color 0.3s;
    }
    .btn-volver:hover {
      background-color: #2980b9;
    }
    table {
      border-collapse: collapse;
      width: 100%;
      margin-bottom: 40px;
    }
    th, td {
      border: 1px solid #ccc;
      padding: 12px;
      text-align: left;
    }
    th {
      background-color: #2c3e50;
      color: white;
    }
    form {
      background-color: white;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
      max-width: 700px;
      margin-bottom: 30px;
    }
    input, select, textarea {
      width: 100%;
      padding: 8px;
      margin-top: 6px;
      margin-bottom: 12px;
      border: 1px solid #ccc;
      border-radius: 6px;
    }
    button {
      background-color: #2980b9;
      color: white;
      border: none;
      padding: 10px 15px;
      border-radius: 5px;
      cursor: pointer;
      margin-right: 10px;
    }
    button:hover {
      background-color: #1c5e83;
    }
    .acciones a {
      margin-right: 10px;
    }
    .oferta-activa { background-color: #d4edda; }
    .oferta-inactiva { background-color: #f8d7da; }
    .exito {
      background-color: #d4edda;
      color: #155724;
      padding: 10px;
      border-radius: 5px;
      margin-bottom: 20px;
    }
    .error {
      background-color: #f8d7da;
      color: #721c24;
      padding: 10px;
      border-radius: 5px;
      margin-bottom: 20px;
    }
    .form-row {
      display: flex;
      gap: 20px;
    }
    .form-group {
      flex: 1;
    }
    input[type="checkbox"] {
      width: auto;
      margin-left: 10px;
    }
    .checkbox-label {
      display: flex;
      align-items: center;
    }
  </style>
</head>
<body>
  <div class="header-container">
    <h2>Ofertas y Descuentos</h2>
    <a href="panel.php" class="btn-volver">Volver al Panel</a>
  </div>

  <?php if (isset($_GET['exito'])): ?>
    <div class="exito">Oferta creada exitosamente!</div>
  <?php endif; ?>

  <?php if (isset($error)): ?>
    <div class="error"><?= $error ?></div>
  <?php endif; ?>

  <!-- Formulario para nueva oferta -->
  <form method="POST">
    <h3>Crear Nueva Oferta</h3>
    <input type="hidden" name="accion" value="agregar">
    
    <div class="form-row">
      <div class="form-group">
        <label>Producto</label>
        <select name="producto_id" required>
          <option value="">Seleccionar producto</option>
          <?php while($p = $productos->fetch_assoc()): ?>
            <option value="<?= $p['producto_id'] ?>"><?= htmlspecialchars($p['nombre']) ?></option>
          <?php endwhile; ?>
        </select>
      </div>
      
      <div class="form-group">
        <label>Porcentaje de Descuento</label>
        <input type="number" name="porcentaje_descuento" step="0.01" min="0.01" max="100" required>
      </div>
    </div>
    
    <div class="form-row">
      <div class="form-group">
        <label>Fecha de Inicio</label>
        <input type="datetime-local" name="fecha_inicio" required>
      </div>
      
      <div class="form-group">
        <label>Fecha de Fin</label>
        <input type="datetime-local" name="fecha_fin" required>
      </div>
    </div>
    
    <div class="checkbox-label">
      <label>Oferta Activa</label>
      <input type="checkbox" name="activa" checked>
    </div>
    
    <button type="submit">Guardar Oferta</button>
  </form>

  <!-- Tabla de ofertas -->
  <table>
    <tr>
      <th>ID</th>
      <th>Producto</th>
      <th>Descuento</th>
      <th>Fecha Inicio</th>
      <th>Fecha Fin</th>
      <th>Estado</th>
      <th>Acciones</th>
    </tr>
    <?php while($o = $ofertas->fetch_assoc()): 
      $estado_clase = $o['activa'] ? 'oferta-activa' : 'oferta-inactiva';
    ?>
    <tr class="<?= $estado_clase ?>">
      <td><?= $o['oferta_id'] ?></td>
      <td><?= htmlspecialchars($o['producto_nombre']) ?></td>
      <td><?= $o['porcentaje_descuento'] ?>%</td>
      <td><?= date('d/m/Y H:i', strtotime($o['fecha_inicio'])) ?></td>
      <td><?= date('d/m/Y H:i', strtotime($o['fecha_fin'])) ?></td>
      <td><?= $o['activa'] ? 'Activa' : 'Inactiva' ?></td>
      <td class="acciones">
        <?php if ($o['activa']): ?>
          <a href="ofertas_descuentos.php?cambiar_estado=<?= $o['oferta_id'] ?>&estado=0">Desactivar</a>
        <?php else: ?>
          <a href="ofertas_descuentos.php?cambiar_estado=<?= $o['oferta_id'] ?>&estado=1">Activar</a>
        <?php endif; ?>
        <a href="ofertas_descuentos.php?eliminar=<?= $o['oferta_id'] ?>" onclick="return confirm('¿Seguro que quieres eliminar esta oferta?');">Eliminar</a>
      </td>
    </tr>
    <?php endwhile; ?>
  </table>
</body>
</html>