<?php
require 'db.php'; // Conexión a la base de datos
header('Content-Type: application/json');

if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Error en la conexión a la base de datos.']);
    exit;
}

$action = $_GET['action'] ?? '';

if ($action == 'datos_iniciales') {
    $pacientes = $conn->query("SELECT id, nombre, apellido FROM pacientes")->fetch_all(MYSQLI_ASSOC);
    $profesionales = $conn->query("SELECT id, nombre, apellido FROM profesionales")->fetch_all(MYSQLI_ASSOC);
    $dietas = $conn->query("SELECT id, descripcion FROM dietas")->fetch_all(MYSQLI_ASSOC);
    $sectores = $conn->query("SELECT id, nombre FROM sectores")->fetch_all(MYSQLI_ASSOC);

    echo json_encode([
        'pacientes' => $pacientes,
        'profesionales' => $profesionales,
        'dietas' => $dietas,
        'sectores' => $sectores
    ]);
} elseif ($action == 'registrar') {
    $data = json_decode(file_get_contents('php://input'), true);

    $paciente_id = $data['paciente_id'] ?? '';
    $fecha_ingreso = $data['fecha_ingreso'] ?? '';
    $fecha_egreso = $data['fecha_egreso'] ?? null;
    $dieta_id = $data['dieta_id'] ?? '';
    $profesional_id = $data['profesional_id'] ?? '';
    $sector_id = $data['sector_id'] ?? '';
    $diagnostico = $data['diagnostico'] ?? '';
    $observacion = $data['observacion'] ?? '';

    if (empty($paciente_id) || empty($fecha_ingreso) || empty($dieta_id) || empty($profesional_id) || empty($sector_id)) {
        echo json_encode(['success' => false, 'message' => 'Todos los campos son obligatorios.']);
        exit;
    }

    $stmt = $conn->prepare("INSERT INTO internaciones (paciente_id, fecha_ingreso, fecha_egreso, dieta_id, profesional_id, sector_id, diagnostico, observacion) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("issiiiss", $paciente_id, $fecha_ingreso, $fecha_egreso, $dieta_id, $profesional_id, $sector_id, $diagnostico, $observacion);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Internación registrada exitosamente.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al registrar la internación.']);
    }

    $stmt->close();
}

$conn->close();
