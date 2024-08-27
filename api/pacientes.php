<?php
include 'db.php'; // Asegúrate de tener tu conexión a la base de datos en db.php

header('Content-Type: application/json'); // Establece el tipo de contenido como JSON

// Crear conexión PDO
try {
    $pdo = new PDO("mysql:host=$servername;dbname=$database;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die(json_encode(['success' => false, 'message' => 'Error al conectar con la base de datos: ' . $e->getMessage()]));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? null;

    // Depura los datos recibidos
    var_dump($_POST);
    exit;

    if (!$action) {
        echo json_encode(['success' => false, 'message' => 'Acción no especificada.']);
        exit;
    }

    switch ($action) {
        case 'create':
            $nombre = $_POST['nombre'] ?? '';
            $apellido = $_POST['apellido'] ?? '';
            $dni = $_POST['dni'] ?? '';
            $fecha_nacimiento = $_POST['fecha_nacimiento'] ?? null;
            $telefono = $_POST['telefono'] ?? '';
            $direccion = $_POST['direccion'] ?? '';

            if (empty($nombre) || empty($apellido) || empty($dni)) {
                echo json_encode(['success' => false, 'message' => 'Todos los campos obligatorios deben ser completados.']);
                exit;
            }

            $stmt = $pdo->prepare("INSERT INTO pacientes (nombre, apellido, dni, fecha_nacimiento, telefono, direccion, estado) VALUES (?, ?, ?, ?, ?, ?, 1)");
            if ($stmt->execute([$nombre, $apellido, $dni, $fecha_nacimiento, $telefono, $direccion])) {
                echo json_encode(['success' => true, 'message' => 'Paciente creado exitosamente.']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Error al crear paciente.']);
            }
            break;

        case 'update':
            $id = $_POST['id'] ?? null;
            $nombre = $_POST['nombre'] ?? '';
            $apellido = $_POST['apellido'] ?? '';
            $dni = $_POST['dni'] ?? '';
            $fecha_nacimiento = $_POST['fecha_nacimiento'] ?? null;
            $telefono = $_POST['telefono'] ?? '';
            $direccion = $_POST['direccion'] ?? '';

            if (empty($nombre) || empty($apellido) || empty($dni) || !$id) {
                echo json_encode(['success' => false, 'message' => 'Todos los campos obligatorios deben ser completados.']);
                exit;
            }

            $stmt = $pdo->prepare("UPDATE pacientes SET nombre=?, apellido=?, dni=?, fecha_nacimiento=?, telefono=?, direccion=? WHERE id=?");
            if ($stmt->execute([$nombre, $apellido, $dni, $fecha_nacimiento, $telefono, $direccion, $id])) {
                echo json_encode(['success' => true, 'message' => 'Paciente actualizado exitosamente.']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Error al actualizar paciente.']);
            }
            break;

        case 'delete':
            $id = $_POST['id'] ?? null;

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
