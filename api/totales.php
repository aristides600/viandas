<?php
header('Content-Type: application/json');
require_once 'db.php';

try {
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Obtener los sectores
    $consultaSectores = "SELECT id, nombre FROM sectores";
    $stmtSectores = $conn->prepare($consultaSectores);
    $stmtSectores->execute();
    $sectores = $stmtSectores->fetchAll(PDO::FETCH_ASSOC);

    // Preparar el arreglo de resultados
    $resultados = [];

    foreach ($sectores as $sector) {
        // Totales de almuerzo por sector
        $consultaTotalesAlmuerzo = "
            SELECT 
                d.nombre AS dieta,
                SUM(cd.cantidad) AS total
            FROM consumos_diarios cd
            JOIN dietas d ON cd.dieta_id = d.id
            WHERE cd.comida_id = 1 AND cd.fecha_consumo = CURDATE() AND cd.sector_id = :sector_id
            GROUP BY cd.dieta_id";
        $stmtTotalesAlmuerzo = $conn->prepare($consultaTotalesAlmuerzo);
        $stmtTotalesAlmuerzo->bindParam(':sector_id', $sector['id']);
        $stmtTotalesAlmuerzo->execute();
        $totalesAlmuerzo = $stmtTotalesAlmuerzo->fetchAll(PDO::FETCH_ASSOC);

        // Totales de cena por sector
        $consultaTotalesCena = "
            SELECT 
                d.nombre AS dieta,
                SUM(cd.cantidad) AS total
            FROM consumos_diarios cd
            JOIN dietas d ON cd.dieta_id = d.id
            WHERE cd.comida_id = 2 AND cd.fecha_consumo = CURDATE() AND cd.sector_id = :sector_id
            GROUP BY cd.dieta_id";
        $stmtTotalesCena = $conn->prepare($consultaTotalesCena);
        $stmtTotalesCena->bindParam(':sector_id', $sector['id']);
        $stmtTotalesCena->execute();
        $totalesCena = $stmtTotalesCena->fetchAll(PDO::FETCH_ASSOC);

        // Totales generales de almuerzo
        $consultaGeneralAlmuerzo = "
            SELECT 
                SUM(cd.cantidad) AS total_general
            FROM consumos_diarios cd
            WHERE cd.comida_id = 1 AND cd.fecha_consumo = CURDATE() AND cd.sector_id = :sector_id";
        $stmtGeneralAlmuerzo = $conn->prepare($consultaGeneralAlmuerzo);
        $stmtGeneralAlmuerzo->bindParam(':sector_id', $sector['id']);
        $stmtGeneralAlmuerzo->execute();
        $generalAlmuerzo = $stmtGeneralAlmuerzo->fetch(PDO::FETCH_ASSOC)['total_general'];

        // Totales generales de cena
        $consultaGeneralCena = "
            SELECT 
                SUM(cd.cantidad) AS total_general
            FROM consumos_diarios cd
            WHERE cd.comida_id = 2 AND cd.fecha_consumo = CURDATE() AND cd.sector_id = :sector_id";
        $stmtGeneralCena = $conn->prepare($consultaGeneralCena);
        $stmtGeneralCena->bindParam(':sector_id', $sector['id']);
        $stmtGeneralCena->execute();
        $generalCena = $stmtGeneralCena->fetch(PDO::FETCH_ASSOC)['total_general'];

        // Número de dietas por comida en la fecha actual
        $consultaCantidadDietas = "
            SELECT 
                cd.comida_id,
                COUNT(DISTINCT cd.dieta_id) AS cantidad_dietas
            FROM consumos_diarios cd
            WHERE cd.fecha_consumo = CURDATE() AND cd.sector_id = :sector_id
            GROUP BY cd.comida_id";
        $stmtCantidadDietas = $conn->prepare($consultaCantidadDietas);
        $stmtCantidadDietas->bindParam(':sector_id', $sector['id']);
        $stmtCantidadDietas->execute();
        $cantidadDietas = $stmtCantidadDietas->fetchAll(PDO::FETCH_ASSOC);

        // Procesar los resultados de cantidad de dietas por comida
        $dietasPorComida = [];
        foreach ($cantidadDietas as $item) {
            $comida = $item['comida_id'] == 1 ? 'almuerzo' : 'cena';
            $dietasPorComida[$comida] = $item['cantidad_dietas'];
        }

        // Añadir los resultados del sector al arreglo final
        $resultados[] = [
            'sector' => $sector['nombre'],
            'totales_almuerzo' => $totalesAlmuerzo,
            'totales_cena' => $totalesCena,
            'general_almuerzo' => $generalAlmuerzo,
            'general_cena' => $generalCena,
            'dietas_por_comida' => $dietasPorComida
        ];
    }

    // Totales generales por dieta y tipo de comida
    $consultaTotalesGenerales = "
        SELECT 
            d.nombre AS dieta,
            cd.comida_id,
            SUM(cd.cantidad) AS total
        FROM consumos_diarios cd
        JOIN dietas d ON cd.dieta_id = d.id
        WHERE cd.fecha_consumo = CURDATE()
        GROUP BY d.nombre, cd.comida_id";
    $stmtTotalesGenerales = $conn->prepare($consultaTotalesGenerales);
    $stmtTotalesGenerales->execute();
    $totalesGenerales = $stmtTotalesGenerales->fetchAll(PDO::FETCH_ASSOC);

    // Añadir los totales generales al arreglo final
    $resultados['totales_generales'] = $totalesGenerales;

    // Respuesta en formato JSON
    echo json_encode($resultados);

} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
