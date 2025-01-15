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



try {
    switch ($method) {
        case 'GET': // Obtener dietas
            $sql = "SELECT
                        pd.id,
                        pd.paciente_id,
                        pd.dieta_id,
                        d.codigo AS codigo_dieta,
                        d.nombre AS nombre_dieta,
                        c1.id AS id_colacion,
                        c1.nombre AS nombre_colacion,
                        s1.id AS id_suplemento,
                        s1.nombre AS nombre_suplemento,
                        postres.nombre AS nombre_postre,
                        u.apellido AS apellido_usuario,
                        u.nombre AS nombre_usuario,
                        pd.usuario_id,
                        pd.internacion_id,
                        pd.fecha_consumo,
                        pd.mensaje,
                        pd.observacion,
                        pd.acompaniante,
                        pd.estado,
                        pd.postre_id,
                        i.sector_id,
                        i.cama,
                        i.diagnostico,
                        p.dni,
                        p.nombre AS nombre_paciente,
                        p.apellido AS apellido_paciente,
                        s.nombre AS nombre_sector,
                        DATEDIFF(CURDATE(), p.fecha_nacimiento) DIV 365 AS edad
                    FROM pacientes_dietas pd
                    JOIN pacientes p ON p.id = pd.paciente_id
                    JOIN internaciones i ON i.id = pd.internacion_id
                    JOIN usuarios u ON u.id = pd.usuario_id
                    JOIN sectores s ON s.id = i.sector_id
                    JOIN dietas d ON d.id = pd.dieta_id
                    LEFT JOIN postres ON postres.id = pd.postre_id
                    LEFT JOIN colaciones c1 ON c1.id = pd.colacion_id
                    LEFT JOIN suplementos s1 ON s1.id = pd.suplemento_id
                   
                    WHERE pd.estado = 1
                    ORDER BY i.sector_id ASC, i.cama ASC"; // Ordenar por sector y cama de forma ascendente

            try {
                $stmt = $conn->prepare($sql);
                $stmt->execute();
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                echo json_encode($result);
            } catch (PDOException $e) {
                echo json_encode(["error" => $e->getMessage()]);
            }
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
            $mensaje = htmlspecialchars(trim($data['mensaje'] ?? ''));
            $observacion = htmlspecialchars(trim($data['observacion'] ?? ''));
            $acompaniante = isset($data['acompaniante']) && $data['acompaniante'] ? 1 : 0;
            $postre_id = !empty($data['postre_id']) ? intval($data['postre_id']) : null;
            $colacion_id = !empty($data['colacion_id']) ? intval($data['colacion_id']) : null;
            $suplemento_id = !empty($data['suplemento_id']) ? intval($data['suplemento_id']) : null;

            // Verificar si usuario_id está definido, puedes obtenerlo de la sesión o algún método de autenticación
            if (!isset($usuario_id)) {
                http_response_code(400);
                echo json_encode(['error' => 'No se pudo determinar el usuario que realiza la acción']);
                exit();
            }

            // Insertar nueva dieta
            $sql = "INSERT INTO pacientes_dietas 
                                    (paciente_id, dieta_id, internacion_id, fecha_consumo, usuario_id, estado, mensaje, observacion, acompaniante, postre_id, colacion_id, suplemento_id) 
                                VALUES 
                                    (:paciente_id, :dieta_id, :internacion_id, NOW(), :usuario_id, 1, :mensaje, :observacion, :acompaniante, :postre_id, :colacion_id, :suplemento_id)";
            $stmt = $conn->prepare($sql);
            $stmt->execute([
                ':paciente_id' => intval($data['paciente_id']),
                ':dieta_id' => intval($data['dieta_id']),
                ':internacion_id' => intval($data['internacion_id']),
                ':usuario_id' => $usuario_id,
                ':mensaje' => $mensaje,
                ':observacion' => $observacion,
                ':acompaniante' => $acompaniante,
                ':postre_id' => $postre_id,
                ':colacion_id' => $colacion_id,
                ':suplemento_id' => $suplemento_id,
            ]);

            echo json_encode(['mensaje' => 'Dieta creada correctamente']);
            break;


        case 'PUT': // Actualizar dieta existente
            // Obtener el ID de la dieta desde la URL si está disponible
            $dieta_id = isset($_GET['id']) ? intval($_GET['id']) : null;
            if (!$dieta_id) {
                http_response_code(400);
                echo json_encode(['error' => 'Falta el ID de la dieta']);
                exit();
            }

            $data = json_decode(file_get_contents('php://input'), true);
            $mensaje = htmlspecialchars(trim($data['mensaje'] ?? ''));
            $observacion = htmlspecialchars(trim($data['observacion'] ?? ''));
            $acompaniante = isset($data['acompaniante']) && $data['acompaniante'] ? 1 : 0;
            $postre_id = !empty($data['postre_id']) ? intval($data['postre_id']) : null;
            $postre_id = !empty($data['colacion_id']) ? intval($data['colacion_id']) : null;
            $postre_id = !empty($data['suplemento_id']) ? intval($data['suplemento_id']) : null;



            $sql = "UPDATE pacientes_dietas 
                        SET 
                            paciente_id = :paciente_id,
                            dieta_id = :dieta_id,
                            internacion_id = :internacion_id,
                            mensaje = :mensaje,
                            observacion = :observacion,
                            acompaniante = :acompaniante,
                            fecha_consumo = NOW(),
                            postre_id = :postre_id,
                            colacion_id = :colacion_id,
                            suplemento_id = :suplemento_id

                        WHERE id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->execute([
                ':paciente_id' => intval($data['paciente_id']),
                ':dieta_id' => intval($data['dieta_id']),
                ':internacion_id' => intval($data['internacion_id']),
                ':mensaje' => $mensaje,
                ':observacion' => $observacion,
                ':acompaniante' => $acompaniante,
                ':postre_id' => $postre_id,
                ':colacion_id' => $colacion_id,
                ':suplemento_id' => $suplemento_id,
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
