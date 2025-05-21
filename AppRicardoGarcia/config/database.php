<?php
$host = "localhost";
$usuarioBD = "root";
$passwordBD = "";
$nombreBD = "mydb";

$conn = new mysqli($host, $usuarioBD, $passwordBD, $nombreBD);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}
?>
