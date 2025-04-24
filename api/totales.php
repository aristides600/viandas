<?php
// header('Content-Type: application/json');

// // Conexión a la base de datos
// require_once 'db.php';

// $fecha = date('Y-m-d');

// // Consulta SQL para el primer grupo de sectores (ID 1, 2, 3) - Almuerzo
// $sqlGrupo1Almuerzo = "
//     SELECT 
//         s.nombre AS sector,
//         SUM(CASE WHEN c.postre_id = 1 THEN c.cantidad ELSE 0 END) AS total_flan,
//         SUM(CASE WHEN c.postre_id = 2 THEN c.cantidad ELSE 0 END) AS total_gelatina,
//         SUM(CASE WHEN d.id = 9 THEN (c.cantidad + c.acompaniante) ELSE 0 END) AS total_dietas_generales,
//         SUM(CASE WHEN d.id != 9 THEN c.cantidad ELSE 0 END) AS total_otras_dietas
//     FROM consumos_diarios c
//     JOIN sectores s ON c.sector_id = s.id
//     JOIN dietas d ON c.dieta_id = d.id
//     WHERE c.fecha_consumo = :fecha_consumo AND c.sector_id IN (1, 2, 3) AND c.comida_id = 1
//     GROUP BY c.sector_id
// ";

// // Consulta SQL para el primer grupo de sectores (ID 1, 2, 3) - Cena
// $sqlGrupo1Cena = "
//     SELECT 
//         s.nombre AS sector,
//         SUM(CASE WHEN c.postre_id = 1 THEN c.cantidad ELSE 0 END) AS total_flan,
//         SUM(CASE WHEN c.postre_id = 2 THEN c.cantidad ELSE 0 END) AS total_gelatina,
//         SUM(CASE WHEN d.id = 9 THEN (c.cantidad + c.acompaniante) ELSE 0 END) AS total_dietas_generales,
//         SUM(CASE WHEN d.id != 9 THEN c.cantidad ELSE 0 END) AS total_otras_dietas
//     FROM consumos_diarios c
//     JOIN sectores s ON c.sector_id = s.id
//     JOIN dietas d ON c.dieta_id = d.id
//     WHERE c.fecha_consumo = :fecha_consumo AND c.sector_id IN (1, 2, 3) AND c.comida_id = 2
//     GROUP BY c.sector_id
// ";

// // Consulta SQL para el segundo grupo de sectores (ID 4 - 9) - Almuerzo
// $sqlGrupo2Almuerzo = "
//     SELECT 
//         s.nombre AS sector,
//         SUM(CASE WHEN c.postre_id = 1 THEN c.cantidad ELSE 0 END) AS total_flan,
//         SUM(CASE WHEN c.postre_id = 2 THEN c.cantidad ELSE 0 END) AS total_gelatina,
//         SUM(CASE WHEN d.id = 9 THEN (c.cantidad + c.acompaniante) ELSE 0 END) AS total_dietas_generales,
//         SUM(CASE WHEN d.id != 9 THEN c.cantidad ELSE 0 END) AS total_otras_dietas
//     FROM consumos_diarios c
//     JOIN sectores s ON c.sector_id = s.id
//     JOIN dietas d ON c.dieta_id = d.id
//     WHERE c.fecha_consumo = :fecha_consumo AND c.sector_id BETWEEN 4 AND 9 AND c.comida_id = 1
//     GROUP BY c.sector_id
// ";

// // Consulta SQL para el segundo grupo de sectores (ID 4 - 9) - Cena
// $sqlGrupo2Cena = "
//     SELECT 
//         s.nombre AS sector,
//         SUM(CASE WHEN c.postre_id = 1 THEN c.cantidad ELSE 0 END) AS total_flan,
//         SUM(CASE WHEN c.postre_id = 2 THEN c.cantidad ELSE 0 END) AS total_gelatina,
//         SUM(CASE WHEN d.id = 9 THEN (c.cantidad + c.acompaniante) ELSE 0 END) AS total_dietas_generales,
//         SUM(CASE WHEN d.id != 9 THEN c.cantidad ELSE 0 END) AS total_otras_dietas
//     FROM consumos_diarios c
//     JOIN sectores s ON c.sector_id = s.id
//     JOIN dietas d ON c.dieta_id = d.id
//     WHERE c.fecha_consumo = :fecha_consumo AND c.sector_id BETWEEN 4 AND 9 AND c.comida_id = 2
//     GROUP BY c.sector_id
// ";

