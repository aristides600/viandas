<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Verifica que el parámetro 'id' esté presente
    if (!isset($_GET['id'])) {
        echo json_encode(['error' => 'ID de internación no proporcionado']);
        exit;
    }

    $id = $_GET['id'];

    // Preparamos la consulta
    $stmt = $conn->prepare("SELECT EXISTS(
        SELECT 1 
        FROM pacientes_dietas pd
        WHERE pd.internacion_id = :id
    ) AS existe");
    
    // Vinculamos el parámetro
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    
    // Ejecutamos la consulta
    $stmt->execute();
    
    // Recuperamos el resultado
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Devolvemos el valor de "existe" como JSON
    echo json_encode($result['existe']);
    
}
?>