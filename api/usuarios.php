<?php
require_once 'db.php';
header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents("php://input"), true);

try {
    switch ($method) {
        case 'GET':
            $stmt = $conn->prepare("SELECT u.id, u.dni, u.apellido, u.nombre, u.usuario, u.clave, u.rol_id, r.nombre as rol 
                                    FROM usuarios u 
                                    JOIN roles r ON u.rol_id = r.id 
                                    WHERE u.estado = 1");
            $stmt->execute();
            $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($usuarios);
            break;

        case 'POST':
            // Verificar si el DNI o el usuario ya existen
            $stmt = $conn->prepare("SELECT COUNT(*) FROM usuarios WHERE (dni = :dni OR usuario = :usuario) AND estado = 1");
            $stmt->execute([':dni' => $input['dni'], ':usuario' => $input['usuario']]);
            if ($stmt->fetchColumn() > 0) {
                http_response_code(409);
                echo json_encode(['error' => 'El DNI o el usuario ya existen.']);
                exit;
            }

            // Insertar nuevo usuario
            $stmt = $conn->prepare("INSERT INTO usuarios (dni, apellido, nombre, usuario, clave, rol_id, estado) 
                                    VALUES (:dni, :apellido, :nombre, :usuario, :clave, :rol_id, 1)");
            $stmt->execute([
                ':dni' => $input['dni'],
                ':apellido' => $input['apellido'],
                ':nombre' => $input['nombre'],
                ':usuario' => $input['usuario'],
                ':clave' => password_hash($input['clave'], PASSWORD_BCRYPT),
                ':rol_id' => $input['rol_id']
            ]);
            echo json_encode(['message' => 'Usuario creado con éxito']);
            break;

        case 'PUT':
            // Validar unicidad de DNI y usuario para otro usuario
            $stmt = $conn->prepare("SELECT COUNT(*) FROM usuarios WHERE 
                                    (dni = :dni OR usuario = :usuario) AND id != :id AND estado = 1");
            $stmt->execute([
                ':dni' => $input['dni'],
                ':usuario' => $input['usuario'],
                ':id' => $input['id']
            ]);
            if ($stmt->fetchColumn() > 0) {
                http_response_code(409);
                echo json_encode(['error' => 'El DNI o el usuario ya están en uso por otro usuario.']);
                exit;
            }

            // Actualizar usuario
            $stmt = $conn->prepare("UPDATE usuarios SET dni = :dni, apellido = :apellido, nombre = :nombre, 
                                    usuario = :usuario, clave = :clave, rol_id = :rol_id WHERE id = :id");
            $stmt->execute([
                ':id' => $input['id'],
                ':dni' => $input['dni'],
                ':apellido' => $input['apellido'],
                ':nombre' => $input['nombre'],
                ':usuario' => $input['usuario'],
                ':clave' => password_hash($input['clave'], PASSWORD_BCRYPT),
                ':rol_id' => $input['rol_id']
            ]);
            echo json_encode(['message' => 'Usuario actualizado con éxito']);
            break;

        case 'DELETE':
            if (isset($_GET['id'])) {
                $id = intval($_GET['id']);
                $stmt = $conn->prepare("UPDATE usuarios SET estado = 0 WHERE id = :id");
                $stmt->execute([':id' => $id]);
                echo json_encode(["mensaje" => "Usuario desactivado con éxito"]);
            } else {
                http_response_code(400);
                echo json_encode(["error" => "ID de usuario no proporcionado"]);
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
