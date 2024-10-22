<?php
require_once 'db.php'; // Incluye tu archivo de conexión PDO
header('Content-Type: application/json');

// Obtener el parámetro dni
$dni = $_GET['dni'] ?? '';

try {
    // Preparar la consulta SQL (ajustar el nombre de la tabla si es diferente)
    $sql = "SELECT p.id, p.dni, p.nombre, p.apellido
            FROM pacientes p
            WHERE p.dni LIKE :dni";

    // Preparar la declaración
    $stmt = $conn->prepare($sql);

    // Bind del parámetro
    $dni = "%$dni%";
    $stmt->bindParam(':dni', $dni, PDO::PARAM_STR);

    // Ejecutar la consulta
    $stmt->execute();

    // Obtener los resultados
    $pacientes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Devolver los resultados como JSON
    echo json_encode($pacientes);
} catch (PDOException $e) {
    // Manejo de errores
    echo json_encode(['success' => false, 'message' => 'Error en la consulta: ' . $e->getMessage()]);
}

// Cerrar la conexión a la base de datos
$conn = null;
?>
