<?php
$host = '127.0.0.1';
$db   = 'mydb';
$user = 'root'; 
$pass = '';     
$charset = 'utf8mb4';

$mysqli = new mysqli($host, $user, $pass, $db);
if ($mysqli->connect_errno) {
    die("Error de conexión: " . $mysqli->connect_error);
}
$mysqli->set_charset($charset);
?>
