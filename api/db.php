<?php

$servername = "localhost";
$username = "root";
$password = "";
$database = "hospital";

// Crear conexión MySQLi
$conn = new mysqli($servername, $username, $password, $database);

// Comprobar conexión MySQLi
if ($conn->connect_error) {
    die("Error al conectar con la base de datos: " . $conn->connect_error);
}

// Crear conexión PDO
try {
    $pdo = new PDO("mysql:host=$servername;dbname=$database;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error al conectar con la base de datos: " . $e->getMessage());
}

?>
