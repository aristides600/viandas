<?php
$servername = "localhost";  // o 127.0.0.1 si localhost da problemas
$username = "root";         // usuario por defecto en XAMPP
$password = "";             // contraseña vacía por defecto en XAMPP
$database = "dieta";       // nombre de la base de datos

try {
    // Crear conexión PDO
    $conn = new PDO("mysql:host=$servername;dbname=$database;charset=utf8", $username, $password);
    
    // Configurar el modo de error de PDO a excepciones
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Desactivar echo para evitar mensajes en producción
    // echo "Conexión exitosa con la base de datos.";
    
} catch (PDOException $e) {
    // Manejo de errores de conexión
    error_log("Error de conexión a la base de datos: " . $e->getMessage()); // Registrar el error en el log del servidor
    die("Error de conexión a la base de datos. Inténtalo más tarde.");       // Mensaje amigable para el usuario final
}
?>