// // Consulta SQL para los totales generales - Almuerzo
// $sqlTotalesAlmuerzo = "
//     SELECT 
//         SUM(CASE WHEN c.postre_id = 1 THEN c.cantidad ELSE 0 END) AS total_flan,
//         SUM(CASE WHEN c.postre_id = 2 THEN c.cantidad ELSE 0 END) AS total_gelatina,
//         SUM(CASE WHEN d.id = 9 THEN (c.cantidad + c.acompaniante) ELSE 0 END) AS total_dietas_generales,
//         SUM(CASE WHEN d.id != 9 THEN c.cantidad ELSE 0 END) AS total_otras_dietas
//     FROM consumos_diarios c
//     JOIN dietas d ON c.dieta_id = d.id
//     WHERE c.fecha_consumo = :fecha_consumo AND c.comida_id = 1
// ";

// // Consulta SQL para los totales generales - Cena
// $sqlTotalesCena = "
//     SELECT 
//         SUM(CASE WHEN c.postre_id = 1 THEN c.cantidad ELSE 0 END) AS total_flan,
//         SUM(CASE WHEN c.postre_id = 2 THEN c.cantidad ELSE 0 END) AS total_gelatina,
//         SUM(CASE WHEN d.id = 9 THEN (c.cantidad + c.acompaniante) ELSE 0 END) AS total_dietas_generales,
//         SUM(CASE WHEN d.id != 9 THEN c.cantidad ELSE 0 END) AS total_otras_dietas
//     FROM consumos_diarios c
//     JOIN dietas d ON c.dieta_id = d.id
//     WHERE c.fecha_consumo = :fecha_consumo AND c.comida_id = 2
// ";

// try {
//     // Ejecutar las consultas para almuerzo y cena
//     // Grupo 1 Almuerzo
//     $consultaGrupo1Almuerzo = $conn->prepare($sqlGrupo1Almuerzo);
//     $consultaGrupo1Almuerzo->bindParam(':fecha_consumo', $fecha, PDO::PARAM_STR);
//     $consultaGrupo1Almuerzo->execute();
//     $datosGrupo1Almuerzo = $consultaGrupo1Almuerzo->fetchAll(PDO::FETCH_ASSOC);

//     // Grupo 1 Cena
//     $consultaGrupo1Cena = $conn->prepare($sqlGrupo1Cena);
//     $consultaGrupo1Cena->bindParam(':fecha_consumo', $fecha, PDO::PARAM_STR);
//     $consultaGrupo1Cena->execute();
//     $datosGrupo1Cena = $consultaGrupo1Cena->fetchAll(PDO::FETCH_ASSOC);

//     // Grupo 2 Almuerzo
//     $consultaGrupo2Almuerzo = $conn->prepare($sqlGrupo2Almuerzo);
//     $consultaGrupo2Almuerzo->bindParam(':fecha_consumo', $fecha, PDO::PARAM_STR);
//     $consultaGrupo2Almuerzo->execute();
//     $datosGrupo2Almuerzo = $consultaGrupo2Almuerzo->fetchAll(PDO::FETCH_ASSOC);

//     // Grupo 2 Cena
//     $consultaGrupo2Cena = $conn->prepare($sqlGrupo2Cena);
//     $consultaGrupo2Cena->bindParam(':fecha_consumo', $fecha, PDO::PARAM_STR);
//     $consultaGrupo2Cena->execute();
//     $datosGrupo2Cena = $consultaGrupo2Cena->fetchAll(PDO::FETCH_ASSOC);

//     // Ejecutar las consultas de los totales generales
//     $consultaTotalesAlmuerzo = $conn->prepare($sqlTotalesAlmuerzo);
//     $consultaTotalesAlmuerzo->bindParam(':fecha_consumo', $fecha, PDO::PARAM_STR);
//     $consultaTotalesAlmuerzo->execute();
//     $totalesAlmuerzo = $consultaTotalesAlmuerzo->fetch(PDO::FETCH_ASSOC);

