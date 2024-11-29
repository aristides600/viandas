<?php
header('Content-Type: application/json');
include 'db.php'; // Conexión a la base de datos

try {
    // Preparar la consulta SQL
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
            WHERE i.fecha_egreso IS NULL AND pd.estado = 1";

    // Preparar la consulta con PDO
    $stmt = $conn->prepare($sql);

    // Ejecutar la consulta
    $stmt->execute();

    // Obtener los resultados en forma de array asociativo
    $pacientes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Enviar el resultado como JSON
    echo json_encode($pacientes);

} catch (PDOException $e) {
    // Manejo de errores en la consulta
    error_log("Error en la consulta SQL: " . $e->getMessage()); // Registrar el error en el log del servidor
    echo json_encode(['error' => 'Error al obtener los datos. Inténtalo más tarde.']);
}
?>
