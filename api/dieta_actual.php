<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Verifica que el parámetro 'id' esté presente
    if (!isset($_GET['id'])) {
        echo json_encode(['error' => 'ID de internación no proporcionado']);
        exit;
    }

    $id = $_GET['id'];

    // Consulta para obtener la información de la internación y el paciente
    $stmt = $conn->prepare("SELECT d.nombre AS dieta_nombre, d.id as dieta_id, pd.acompaniante, pd.observacion, p.nombre AS postre_nombre, pd.id AS pd_id
        FROM internaciones i
        JOIN pacientes_dietas pd ON i.id = pd.internacion_id
        JOIN dietas d ON pd.dieta_id = d.id
        LEFT JOIN postres p ON pd.postre_id = p.id
        WHERE i.id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        // Devuelve la información en formato JSON
        echo json_encode($result);
    } else {
        echo json_encode(['error' => 'Internación no encontrada']);
    }
}
?>
