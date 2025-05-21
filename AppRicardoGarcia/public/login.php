<?php
require_once "config/database.php";
$host = "localhost";
$usuarioBD = "root";
$passwordBD = ""; // cámbialo si tu MySQL tiene contraseña
$nombreBD = "mydb";

$conn = new mysqli($host, $usuarioBD, $passwordBD, $nombreBD);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Recoge los datos del formulario
$usuario = $_POST['usuario'];
$contraseña = $_POST['password']; // asegúrate que el input en HTML tenga name="password"

// Consulta preparada para evitar inyección SQL
$stmt = $conn->prepare("SELECT * FROM Empleado WHERE usuario = ? AND contraseña = ?");
$stmt->bind_param("ss", $usuario, $contraseña);
$stmt->execute();

$resultado = $stmt->get_result();

if ($resultado->num_rows === 1) {
    session_start();
    $_SESSION['usuario'] = $usuario;
    header("Location: panel.php"); // redirige al panel de empleados
} else {
    echo "<script>alert('Usuario o contraseña incorrectos'); window.location.href='index.html';</script>";
}

$stmt->close();
$conn->close();
?>
