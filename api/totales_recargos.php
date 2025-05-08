<?php
header('Content-Type: application/json'); // Important: Set the correct header

include 'db.php'; // Your PDO connection file

try {
    $stmt = $conn->prepare("
        SELECT sector, comida_id, SUM(cantidad) AS total_cantidad
        FROM recargos
        WHERE DATE(fecha_consumo) = CURDATE()
        GROUP BY sector, comida_id
        ORDER BY sector, comida_id
    ");

    $stmt->execute();
    $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($resultados) {
        echo json_encode($resultados); // Encode the results as JSON
    } else {
        echo json_encode([]); // Return an empty array if no data
    }

} catch (PDOException $e) {
    error_log("Error al ejecutar la consulta: " . $e->getMessage());
    http_response_code(500); // Set HTTP status code to 500 (Internal Server Error)
    echo json_encode(['error' => "Error al obtener los datos."]); // Return an error message as JSON
}
?>