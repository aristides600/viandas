<?php
require_once 'db.php';
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Usuario no autenticado']);
    exit();
}

$usuario_id = $_SESSION['user_id'];
$metodo = $_SERVER['REQUEST_METHOD'];
$entrada = json_decode(file_get_contents("php://input"), true);

try {
    switch ($metodo) {
        case 'GET':
            $consulta = $conn->prepare("
                SELECT r.*, c.nombre AS comida_nombre 
                FROM recargos r 
                JOIN comidas c ON r.comida_id = c.id 
                WHERE r.estado = 1
            ");
            $consulta->execute(); 
            echo json_encode($consulta->fetchAll(PDO::FETCH_ASSOC));
            break;

        case 'POST':
            if (!$entrada) throw new Exception('Entrada vacía');
            
            $nombre = strtoupper($entrada['nombre'] ?? '');
            $sector = strtoupper($entrada['sector'] ?? '');
            $comida_id = $entrada['comida_id'] ?? null;
            $cantidad = (int)($entrada['cantidad'] ?? 0);
            $observacion = $entrada['observacion'] ?? '';
            $dieta_id = 9;
            $estado = 1;
            $fecha_alta = date('Y-m-d');

            // Validar combinación única
            $validar = $conn->prepare("
                SELECT COUNT(*) FROM recargos 
                WHERE nombre = :nombre AND sector = :sector AND comida_id = :comida_id AND estado = 1
            ");
            $validar->execute([
                ':nombre' => $nombre,
                ':sector' => $sector,
                ':comida_id' => $comida_id,
            ]);

            if ($validar->fetchColumn() > 0) {
                http_response_code(409);
                echo json_encode(['error' => 'Ya existe un recargo con ese nombre, sector y comida.']);
                exit();
            }

            $insertar = $conn->prepare("
                INSERT INTO recargos (nombre, sector, fecha_alta, dieta_id, comida_id, cantidad, usuario_id, observacion, estado)
                VALUES (:nombre, :sector, :fecha_alta, :dieta_id, :comida_id, :cantidad, :usuario_id, :observacion, :estado)
            ");
            $insertar->execute([
                ':nombre' => $nombre,
                ':sector' => $sector,
                ':fecha_alta' => $fecha_alta,
                ':dieta_id' => $dieta_id,
                ':comida_id' => $comida_id,
                ':cantidad' => $cantidad,
                ':usuario_id' => $usuario_id,
                ':observacion' => $observacion,
                ':estado' => $estado
            ]);

            echo json_encode(['mensaje' => 'Recargo guardado correctamente']);
            break;

        case 'PUT':
            parse_str($_SERVER['QUERY_STRING'], $params);
            $id = $params['id'] ?? null;

            if (!$id) {
                http_response_code(400);
                echo json_encode(['error' => 'ID no proporcionado']);
                exit();
            }

            $campos = [];
            $valores = [];

            foreach (['nombre', 'sector', 'cantidad', 'observacion'] as $campo) {
                if (isset($entrada[$campo])) {
                    $campos[] = "$campo = :$campo";
                    $valores[":$campo"] = $campo === 'nombre' || $campo === 'sector' 
                        ? strtoupper($entrada[$campo]) 
                        : $entrada[$campo];
                }
            }

            if (empty($campos)) {
                http_response_code(400);
                echo json_encode(['error' => 'No hay campos para actualizar']);
                exit();
            }

            $valores[':id'] = $id;

            $actualizar = $conn->prepare("UPDATE recargos SET " . implode(', ', $campos) . " WHERE id = :id");
            $actualizar->execute($valores);

            echo json_encode(['mensaje' => 'Recargo actualizado correctamente']);
            break;

        case 'DELETE':
            parse_str($_SERVER['QUERY_STRING'], $params);
            $id = $params['id'] ?? null;

            if (!$id) {
                http_response_code(400);
                echo json_encode(['error' => 'ID no proporcionado']);
                exit();
            }

            $eliminar = $conn->prepare("UPDATE recargos SET estado = 0 WHERE id = :id");
            $eliminar->execute([':id' => $id]);

            echo json_encode(['mensaje' => 'Recargo eliminado correctamente']);
            break;

        default:
            http_response_code(405);
            echo json_encode(['error' => 'Método no permitido']);
    }

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Error del servidor: ' . $e->getMessage()]);
}
