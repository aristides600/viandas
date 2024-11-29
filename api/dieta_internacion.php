<?php
include 'db.php'; // Archivo que establece la conexión en la variable $conn

if (isset($_GET['internacion_id'])) {
    $internacion_id = $_GET['internacion_id'];

    try {
        // Consulta para obtener la información del paciente y la internación
        $stmt = $conn->prepare("
            SELECT 
                p.id AS paciente_id, 
                p.nombre, 
                p.apellido, 
                p.dni, 
                p.fecha_nacimiento,
                i.id AS internacion_id, 
                i.diagnostico, 
                i.fecha_ingreso, 
                i.fecha_egreso
            FROM internaciones i
            JOIN pacientes p ON p.id = i.paciente_id
            WHERE i.id = ?
        ");
        $stmt->execute([$internacion_id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($data) {
            // Si se encuentra la información, enviarla en formato JSON
            echo json_encode([
                'paciente' => [
                    'id' => $data['paciente_id'],
                    'nombre' => $data['nombre'],
                    'apellido' => $data['apellido'],
                    'dni' => $data['dni'],
                    'fecha_nacimiento' => $data['fecha_nacimiento'],
                ],
                'internacion' => [
                    'id' => $data['internacion_id'],
                    'diagnostico' => $data['diagnostico'],
                    'fecha_ingreso' => $data['fecha_ingreso'],
                    'fecha_egreso' => $data['fecha_egreso'],
                ]
            ]);
        } else {
            // Si no se encuentra la internación
            http_response_code(404);
            echo json_encode(['error' => 'No se encontró la información del paciente.']);
        }
    } catch (PDOException $e) {
        // En caso de error en el servidor
        http_response_code(500);
        echo json_encode(['error' => 'Error en el servidor: ' . $e->getMessage()]);
    }
} else {
    // Si no se proporciona el ID de la internación
    http_response_code(400);
    echo json_encode(['error' => 'Falta el ID de la internación.']);
}
?>
