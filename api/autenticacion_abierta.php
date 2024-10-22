<?php
session_start();
require_once 'db.php';

// Verificar si el usuario está logueado
if (isset($_SESSION['user_id'])) {
    // Obtener rol del usuario
    $user_id = $_SESSION['user_id'];

    try {
        $sql = "SELECT rol_id FROM usuarios WHERE id = :user_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $rol_id = 0;

        if ($result) {
            $rol_id = $result['rol_id'];
        } else {
            // Si el usuario no existe, cerrar sesión y redirigir a login
            session_destroy();
            header('Location: login.php');
            exit();
        }

        // Redirigir al index si el usuario está logueado
        header('Location: index.php');
        exit();
    } catch (PDOException $e) {
        // Manejo de errores de la base de datos
        error_log("Error en la consulta de rol: " . $e->getMessage());
        session_destroy();
        header('Location: login.php');
        exit();
    }
}
?>
