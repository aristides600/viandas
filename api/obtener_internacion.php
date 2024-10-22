<?php
// Conexión a la base de datos
require_once 'db.php';
header('Content-Type: application/json');

// Obtener el ID de la internación desde el parámetro de la URL
$internacion_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($internacion_id > 0) {
    try {
        // Consulta para obtener los datos del paciente y la internación
        $sql = "SELECT i.id AS internacion_id, i.fecha_ingreso, i.fecha_egreso, i.diagnostico, p.id AS paciente_id, p.dni, p.nombre, p.apellido, p.fecha_nacimiento, p.sexo_id, p.telefono
                FROM internaciones i
                JOIN pacientes p ON i.paciente_id = p.id
                WHERE i.id = :internacion_id";

        // Preparar la consulta
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':internacion_id', $internacion_id, PDO::PARAM_INT);
        $stmt->execute();

        // Obtener los resultados
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($resultado) {
            echo json_encode($resultado);
        } else {
            echo json_encode(['error' => 'No se encontró la internación']);
        }
    } catch (PDOException $e) {
        error_log("Error en la consulta: " . $e->getMessage());
        echo json_encode(['error' => 'Error al obtener los datos']);
    }
} else {
    echo json_encode(['error' => 'ID de internación no válido']);
}
?>
