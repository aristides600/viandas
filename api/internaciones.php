<?php
require_once 'db.php';
header('Content-Type: application/json');
session_start();

// Obtener la solicitud
$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true);

switch ($method) {
    case 'GET':
        // Obtener todas las internaciones con datos relacionados
        $sql = "SELECT i.id, i.paciente_id, i.fecha_ingreso, i.fecha_egreso, 
                       i.diagnostico, i.observacion, p.nombre AS paciente_nombre, 
                       pro.nombre AS profesional_nombre, s.nombre AS sector_nombre, 
                       u.nombre AS usuario_nombre, u.apellido AS usuario_apellido
                FROM internaciones i
                JOIN pacientes p ON i.paciente_id = p.id
                JOIN profesionales pro ON i.profesional_id = pro.id
                JOIN sectores s ON i.sector_id = s.id
                JOIN usuarios u ON i.usuario_id = u.id
                WHERE i.estado = 1
                ORDER BY i.fecha_ingreso DESC";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $internaciones = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($internaciones);
        break;

    case 'POST':
        date_default_timezone_set('America/Argentina/Buenos_Aires');

        $paciente_id = $input['paciente_id'];
        $profesional_id = $input['profesional_id'];
        $sector_id = $input['sector_id'];
        $diagnostico = $input['diagnostico'];
        $observacion = $input['observacion'];
        $usuario_id = $_SESSION['user_id'];
        $estado = 1;

        // Verificar si el paciente ya está internado
        $checkQuery = "SELECT id FROM internaciones WHERE paciente_id = ? AND estado != 0 AND fecha_egreso IS NULL";
        $checkStmt = $conn->prepare($checkQuery);
        $checkStmt->execute([$paciente_id]);

        if ($checkStmt->rowCount() > 0) {
            // Si ya está internado, devolver un error 409 (conflicto)
            http_response_code(409);
            echo json_encode(['message' => 'El paciente ya está internado']);
        } else {
            // Insertar nueva internación
            $sql = "INSERT INTO internaciones (paciente_id, fecha_ingreso, profesional_id, sector_id, usuario_id, diagnostico, observacion, estado)
                    VALUES (?, NOW(), ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$paciente_id, $profesional_id, $sector_id, $usuario_id, $diagnostico, $observacion, $estado]);

            if ($stmt->rowCount() > 0) {
                echo json_encode(["message" => "Internación agregada con éxito"]);
            } else {
                echo json_encode(["message" => "Error al agregar la internación"]);
            }
        }
        break;

    case 'PUT':
        // Editar una internación existente
        $id = $input['id'];
        $paciente_id = $input['paciente_id'];
        $profesional_id = $input['profesional_id'];
        $sector_id = $input['sector_id'];
        $fecha_egreso = $input['fecha_egreso'] ? $input['fecha_egreso'] : null;
        $diagnostico = $input['diagnostico'];
        $observacion = $input['observacion'];

        // Actualizar la internación
        $sql = "UPDATE internaciones 
                SET profesional_id = ?, sector_id = ?, fecha_egreso = ?, diagnostico = ?, observacion = ? 
                WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$profesional_id, $sector_id, $fecha_egreso, $diagnostico, $observacion, $id]);

        if ($stmt->rowCount() > 0) {
            echo json_encode(['message' => 'Internación editada correctamente']);
        } else {
            http_response_code(500);
            echo json_encode(['message' => 'Error al editar la internación']);
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(['message' => 'Método no permitido']);
        break;
}

// Cerrar la conexión
$conn = null;
?>
