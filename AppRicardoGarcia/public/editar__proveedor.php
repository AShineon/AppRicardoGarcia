<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: index.html");
    exit();
}
require_once "../config/database.php";

if (!isset($_GET['id'])) {
    header("Location: proveedores.php");
    exit();
}

$id = $_GET['id'];

// Procesar actualización
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $direccion = $_POST['direccion'];
    $email = $_POST['email'];
    $telefono = $_POST['telefono'];

    $stmt = $conn->prepare("UPDATE Proveedor SET nombre = ?, direccion = ?, email = ?, telefono = ? WHERE proveedor_id = ?");
    $stmt->bind_param("ssssi", $nombre, $direccion, $email, $telefono, $id);
    $stmt->execute();

    header("Location: proveedores.php");
    exit();
}

// Obtener datos actuales del proveedor
$stmt = $conn->prepare("SELECT * FROM Proveedor WHERE proveedor_id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) {
    echo "Proveedor no encontrado.";
    exit();
}
$proveedor = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Editar Proveedor</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f4f6f9;
      padding: 30px;
    }
    form {
      background-color: white;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
      max-width: 500px;
    }
    label {
      display: block;
      margin-top: 12px;
    }
    input {
      width: 100%;
      padding: 8px;
      margin-top: 6px;
      border: 1px solid #ccc;
      border-radius: 6px;
    }
    button {
      margin-top: 20px;
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
    a {
      display: inline-block;
      margin-top: 15px;
      color: #2980b9;
      text-decoration: none;
    }
    a:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>
  <h2>Editar Proveedor</h2>
  <form method="POST">
    <label>Nombre</label>
    <input type="text" name="nombre" value="<?= htmlspecialchars($proveedor['nombre']) ?>" required>
    
    <label>Dirección</label>
    <input type="text" name="direccion" value="<?= htmlspecialchars($proveedor['direccion']) ?>" required>
    
    <label>Email</label>
    <input type="email" name="email" value="<?= htmlspecialchars($proveedor['email']) ?>" required>
    
    <label>Teléfono</label>
    <input type="text" name="telefono" value="<?= htmlspecialchars($proveedor['telefono']) ?>" required>
    
    <button type="submit">Guardar Cambios</button>
  </form>

  <a href="proveedores.php">Volver a Proveedores</a>
</body>
</html>
