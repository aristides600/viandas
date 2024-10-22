<?php
require_once 'db.php';
header('Content-Type: application/json');

// Obtener la solicitud
$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true);

switch ($method) {
    case 'GET':
        // Obtener todos los roles
        try {
            $stmt = $conn->query("SELECT * FROM roles");
            $roles = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($roles);
        } catch (PDOException $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
        break;
}

// Cerrar la conexión
$conn = null;
?>
