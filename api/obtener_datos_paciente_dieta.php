<?php
header('Content-Type: application/json');
include 'db.php';

$id = $_GET['id'];

try {
    $query = "
        SELECT 
            pd.id AS id_dieta,
            pd.dieta_id,
            pd.acompaniante,
            pd.mensaje,
            pd.observacion,
            pd.postre_id,
            pd.colacion_id,
            pd.suplemento_id,
            p.nombre AS nombre_paciente,
            p.apellido AS apellido_paciente,
            p.dni AS dni_paciente,
            p.fecha_nacimiento,
            i.diagnostico,
            i.fecha_ingreso,
            i.fecha_egreso
        FROM pacientes_dietas pd
        JOIN pacientes p ON p.id = pd.paciente_id
        JOIN internaciones i ON i.id = pd.internacion_id
        WHERE pd.id = :id
    ";

    // Preparar y ejecutar consulta
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    $data = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($data) {
        echo json_encode($data);
    } else {
        http_response_code(404);
        echo json_encode(['message' => 'No se encontraron datos.']);
    }
} catch (PDOException $e) {
    // Manejo de errores
    http_response_code(500);
    echo json_encode(['message' => 'Error en el servidor: ' . $e->getMessage()]);
}
