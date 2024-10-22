<?php
require 'db.php'; // Conexión a la base de datos
header('Content-Type: application/json');

$action = $_GET['action'] ?? '';

if ($action == 'obtener_sectores') {
    $sectores = $conn->query("SELECT id, nombre FROM sectores")->fetch_all(MYSQLI_ASSOC);
    echo json_encode($sectores);
} elseif ($action == 'buscar_internaciones') {
    $data = json_decode(file_get_contents('php://input'), true);
    $sector_id = $data['sector_id'];
    $fecha_desde = $data['fecha_desde'];
    $fecha_hasta = $data['fecha_hasta'] ?: date('Y-m-d'); // Si no se especifica fecha_hasta, toma la fecha actual.

    // Consulta SQL para buscar internaciones
    $sql = "
        SELECT i.*, 
               p.nombre AS paciente_nombre, p.apellido AS paciente_apellido,
               d.descripcion AS dieta_descripcion,
               pr.nombre AS profesional_nombre, pr.apellido AS profesional_apellido,
               s.nombre AS sector_nombre
        FROM internaciones i
        JOIN pacientes p ON i.paciente_id = p.id
        JOIN dietas d ON i.dieta_id = d.id
        JOIN profesionales pr ON i.profesional_id = pr.id
        JOIN sectores s ON i.sector_id = s.id
        WHERE i.sector_id = ?
          AND (i.fecha_ingreso BETWEEN ? AND ? OR i.fecha_egreso IS NULL OR i.fecha_egreso <= ?)
    ";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isss", $sector_id, $fecha_desde, $fecha_hasta, $fecha_hasta);
    $stmt->execute();
    $result = $stmt->get_result();
    $internaciones = $result->fetch_all(MYSQLI_ASSOC);

    echo json_encode($internaciones);
}

$conn->close();
