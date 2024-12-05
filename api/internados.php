<?php
require_once 'db.php';

$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';
$estado = isset($_GET['estado']) ? $_GET['estado'] : 'pendiente';

header('Content-Type: application/json');
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['message' => 'Usuario no autenticado']);
    exit();
}

try {
    // Obtener el método de solicitud
    $method = $_SERVER['REQUEST_METHOD'];
    $input = json_decode(file_get_contents('php://input'), true);

    switch ($method) {
        case 'GET':
            // Consulta SQL para obtener internaciones con datos relacionados, aplicando filtro opcional
            if ($estado == 'pendiente') {
                $sql = "SELECT i.id, i.paciente_id, i.fecha_ingreso, 
                               i.fecha_egreso, 
                               i.diagnostico,
                               p.dni, 
                               p.apellido,
                               p.nombre,
                               s.nombre AS sector_nombre, 
                               u.nombre AS usuario_nombre, 
                               u.apellido AS usuario_apellido,
                               pd.observacion
                        FROM internaciones i
                        JOIN pacientes p ON i.paciente_id = p.id
                        JOIN sectores s ON i.sector_id = s.id
                        JOIN usuarios u ON i.usuario_id = u.id
                        LEFT JOIN pacientes_dietas pd ON pd.internacion_id = i.id
                        WHERE i.estado = 1 AND i.fecha_egreso IS NULL";
            } else {
                $sql = "SELECT i.id, i.paciente_id, i.fecha_ingreso, 
                               i.fecha_egreso, 
                               i.diagnostico, 
                               p.dni, 
                               p.apellido,
                               p.nombre,
                               s.nombre AS sector_nombre, 
                               u.nombre AS usuario_nombre, 
                               u.apellido AS usuario_apellido,
                               pd.observacion
                        FROM internaciones i
                        JOIN pacientes p ON i.paciente_id = p.id
                        JOIN sectores s ON i.sector_id = s.id
                        JOIN usuarios u ON i.usuario_id = u.id
                        LEFT JOIN pacientes_dietas pd ON pd.internacion_id = i.id
                        WHERE i.estado = 0 AND i.fecha_egreso IS NOT NULL";
            }

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
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($result);
            break;

        case 'POST':
            // Registrar una nueva internación
            date_default_timezone_set('America/Argentina/Buenos_Aires');

            $paciente_id = $input['paciente_id'];
            $sector_id = $input['sector_id'];
            $diagnostico = strtoupper($input['diagnostico']); // Convertir a mayúsculas
            $usuario_id = $_SESSION['user_id'];
            $estado = 1; // Estado de internación (1 = activo)

            // Verificar si el paciente ya está internado
            $checkQuery = "SELECT id FROM internaciones WHERE paciente_id = ? AND estado != 0 AND fecha_egreso IS NULL";
            $checkStmt = $conn->prepare($checkQuery);
            $checkStmt->execute([$paciente_id]);

            if ($checkStmt->rowCount() > 0) {
                // Si ya está internado, devolver un error 409 (conflicto)
                http_response_code(409);
                echo json_encode(['message' => 'El paciente ya está internado']);
            } else {
                try {
                    // Insertar nueva internación
                    $sql = "INSERT INTO internaciones (paciente_id, fecha_ingreso, sector_id, usuario_id, diagnostico, estado)
                                VALUES (?, NOW(), ?, ?, ?, ?)";
                    $stmt = $conn->prepare($sql);
                    $stmt->execute([$paciente_id, $sector_id, $usuario_id, $diagnostico, $estado]);

                    if ($stmt->rowCount() > 0) {
                        echo json_encode(["message" => "Internación agregada con éxito"]);
                    } else {
                        echo json_encode(["message" => "Error al agregar la internación"]);
                    }
                } catch (PDOException $e) {
                    error_log("Error SQL: " . $e->getMessage());
                    http_response_code(500);
                    echo json_encode(['error' => 'Error interno del servidor al agregar internación']);
                }
            }
            break;

        case 'PUT':
            // Actualizar internación
            $input = json_decode(file_get_contents('php://input'), true);

            try {
                // Obtener los datos enviados
                $id = $input['id'] ?? null;
                $sector_id = $input['sector_id'] ?? null;
                $diagnostico = isset($input['diagnostico']) ? strtoupper($input['diagnostico']) : null; // Convertir a mayúsculas

                // Verificar que todos los datos necesarios estén presentes
                if (!$id || !$sector_id || !$diagnostico) {
                    http_response_code(400);
                    echo json_encode(['error' => 'Faltan datos necesarios (ID, sector, diagnóstico).']);
                    exit();
                }

                // Preparar y ejecutar la consulta para actualizar la internación
                $sql = "UPDATE internaciones SET sector_id = :sector_id, diagnostico = :diagnostico WHERE id = :id";
                $stmt = $conn->prepare($sql);

                // Enlazar los parámetros de la consulta para evitar inyecciones SQL
                $stmt->bindParam(':sector_id', $sector_id, PDO::PARAM_INT);
                $stmt->bindParam(':diagnostico', $diagnostico, PDO::PARAM_STR);
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);

                // Ejecutar la consulta
                $stmt->execute();

                // Verificar si se realizó alguna actualización
                if ($stmt->rowCount() > 0) {
                    echo json_encode(['message' => 'La internación fue actualizada correctamente.']);
                } else {
                    http_response_code(404);
                    echo json_encode(['message' => 'No se encontró la internación o no hubo cambios.']);
                }
            } catch (PDOException $e) {
                // Manejo de errores SQL
                error_log("Error SQL: " . $e->getMessage());
                http_response_code(500);
                echo json_encode(['error' => 'Error interno del servidor al actualizar la internación.']);
            }
            break;
    }
} catch (PDOException $e) {
    echo json_encode(['message' => 'Error de servidor', 'error' => $e->getMessage()]);
}
