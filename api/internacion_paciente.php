<?php
include 'db.php';

if (isset($_GET['internacion_id'])) {
    $internacion_id = $_GET['internacion_id'];

    try {
        $stmt = $pdo->prepare("
            SELECT i.*, p.nombre, p.apellido, p.dni, p.fecha_nacimiento 
            FROM internaciones i
            JOIN pacientes p ON i.paciente_id = p.id
            WHERE i.id = ?
        ");
        $stmt->execute([$internacion_id]);
        $internacion = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($internacion) {
            echo json_encode([
                'paciente' => [
                    'id' => $internacion['paciente_id'],
                    'nombre' => $internacion['nombre'],
                    'apellido' => $internacion['apellido'],
                    'dni' => $internacion['dni'],
                    'fecha_nacimiento' => $internacion['fecha_nacimiento']
                ],
                'internacion' => [
                    'diagnostico' => $internacion['diagnostico'],
                    'fecha_ingreso' => $internacion['fecha_ingreso'],
                    'fecha_egreso' => $internacion['fecha_egreso']
                ]
            ]);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Internación no encontrada.']);
        }
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Error al consultar la base de datos.']);
    }
} else {
    http_response_code(400);
    echo json_encode(['error' => 'Falta el ID de la internación.']);
}
?>
