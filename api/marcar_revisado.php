<?php
header('Content-Type: application/json');

require 'db.php'; // Archivo de conexión a la base de datos

$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['id'], $data['revisado'])) {
    $id = (int)$data['id'];
    $revisado = (int)$data['revisado'];

    $sql = "UPDATE internaciones SET revisado = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt->execute([$revisado, $id])) {
        echo json_encode(['success' => 'Internación actualizada correctamente.']);
    } else {
        echo json_encode(['error' => 'Error al actualizar la internación.']);
    }
} else {
    echo json_encode(['error' => 'Datos inválidos.']);
}
