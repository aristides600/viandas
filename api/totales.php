<?php
header('Content-Type: application/json');

// Conexión a la base de datos
require_once 'db.php';

$fecha = date('Y-m-d');

// Consulta SQL para el primer grupo de sectores (ID 1, 2, 3) - Almuerzo
$sqlGrupo1Almuerzo = "
    SELECT 
        s.nombre AS sector,
        SUM(CASE WHEN c.postre_id = 1 THEN c.cantidad ELSE 0 END) AS total_flan,
        SUM(CASE WHEN c.postre_id = 2 THEN c.cantidad ELSE 0 END) AS total_gelatina,
        SUM(CASE WHEN d.id = 9 THEN (c.cantidad + c.acompaniante) ELSE 0 END) AS total_dietas_generales,
        SUM(CASE WHEN d.id != 9 THEN c.cantidad ELSE 0 END) AS total_otras_dietas
    FROM consumos_diarios c
    JOIN sectores s ON c.sector_id = s.id
    JOIN dietas d ON c.dieta_id = d.id
    WHERE c.fecha_consumo = :fecha_consumo AND c.sector_id IN (1, 2, 3) AND c.comida_id = 1
    GROUP BY c.sector_id
";

// Consulta SQL para el primer grupo de sectores (ID 1, 2, 3) - Cena
$sqlGrupo1Cena = "
    SELECT 
        s.nombre AS sector,
        SUM(CASE WHEN c.postre_id = 1 THEN c.cantidad ELSE 0 END) AS total_flan,
        SUM(CASE WHEN c.postre_id = 2 THEN c.cantidad ELSE 0 END) AS total_gelatina,
        SUM(CASE WHEN d.id = 9 THEN (c.cantidad + c.acompaniante) ELSE 0 END) AS total_dietas_generales,
        SUM(CASE WHEN d.id != 9 THEN c.cantidad ELSE 0 END) AS total_otras_dietas
    FROM consumos_diarios c
    JOIN sectores s ON c.sector_id = s.id
    JOIN dietas d ON c.dieta_id = d.id
    WHERE c.fecha_consumo = :fecha_consumo AND c.sector_id IN (1, 2, 3) AND c.comida_id = 2
    GROUP BY c.sector_id
";

// Consulta SQL para el segundo grupo de sectores (ID 4 - 9) - Almuerzo
$sqlGrupo2Almuerzo = "
    SELECT 
        s.nombre AS sector,
        SUM(CASE WHEN c.postre_id = 1 THEN c.cantidad ELSE 0 END) AS total_flan,
        SUM(CASE WHEN c.postre_id = 2 THEN c.cantidad ELSE 0 END) AS total_gelatina,
        SUM(CASE WHEN d.id = 9 THEN (c.cantidad + c.acompaniante) ELSE 0 END) AS total_dietas_generales,
        SUM(CASE WHEN d.id != 9 THEN c.cantidad ELSE 0 END) AS total_otras_dietas
    FROM consumos_diarios c
    JOIN sectores s ON c.sector_id = s.id
    JOIN dietas d ON c.dieta_id = d.id
    WHERE c.fecha_consumo = :fecha_consumo AND c.sector_id BETWEEN 4 AND 9 AND c.comida_id = 1
    GROUP BY c.sector_id
";

// Consulta SQL para el segundo grupo de sectores (ID 4 - 9) - Cena
$sqlGrupo2Cena = "
    SELECT 
        s.nombre AS sector,
        SUM(CASE WHEN c.postre_id = 1 THEN c.cantidad ELSE 0 END) AS total_flan,
        SUM(CASE WHEN c.postre_id = 2 THEN c.cantidad ELSE 0 END) AS total_gelatina,
        SUM(CASE WHEN d.id = 9 THEN (c.cantidad + c.acompaniante) ELSE 0 END) AS total_dietas_generales,
        SUM(CASE WHEN d.id != 9 THEN c.cantidad ELSE 0 END) AS total_otras_dietas
    FROM consumos_diarios c
    JOIN sectores s ON c.sector_id = s.id
    JOIN dietas d ON c.dieta_id = d.id
    WHERE c.fecha_consumo = :fecha_consumo AND c.sector_id BETWEEN 4 AND 9 AND c.comida_id = 2
    GROUP BY c.sector_id
";

