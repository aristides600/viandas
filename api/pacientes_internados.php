<?php
header('Content-Type: application/json');
include 'db.php'; // Conexión a la base de datos con PDO

$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

try {
    $sql = "
        SELECT 
            p.dni,
            p.apellido AS apellido_paciente,
            p.nombre AS nombre_paciente,
            d.codigo AS codigo_dieta,
            d.nombre AS nombre_dieta,
            pd.observacion,
            pd.acompaniante,
            s.nombre AS nombre_sector,
            pd.fecha_consumo,
            c.nombre AS nombre_comida,
            TIMESTAMPDIFF(YEAR, p.fecha_nacimiento, CURDATE()) AS edad,
            CASE p.sexo_id
                WHEN 1 THEN 'Masculino'
                WHEN 2 THEN 'Femenino'
                ELSE 'Otro'
            END AS sexo
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

    if ($searchTerm) {
        $sql .= " AND (p.dni LIKE :search OR p.apellido LIKE :search)";
    }

    $stmt = $conn->prepare($sql);

    if ($searchTerm) {
        $stmt->bindValue(':search', "%$searchTerm%");
    }

    $stmt->execute();
    $pacientes_dietas = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($pacientes_dietas);

} catch (PDOException $e) {
    error_log("Error en la consulta SQL: " . $e->getMessage());
    echo json_encode(['error' => 'Error al obtener los datos. Inténtelo más tarde.']);
}
?>