//     $consultaTotalesCena = $conn->prepare($sqlTotalesCena);
//     $consultaTotalesCena->bindParam(':fecha_consumo', $fecha, PDO::PARAM_STR);
//     $consultaTotalesCena->execute();
//     $totalesCena = $consultaTotalesCena->fetch(PDO::FETCH_ASSOC);

//     // Crear la respuesta con los datos de cada grupo y los totales generales
//     $respuesta = [
//         'por_sector' => [
//             'almuerzo' => [
//                 'grupo_1' => $datosGrupo1Almuerzo,
//                 'grupo_2' => $datosGrupo2Almuerzo,
//                 'totales' => $totalesAlmuerzo // Totales generales de almuerzo
//             ],
//             'cena' => [
//                 'grupo_1' => $datosGrupo1Cena,
//                 'grupo_2' => $datosGrupo2Cena,
//                 'totales' => $totalesCena // Totales generales de cena
//             ]
//         ]
//     ];

//     echo json_encode($respuesta);
// } catch (PDOException $e) {
//     error_log("Error en la consulta: " . $e->getMessage());
//     echo json_encode(['error' => 'Error en la consulta.']);
// }
// header('Content-Type: application/json');
// require_once 'db.php';

// $fecha = date('Y-m-d');

// // Definimos los grupos de sectores
// $gruposSectores = [
//     'grupo_1' => [1, 2, 3],
//     'grupo_2' => range(4, 9),
//     // Podés seguir agregando más grupos si querés
//     // 'grupo_3' => [10, 11, 12],
// ];

// // Definimos las comidas (ID => Nombre)
// $comidas = [
//     1 => 'almuerzo',
//     2 => 'cena',
// ];

// $respuesta = ['por_sector' => []];

// try {
//     foreach ($comidas as $comidaId => $nombreComida) {
//         $respuesta['por_sector'][$nombreComida] = [];

//         // Totales generales para la comida
//         $sqlTotales = "
//             SELECT 
//                 SUM(CASE WHEN c.postre_id = 1 THEN c.cantidad ELSE 0 END) AS total_flan,
//                 SUM(CASE WHEN c.postre_id = 2 THEN c.cantidad ELSE 0 END) AS total_gelatina,
//                 SUM(CASE WHEN d.id = 9 THEN (c.cantidad + c.acompaniante) ELSE 0 END) AS total_dietas_generales,
//                 SUM(CASE WHEN d.id != 9 THEN c.cantidad ELSE 0 END) AS total_otras_dietas
//             FROM consumos_diarios c
//             JOIN dietas d ON c.dieta_id = d.id
//             WHERE c.fecha_consumo = :fecha AND c.comida_id = :comida_id
//         ";
//         $stmtTotales = $conn->prepare($sqlTotales);
//         $stmtTotales->execute([':fecha' => $fecha, ':comida_id' => $comidaId]);
//         $totalesGenerales = $stmtTotales->fetch(PDO::FETCH_ASSOC);

//         // Guardar totales generales
//         $respuesta['por_sector'][$nombreComida]['totales'] = $totalesGenerales;

//         // Totales por grupo
//         foreach ($gruposSectores as $nombreGrupo => $sectores) {
//             $inSectores = implode(',', $sectores); // Para la cláusula IN

//             $sqlGrupo = "
//                 SELECT 
//                     s.nombre AS sector,
//                     SUM(CASE WHEN c.postre_id = 1 THEN c.cantidad ELSE 0 END) AS total_flan,
//                     SUM(CASE WHEN c.postre_id = 2 THEN c.cantidad ELSE 0 END) AS total_gelatina,
//                     SUM(CASE WHEN d.id = 9 THEN (c.cantidad + c.acompaniante) ELSE 0 END) AS total_dietas_generales,
//                     SUM(CASE WHEN d.id != 9 THEN c.cantidad ELSE 0 END) AS total_otras_dietas
//                 FROM consumos_diarios c
//                 JOIN sectores s ON c.sector_id = s.id
//                 JOIN dietas d ON c.dieta_id = d.id
//                 WHERE c.fecha_consumo = :fecha AND c.comida_id = :comida_id AND c.sector_id IN ($inSectores)
//                 GROUP BY c.sector_id
//             ";

//             $stmtGrupo = $conn->prepare($sqlGrupo);
//             $stmtGrupo->execute([':fecha' => $fecha, ':comida_id' => $comidaId]);
//             $datosGrupo = $stmtGrupo->fetchAll(PDO::FETCH_ASSOC);

