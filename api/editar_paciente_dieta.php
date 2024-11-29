<?php
include 'db.php'; // Archivo de conexión a la base de datos

$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['id'])) {
    try {
        $query = $conn->prepare("
            UPDATE pacientes_dietas 
            SET dieta_id = :dieta_id, 
                fecha_consumo = :fecha_consumo, 
                acompaniante = :acompaniante, 
                observacion = :observacion, 
                postre_id = :postre_id 
            WHERE id = :id
        ");

        $query->bindParam(':dieta_id', $data['dieta_id']);
        $query->bindParam(':fecha_consumo', $data['fecha_consumo']);
        $query->bindParam(':acompaniante', $data['acompaniante'], PDO::PARAM_BOOL);
        $query->bindParam(':observacion', $data['observacion']);
        $query->bindParam(':postre_id', $data['postre_id']);
        $query->bindParam(':id', $data['id'], PDO::PARAM_INT);

        $query->execute();

        echo json_encode(['message' => 'Dieta del paciente actualizada correctamente']);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['message' => 'Error al actualizar la dieta', 'error' => $e->getMessage()]);
    }
} else {
    http_response_code(400);
    echo json_encode(['message' => 'Datos incompletos para actualizar la dieta']);
}
$conn = null;
?>
