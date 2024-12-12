<?php
include 'db.php'; // Asegúrate de que el archivo db.php contiene la conexión PDO.

$id = $_GET['id'] ?? null;

if (!$id) {
    http_response_code(400);
    echo json_encode(['error' => 'ID no proporcionado.']);
    exit;
}

$query = "
    SELECT 
        i.id,
        i.diagnostico,
        i.cama,
        i.fecha_ingreso,
        i.fecha_egreso,
        i.sector_id,
        s.nombre AS nombre_sector,
        p.nombre AS nombre_paciente,
        p.apellido AS apellido_paciente,
        p.dni AS dni_paciente,
        p.fecha_nacimiento
    FROM internaciones i
    JOIN sectores s ON i.sector_id = s.id
    JOIN pacientes p ON i.paciente_id = p.id
    WHERE i.id = :id
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
        echo json_encode(['error' => 'Internación no encontrada.']);
    }
} catch (PDOException $e) {
    // Manejo de errores
    error_log("Error al consultar la base de datos: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'Error interno del servidor.']);
}
?>