//             $respuesta['por_sector'][$nombreComida][$nombreGrupo] = $datosGrupo;
//         }
//     }

//     echo json_encode($respuesta);
// } catch (PDOException $e) {
//     error_log("Error en la consulta: " . $e->getMessage());
//     echo json_encode(['error' => 'Error en la consulta.']);
// }

// header('Content-Type: application/json');

// // Conexión a la base de datos
// require_once 'db.php';

// $fecha = date('Y-m-d');

// // Consultas ajustadas para almuerzo y cena de cada grupo

// // Grupo 1 Almuerzo
// $sqlGrupo1Almuerzo = "
//     SELECT 
//         s.nombre AS sector,
//         SUM(CASE WHEN c.postre_id = 1 THEN c.cantidad ELSE 0 END) AS total_flan,
//         SUM(CASE WHEN c.postre_id = 2 THEN c.cantidad ELSE 0 END) AS total_gelatina,
//         SUM(CASE WHEN d.id = 9 THEN (c.cantidad + c.acompaniante) ELSE 0 END) AS total_dietas_generales,
//         SUM(CASE WHEN d.id != 9 THEN c.cantidad ELSE 0 END) AS total_otras_dietas
//     FROM consumos_diarios c
//     JOIN sectores s ON c.sector_id = s.id
//     JOIN dietas d ON c.dieta_id = d.id
//     WHERE c.fecha_consumo = :fecha_consumo AND c.sector_id IN (1, 2, 3) AND c.comida_id = 1
//     GROUP BY c.sector_id
// ";

// // Grupo 1 Cena
// $sqlGrupo1Cena = "
//     SELECT 
//         s.nombre AS sector,
//         SUM(CASE WHEN c.postre_id = 1 THEN c.cantidad ELSE 0 END) AS total_flan,
//         SUM(CASE WHEN c.postre_id = 2 THEN c.cantidad ELSE 0 END) AS total_gelatina,
//         SUM(CASE WHEN d.id = 9 THEN (c.cantidad + c.acompaniante) ELSE 0 END) AS total_dietas_generales,
//         SUM(CASE WHEN d.id != 9 THEN c.cantidad ELSE 0 END) AS total_otras_dietas
//     FROM consumos_diarios c
//     JOIN sectores s ON c.sector_id = s.id
//     JOIN dietas d ON c.dieta_id = d.id
//     WHERE c.fecha_consumo = :fecha_consumo AND c.sector_id IN (1, 2, 3) AND c.comida_id = 2
//     GROUP BY c.sector_id
// ";

// // Grupo 2 Almuerzo
// $sqlGrupo2Almuerzo = "
//     SELECT 
//         s.nombre AS sector,
//         SUM(CASE WHEN c.postre_id = 1 THEN c.cantidad ELSE 0 END) AS total_flan,
//         SUM(CASE WHEN c.postre_id = 2 THEN c.cantidad ELSE 0 END) AS total_gelatina,
//         SUM(CASE WHEN d.id = 9 THEN (c.cantidad + c.acompaniante) ELSE 0 END) AS total_dietas_generales,
//         SUM(CASE WHEN d.id != 9 THEN c.cantidad ELSE 0 END) AS total_otras_dietas
//     FROM consumos_diarios c
//     JOIN sectores s ON c.sector_id = s.id
//     JOIN dietas d ON c.dieta_id = d.id
//     WHERE c.fecha_consumo = :fecha_consumo AND c.sector_id BETWEEN 4 AND 9 AND c.comida_id = 1
//     GROUP BY c.sector_id
// ";

// // Grupo 2 Cena
// $sqlGrupo2Cena = "
//     SELECT 
//         s.nombre AS sector,
//         SUM(CASE WHEN c.postre_id = 1 THEN c.cantidad ELSE 0 END) AS total_flan,
//         SUM(CASE WHEN c.postre_id = 2 THEN c.cantidad ELSE 0 END) AS total_gelatina,
//         SUM(CASE WHEN d.id = 9 THEN (c.cantidad + c.acompaniante) ELSE 0 END) AS total_dietas_generales,
//         SUM(CASE WHEN d.id != 9 THEN c.cantidad ELSE 0 END) AS total_otras_dietas
//     FROM consumos_diarios c
//     JOIN sectores s ON c.sector_id = s.id
//     JOIN dietas d ON c.dieta_id = d.id
//     WHERE c.fecha_consumo = :fecha_consumo AND c.sector_id BETWEEN 4 AND 9 AND c.comida_id = 2
//     GROUP BY c.sector_id
// ";

