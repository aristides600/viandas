<?php
require_once 'db.php';

// Función para verificar si el usuario tiene permiso para un módulo específico
function tienePermiso($usuario_id, $modulo) {
    global $conn;
    
    // Consulta SQL
    $sql = "
        SELECT p.permiso
        FROM usuarios u
        JOIN roles r ON u.rol_id = r.id
        JOIN permisos p ON r.id = p.rol_id
        WHERE u.id = :usuario_id AND p.modulo = :modulo
    ";
    
    // Preparar la consulta
    $stmt = $conn->prepare($sql);
    
    // Enlazar los parámetros con bindParam
    $stmt->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT);
    $stmt->bindParam(':modulo', $modulo, PDO::PARAM_STR);
    
    // Ejecutar la consulta
    $stmt->execute();
    
    // Obtener el resultado
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Verificar si se encontró un permiso y devolver verdadero o falso
    if ($result) {
        return $result['permiso'] == 1;
    }
    
    return false;
}
?>
