<?php
require 'DB.php';

if (!isset($_GET['id'])) {
    header('Location: index.php');
    exit;
}

$id = intval($_GET['id']);

$mysqli->query("DELETE FROM producto WHERE producto_id = $id");

header('Location: index.php');
exit;
