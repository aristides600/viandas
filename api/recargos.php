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
            $consulta = $conn->prepare("SELECT * FROM recargos WHERE estado = 1");
            $consulta->execute();
            echo json_encode($consulta->fetchAll(PDO::FETCH_ASSOC));
            break;

        case 'POST':
            $nombre = strtoupper($entrada['nombre']);
            $sector = strtoupper($entrada['sector']);
            $comida_id = $entrada['comida_id'];
            $cantidad = $entrada['cantidad'];
            $dieta_id = 9;
            $estado = 1;
            $fecha_alta = date('Y-m-d');

            // Validar combinación única de nombre + sector + comida_id
            $validarCombinacion = $conn->prepare("SELECT COUNT(*) FROM recargos WHERE nombre = :nombre AND sector = :sector AND comida_id = :comida_id AND estado = 1");
            $validarCombinacion->execute([
                ':nombre' => $nombre,
                ':sector' => $sector,
                ':comida_id' => $comida_id
            ]);
            if ($validarCombinacion->fetchColumn() > 0) {
                http_response_code(409);
                echo json_encode(['error' => 'Ya existe un recargo con ese nombre, sector y comida.']);
                exit();
            }

            $insertar = $conn->prepare("INSERT INTO recargos (nombre, sector, fecha_alta, dieta_id, comida_id, cantidad, usuario_id, estado)
                                        VALUES (:nombre, :sector, :fecha_alta, :dieta_id, :comida_id, :cantidad, :usuario_id, :estado)");
            $insertar->execute([
                ':nombre' => $nombre,
                ':sector' => $sector,
                ':fecha_alta' => $fecha_alta,
                ':dieta_id' => $dieta_id,
                ':comida_id' => $comida_id,
                ':cantidad' => $cantidad,
                ':usuario_id' => $usuario_id,
                ':estado' => $estado
            ]);
            echo json_encode(['mensaje' => 'Recargo creado con éxito']);
            break;

        case 'PUT':
            $id = $entrada['id'];
            $nombre = strtoupper($entrada['nombre']);
            $sector = strtoupper($entrada['sector']);
            $comida_id = $entrada['comida_id'];
            $cantidad = $entrada['cantidad'];
            $dieta_id = $entrada['dieta_id'] ?? 9;

            // Validar combinación única excluyendo el actual
            $validarCombinacion = $conn->prepare("SELECT COUNT(*) FROM recargos WHERE nombre = :nombre AND sector = :sector AND comida_id = :comida_id AND estado = 1 AND id != :id");
            $validarCombinacion->execute([
                ':nombre' => $nombre,
                ':sector' => $sector,
                ':comida_id' => $comida_id,
                ':id' => $id
            ]);
            if ($validarCombinacion->fetchColumn() > 0) {
                http_response_code(409);
                echo json_encode(['error' => 'Ya existe otro recargo con ese nombre, sector y comida.']);
                exit();
            }

            $actualizar = $conn->prepare("UPDATE recargos SET nombre = :nombre, sector = :sector, dieta_id = :dieta_id, comida_id = :comida_id, cantidad = :cantidad WHERE id = :id");
            $actualizar->execute([
                ':id' => $id,
                ':nombre' => $nombre,
                ':sector' => $sector,
                ':dieta_id' => $dieta_id,
                ':comida_id' => $comida_id,
                ':cantidad' => $cantidad
            ]);
            echo json_encode(['mensaje' => 'Recargo actualizado con éxito']);
            break;

        case 'DELETE':
            if (isset($_GET['id'])) {
                $id = intval($_GET['id']);
                $eliminar = $conn->prepare("UPDATE recargos SET estado = 0 WHERE id = :id");
                $eliminar->execute([':id' => $id]);
                echo json_encode(["mensaje" => "Recargo desactivado con éxito"]);
            } else {
                http_response_code(400);
                echo json_encode(["error" => "ID de recargo no proporcionado"]);
            }
            break;

        default:
            http_response_code(405);
            echo json_encode(['error' => 'Método no permitido']);
            break;
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Error del servidor: ' . $e->getMessage()]);
}
