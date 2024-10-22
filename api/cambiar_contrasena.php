<?php
// cambiar_contrasena.php
session_start();
require 'db.php'; // Incluye tu conexión a la base de datos

// Obtener datos enviados desde el frontend
$data = json_decode(file_get_contents("php://input"), true);
$currentPassword = $data['currentPassword'] ?? '';
$newPassword = $data['newPassword'] ?? '';

// Obtener el ID del usuario logeado
$userId = $_SESSION['user_id'];

try {
    // Verificar la contraseña actual
    $query = $conn->prepare("SELECT clave FROM usuarios WHERE id = :id");
    $query->bindParam(':id', $userId, PDO::PARAM_INT);
    $query->execute();
    $user = $query->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($currentPassword, $user['clave'])) {
        // Encriptar la nueva contraseña
        $newPasswordHashed = password_hash($newPassword, PASSWORD_BCRYPT);

        // Actualizar la contraseña en la base de datos
        $updateQuery = $conn->prepare("UPDATE usuarios SET clave = :newPassword WHERE id = :id");
        $updateQuery->bindParam(':newPassword', $newPasswordHashed, PDO::PARAM_STR);
        $updateQuery->bindParam(':id', $userId, PDO::PARAM_INT);

        if ($updateQuery->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al actualizar la contraseña.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Contraseña actual incorrecta.']);
    }
} catch (PDOException $e) {
    // Manejo de errores de la base de datos
    error_log("Error en la consulta de cambio de contraseña: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Error al procesar la solicitud.']);
}

// Cerrar la conexión a la base de datos
$conn = null;
?>
