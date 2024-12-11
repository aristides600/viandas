<?php
include('db.php');
header('Content-Type: application/json');

$fecha_desde = isset($_GET['fecha_desde']) ? $_GET['fecha_desde'] : null;
$fecha_hasta = isset($_GET['fecha_hasta']) ? $_GET['fecha_hasta'] : null;

if ($fecha_desde && $fecha_hasta) {
    try {
        $query = "SELECT 
                    s.nombre AS sector,
                    COUNT(cd.cantidad) AS cantidad
                  FROM consumos_diarios cd
                  JOIN sectores s ON cd.sector_id = s.id
                  WHERE cd.fecha_consumo BETWEEN :fecha_desde AND :fecha_hasta
                  GROUP BY s.nombre";
        
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':fecha_desde', $fecha_desde);
        $stmt->bindParam(':fecha_hasta', $fecha_hasta);
        $stmt->execute();

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Depura la respuesta antes de enviarla
        // print_r($result);
        echo json_encode($result);

    } catch (PDOException $e) {
        echo json_encode(['error' => 'Error al obtener los datos: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['error' => 'Faltan las fechas de inicio y fin']);
}
?>
