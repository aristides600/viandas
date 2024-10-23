<?php
// Conexión a la base de datos usando PDO
include 'db.php'; // Asegúrate de tener tu conexión a la base de datos en db.php

header('Content-Type: application/json'); // Establece el tipo de contenido como JSON

try {
    // Obtener las fechas desde el parámetro GET
    $fecha_desde = isset($_GET['fecha_desde']) ? $_GET['fecha_desde'] : null;
    $fecha_hasta = isset($_GET['fecha_hasta']) ? $_GET['fecha_hasta'] : null;

    // Validar las fechas
    if (!$fecha_desde || !$fecha_hasta) {
        echo json_encode(['error' => 'Fechas no proporcionadas']);
        exit;
    }

    // Asegurarse de que las fechas estén en el formato correcto
    $fecha_desde = date('Y-m-d H:i:s', strtotime($fecha_desde));
    $fecha_hasta = date('Y-m-d H:i:s', strtotime($fecha_hasta));

    // Consulta actualizada
    $query = "SELECT 
                sectores.nombre AS sector,
                dietas.nombre AS dieta,
                COUNT(internaciones.id) AS cantidad
              FROM 
                internaciones
              JOIN 
                pacientes_dietas ON internaciones.id = pacientes_dietas.internacion_id
              JOIN 
                dietas ON pacientes_dietas.dieta_id = dietas.id
              JOIN 
                sectores ON internaciones.sector_id = sectores.id
              WHERE 
                internaciones.fecha_ingreso BETWEEN :fecha_desde AND :fecha_hasta
              GROUP BY 
                sectores.nombre, dietas.nombre
              ORDER BY 
                sectores.nombre, dietas.nombre";

    // Preparar la consulta
    $stmt = $conn->prepare($query);
    
    // Asignar los parámetros
    $stmt->bindParam(':fecha_desde', $fecha_desde);
    $stmt->bindParam(':fecha_hasta', $fecha_hasta);
    
    // Ejecutar la consulta
    $stmt->execute();

    // Obtener los resultados
    $reportData = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Devolver los datos en formato JSON
    echo json_encode($reportData);

} catch (Exception $e) {
    // Manejo de excepciones
    echo json_encode(['error' => $e->getMessage()]);
}
?>
