<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['internacion_id'])) {
    $internacion_id = $_GET['internacion_id'];

    $query = "SELECT cs.*, s_anterior.nombre AS sector_anterior_nombre, s_nuevo.nombre AS sector_nuevo_nombre 
              FROM cambios_sectores cs 
              LEFT JOIN sectores s_anterior ON cs.sector_anterior_id = s_anterior.id 
              LEFT JOIN sectores s_nuevo ON cs.sector_nuevo_id = s_nuevo.id 
              WHERE cs.internacion_id = :internacion_id 
              ORDER BY cs.fecha_cambio DESC";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':internacion_id', $internacion_id, PDO::PARAM_INT);
    $stmt->execute();

    $historial = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($historial);
}
?>
