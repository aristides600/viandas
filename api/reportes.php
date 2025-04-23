<?php
// include('db.php');
// header('Content-Type: application/json');

// $fecha_desde = isset($_GET['fecha_desde']) ? $_GET['fecha_desde'] : null;
// $fecha_hasta = isset($_GET['fecha_hasta']) ? $_GET['fecha_hasta'] : null;

// // Validar las fechas
// if ($fecha_desde && $fecha_hasta) {
//     // Asegurarse de que las fechas estén en el formato correcto (Y-m-d)
//     $fecha_desde = date('Y-m-d', strtotime($fecha_desde));
//     $fecha_hasta = date('Y-m-d', strtotime($fecha_hasta));

//     if ($fecha_desde && $fecha_hasta) {
//         try {
//             // Consulta SQL
//             $query = "SELECT 
//                        sectores.nombre AS sector,
//                        dietas.nombre AS dieta,
//                        COUNT(cd.cantidad) AS cantidad
//                     FROM 
//                        consumos_diarios AS cd
//                     JOIN 
//                        dietas ON cd.dieta_id = dietas.id
//                     JOIN 
//                        sectores ON cd.sector_id = sectores.id
//                     WHERE 
//                        cd.fecha_consumo BETWEEN :fecha_desde AND :fecha_hasta
//                     GROUP BY 
//                        sectores.nombre, dietas.nombre
//                     ORDER BY 
//                        cantidad DESC, sectores.nombre, dietas.nombre";

//             // Preparar la consulta
//             $stmt = $conn->prepare($query);

//             // Asignar los parámetros
//             $stmt->bindParam(':fecha_desde', $fecha_desde);
//             $stmt->bindParam(':fecha_hasta', $fecha_hasta);

//             // Ejecutar la consulta
//             $stmt->execute();

//             // Obtener los resultados
//             $reportData = $stmt->fetchAll(PDO::FETCH_ASSOC);

//             // Devolver los datos en formato JSON
//             echo json_encode($reportData);

//         } catch (Exception $e) {
//             // Manejo de excepciones
//             echo json_encode(['error' => $e->getMessage()]);
//         }
//     } else {
//         echo json_encode(['error' => 'Las fechas no son válidas.']);
//     }
// } else {
//     echo json_encode(['error' => 'Las fechas no fueron proporcionadas.']);
// }


///////////
include('db.php');
header('Content-Type: application/json');

$fecha_desde = isset($_GET['fecha_desde']) ? $_GET['fecha_desde'] : null;
$fecha_hasta = isset($_GET['fecha_hasta']) ? $_GET['fecha_hasta'] : null;

if ($fecha_desde && $fecha_hasta) {
    $fecha_desde = date('Y-m-d', strtotime($fecha_desde));
    $fecha_hasta = date('Y-m-d', strtotime($fecha_hasta));

    if ($fecha_desde && $fecha_hasta) {
        try {
            // Consulta con UNION para sumar acompañantes a la dieta GENERAL
            $query = "
                SELECT 
                    sectores.nombre AS sector,
                    dietas.nombre AS dieta,
                    COUNT(*) AS cantidad
                FROM (
                    -- Parte 1: consumos normales
                    SELECT cd.sector_id, cd.dieta_id
                    FROM consumos_diarios cd
                    WHERE cd.fecha_consumo BETWEEN :fecha_desde AND :fecha_hasta

                    UNION ALL

                    -- Parte 2: sumar 1 GENERAL por cada acompañante
                    SELECT cd.sector_id, 9 AS dieta_id
                    FROM consumos_diarios cd
                    WHERE cd.fecha_consumo BETWEEN :fecha_desde AND :fecha_hasta
                    AND cd.acompaniante = 1
                ) AS sub
                JOIN dietas ON sub.dieta_id = dietas.id
                JOIN sectores ON sub.sector_id = sectores.id
                GROUP BY sectores.nombre, dietas.nombre
                ORDER BY cantidad DESC, sectores.nombre, dietas.nombre
            ";

            $stmt = $conn->prepare($query);
            $stmt->bindParam(':fecha_desde', $fecha_desde);
            $stmt->bindParam(':fecha_hasta', $fecha_hasta);
            $stmt->execute();

            $reportData = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($reportData);
        } catch (Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    } else {
        echo json_encode(['error' => 'Las fechas no son válidas.']);
    }
} else {
    echo json_encode(['error' => 'Las fechas no fueron proporcionadas.']);
}


?>