// // Totales generales Almuerzo
// $sqlTotalesAlmuerzo = "
//     SELECT 
//         SUM(CASE WHEN c.postre_id = 1 THEN c.cantidad ELSE 0 END) AS total_flan,
//         SUM(CASE WHEN c.postre_id = 2 THEN c.cantidad ELSE 0 END) AS total_gelatina,
//         SUM(CASE WHEN d.id = 9 THEN (c.cantidad + c.acompaniante) ELSE 0 END) AS total_dietas_generales,
//         SUM(CASE WHEN d.id != 9 THEN c.cantidad ELSE 0 END) AS total_otras_dietas
//     FROM consumos_diarios c
//     JOIN dietas d ON c.dieta_id = d.id
//     WHERE c.fecha_consumo = :fecha_consumo AND c.comida_id = 1
// ";

// // Totales generales Cena
// $sqlTotalesCena = "
//     SELECT 
//         SUM(CASE WHEN c.postre_id = 1 THEN c.cantidad ELSE 0 END) AS total_flan,
//         SUM(CASE WHEN c.postre_id = 2 THEN c.cantidad ELSE 0 END) AS total_gelatina,
//         SUM(CASE WHEN d.id = 9 THEN (c.cantidad + c.acompaniante) ELSE 0 END) AS total_dietas_generales,
//         SUM(CASE WHEN d.id != 9 THEN c.cantidad ELSE 0 END) AS total_otras_dietas
//     FROM consumos_diarios c
//     JOIN dietas d ON c.dieta_id = d.id
//     WHERE c.fecha_consumo = :fecha_consumo AND c.comida_id = 2
// ";

// try {
//     // Ejecutar las consultas para almuerzo y cena
//     // Grupo 1 Almuerzo
//     $consultaGrupo1Almuerzo = $conn->prepare($sqlGrupo1Almuerzo);
//     $consultaGrupo1Almuerzo->bindParam(':fecha_consumo', $fecha, PDO::PARAM_STR);
//     $consultaGrupo1Almuerzo->execute();
//     $datosGrupo1Almuerzo = $consultaGrupo1Almuerzo->fetchAll(PDO::FETCH_ASSOC);

//     // Grupo 1 Cena
//     $consultaGrupo1Cena = $conn->prepare($sqlGrupo1Cena);
//     $consultaGrupo1Cena->bindParam(':fecha_consumo', $fecha, PDO::PARAM_STR);
//     $consultaGrupo1Cena->execute();
//     $datosGrupo1Cena = $consultaGrupo1Cena->fetchAll(PDO::FETCH_ASSOC);

//     // Grupo 2 Almuerzo
//     $consultaGrupo2Almuerzo = $conn->prepare($sqlGrupo2Almuerzo);
//     $consultaGrupo2Almuerzo->bindParam(':fecha_consumo', $fecha, PDO::PARAM_STR);
//     $consultaGrupo2Almuerzo->execute();
//     $datosGrupo2Almuerzo = $consultaGrupo2Almuerzo->fetchAll(PDO::FETCH_ASSOC);

//     // Grupo 2 Cena
//     $consultaGrupo2Cena = $conn->prepare($sqlGrupo2Cena);
//     $consultaGrupo2Cena->bindParam(':fecha_consumo', $fecha, PDO::PARAM_STR);
//     $consultaGrupo2Cena->execute();
//     $datosGrupo2Cena = $consultaGrupo2Cena->fetchAll(PDO::FETCH_ASSOC);

//     // Ejecutar las consultas de los totales generales
//     $consultaTotalesAlmuerzo = $conn->prepare($sqlTotalesAlmuerzo);
//     $consultaTotalesAlmuerzo->bindParam(':fecha_consumo', $fecha, PDO::PARAM_STR);
//     $consultaTotalesAlmuerzo->execute();
//     $totalesAlmuerzo = $consultaTotalesAlmuerzo->fetch(PDO::FETCH_ASSOC);

