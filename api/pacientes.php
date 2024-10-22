<?php
include 'db.php'; // Conexión a la base de datos

header('Content-Type: application/json');

// Crear conexión PDO
try {
    $pdo = new PDO("mysql:host=$servername;dbname=$database;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die(json_encode(['success' => false, 'message' => 'Error al conectar con la base de datos: ' . $e->getMessage()]));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Capturar la entrada JSON
    $data = json_decode(file_get_contents('php://input'), true);
    $action = $data['action'] ?? null;

    if (!$action) {
        echo json_encode(['success' => false, 'message' => 'Acción no especificada.']);
        exit;
    }

    switch ($action) {
        case 'create':
            $nombre = $data['nombre'] ?? '';
            $apellido = $data['apellido'] ?? '';
            $dni = $data['dni'] ?? '';
            $fecha_nacimiento = $data['fecha_nacimiento'] ?? null;
            $telefono = $data['telefono'] ?? '';
            $sexo_id = $data['sexo_id'] ?? '';

            if (empty($nombre) || empty($apellido) || empty($dni)) {
                echo json_encode(['success' => false, 'message' => 'Todos los campos obligatorios deben ser completados.']);
                exit;
            }

            $stmt = $pdo->prepare("INSERT INTO pacientes (nombre, apellido, dni, fecha_nacimiento, telefono, sexo_id, estado) VALUES (?, ?, ?, ?, ?, ?, 1)");
            if ($stmt->execute([$nombre, $apellido, $dni, $fecha_nacimiento, $telefono, $sexo_id])) {
                echo json_encode(['success' => true, 'message' => 'Paciente creado exitosamente.']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Error al crear paciente.']);
            }
            break;

        case 'update':
            $id = $data['id'] ?? null;
            $nombre = $data['nombre'] ?? '';
            $apellido = $data['apellido'] ?? '';
            $dni = $data['dni'] ?? '';
            $fecha_nacimiento = $data['fecha_nacimiento'] ?? null;
            $telefono = $data['telefono'] ?? '';
            $sexo_id = $data['sexo_id'] ?? '';

            if (empty($nombre) || empty($apellido) || empty($dni) || !$id) {
                echo json_encode(['success' => false, 'message' => 'Todos los campos obligatorios deben ser completados.']);
                exit;
            }

            $stmt = $pdo->prepare("UPDATE pacientes SET nombre=?, apellido=?, dni=?, fecha_nacimiento=?, telefono=?, sexo_id=? WHERE id=?");
            if ($stmt->execute([$nombre, $apellido, $dni, $fecha_nacimiento, $telefono, $sexo_id, $id])) {
                echo json_encode(['success' => true, 'message' => 'Paciente actualizado exitosamente.']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Error al actualizar paciente.']);
            }
            break;

        case 'delete':
            $id = $data['id'] ?? null;

            if (!$id) {
                echo json_encode(['success' => false, 'message' => 'ID no especificado.']);
                exit;
            }

            $stmt = $pdo->prepare("UPDATE pacientes SET estado=0 WHERE id=?");
            if ($stmt->execute([$id])) {
                echo json_encode(['success' => true, 'message' => 'Paciente marcado como eliminado exitosamente.']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Error al marcar paciente como eliminado.']);
            }
            break;

        default:
            echo json_encode(['success' => false, 'message' => 'Acción no válida.']);
            break;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'read') {
    $stmt = $pdo->prepare("SELECT * FROM pacientes WHERE estado = 1");
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($result);
}
?>
