<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: index.html");
    exit();
}
require_once "../config/database.php";

// Agregar o actualizar configuración
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $config_id = isset($_POST['config_id']) ? intval($_POST['config_id']) : 0;
    $clave = $_POST['clave'];
    $valor = $_POST['valor'];
    $descripcion = $_POST['descripcion'];

    if ($config_id > 0) {
        // Actualizar
        $stmt = $conn->prepare("UPDATE Configuracion SET clave = ?, valor = ?, descripcion = ? WHERE config_id = ?");
        $stmt->bind_param("sssi", $clave, $valor, $descripcion, $config_id);
        $stmt->execute();
    } else {
        // Insertar nuevo
        $stmt = $conn->prepare("INSERT INTO Configuracion (clave, valor, descripcion) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $clave, $valor, $descripcion);
        $stmt->execute();
    }
    header("Location: configuracion.php");
    exit();
}

// Eliminar configuración
if (isset($_GET['eliminar'])) {
    $id = intval($_GET['eliminar']);
    $conn->query("DELETE FROM Configuracion WHERE config_id = $id");
    header("Location: configuracion.php");
    exit();
}

// Obtener todas las configuraciones
$result = $conn->query("SELECT * FROM Configuracion ORDER BY config_id DESC");

// Para editar, si hay id en GET
$editarConfig = null;
if (isset($_GET['editar'])) {
    $idEditar = intval($_GET['editar']);
    $resEditar = $conn->query("SELECT * FROM Configuracion WHERE config_id = $idEditar");
    $editarConfig = $resEditar->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Configuración</title>
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
      max-width: 600px;
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
  <h2>Configuración</h2>

  <!-- Tabla de configuraciones -->
  <table>
    <tr>
      <th>ID</th>
      <th>Clave</th>
      <th>Valor</th>
      <th>Descripción</th>
      <th>Acciones</th>
    </tr>
    <?php while($row = $result->fetch_assoc()): ?>
    <tr>
      <td><?= $row['config_id'] ?></td>
      <td><?= htmlspecialchars($row['clave']) ?></td>
      <td><?= htmlspecialchars($row['valor']) ?></td>
      <td><?= htmlspecialchars($row['descripcion']) ?></td>
      <td class="acciones">
        <a href="configuracion.php?editar=<?= $row['config_id'] ?>">Editar</a>
        <a href="configuracion.php?eliminar=<?= $row['config_id'] ?>" onclick="return confirm('¿Seguro que quieres eliminar esta configuración?');">Eliminar</a>
      </td>
    </tr>
    <?php endwhile; ?>
  </table>

  <!-- Formulario para agregar o editar configuración -->
  <form method="POST">
    <h3><?= $editarConfig ? "Editar configuración" : "Agregar nueva configuración" ?></h3>
    <input type="hidden" name="config_id" value="<?= $editarConfig ? $editarConfig['config_id'] : '' ?>">
    <label>Clave</label>
    <input type="text" name="clave" required value="<?= $editarConfig ? htmlspecialchars($editarConfig['clave']) : '' ?>" <?= $editarConfig ? 'readonly' : '' ?>>
    <label>Valor</label>
    <input type="text" name="valor" required value="<?= $editarConfig ? htmlspecialchars($editarConfig['valor']) : '' ?>">
    <label>Descripción</label>
    <input type="text" name="descripcion" value="<?= $editarConfig ? htmlspecialchars($editarConfig['descripcion']) : '' ?>">
    <button type="submit"><?= $editarConfig ? "Guardar cambios" : "Agregar configuración" ?></button>
  </form>
</body>
</html>
