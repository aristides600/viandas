<?php
// Incluir el archivo de conexión
require_once 'db.php';

header('Content-Type: application/json');

$internacion_id = isset($_GET['internacion_id']) && is_numeric($_GET['internacion_id']) ? (int)$_GET['internacion_id'] : null;

if (!$internacion_id) {
    echo json_encode(['error' => 'No se proporcionó un internacion_id válido']);
    exit;
}

try {
    // Preparar consulta para obtener los datos de cambios de dietas
    $query = "
        SELECT 
            cd.id,
            s.nombre AS sector,
            COALESCE(da.nombre, 'Sin información') AS dieta_anterior,
            dn.nombre AS dieta_nueva,
            cd.fecha_cambio,
            CONCAT(u.nombre, ' ', u.apellido) AS usuario
        FROM cambios_dietas cd
        LEFT JOIN sectores s ON cd.sector_id = s.id
        LEFT JOIN dietas da ON cd.dieta_anterior_id = da.id
        LEFT JOIN dietas dn ON cd.dieta_nueva_id = dn.id
        LEFT JOIN usuarios u ON cd.usuario_id = u.id
        WHERE cd.internacion_id = :internacion_id
    ";

    $stmt = $conn->prepare($query);
    $stmt->bindParam(':internacion_id', $internacion_id, PDO::PARAM_INT);
    $stmt->execute();

    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($data);

} catch (PDOException $e) {
    error_log("Error en la consulta: " . $e->getMessage());
    echo json_encode(['error' => 'Error al recuperar los datos.']);
}
?>
