<?php

require_once 'db.php'; 

header('Content-Type: application/json');

session_start();

$input = json_decode(file_get_contents('php://input'), true);
$usuario = $input['usuario'] ?? null;
$clave = $input['clave'] ?? null;

$data = [];

if (!empty($usuario) && !empty($clave)) {
    try {
        $sql = "SELECT id, nombre, apellido, clave, rol_id FROM usuarios WHERE usuario = :usuario";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':usuario', $usuario, PDO::PARAM_STR);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row && password_verify($clave, $row['clave'])) {
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['rol_id'] = $row['rol_id'];
            $_SESSION['nombre'] = $row['nombre'];
            $_SESSION['apellido'] = $row['apellido'];

            // Establecer tiempo de inactividad
            $_SESSION['ultimo_acceso'] = time(); 

            $data['success'] = true;
            $data['message'] = 'Inicio de sesión exitoso';
        } else {
            $data['success'] = false;
            $data['message'] = 'Usuario o clave incorrectos';
        }
    } catch (PDOException $e) {
        $data['success'] = false;
        $data['message'] = 'Error en la consulta: ' . $e->getMessage();
    }
} else {
    $data['success'] = false;
    $data['message'] = 'Faltan datos';
}

echo json_encode($data);
$conn = null;
?>
