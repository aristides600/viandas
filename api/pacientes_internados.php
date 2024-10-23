<?php
header('Content-Type: application/json');

include 'db.php'; // Incluir la conexión a la base de datos con PDO

try {
    // Consulta SQL para obtener los datos de pacientes internados, dietas y sectores
    $sql = "
        SELECT 
            p.apellido AS apellido_paciente,
            p.nombre AS nombre_paciente,
            d.codigo AS codigo_dieta,
            d.nombre AS nombre_dieta,  -- Añadido nombre de la dieta
            pd.observacion,
            c.nombre AS nombre_comida,
            s.nombre AS nombre_sector,
            pd.fecha_consumo
        FROM pacientes p
        JOIN internaciones i ON p.id = i.paciente_id
        JOIN pacientes_dietas pd ON p.id = pd.paciente_id AND i.id = pd.internacion_id
        JOIN dietas d ON pd.dieta_id = d.id
        JOIN sectores s ON i.sector_id = s.id
        JOIN comidas c ON pd.comida_id = c.id
        WHERE i.fecha_egreso IS NULL 
          AND pd.estado = 1 
          AND pd.fecha_consumo = CURDATE()
    ";

    // Preparar la consulta
    $stmt = $conn->prepare($sql);

    // Ejecutar la consulta
    $stmt->execute();

    // Obtener los resultados como un array asociativo
    $pacientes_dietas = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Devolver los resultados en formato JSON
    echo json_encode($pacientes_dietas);

} catch (PDOException $e) {
    // Manejo de errores, registrar el error y enviar una respuesta de error
    error_log("Error en la consulta SQL: " . $e->getMessage()); // Registrar el error en el log del servidor
    echo json_encode(['error' => 'Error al obtener los datos. Inténtelo más tarde.']);
}

// La conexión PDO se cierra automáticamente al final del script
?>