// Consulta SQL para los totales generales - Almuerzo
$sqlTotalesAlmuerzo = "
    SELECT 
        SUM(CASE WHEN c.postre_id = 1 THEN c.cantidad ELSE 0 END) AS total_flan,
        SUM(CASE WHEN c.postre_id = 2 THEN c.cantidad ELSE 0 END) AS total_gelatina,
        SUM(CASE WHEN d.id = 9 THEN (c.cantidad + c.acompaniante) ELSE 0 END) AS total_dietas_generales,
        SUM(CASE WHEN d.id != 9 THEN c.cantidad ELSE 0 END) AS total_otras_dietas
    FROM consumos_diarios c
    JOIN dietas d ON c.dieta_id = d.id
    WHERE c.fecha_consumo = :fecha_consumo AND c.comida_id = 1
";

// Consulta SQL para los totales generales - Cena
$sqlTotalesCena = "
    SELECT 
        SUM(CASE WHEN c.postre_id = 1 THEN c.cantidad ELSE 0 END) AS total_flan,
        SUM(CASE WHEN c.postre_id = 2 THEN c.cantidad ELSE 0 END) AS total_gelatina,
        SUM(CASE WHEN d.id = 9 THEN (c.cantidad + c.acompaniante) ELSE 0 END) AS total_dietas_generales,
        SUM(CASE WHEN d.id != 9 THEN c.cantidad ELSE 0 END) AS total_otras_dietas
    FROM consumos_diarios c
    JOIN dietas d ON c.dieta_id = d.id
    WHERE c.fecha_consumo = :fecha_consumo AND c.comida_id = 2
";

try {
    // Ejecutar las consultas para almuerzo y cena
    // Grupo 1 Almuerzo
    $consultaGrupo1Almuerzo = $conn->prepare($sqlGrupo1Almuerzo);
    $consultaGrupo1Almuerzo->bindParam(':fecha_consumo', $fecha, PDO::PARAM_STR);
    $consultaGrupo1Almuerzo->execute();
    $datosGrupo1Almuerzo = $consultaGrupo1Almuerzo->fetchAll(PDO::FETCH_ASSOC);

    // Grupo 1 Cena
    $consultaGrupo1Cena = $conn->prepare($sqlGrupo1Cena);
    $consultaGrupo1Cena->bindParam(':fecha_consumo', $fecha, PDO::PARAM_STR);
    $consultaGrupo1Cena->execute();
    $datosGrupo1Cena = $consultaGrupo1Cena->fetchAll(PDO::FETCH_ASSOC);

    // Grupo 2 Almuerzo
    $consultaGrupo2Almuerzo = $conn->prepare($sqlGrupo2Almuerzo);
    $consultaGrupo2Almuerzo->bindParam(':fecha_consumo', $fecha, PDO::PARAM_STR);
    $consultaGrupo2Almuerzo->execute();
    $datosGrupo2Almuerzo = $consultaGrupo2Almuerzo->fetchAll(PDO::FETCH_ASSOC);

    // Grupo 2 Cena
    $consultaGrupo2Cena = $conn->prepare($sqlGrupo2Cena);
    $consultaGrupo2Cena->bindParam(':fecha_consumo', $fecha, PDO::PARAM_STR);
    $consultaGrupo2Cena->execute();
    $datosGrupo2Cena = $consultaGrupo2Cena->fetchAll(PDO::FETCH_ASSOC);

    // Ejecutar las consultas de los totales generales
    $consultaTotalesAlmuerzo = $conn->prepare($sqlTotalesAlmuerzo);
    $consultaTotalesAlmuerzo->bindParam(':fecha_consumo', $fecha, PDO::PARAM_STR);
    $consultaTotalesAlmuerzo->execute();
    $totalesAlmuerzo = $consultaTotalesAlmuerzo->fetch(PDO::FETCH_ASSOC);

    $consultaTotalesCena = $conn->prepare($sqlTotalesCena);
    $consultaTotalesCena->bindParam(':fecha_consumo', $fecha, PDO::PARAM_STR);
    $consultaTotalesCena->execute();
    $totalesCena = $consultaTotalesCena->fetch(PDO::FETCH_ASSOC);

    // Crear la respuesta con los datos de cada grupo y los totales generales
    $respuesta = [
        'por_sector' => [
            'almuerzo' => [
                'grupo_1' => $datosGrupo1Almuerzo,
                'grupo_2' => $datosGrupo2Almuerzo,
                'totales' => $totalesAlmuerzo // Totales generales de almuerzo
            ],
            'cena' => [
                'grupo_1' => $datosGrupo1Cena,
                'grupo_2' => $datosGrupo2Cena,
                'totales' => $totalesCena // Totales generales de cena
            ]
        ]
    ];

    echo json_encode($respuesta);
} catch (PDOException $e) {
    error_log("Error en la consulta: " . $e->getMessage());
    echo json_encode(['error' => 'Error en la consulta.']);
}
?>