//     $consultaTotalesCena = $conn->prepare($sqlTotalesCena);
//     $consultaTotalesCena->bindParam(':fecha_consumo', $fecha, PDO::PARAM_STR);
//     $consultaTotalesCena->execute();
//     $totalesCena = $consultaTotalesCena->fetch(PDO::FETCH_ASSOC);

//     // Crear la respuesta con los datos de cada grupo y los totales generales
//     $respuesta = [
//         'por_sector' => [
//             'almuerzo' => [
//                 'grupo_1' => $datosGrupo1Almuerzo,
//                 'grupo_2' => $datosGrupo2Almuerzo,
//                 'totales' => $totalesAlmuerzo // Totales generales de almuerzo
//             ],
//             'cena' => [
//                 'grupo_1' => $datosGrupo1Cena,
//                 'grupo_2' => $datosGrupo2Cena,
//                 'totales' => $totalesCena // Totales generales de cena
//             ]
//         ]
//     ];

//     echo json_encode($respuesta);
// } catch (PDOException $e) {
//     error_log("Error en la consulta: " . $e->getMessage());
//     echo json_encode(['error' => 'Error en la consulta.']);
// }


header('Content-Type: application/json');

// Conexión a la base de datos
require_once 'db.php';

$fecha = date('Y-m-d');

// Consultas ajustadas para almuerzo y cena de cada grupo

// Grupo 1 Almuerzo
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

// Grupo 1 Cena
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

// Grupo 2 Almuerzo
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

// Grupo 2 Cena
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

// Totales generales Almuerzo
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

// Totales generales Cena
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
    // Grupo 1 Almuerzo
    $stmt1A = $conn->prepare($sqlGrupo1Almuerzo);
    $stmt1A->bindParam(':fecha_consumo', $fecha, PDO::PARAM_STR);
    $stmt1A->execute();
    $datosGrupo1Almuerzo = $stmt1A->fetchAll(PDO::FETCH_ASSOC);

    // Grupo 1 Cena
    $stmt1C = $conn->prepare($sqlGrupo1Cena);
    $stmt1C->bindParam(':fecha_consumo', $fecha, PDO::PARAM_STR);
    $stmt1C->execute();
    $datosGrupo1Cena = $stmt1C->fetchAll(PDO::FETCH_ASSOC);

    // Grupo 2 Almuerzo
    $stmt2A = $conn->prepare($sqlGrupo2Almuerzo);
    $stmt2A->bindParam(':fecha_consumo', $fecha, PDO::PARAM_STR);
    $stmt2A->execute();
    $datosGrupo2Almuerzo = $stmt2A->fetchAll(PDO::FETCH_ASSOC);

    // Grupo 2 Cena
    $stmt2C = $conn->prepare($sqlGrupo2Cena);
    $stmt2C->bindParam(':fecha_consumo', $fecha, PDO::PARAM_STR);
    $stmt2C->execute();
    $datosGrupo2Cena = $stmt2C->fetchAll(PDO::FETCH_ASSOC);

    // Totales Almuerzo
    $stmtTA = $conn->prepare($sqlTotalesAlmuerzo);
    $stmtTA->bindParam(':fecha_consumo', $fecha, PDO::PARAM_STR);
    $stmtTA->execute();
    $totalesAlmuerzo = $stmtTA->fetch(PDO::FETCH_ASSOC);

    // Totales Cena
    $stmtTC = $conn->prepare($sqlTotalesCena);
    $stmtTC->bindParam(':fecha_consumo', $fecha, PDO::PARAM_STR);
    $stmtTC->execute();
    $totalesCena = $stmtTC->fetch(PDO::FETCH_ASSOC);

    // Construcción de respuesta
    $respuesta = [
        'por_sector' => [
            'almuerzo' => [
                'grupo_1' => $datosGrupo1Almuerzo,
                'grupo_2' => $datosGrupo2Almuerzo,
                'totales' => $totalesAlmuerzo
            ],
            'cena' => [
                'grupo_1' => $datosGrupo1Cena,
                'grupo_2' => $datosGrupo2Cena,
                'totales' => $totalesCena
            ]
        ]
    ];

    echo json_encode($respuesta);
} catch (PDOException $e) {
    error_log("Error en la consulta: " . $e->getMessage());
    echo json_encode(['error' => 'Error en la consulta.']);
}

