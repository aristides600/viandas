<?php
include 'db.php';

// Obtener el sector actual de un paciente
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $internacion_id = $_GET['id'];

    $query = "SELECT * FROM cambios_sectores WHERE internacion_id = :internacion_id ORDER BY fecha_cambio DESC LIMIT 1";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':internacion_id', $internacion_id, PDO::PARAM_INT);
    $stmt->execute();

    echo json_encode($stmt->fetch(PDO::FETCH_ASSOC));
}

// Guardar el cambio de sector
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $internacion_id = $_POST['internacion_id'];
    $sector_anterior_id = $_POST['sector_anterior_id'];
    $sector_nuevo_id = $_POST['sector_nuevo_id'];
    $fecha_cambio = date("Y-m-d H:i:s");

    $query = "INSERT INTO cambios_sectores (internacion_id, sector_anterior_id, sector_nuevo_id, fecha_cambio) 
              VALUES (:internacion_id, :sector_anterior_id, :sector_nuevo_id, :fecha_cambio)";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':internacion_id', $internacion_id, PDO::PARAM_INT);
    $stmt->bindParam(':sector_anterior_id', $sector_anterior_id, PDO::PARAM_INT);
    $stmt->bindParam(':sector_nuevo_id', $sector_nuevo_id, PDO::PARAM_INT);
    $stmt->bindParam(':fecha_cambio', $fecha_cambio);

    if ($stmt->execute()) {
        echo json_encode(['message' => 'Sector actualizado con éxito']);
    } else {
        echo json_encode(['error' => 'No se pudo actualizar el sector']);
    }
}
?>
