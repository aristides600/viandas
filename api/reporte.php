<?php
// Conexión a la base de datos
include 'db.php'; // Asegúrate de tener tu conexión a la base de datos en db.php

header('Content-Type: application/json'); // Establece el tipo de contenido como JSON

try {
    $fecha_desde = $_GET['fecha_desde'];
    $fecha_hasta = $_GET['fecha_hasta'];

    if (!$fecha_desde || !$fecha_hasta) {
        echo json_encode(['error' => 'Fechas no proporcionadas']);
        exit;
    }

    $query = "SELECT 
                sectores.nombre AS sector,
                dietas.descripcion AS dieta,
                COUNT(internaciones.id) AS cantidad
              FROM 
                internaciones
              JOIN 
                dietas ON internaciones.dieta_id = dietas.id
              JOIN 
                sectores ON internaciones.sector_id = sectores.id
              WHERE 
                internaciones.fecha_ingreso BETWEEN ? AND ?
              GROUP BY 
                sectores.nombre, dietas.descripcion
              ORDER BY 
                sectores.nombre, dietas.descripcion";

    $stmt = $conn->prepare($query);
    if (!$stmt) {
        throw new Exception("Error en la preparación de la consulta: " . $conn->error);
    }
    
    $stmt->bind_param('ss', $fecha_desde, $fecha_hasta);
    if (!$stmt->execute()) {
        throw new Exception("Error en la ejecución de la consulta: " . $stmt->error);
    }

    $result = $stmt->get_result();
    $reportData = [];

    while ($row = $result->fetch_assoc()) {
        $reportData[] = $row;
    }

    echo json_encode($reportData);

} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
