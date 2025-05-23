<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: index.html");
    exit();
}

require_once "../config/database.php";

// Insertar proveedor
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'agregar') {
    $nombre = $_POST['nombre'];
    $direccion = $_POST['direccion'];
    $email = $_POST['email'];
    $telefono = $_POST['telefono'];
    $fecha = date('Y-m-d H:i:s');

    $stmt = $conn->prepare("INSERT INTO Proveedor (proveedor_id, nombre, direccion, email, telefono, fecha_registro) VALUES (NULL, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $nombre, $direccion, $email, $telefono, $fecha);
    $stmt->execute();
    header("Location: proveedores.php");
    exit();
}

// Eliminar proveedor
if (isset($_GET['eliminar'])) {
    $id = $_GET['eliminar'];
    $conn->query("DELETE FROM Proveedor WHERE proveedor_id = $id");
    header("Location: proveedores.php");
    exit();
}

// Obtener todos los proveedores
$result = $conn->query("SELECT * FROM Proveedor ORDER BY proveedor_id DESC");
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Proveedores</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f4f6f9;
      padding: 30px;
    }
    h2 {
      margin-bottom: 20px;
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
      max-width: 500px;
    }
    input {
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
  </style>
</head>
<body>
  <h2>Proveedores</h2>

  <!-- Tabla de proveedores -->
  <table>
    <tr>
      <th>ID</th>
      <th>Nombre</th>
      <th>Dirección</th>
      <th>Email</th>
      <th>Teléfono</th>
      <th>Acciones</th>
    </tr>
    <?php while($row = $result->fetch_assoc()): ?>
    <tr>
      <td><?= $row['proveedor_id'] ?></td>
      <td><?= htmlspecialchars($row['nombre']) ?></td>
      <td><?= htmlspecialchars($row['direccion']) ?></td>
      <td><?= htmlspecialchars($row['email']) ?></td>
      <td><?= htmlspecialchars($row['telefono']) ?></td>
      <td class="acciones">
        <a href="editar_proveedor.php?id=<?= $row['proveedor_id'] ?>">Editar</a>
        <a href="proveedores.php?eliminar=<?= $row['proveedor_id'] ?>" onclick="return confirm('¿Seguro que quieres eliminar este proveedor?');">Eliminar</a>
      </td>
    </tr>
    <?php endwhile; ?>
  </table>

  <!-- Formulario para agregar proveedor -->
  <form method="POST">
    <h3>Agregar nuevo proveedor</h3>
    <input type="hidden" name="accion" value="agregar">
    
    <label>Nombre</label>
    <input type="text" name="nombre" required>
    
    <label>Dirección</label>
    <input type="text" name="direccion" required>
    
    <label>Email</label>
    <input type="email" name="email" required>
    
    <label>Teléfono</label>
    <input type="text" name="telefono" required>
    
    <button type="submit">Agregar Proveedor</button>
  </form>
</body>
</html>
