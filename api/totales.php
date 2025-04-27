<?php
header('Content-Type: application/json');
require_once 'db.php';

date_default_timezone_set('America/Argentina/Buenos_Aires');

$fecha = date('Y-m-d');

try {
    $stmt = $conn->prepare("
        SELECT 
            cd.*, 
            p.nombre AS postre_nombre, 
            s.nombre AS sector_nombre, 
            d.nombre AS dieta_nombre
        FROM 
            consumos_diarios cd
        LEFT JOIN postres p ON cd.postre_id = p.id
        LEFT JOIN sectores s ON cd.sector_id = s.id
        LEFT JOIN dietas d ON cd.dieta_id = d.id
        WHERE 
            cd.fecha_consumo = :fecha
    ");
    $stmt->bindParam(':fecha', $fecha);
    $stmt->execute();
    
    $datos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($datos);
} catch (PDOException $e) {
    echo json_encode([
        'error' => $e->getMessage()
    ]);
}
?>
