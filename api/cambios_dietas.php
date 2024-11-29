<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['internacion_id'])) {
    $internacion_id = $_GET['internacion_id'];

    try {
        $stmt = $pdo->prepare("
            SELECT cd.fecha_cambio, 
                   da.nombre AS dieta_anterior, 
                   dn.nombre AS dieta_nueva,
                   s.nombre AS sector
            FROM cambios_dietas cd
            LEFT JOIN dietas da ON cd.dieta_anterior_id = da.id
            JOIN dietas dn ON cd.dieta_nueva_id = dn.id
            JOIN sectores s ON cd.sector_id = s.id
            WHERE cd.internacion_id = ?
        ");

        $stmt->execute([$internacion_id]);
        $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Devuelve un arreglo vacío si no hay resultados
        echo json_encode($resultados);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Error al obtener el historial de dietas']);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    try {
        $stmt = $pdo->prepare("
            INSERT INTO cambios_dietas (paciente_id, usuario_id, internacion_id, dieta_anterior_id, dieta_nueva_id, fecha_cambio, observacion) 
            VALUES (?, ?, ?, ?, ?, NOW(), ?)
        ");
        $stmt->execute([
            $data['paciente_id'],
            $data['usuario_id'],
            $data['internacion_id'],
            $data['dieta_anterior_id'] ?? null,
            $data['dieta_id'],
            $data['observacion']
        ]);
        echo json_encode(['message' => 'Dieta guardada correctamente']);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Error al guardar la dieta']);
    }
}
