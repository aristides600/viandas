<?php
header('Content-Type: application/json');

include 'db.php'; // Incluye la conexión a la base de datos con PDO

try {
    // Consulta SQL para obtener los pacientes internados
    $sql = "SELECT 
                p.nombre AS nombre_paciente,
                p.apellido AS apellido_paciente,
                d.nombre AS nombre_dieta,
                d.codigo AS codigo_dieta,
                s.nombre AS nombre_sector,
                pd.observacion
            FROM pacientes p
            JOIN internaciones i ON p.id = i.paciente_id
            JOIN pacientes_dietas pd ON p.id = pd.paciente_id AND i.id = pd.internacion_id
            JOIN dietas d ON pd.dieta_id = d.id
            JOIN sectores s ON i.sector_id = s.id
            WHERE i.fecha_egreso IS NULL AND pd.estado = 1 AND pd.fecha_consumo = CURDATE()";

    // Preparar la consulta
    $stmt = $conn->prepare($sql);
    
    // Ejecutar la consulta
    $stmt->execute();
    
    // Obtener los resultados como un array asociativo
    $pacientes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Devolver los resultados en formato JSON
    echo json_encode($pacientes);

} catch (PDOException $e) {
    // Manejo de errores
    error_log("Error en la consulta SQL: " . $e->getMessage()); // Registrar el error en el log del servidor
    echo json_encode(['error' => 'Error al obtener los datos. Inténtelo más tarde.']);
}

// Cerrar conexión (PDO no requiere explícitamente cerrar conexiones, se maneja automáticamente)
?>
