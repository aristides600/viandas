<?php
require_once 'db.php'; // Incluye tu archivo de conexión PDO
header('Content-Type: application/json');

// Obtener el parámetro busqueda
$busqueda = $_GET['busqueda'] ?? '';

// Crear la consulta SQL
$sql = "SELECT * FROM pacientes WHERE dni LIKE :busqueda OR apellido LIKE :busqueda";
$stmt = $conn->prepare($sql);

// Usar el parámetro de búsqueda
$busquedaParam = "%" . $busqueda . "%";
$stmt->bindParam(':busqueda', $busquedaParam);
$stmt->execute();

// Obtener los resultados
$pacientes = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Devolver los resultados como JSON
echo json_encode($pacientes);
?>
