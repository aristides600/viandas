<?php
require_once 'db.php';
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

header('Content-Type: application/json');
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['message' => 'Usuario no autenticado']);
    exit();
}

try {
    // Obtener la solicitud
    $method = $_SERVER['REQUEST_METHOD'];
    $input = json_decode(file_get_contents('php://input'), true);

    switch ($method) {
        case 'GET':
            // Consulta SQL para obtener internaciones con datos relacionados, aplicando filtro opcional
            $sql = "SELECT i.id, i.paciente_id, i.fecha_ingreso, 
                           MAX(i.fecha_egreso) AS fecha_egreso, 
                           MAX(i.diagnostico) AS diagnostico, 
                           CONCAT(p.apellido, ', ', p.nombre) AS paciente_nombre, 
                           pro.nombre AS profesional_nombre, 
                           s.nombre AS sector_nombre, 
                           u.nombre AS usuario_nombre, 
                           u.apellido AS usuario_apellido,
                           MAX(pd.observacion) AS observacion
                    FROM internaciones i
                    JOIN pacientes p ON i.paciente_id = p.id
                    JOIN profesionales pro ON i.profesional_id = pro.id
                    JOIN sectores s ON i.sector_id = s.id
                    JOIN usuarios u ON i.usuario_id = u.id
                    LEFT JOIN pacientes_dietas pd ON pd.internacion_id = i.id
                    WHERE i.estado = 1
                    GROUP BY i.paciente_id
                    ORDER BY i.fecha_ingreso DESC";

            // Agregar condición de búsqueda si hay un término de búsqueda
            if ($searchTerm) {
                $sql .= " HAVING (p.dni LIKE :search OR p.apellido LIKE :search)";
            }

            $stmt = $conn->prepare($sql);

            // Enlazar el término de búsqueda si existe
            if ($searchTerm) {
                $stmt->bindValue(':search', "%$searchTerm%");
            }

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
                $sql = "INSERT INTO internaciones (paciente_id, fecha_ingreso, profesional_id, sector_id, usuario_id, diagnostico, estado)
                        VALUES (?, NOW(), ?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->execute([$paciente_id, $profesional_id, $sector_id, $usuario_id, $diagnostico, $estado]);

                if ($stmt->rowCount() > 0) {
                    echo json_encode(["message" => "Internación agregada con éxito"]);
                } else {
                    echo json_encode(["message" => "Error al agregar la internación"]);
                }
            }
            break;

        case 'PUT':
            // Dar de alta a una internación existente
            $id = $input['id'];
            $fecha_egreso = date('Y-m-d H:i:s');
            $estado = 0; // Cambiar estado a falso

            // Actualizar la internación
            $sql = "UPDATE internaciones 
                        SET fecha_egreso = ?, estado = ? 
                        WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$fecha_egreso, $estado, $id]);

            // Actualizar el estado en la tabla pacientes_dietas
            $sqlDietas = "UPDATE pacientes_dietas 
                              SET estado = 0 
                              WHERE internacion_id = ?";
            $stmtDietas = $conn->prepare($sqlDietas);
            $stmtDietas->execute([$id]);

            if ($stmt->rowCount() > 0) {
                echo json_encode(['message' => 'Alta realizada correctamente']);
            } else {
                http_response_code(500);
                echo json_encode(['message' => 'Error al realizar la alta']);
            }
            break;

        default:
            http_response_code(405);
            echo json_encode(['message' => 'Método no permitido']);
            break;
    }
} catch (PDOException $e) {
    error_log("Error en la consulta SQL: " . $e->getMessage());
    echo json_encode(['error' => 'Error al obtener los datos. Inténtelo más tarde.']);
}

// Cerrar la conexión
$conn = null;
