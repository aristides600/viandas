<?php
// Conexión a la base de datos
require_once 'db.php'; // Incluye el archivo de conexión PDO
header('Content-Type: application/json');

// Recibir los datos del formulario en formato JSON
$data = json_decode(file_get_contents("php://input"), true);

// Extraer los valores del array $data
$paciente_id = $data['paciente_id'];
$dieta_id = $data['dieta_id'];
$internacion_id = $data['internacion_id'];
$comida_id = $data['comida_id'];
$fecha_consumo = $data['fecha_consumo'];
$observacion = $data['observacion'];
$acompaniante = $data['acompaniante'] ? 1 : 0;
$estado = 1;

try {
    // Preparar la consulta SQL
    $sql = "INSERT INTO pacientes_dietas 
            (paciente_id, dieta_id, internacion_id, comida_id, fecha_consumo, observacion, acompaniante, estado) 
            VALUES (:paciente_id, :dieta_id, :internacion_id, :comida_id, :fecha_consumo, :observacion, :acompaniante, :estado)";

    // Preparar la sentencia
    $stmt = $conn->prepare($sql);

    // Vincular los valores con los parámetros
    $stmt->bindParam(':paciente_id', $paciente_id, PDO::PARAM_INT);
    $stmt->bindParam(':dieta_id', $dieta_id, PDO::PARAM_INT);
    $stmt->bindParam(':internacion_id', $internacion_id, PDO::PARAM_INT);
    $stmt->bindParam(':comida_id', $comida_id, PDO::PARAM_INT);
    $stmt->bindParam(':fecha_consumo', $fecha_consumo, PDO::PARAM_STR);
    $stmt->bindParam(':observacion', $observacion, PDO::PARAM_STR);
    $stmt->bindParam(':acompaniante', $acompaniante, PDO::PARAM_INT);
    $stmt->bindParam(':estado', $estado, PDO::PARAM_INT);

    // Ejecutar la sentencia
    if ($stmt->execute()) {
        echo json_encode(['message' => 'Dieta guardada correctamente']);
    } else {
        echo json_encode(['error' => 'No se pudo guardar la dieta']);
    }
} catch (PDOException $e) {
    // Capturar errores y devolver un mensaje JSON
    error_log("Error en la consulta: " . $e->getMessage()); // Registrar el error en el log del servidor
    echo json_encode(['error' => 'Error en el servidor. Inténtalo más tarde.']);
}
?>
