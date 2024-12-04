<?php
require_once 'db.php';
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

header('Content-Type: application/json');
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Usuario no autenticado']);
    exit();
}

try {
    $method = $_SERVER['REQUEST_METHOD'];
    $input = json_decode(file_get_contents('php://input'), true);

    switch ($method) {
        case 'GET':
            // Consulta SQL para obtener internaciones con datos relacionados, aplicando filtro opcional
            $sql = "SELECT i.id, i.paciente_id, i.fecha_ingreso, 
                           MAX(i.fecha_egreso) AS fecha_egreso, 
                           MAX(i.diagnostico) AS diagnostico, 
                           CONCAT(p.apellido, ', ', p.nombre) AS paciente_nombre, 
                           s.nombre AS sector_nombre, 
                           u.nombre AS usuario_nombre, 
                           u.apellido AS usuario_apellido,
                           MAX(pd.observacion) AS observacion
                    FROM internaciones i
                    JOIN pacientes p ON i.paciente_id = p.id
                    JOIN sectores s ON i.sector_id = s.id
                    JOIN usuarios u ON i.usuario_id = u.id
                    LEFT JOIN pacientes_dietas pd ON pd.internacion_id = i.id
                    WHERE i.estado = 1
                    GROUP BY i.paciente_id
                    ORDER BY i.fecha_ingreso DESC";

            if ($searchTerm) {
                $sql .= " HAVING (p.dni LIKE :search OR p.apellido LIKE :search)";
            }

            $stmt = $conn->prepare($sql);

            if ($searchTerm) {
                $stmt->bindValue(':search', "%$searchTerm%");
            }

            $stmt->execute();
            $internaciones = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($internaciones);
            break;

        case 'PUT':
            // Dar de alta a una internación existente
            $id = $input['id'];
            $fecha_egreso = date('Y-m-d H:i:s');
            $estado = 0; // Cambiar estado a 0

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
                echo json_encode(['success' => 'Alta realizada correctamente']);
            } else {
                echo json_encode(['error' => 'Error al realizar el alta']);
            }
            break;

        default:
            http_response_code(405);
            echo json_encode(['error' => 'Método no permitido']);
            break;
    }
} catch (PDOException $e) {
    echo json_encode(['error' => 'Error de servidor']);
}
?>
