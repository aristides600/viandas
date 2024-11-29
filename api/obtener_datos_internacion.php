<?php
header('Content-Type: application/json');
include 'db.php'; // Incluye tu conexión PDO.

// Obtener el ID de la internación de los parámetros de la URL
$id_internacion = $_GET['id'] ?? null;

if (!$id_internacion) {
    http_response_code(400);
    echo json_encode(['error' => 'ID de internación no proporcionado']);
    exit;
}

try {
    // Preparar la consulta
    $query = "
        SELECT 
            i.id AS internacion_id,
            i.fecha_ingreso,
            i.fecha_egreso,
            i.diagnostico,
            p.nombre,
            p.apellido,
            p.dni,
            p.fecha_nacimiento
        FROM internaciones i
        INNER JOIN pacientes p ON i.paciente_id = p.id
        WHERE i.id = :id_internacion
    ";

    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id_internacion', $id_internacion, PDO::PARAM_INT);
    $stmt->execute();

    // Verificar si se encontró la internación
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($result) {
        echo json_encode($result);
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'Internación no encontrada']);
    }
} catch (PDOException $e) {
    // Manejo de errores
    error_log("Error en la consulta: " . $e->getMessage()); // Registrar el error en el log del servidor
    http_response_code(500);
    echo json_encode(['error' => 'Error al procesar la solicitud. Inténtalo más tarde.']);
}
?>
