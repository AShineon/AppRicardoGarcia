<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: index.html");
    exit();
}

require_once "../config/database.php";

// Insertar venta
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'agregar') {
    // Recoger datos del formulario
    $cliente_id = $_POST['cliente_id'];
    $empleado_id = $_POST['empleado_id'];
    $fecha_hora = date('Y-m-d H:i:s', strtotime($_POST['fecha_hora']));
    $subtotal = floatval($_POST['subtotal']);
    $decuento_total = floatval($_POST['descuento_total']); // Nota: variable con "s" del formulario
    $impuesto = floatval($_POST['impuesto']);
    $total = floatval($_POST['total']);
    $metodo_pago = $_POST['metodo_pago'];
    $estado = $_POST['estado'];

    // Consulta SQL con el nombre EXACTO de la columna (decuento_total sin "s")
    $sql = "INSERT INTO Venta (
                venta_id, 
                cliente_id, 
                empleado_id, 
                fecha_hora, 
                subtotal, 
                decuento_total,
                impuesto, 
                total, 
                metodo_pago, 
                estado
            ) VALUES (NULL, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    // Debug: Ver consulta SQL completa
    error_log("SQL a ejecutar: " . $sql);
    
    $stmt = $conn->prepare($sql);
    
    if ($stmt === false) {
        die("Error al preparar la consulta: " . $conn->error . "<br>SQL: " . htmlspecialchars($sql));
    }

    // Vinculación de parámetros
    $bind_result = $stmt->bind_param(
        "iisddddss", 
        $cliente_id, 
        $empleado_id, 
        $fecha_hora, 
        $subtotal, 
        $decuento_total, // Variable con nombre diferente
        $impuesto, 
        $total, 
        $metodo_pago, 
        $estado
    );
    
    if ($bind_result === false) {
        die("Error al vincular parámetros: " . $stmt->error);
    }

    if (!$stmt->execute()) {
        die("Error al ejecutar la consulta: " . $stmt->error);
    }

    header("Location: ventas.php");
    exit();
}

// Eliminar venta
if (isset($_GET['eliminar'])) {
    $id = $_GET['eliminar'];
    $conn->query("DELETE FROM Venta WHERE venta_id = $id");
    header("Location: ventas.php");
    exit();
}

// Obtener todas las ventas con info de cliente y empleado (join)
$result = $conn->query("
    SELECT v.*, c.nombre AS cliente_nombre, c.apellido AS cliente_apellido,
           e.nombre AS empleado_nombre, e.apellido AS empleado_apellido
    FROM Venta v
    JOIN Cliente c ON v.cliente_id = c.cliente_id
    JOIN Empleado e ON v.empleado_id = e.empleado_id
    ORDER BY v.venta_id DESC
");

// Obtener clientes y empleados para el dropdown
$clientes = $conn->query("SELECT cliente_id, nombre, apellido FROM Cliente ORDER BY nombre");
$empleados = $conn->query("SELECT empleado_id, nombre, apellido FROM Empleado ORDER BY nombre");

?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Ventas</title>
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
    }
    input, select {
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
    }
    button:hover {
      background-color: #1c5e83;
    }
    .acciones a {
      margin-right: 10px;
    }
    .form-row {
  display: flex;
  gap: 20px;
  margin-bottom: 15px;
}

.form-group {
  flex: 1;
}

input[type="number"], 
input[type="datetime-local"] {
  width: 100%;
  padding: 8px;
  margin-top: 6px;
  margin-bottom: 12px;
  border: 1px solid #ccc;
  border-radius: 6px;
}
  </style>
