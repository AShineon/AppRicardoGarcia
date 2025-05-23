<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: index.html");
    exit();
}

require_once "../config/database.php";

// Insertar reporte
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'agregar') {
    $tipo = $_POST['tipo'];
    $descripcion = $_POST['descripcion'];
    $estado = 'Abierto'; // Estado por defecto
    $cliente_id = !empty($_POST['cliente_id']) ? $_POST['cliente_id'] : NULL;
    $producto_id = !empty($_POST['producto_id']) ? $_POST['producto_id'] : NULL;
    $empleado_id = !empty($_POST['empleado_id']) ? $_POST['empleado_id'] : NULL;
    $venta_id = !empty($_POST['venta_id']) ? $_POST['venta_id'] : NULL;
    $fecha_creacion = date('Y-m-d H:i:s');

    $stmt = $conn->prepare("INSERT INTO Reportes (
                tipo, 
                descripcion, 
                estado, 
                cliente_id, 
                producto_id, 
                empleado_id, 
                venta_id, 
                fecha_creacion
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    
    $stmt->bind_param("sssiiiss", 
        $tipo, 
        $descripcion, 
        $estado,
        $cliente_id,
        $producto_id,
        $empleado_id,
        $venta_id,
        $fecha_creacion
    );
    
    if ($stmt->execute()) {
        header("Location: consultas_reportes.php?exito=1");
        exit();
    } else {
        $error = "Error al crear el reporte: " . $stmt->error;
    }
}

// Cambiar estado del reporte
if (isset($_GET['cambiar_estado'])) {
    $reporte_id = $_GET['cambiar_estado'];
    $nuevo_estado = $_GET['estado'];
    
    $stmt = $conn->prepare("UPDATE Reportes SET estado = ? WHERE reporte_id = ?");
    $stmt->bind_param("si", $nuevo_estado, $reporte_id);
    $stmt->execute();
    
    // Si el nuevo estado es "Cerrado", actualizar fecha_cierre
    if ($nuevo_estado == 'Cerrado') {
        $conn->query("UPDATE Reportes SET fecha_cierre = NOW() WHERE reporte_id = $reporte_id");
    }
    
    header("Location: consultas_reportes.php");
    exit();
}

// Obtener todos los reportes con información relacionada
$reportes = $conn->query("
    SELECT r.*, 
           c.nombre AS cliente_nombre, 
           p.nombre AS producto_nombre,
           e.nombre AS empleado_nombre,
           v.venta_id AS venta_referencia
    FROM Reportes r
    LEFT JOIN Cliente c ON r.cliente_id = c.cliente_id
    LEFT JOIN Producto p ON r.producto_id = p.producto_id
    LEFT JOIN Empleado e ON r.empleado_id = e.empleado_id
    LEFT JOIN Venta v ON r.venta_id = v.venta_id
    ORDER BY r.fecha_creacion DESC
");

// Obtener datos para los dropdowns
$clientes = $conn->query("SELECT cliente_id, nombre FROM Cliente ORDER BY nombre");
$productos = $conn->query("SELECT producto_id, nombre FROM Producto ORDER BY nombre");
$empleados = $conn->query("SELECT empleado_id, nombre FROM Empleado ORDER BY nombre");
$ventas = $conn->query("SELECT venta_id FROM Venta ORDER BY venta_id DESC");
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Consultas y Reportes</title>
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
    textarea {
      min-height: 100px;
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
    .estado-abierto { background-color: #ffdddd; }
    .estado-proceso { background-color: #fff3cd; }
    .estado-resuelto { background-color: #d4edda; }
    .estado-cerrado { background-color: #d1ecf1; }
    .exito {
      background-color: #d4edda;
      color: #155724;
      padding: 10px;
      border-radius: 5px;
      margin-bottom: 20px;
    }
  </style>
</head>
<body>
  <div class="header-container">
    <h2>Consultas y Reportes</h2>
    <a href="panel.php" class="btn-volver">Volver al Panel</a>
  </div>

  <?php if (isset($_GET['exito'])): ?>
    <div class="exito">Reporte creado exitosamente!</div>
  <?php endif; ?>

  <!-- Formulario para nuevo reporte -->
  <form method="POST">
    <h3>Crear Nuevo Reporte</h3>
    <input type="hidden" name="accion" value="agregar">
    
    <label>Tipo de Reporte</label>
    <select name="tipo" required>
      <option value="Queja">Queja</option>
      <option value="Reclamo">Reclamo</option>
      <option value="Sugerencia">Sugerencia</option>
      <option value="Reporte">Reporte</option>
    </select>
    
    <label>Descripción</label>
    <textarea name="descripcion" required></textarea>
    
    <div style="display: flex; gap: 20px;">
      <div style="flex: 1;">
        <label>Cliente (Opcional)</label>
        <select name="cliente_id">
          <option value="">Seleccionar cliente</option>
          <?php while($c = $clientes->fetch_assoc()): ?>
            <option value="<?= $c['cliente_id'] ?>"><?= htmlspecialchars($c['nombre']) ?></option>
          <?php endwhile; ?>
        </select>
      </div>
      
      <div style="flex: 1;">
        <label>Producto (Opcional)</label>
        <select name="producto_id">
          <option value="">Seleccionar producto</option>
          <?php while($p = $productos->fetch_assoc()): ?>
            <option value="<?= $p['producto_id'] ?>"><?= htmlspecialchars($p['nombre']) ?></option>
          <?php endwhile; ?>
        </select>
      </div>
    </div>
    
    <div style="display: flex; gap: 20px;">
      <div style="flex: 1;">
        <label>Empleado (Opcional)</label>
        <select name="empleado_id">
          <option value="">Seleccionar empleado</option>
          <?php while($e = $empleados->fetch_assoc()): ?>
            <option value="<?= $e['empleado_id'] ?>"><?= htmlspecialchars($e['nombre']) ?></option>
          <?php endwhile; ?>
        </select>
      </div>
      
      <div style="flex: 1;">
        <label>Venta (Opcional)</label>
        <select name="venta_id">
          <option value="">Seleccionar venta</option>
          <?php while($v = $ventas->fetch_assoc()): ?>
            <option value="<?= $v['venta_id'] ?>">Venta #<?= $v['venta_id'] ?></option>
          <?php endwhile; ?>
        </select>
      </div>
    </div>
    
    <button type="submit">Guardar Reporte</button>
  </form>

  <!-- Tabla de reportes -->
  <table>
    <tr>
      <th>ID</th>
      <th>Tipo</th>
      <th>Descripción</th>
      <th>Fecha</th>
      <th>Estado</th>
      <th>Relacionado con</th>
      <th>Acciones</th>
    </tr>
    <?php while($r = $reportes->fetch_assoc()): 
      $estado_clase = strtolower(str_replace(' ', '-', $r['estado']));
    ?>
    <tr class="estado-<?= $estado_clase ?>">
      <td><?= $r['reporte_id'] ?></td>
      <td><?= htmlspecialchars($r['tipo']) ?></td>
      <td><?= htmlspecialchars($r['descripcion']) ?></td>
      <td><?= date('d/m/Y H:i', strtotime($r['fecha_creacion'])) ?></td>
      <td><?= htmlspecialchars($r['estado']) ?></td>
      <td>
        <?php 
          $relacionados = [];
          if ($r['cliente_nombre']) $relacionados[] = "Cliente: ".$r['cliente_nombre'];
          if ($r['producto_nombre']) $relacionados[] = "Producto: ".$r['producto_nombre'];
          if ($r['empleado_nombre']) $relacionados[] = "Empleado: ".$r['empleado_nombre'];
          if ($r['venta_referencia']) $relacionados[] = "Venta #".$r['venta_referencia'];
          echo implode("<br>", $relacionados);
        ?>
      </td>
      <td class="acciones">
        <?php if ($r['estado'] != 'Cerrado'): ?>
          <a href="consultas_reportes.php?cambiar_estado=<?= $r['reporte_id'] ?>&estado=En proceso">Marcar en Proceso</a>
          <a href="consultas_reportes.php?cambiar_estado=<?= $r['reporte_id'] ?>&estado=Resuelto">Marcar Resuelto</a>
          <a href="consultas_reportes.php?cambiar_estado=<?= $r['reporte_id'] ?>&estado=Cerrado">Cerrar</a>
        <?php else: ?>
          <span>Cerrado el <?= date('d/m/Y', strtotime($r['fecha_cierre'])) ?></span>
        <?php endif; ?>
      </td>
    </tr>
    <?php endwhile; ?>
  </table>
</body>
</html>