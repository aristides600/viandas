<?php
include 'db.php'; // Asegúrate de que este archivo contiene la conexión PDO.

$id = $_GET['id'] ?? null;

if (!$id) {
    http_response_code(400);
    echo json_encode(['error' => 'ID no proporcionado.']);
    exit;
}

$query = "
    SELECT 
        p.id,
        p.dni,
        p.apellido,
        p.nombre,
        p.fecha_nacimiento,
        p.sexo_id
    FROM pacientes p
    WHERE p.id = :id
";

try {
    $stmt = $conn->prepare($query); // Preparar consulta
    $stmt->bindParam(':id', $id, PDO::PARAM_INT); // Vincular el parámetro de entrada
    $stmt->execute(); // Ejecutar la consulta

    $result = $stmt->fetch(PDO::FETCH_ASSOC); // Obtener los datos

    if ($result) {
        echo json_encode($result); // Devolver los datos como JSON
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'Paciente no encontrado.']);
    }
} catch (PDOException $e) {
    // Manejo de errores
    error_log("Error al consultar la base de datos: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'Error interno del servidor.']);
}
