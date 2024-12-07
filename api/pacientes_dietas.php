<?php
session_start();

// Incluir el archivo de conexión
require 'db.php';

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Usuario no autenticado']);
    exit();
}

// Obtener el ID del usuario autenticado
$usuario_id = $_SESSION['user_id'];

// Obtener el método de la solicitud
$method = $_SERVER['REQUEST_METHOD'];

// Obtener el ID de la dieta desde la URL si está disponible
$dieta_id = isset($_GET['id']) ? intval($_GET['id']) : null;

try {
    switch ($method) {
        case 'GET': // Obtener dietas
            $sql = "SELECT
                        pd.id,
                        pd.paciente_id,
                        pd.dieta_id,
                        d.codigo AS codigo_dieta,
                        d.nombre AS nombre_dieta,
                        pd.usuario_id,
                        pd.internacion_id,
                        pd.fecha_consumo,
                        pd.observacion,
                        pd.acompaniante,
                        pd.estado,
                        pd.postre_id,
                        i.sector_id,
                        p.nombre AS nombre_paciente,
                        p.apellido AS apellido_paciente,
                        s.nombre AS nombre_sector,
                        DATEDIFF(CURDATE(), p.fecha_nacimiento) DIV 365 AS edad
                    FROM pacientes_dietas pd
                    JOIN pacientes p ON p.id = pd.paciente_id
                    JOIN internaciones i ON i.id = pd.internacion_id
                    JOIN sectores s ON s.id = i.sector_id
                    JOIN dietas d ON d.id = pd.dieta_id
                    WHERE pd.estado = 1";

            $stmt = $conn->prepare($sql);
            $stmt->execute();
            echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
            break;

        case 'POST': // Crear una nueva dieta
            $data = json_decode(file_get_contents('php://input'), true);

            if (empty($data['paciente_id']) || empty($data['dieta_id']) || empty($data['internacion_id'])) {
                http_response_code(400);
                echo json_encode(['error' => 'Faltan datos obligatorios']);
                exit();
            }

            // Verificar si ya existe un registro con paciente_id y estado = 1
            $checkSql = "SELECT COUNT(*) FROM pacientes_dietas WHERE paciente_id = :paciente_id AND estado = 1";
            $checkStmt = $conn->prepare($checkSql);
            $checkStmt->execute([':paciente_id' => intval($data['paciente_id'])]);
            $existingCount = $checkStmt->fetchColumn();

            if ($existingCount > 0) {
                http_response_code(400);
                echo json_encode(['error' => 'El paciente ya tiene una dieta activa.']);
                exit();
            }

            // Convertir los valores a mayúsculas
            $observacion = strtoupper(htmlspecialchars(trim($data['observacion'] ?? '')));
            $acompaniante = isset($data['acompaniante']) && $data['acompaniante'] ? 1 : 0;
            $postre_id = !empty($data['postre_id']) ? intval($data['postre_id']) : null;

            // Insertar nueva dieta
            $sql = "INSERT INTO pacientes_dietas 
                                (paciente_id, dieta_id, internacion_id, usuario_id, estado, observacion, acompaniante, postre_id) 
                            VALUES 
                                (:paciente_id, :dieta_id, :internacion_id, :usuario_id, 1, :observacion, :acompaniante, :postre_id)";
            $stmt = $conn->prepare($sql);
            $stmt->execute([
                ':paciente_id' => intval($data['paciente_id']),
                ':dieta_id' => intval($data['dieta_id']),
                ':internacion_id' => intval($data['internacion_id']),
                ':usuario_id' => $usuario_id,
                ':observacion' => $observacion,
                ':acompaniante' => $acompaniante,
                ':postre_id' => $postre_id,
            ]);

            echo json_encode(['mensaje' => 'Dieta creada correctamente']);
            break;

        case 'PUT': // Actualizar dieta existente
            if (!$dieta_id) {
                http_response_code(400);
                echo json_encode(['error' => 'Falta el ID de la dieta']);
                exit();
            }

            $data = json_decode(file_get_contents('php://input'), true);

            // Convertir los valores a mayúsculas
            $observacion = strtoupper(htmlspecialchars(trim($data['observacion'] ?? '')));
            $acompaniante = isset($data['acompaniante']) && $data['acompaniante'] ? 1 : 0;
            $postre_id = !empty($data['postre_id']) ? intval($data['postre_id']) : null;

            $sql = "UPDATE pacientes_dietas 
                        SET 
                            paciente_id = :paciente_id,
                            dieta_id = :dieta_id,
                            internacion_id = :internacion_id,
                            observacion = :observacion,
                            acompaniante = :acompaniante,
                            postre_id = :postre_id
                        WHERE id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->execute([
                ':paciente_id' => intval($data['paciente_id']),
                ':dieta_id' => intval($data['dieta_id']),
                ':internacion_id' => intval($data['internacion_id']),
                ':observacion' => $observacion,
                ':acompaniante' => $acompaniante,
                ':postre_id' => $postre_id,
                ':id' => $dieta_id,
            ]);

            echo json_encode(['mensaje' => 'Dieta actualizada correctamente']);
            break;


        case 'DELETE': // Borrado lógico
            if (!$dieta_id) {
                http_response_code(400);
                echo json_encode(['error' => 'Falta el ID de la dieta']);
                exit();
            }

            $sql = "UPDATE pacientes_dietas SET estado = 0 WHERE id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->execute([':id' => $dieta_id]);

            echo json_encode(['mensaje' => 'Dieta eliminada correctamente']);
            break;

        default:
            http_response_code(405);
            echo json_encode(['error' => 'Método no permitido']);
            break;
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Error en la base de datos: ' . $e->getMessage()]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Error inesperado: ' . $e->getMessage()]);
}
