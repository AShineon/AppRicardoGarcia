<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: index.html");
    exit();
}
require_once "../config/database.php";

// Agregar o actualizar empleado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $empleado_id = isset($_POST['empleado_id']) ? intval($_POST['empleado_id']) : 0;
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $puesto = $_POST['puesto'];
    $usuario = $_POST['usuario'];
    $contraseña = $_POST['contraseña'];
    $activo = $_POST['activo'];

    if ($empleado_id > 0) {
        // Actualizar empleado
        $stmt = $conn->prepare("UPDATE Empleado SET nombre = ?, apellido = ?, puesto = ?, usuario = ?, contraseña = ?, activo = ? WHERE empleado_id = ?");
        $stmt->bind_param("ssssssi", $nombre, $apellido, $puesto, $usuario, $contraseña, $activo, $empleado_id);
    } else {
        // Insertar nuevo empleado
        $stmt = $conn->prepare("INSERT INTO Empleado (nombre, apellido, puesto, usuario, contraseña, activo) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $nombre, $apellido, $puesto, $usuario, $contraseña, $activo);
    }
    $stmt->execute();
    header("Location: configuracion.php");
    exit();
}

// Eliminar empleado
if (isset($_GET['eliminar'])) {
    $id = intval($_GET['eliminar']);
    $conn->query("DELETE FROM Empleado WHERE empleado_id = $id");
    header("Location: configuracion.php");
    exit();
}

// Obtener todos los empleados
$result = $conn->query("SELECT * FROM Empleado ORDER BY empleado_id DESC");

// Para editar, si hay id en GET
$editarEmpleado = null;
if (isset($_GET['editar'])) {
    $idEditar = intval($_GET['editar']);
    $resEditar = $conn->query("SELECT * FROM Empleado WHERE empleado_id = $idEditar");
    if ($resEditar && $resEditar->num_rows > 0) {
        $editarEmpleado = $resEditar->fetch_assoc();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Gestión de Empleados</title>
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
      color: #2980b9;
      text-decoration: none;
    }
    .acciones a:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>
  <h2>Gestión de Empleados</h2>
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
      <h2>Gestión de Empleados</h2>
      <a href="panel.php" style="background-color: #3498db; color: white; padding: 8px 15px; border-radius: 5px; text-decoration: none; font-weight: bold;">Volver al Panel</a>
    </div>

  <!-- Tabla de empleados -->
  <table>
    <tr>
      <th>ID</th>
      <th>Nombre</th>
      <th>Apellido</th>
      <th>Puesto</th>
      <th>Usuario</th>
      <th>Estado</th>
      <th>Acciones</th>
    </tr>
    <?php while($row = $result->fetch_assoc()): ?>
    <tr>
      <td><?= $row['empleado_id'] ?></td>
      <td><?= htmlspecialchars($row['nombre']) ?></td>
      <td><?= htmlspecialchars($row['apellido']) ?></td>
      <td><?= htmlspecialchars($row['puesto']) ?></td>
      <td><?= htmlspecialchars($row['usuario']) ?></td>
      <td><?= htmlspecialchars($row['activo']) ?></td>
      <td class="acciones">
        <a href="configuracion.php?editar=<?= $row['empleado_id'] ?>">Editar</a>
        <a href="configuracion.php?eliminar=<?= $row['empleado_id'] ?>" onclick="return confirm('¿Seguro que quieres eliminar este empleado?');">Eliminar</a>
      </td>
    </tr>
    <?php endwhile; ?>
  </table>

  <!-- Formulario para agregar o editar empleado -->
  <form method="POST">
    <h3><?= $editarEmpleado ? "Editar empleado" : "Agregar nuevo empleado" ?></h3>
    <input type="hidden" name="empleado_id" value="<?= $editarEmpleado ? $editarEmpleado['empleado_id'] : '' ?>">
    
    <label>Nombre</label>
    <input type="text" name="nombre" required value="<?= $editarEmpleado ? htmlspecialchars($editarEmpleado['nombre']) : '' ?>">
    
    <label>Apellido</label>
    <input type="text" name="apellido" required value="<?= $editarEmpleado ? htmlspecialchars($editarEmpleado['apellido']) : '' ?>">
    
    <label>Puesto</label>
    <select name="puesto" required>
      <option value="Vendedor" <?= ($editarEmpleado && $editarEmpleado['puesto'] == 'Vendedor') ? 'selected' : '' ?>>Vendedor</option>
      <option value="Supervisor" <?= ($editarEmpleado && $editarEmpleado['puesto'] == 'Supervisor') ? 'selected' : '' ?>>Supervisor</option>
      <option value="Gerente" <?= ($editarEmpleado && $editarEmpleado['puesto'] == 'Gerente') ? 'selected' : '' ?>>Gerente</option>
      <option value="Almacenero" <?= ($editarEmpleado && $editarEmpleado['puesto'] == 'Almacenero') ? 'selected' : '' ?>>Almacenero</option>
      <option value="Administrativo" <?= ($editarEmpleado && $editarEmpleado['puesto'] == 'Administrativo') ? 'selected' : '' ?>>Administrativo</option>
    </select>
    
    <label>Usuario</label>
    <input type="text" name="usuario" required value="<?= $editarEmpleado ? htmlspecialchars($editarEmpleado['usuario']) : '' ?>">
    
    <label>Contraseña</label>
    <input type="password" name="contraseña" required value="<?= $editarEmpleado ? htmlspecialchars($editarEmpleado['contraseña']) : '' ?>">
    
    <label>Estado</label>
    <select name="activo" required>
      <option value="Activo" <?= ($editarEmpleado && $editarEmpleado['activo'] == 'Activo') ? 'selected' : '' ?>>Activo</option>
      <option value="Inactivo" <?= ($editarEmpleado && $editarEmpleado['activo'] == 'Inactivo') ? 'selected' : '' ?>>Inactivo</option>
      <option value="Suspendido" <?= ($editarEmpleado && $editarEmpleado['activo'] == 'Suspendido') ? 'selected' : '' ?>>Suspendido</option>
      <option value="Vacaciones" <?= ($editarEmpleado && $editarEmpleado['activo'] == 'Vacaciones') ? 'selected' : '' ?>>Vacaciones</option>
    </select>
    
    <button type="submit"><?= $editarEmpleado ? "Guardar cambios" : "Agregar empleado" ?></button>
  </form>
</body>
</html>