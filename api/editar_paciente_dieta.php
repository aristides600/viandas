<?php 
include 'db.php'; // Archivo de conexión a la base de datos

// Leer el cuerpo de la solicitud
$data = json_decode(file_get_contents('php://input'), true);
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['message' => 'Usuario no autenticado']);
    exit();
}

// Validar que los datos necesarios están presentes
if (isset($data['id'], $data['dieta_id'])) {
    try {
        // Si no se proporciona fecha_consumo, usar la fecha actual
        $fecha_consumo = $data['fecha_consumo'] ?? date('Y-m-d');

        // Preparar la consulta
        $query = $conn->prepare("
            UPDATE pacientes_dietas 
            SET dieta_id = :dieta_id, 
                fecha_consumo = :fecha_consumo, 
                acompaniante = :acompaniante, 
                observacion = :observacion,
                usuario_id = :usuario_id, 
                postre_id = :postre_id 
            WHERE id = :id
        ");

        // Asignar los parámetros con el tipo correcto
        $query->bindParam(':dieta_id', $data['dieta_id'], PDO::PARAM_INT);
        $query->bindParam(':fecha_consumo', $fecha_consumo); // Usar la fecha actual si no está definida
        $query->bindParam(':acompaniante', $data['acompaniante'], PDO::PARAM_BOOL);
        $query->bindParam(':observacion', $data['observacion']);
        $query->bindParam(':postre_id', $data['postre_id'], PDO::PARAM_INT);
        $query->bindParam(':usuario_id', $_SESSION['user_id'], PDO::PARAM_INT); // Se asigna el ID del usuario logueado
        $query->bindParam(':id', $data['id'], PDO::PARAM_INT);

        // Ejecutar la consulta
        $query->execute();

        // Enviar respuesta exitosa
        echo json_encode(['message' => 'Dieta del paciente actualizada correctamente']);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['message' => 'Error al actualizar la dieta', 'error' => $e->getMessage()]);
    }
} else {
    // Respuesta en caso de datos incompletos
    http_response_code(400);
    echo json_encode(['message' => 'Datos incompletos para actualizar la dieta']);
}

// Cerrar la conexión
$conn = null;
?>
