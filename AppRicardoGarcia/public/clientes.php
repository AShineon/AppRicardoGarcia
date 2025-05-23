<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: index.html");
    exit();
}
require_once "../config/database.php";

// Insertar cliente
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'agregar') {
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $direccion = $_POST['direccion'];
    $email = $_POST['email'];
    $telefono = $_POST['telefono'];
    $fecha = date('Y-m-d H:i:s');

    $stmt = $conn->prepare("INSERT INTO Cliente (cliente_id, nombre, apellido, direccion, email, telefono, fecha_registro) VALUES (NULL, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $nombre, $apellido, $direccion, $email, $telefono, $fecha);
    $stmt->execute();
    header("Location: clientes.php");
    exit();
}

// Eliminar cliente
if (isset($_GET['eliminar'])) {
    $id = $_GET['eliminar'];
    $conn->query("DELETE FROM Cliente WHERE cliente_id = $id");
    header("Location: clientes.php");
    exit();
}

// Obtener todos los clientes
$result = $conn->query("SELECT * FROM Cliente ORDER BY cliente_id DESC");
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Clientes</title>
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
  <h2>Clientes</h2>

  <!-- Tabla de clientes -->
  <table>
    <tr>
      <th>ID</th>
      <th>Nombre</th>
      <th>Apellido</th>
      <th>Email</th>
      <th>Teléfono</th>
      <th>Acciones</th>
    </tr>
    <?php while($row = $result->fetch_assoc()): ?>
    <tr>
      <td><?= $row['cliente_id'] ?></td>
      <td><?= htmlspecialchars($row['nombre']) ?></td>
      <td><?= htmlspecialchars($row['apellido']) ?></td>
      <td><?= htmlspecialchars($row['email']) ?></td>
      <td><?= htmlspecialchars($row['telefono']) ?></td>
      <td class="acciones">
        <a href="editar_cliente.php?id=<?= $row['cliente_id'] ?>">Editar</a>
        <a href="clientes.php?eliminar=<?= $row['cliente_id'] ?>" onclick="return confirm('¿Seguro que quieres eliminar este cliente?');">Eliminar</a>
      </td>
    </tr>
    <?php endwhile; ?>
  </table>

  <!-- Formulario para agregar cliente -->
  <form method="POST">
    <h3>Agregar nuevo cliente</h3>
    <input type="hidden" name="accion" value="agregar">
    <label>Nombre</label>
    <input type="text" name="nombre" required>
    <label>Apellido</label>
    <input type="text" name="apellido" required>
    <label>Dirección</label>
    <input type="text" name="direccion" required>
    <label>Email</label>
    <input type="email" name="email" required>
    <label>Teléfono</label>
    <input type="text" name="telefono" required>
    <button type="submit">Agregar Cliente</button>
  </form>
</body>
</html>
