<?php
include 'db.php'; // Conexión a la base de datos

header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        // Corrige el alias "sexo" en la consulta y asegúrate de que se traigan todos los pacientes activos
        $stmt = $conn->prepare("SELECT p.id, p.dni, p.nombre, p.apellido, p.fecha_nacimiento, s.nombre AS sexo, p.fecha_alta, p.estado
                               FROM pacientes p
                               JOIN sexos s ON p.sexo_id = s.id
                               WHERE p.estado = 1");
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($result);
        break;

    case 'POST':
        $data = json_decode(file_get_contents('php://input'), true);
        if ($data) {
            $dni = strtoupper($data['dni']);
            $nombre = strtoupper($data['nombre']);
            $apellido = strtoupper($data['apellido']);
            $fecha_nacimiento = $data['fecha_nacimiento'];
            $sexo_id = $data['sexo_id'];

            $stmt = $conn->prepare("SELECT COUNT(*) FROM pacientes WHERE dni = ?");
            $stmt->execute([$dni]);
            $count = $stmt->fetchColumn();

            if ($count > 0) {
                http_response_code(400);
                echo json_encode(['message' => 'El DNI ya está registrado']);
            } else {
                $stmt = $conn->prepare(
                    "INSERT INTO pacientes (dni, nombre, apellido, fecha_nacimiento, sexo_id, fecha_alta, estado) 
                        VALUES (?, ?, ?, ?, ?, NOW(), 1)"
                );
                $stmt->execute([
                    $dni,
                    $nombre,
                    $apellido,
                    $fecha_nacimiento,
                    $sexo_id
                ]);
                echo json_encode(['message' => 'Paciente agregado exitosamente']);
            }
        } else {
            http_response_code(400);
            echo json_encode(['message' => 'Datos inválidos']);
        }
        break;


    case 'PUT':
        $data = json_decode(file_get_contents('php://input'), true);
        if ($data && isset($_GET['id'])) {
            // Convertir todos los campos a mayúsculas
            $dni = strtoupper($data['dni']);
            $nombre = strtoupper($data['nombre']);
            $apellido = strtoupper($data['apellido']);
            $fecha_nacimiento = strtoupper($data['fecha_nacimiento']);
            $sexo_id = strtoupper($data['sexo_id']);

            // Validar que el DNI no esté duplicado
            $stmt = $conn->prepare("SELECT COUNT(*) FROM pacientes WHERE dni = ? AND id != ?");
            $stmt->execute([$dni, $_GET['id']]);
            $count = $stmt->fetchColumn();

            if ($count > 0) {
                http_response_code(400);
                echo json_encode(['message' => 'El DNI ya está registrado']);
            } else {
                // Actualizar los datos del paciente
                $stmt = $conn->prepare("UPDATE pacientes SET dni = ?, nombre = ?, apellido = ?, fecha_nacimiento = ?, sexo_id = ? 
                WHERE id = ? AND estado = 1");
                $stmt->execute([
                    $dni,
                    $nombre,
                    $apellido,
                    $fecha_nacimiento,
                    $sexo_id,
                    $_GET['id']
                ]);
                echo json_encode(['message' => 'Paciente actualizado exitosamente']);
            }
        } else {
            http_response_code(400);
            echo json_encode(['message' => 'Datos inválidos o ID no especificado']);
        }
        break;

    case 'DELETE':
        if (isset($_GET['id'])) {
            $stmt = $conn->prepare("UPDATE pacientes SET estado = 0 WHERE id = ?");
            $stmt->execute([$_GET['id']]);
            echo json_encode(['message' => 'Paciente eliminado exitosamente']);
        } else {
            http_response_code(400);
            echo json_encode(['message' => 'ID no especificado']);
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(['message' => 'Método no permitido']);
}
