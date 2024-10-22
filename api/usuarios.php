<?php
require_once 'db.php';
header('Content-Type: application/json');

// Obtener la solicitud
$method = $_SERVER['REQUEST_METHOD'];

// Leer el cuerpo de la solicitud
$input = json_decode(file_get_contents("php://input"), true);

try {
    switch ($method) {
        case 'GET':
            // Listar usuarios
            $stmt = $conn->prepare("SELECT u.id, u.dni, u.apellido, u.nombre, u.usuario, u.estado, u.rol_id, r.nombre as rol FROM usuarios u JOIN roles r ON u.rol_id = r.id");
            $stmt->execute();
            $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($usuarios);
            break;

        case 'POST':
            // Crear usuario
            $dni = $input['dni'];
            $apellido = strtoupper($input['apellido']);
            $nombre = strtoupper($input['nombre']);

            $usuario = $input['usuario'];
            $clave = password_hash($input['clave'], PASSWORD_BCRYPT);
            $rol_id = $input['rol_id'];
            $estado = 1;

            // Verificar si el usuario o DNI ya existe
            $stmt = $conn->prepare("SELECT COUNT(*) FROM usuarios WHERE dni = ? OR usuario = ?");
            $stmt->execute([$dni, $usuario]);
            if ($stmt->fetchColumn() > 0) {
                http_response_code(409); // Conflict
                echo json_encode(['error' => 'El DNI o el nombre de usuario ya existe']);
                exit;
            }

            // Insertar el nuevo usuario
            $stmt = $conn->prepare("INSERT INTO usuarios (dni, apellido, nombre, usuario, clave, rol_id, estado) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$dni, $apellido, $nombre, $usuario, $clave, $rol_id, $estado]);

            echo json_encode(['message' => 'Usuario creado con éxito']);
            break;

        case 'PUT':
            // Editar usuario
            $id = $_GET['id'];
            $dni = $input['dni'];
            $apellido = strtoupper($input['apellido']);
            $nombre = strtoupper($input['nombre']);
            $usuario = $input['usuario'];
            $rol_id = $input['rol_id'];
            // $estado = $input['estado'];

            // Verificar si el nuevo DNI o usuario ya existen en otro registro
            $stmt = $conn->prepare("SELECT COUNT(*) FROM usuarios WHERE (dni = ? OR usuario = ?) AND id != ?");
            $stmt->execute([$dni, $usuario, $id]);
            if ($stmt->fetchColumn() > 0) {
                http_response_code(409); // Conflict
                echo json_encode(['error' => 'El DNI o el nombre de usuario ya existe en otro registro']);
                exit;
            }

            // Actualizar usuario
            $stmt = $conn->prepare("UPDATE usuarios SET dni = ?, apellido = ?, nombre = ?, usuario = ?, rol_id = ? WHERE id = ?");
            $stmt->execute([$dni, $apellido, $nombre, $usuario, $rol_id, $id]);

            echo json_encode(['message' => 'Usuario actualizado con éxito']);
            break;


        case 'DELETE':
            // Eliminar usuario
            $id = $_GET['id'];
            $stmt = $conn->prepare("DELETE FROM usuarios WHERE id = ?");
            $stmt->execute([$id]);
            echo json_encode(['message' => 'Usuario eliminado con éxito']);
            break;

        default:
            http_response_code(405); // Method Not Allowed
            echo json_encode(['error' => 'Método no permitido']);
            break;
    }
} catch (PDOException $e) {
    http_response_code(500); // Internal Server Error
    echo json_encode(['error' => 'Error en la base de datos: ' . $e->getMessage()]);
}
