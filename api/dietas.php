<?php
require_once 'db.php';
header('Content-Type: application/json');

// Obtener la solicitud
$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true);

switch ($method) {
    case 'GET':
        // Obtener todas las dietas
        try {
            $stmt = $conn->query("SELECT * FROM dietas");
            $dietas = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($dietas);
        } catch (PDOException $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
        break;

    case 'POST':
        // Agregar una nueva dieta
        try {
            $internacion_id = $input['internacion_id'];
            $dieta_id = $input['dieta_id'];
            $fecha = date('Y-m-d H:i:s');
            $usuario_id = $input['usuario_id'];

            $sql = "INSERT INTO dietas_pacientes (internacion_id, dieta_id, fecha, usuario_id) VALUES (:internacion_id, :dieta_id, :fecha, :usuario_id)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':internacion_id', $internacion_id, PDO::PARAM_INT);
            $stmt->bindParam(':dieta_id', $dieta_id, PDO::PARAM_INT);
            $stmt->bindParam(':fecha', $fecha);
            $stmt->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT);

            if ($stmt->execute()) {
                echo json_encode(["status" => "success", "message" => "Dieta agregada exitosamente"]);
            } else {
                echo json_encode(["status" => "error", "message" => "Error al agregar dieta"]);
            }
        } catch (PDOException $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
        break;

    case 'DELETE':
        // Eliminar una dieta
        try {
            $id = $input['id'];

            $sql = "DELETE FROM dietas_pacientes WHERE id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);

            if ($stmt->execute()) {
                echo json_encode(["status" => "success", "message" => "Dieta eliminada exitosamente"]);
            } else {
                echo json_encode(["status" => "error", "message" => "Error al eliminar dieta"]);
            }
        } catch (PDOException $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
        break;
}

// Cerrar la conexión
$conn = null;
?>
