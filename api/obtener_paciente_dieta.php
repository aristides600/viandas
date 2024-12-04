<?php
include 'db.php'; // Archivo de conexión a la base de datos

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    try {
        $query = $conn->prepare("SELECT * FROM pacientes_dietas pd JOIN internaciones i ON pd.internacion_id = i.id WHERE i.id = :id");
        $query->bindParam(':id', $id, PDO::PARAM_INT);
        $query->execute();

        $pacienteDieta = $query->fetch(PDO::FETCH_ASSOC);

        if ($pacienteDieta) {
            echo json_encode($pacienteDieta);
        } else {
            http_response_code(404);
            echo json_encode(['message' => 'Dieta del paciente no encontrada']);
        }
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['message' => 'Error en la consulta', 'error' => $e->getMessage()]);
    }
} else {
    http_response_code(400);
    echo json_encode(['message' => 'ID de la dieta del paciente no proporcionado']);
}
$conn = null;
?>
