<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: index.html"); // Si no hay sesión, redirige al login
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Menú Principal</title>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Segoe UI', sans-serif;
    }

    body {
      background-color: #f4f6f9;
    }

    .navbar {
      background-color: #2c3e50;
      padding: 15px 30px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      color: white;
    }

    .navbar h2 {
      font-size: 22px;
    }

    .nav-links {
      display: flex;
      gap: 20px;
    }

    .nav-links a {
      color: white;
      text-decoration: none;
      font-weight: 500;
      transition: color 0.2s;
    }

    .nav-links a:hover {
      color: #1abc9c;
    }

    .contenido {
      padding: 40px;
    }

    .bienvenida {
      font-size: 24px;
      margin-bottom: 20px;
    }

    .logout-btn {
      background-color: #e74c3c;
      color: white;
      padding: 8px 15px;
      border: none;
      border-radius: 5px;
      font-weight: bold;
      cursor: pointer;
    }

    .logout-btn:hover {
      background-color: #c0392b;
    }
  </style>
</head>
<body>

  <div class="navbar">
    <h2>Mis Trapitos</h2>
    <div class="nav-links">
      <a href="ventas.php">Ventas</a>
      <a href="proveedores.php">Proveedores</a>
      <a href="clientes.php">Clientes</a>
      <a href="../../inventario/index.php">Inventario</a>
      <a href="ofertas_descuentos.php">Descuentos y Promociones</a>
      <a href="consultas_reportes.php">Consultas y Reportes</a>
      <a href="configuracion.php">Configuración</a>
    </div>
    <form action="logout.php" method="POST" style="margin-left: 20px;">
      <button type="submit" class="logout-btn">Cerrar sesión</button>
    </form>
  </div>

  <div class="contenido">
    <div class="bienvenida">Bienvenido, <strong><?php echo $_SESSION['usuario']; ?></strong></div>
    <p>Selecciona una opción del menú para comenzar.</p>
  </div>

</body>
</html>
