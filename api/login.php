<?php

require_once 'db.php';

// Establecer el tipo de contenido como JSON
header('Content-Type: application/json');

// Iniciar sesión si no está ya iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Recibir los datos del formulario (se espera JSON)
$input = json_decode(file_get_contents('php://input'), true);
$usuario = $input['usuario'] ?? null;
$clave = $input['clave'] ?? null;

$data = [];

// Verificar que se hayan recibido el usuario y la clave
if (!empty($usuario) && !empty($clave)) {
    // Consultar la base de datos para obtener la clave hasheada del usuario
    $sql = "SELECT id, nombre, apellido, clave FROM usuarios WHERE usuario = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param('s', $usuario);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $hash = $row['clave'];

            // Verificar si la clave ingresada coincide con la clave hasheada en la base de datos
            if (password_verify($clave, $hash)) {
                // Iniciar sesión con el ID de usuario, nombre, apellido y el rol
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['nombre'] = $row['nombre'];
                $_SESSION['apellido'] = $row['apellido'];

                // Establecer el user_id para la sesión MySQL
                $user_id = intval($_SESSION['user_id']);
                if ($conn->query("SET @my_user_id = $user_id") === TRUE) {
                    // Verificar que la variable de sesión se haya establecido correctamente
                    $result = $conn->query("SELECT @my_user_id");
                    if ($result) {
                        $row = $result->fetch_assoc();
                        $my_user_id = $row['@my_user_id'];
                        if ($my_user_id == $user_id) {
                            $data['success'] = true;
                            $data['message'] = 'Inicio de sesión exitoso y variable de sesión establecida. User ID: ' . $my_user_id;
                        } else {
                            $data['success'] = false;
                            $data['message'] = 'Error al verificar la variable de sesión: valor incorrecto';
                        }
                    } else {
                        $data['success'] = false;
                        $data['message'] = 'Error al verificar la variable de sesión: ' . $conn->error;
                    }
                } else {
                    $data['success'] = false;
                    $data['message'] = 'Error al establecer la variable de sesión: ' . $conn->error;
                }
            } else {
                $data['success'] = false;
                $data['message'] = 'Clave incorrecta';
            }
        } else {
            $data['success'] = false;
            $data['message'] = 'Usuario no encontrado';
        }

        // $stmt->close();
    } else {
        $data['success'] = false;
        $data['message'] = 'Error en la preparación de la consulta: ' . $conn->error;
    }
} else {
    $data['success'] = false;
    $data['message'] = 'Faltan datos';
}

// Devolver la respuesta como JSON
echo json_encode($data);

?>