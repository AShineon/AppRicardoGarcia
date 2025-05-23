<?php
session_start();
require_once "config/database.php";

$id = $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM Cliente WHERE cliente_id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$cliente = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $direccion = $_POST['direccion'];
    $email = $_POST['email'];
    $telefono = $_POST['telefono'];

    $stmt = $conn->prepare("UPDATE Cliente SET nombre=?, apellido=?, direccion=?, email=?, telefono=? WHERE cliente_id=?");
    $stmt->bind_param("sssssi", $nombre, $apellido, $direccion, $email, $telefono, $id);
    $stmt->execute();

    header("Location: clientes.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Editar Cliente</title>
</head>
<body>
  <h2>Editar Cliente</h2>
  <form method="POST">
    <label>Nombre:</label><br>
    <input type="text" name="nombre" value="<?= htmlspecialchars($cliente['nombre']) ?>" required><br><br>
    <label>Apellido:</label><br>
    <input type="text" name="apellido" value="<?= htmlspecialchars($cliente['apellido']) ?>" required><br><br>
    <label>Dirección:</label><br>
    <input type="text" name="direccion" value="<?= htmlspecialchars($cliente['direccion']) ?>" required><br><br>
    <label>Email:</label><br>
    <input type="email" name="email" value="<?= htmlspecialchars($cliente['email']) ?>" required><br><br>
    <label>Teléfono:</label><br>
    <input type="text" name="telefono" value="<?= htmlspecialchars($cliente['telefono']) ?>" required><br><br>
    <button type="submit">Guardar Cambios</button>
  </form>
</body>
</html>