</head>
<body>
    <div class="header-container">
      <h2>Ventas</h2>
      <a href="panel.php" class="btn-volver">Volver al Panel</a>
    </div>

  <!-- Tabla de ventas -->
  <table>
    <tr>
      <th>ID</th>
      <th>Cliente</th>
      <th>Empleado</th>
      <th>Fecha y Hora</th>
      <th>Subtotal</th>
      <th>Descuento</th>
      <th>Impuesto</th>
      <th>Total</th>
      <th>Método Pago</th>
      <th>Estado</th>
      <th>Acciones</th>
    </tr>
    <?php while($row = $result->fetch_assoc()): ?>
    <tr>
      <td><?= $row['venta_id'] ?></td>
      <td><?= htmlspecialchars($row['cliente_nombre'] . " " . $row['cliente_apellido']) ?></td>
      <td><?= htmlspecialchars($row['empleado_nombre'] . " " . $row['empleado_apellido']) ?></td>
      <td><?= $row['fecha_hora'] ?></td>
      <td><?= number_format($row['subtotal'], 2) ?></td>
      <td><?= number_format($row['decuento_total'], 2) ?></td>
      <td><?= number_format($row['impuesto'], 2) ?></td>
      <td><?= number_format($row['total'], 2) ?></td>
      <td><?= htmlspecialchars($row['metodo_pago']) ?></td>
      <td><?= htmlspecialchars($row['estado']) ?></td>
      <td class="acciones">
        <a href="editar_venta.php?id=<?= $row['venta_id'] ?>">Editar</a>
        <a href="ventas.php?eliminar=<?= $row['venta_id'] ?>" onclick="return confirm('¿Seguro que quieres eliminar esta venta?');">Eliminar</a>
      </td>
    </tr>
    <?php endwhile; ?>
  </table>

  <!-- Formulario para agregar venta -->
<!-- Formulario para agregar venta -->
<form method="POST">
  <h3>Agregar nueva venta</h3>
  <input type="hidden" name="accion" value="agregar">
  
  <div class="form-row">
    <div class="form-group">
      <label>Cliente</label>
      <select name="cliente_id" required>
        <option value="">Seleccione un cliente</option>
        <?php while($c = $clientes->fetch_assoc()): ?>
          <option value="<?= $c['cliente_id'] ?>"><?= htmlspecialchars($c['nombre'] . " " . $c['apellido']) ?></option>
        <?php endwhile; ?>
      </select>
    </div>
    
    <div class="form-group">
      <label>Empleado</label>
      <select name="empleado_id" required>
        <option value="">Seleccione un empleado</option>
        <?php while($e = $empleados->fetch_assoc()): ?>
          <option value="<?= $e['empleado_id'] ?>"><?= htmlspecialchars($e['nombre'] . " " . $e['apellido']) ?></option>
        <?php endwhile; ?>
      </select>
    </div>
  </div>

  <div class="form-row">
    <div class="form-group">
      <label>Fecha y Hora</label>
      <input type="datetime-local" name="fecha_hora" value="<?= date('Y-m-d\TH:i') ?>" required>
    </div>
    
    <div class="form-group">
      <label>Método de pago</label>
      <select name="metodo_pago" required>
        <option value="EFECTIVO">EFECTIVO</option>
        <option value="TARJETA_CREDITO">TARJETA CRÉDITO</option>
        <option value="TARJETA_DEBITO">TARJETA DÉBITO</option>
        <option value="TRANSFERENCIA">TRANSFERENCIA</option>
        <option value="OTRO">OTRO</option>
      </select>
    </div>
  </div>

  <div class="form-row">
    <div class="form-group">
      <label>Subtotal</label>
      <input type="number" name="subtotal" step="0.01" min="0" value="0.00" required>
    </div>
    
    <div class="form-group">
      <label>Descuento Total</label>
      <input type="number" name="descuento_total" step="0.01" min="0" value="0.00" required>
    </div>
    
    <div class="form-group">
      <label>Impuesto</label>
      <input type="number" name="impuesto" step="0.01" min="0" value="0.00" required>
    </div>
  </div>

  <div class="form-row">
    <div class="form-group">
      <label>Total</label>
      <input type="number" name="total" step="0.01" min="0" value="0.00" required>
    </div>
    
    <div class="form-group">
      <label>Estado</label>
      <select name="estado" required>
        <option value="COMPLETADA">COMPLETADA</option>
        <option value="CANCELADA">CANCELADA</option>
        <option value="DEVOLUCION">DEVOLUCIÓN</option>
        <option value="PENDIENTE">PENDIENTE</option>
      </select>
    </div>
  </div>

  <button type="submit">Agregar Venta</button>
</form>
</body>
</html>
