<?php
require_once 'db.php';

function tienePermiso($usuario_id, $modulo) {
    global $conn;

    if (!$conn || !($conn instanceof PDO)) {
        throw new Exception("Conexión a la base de datos no establecida.");
    }

    $sql = "
        SELECT p.permiso
        FROM usuarios u
        JOIN roles r ON u.rol_id = r.id
        JOIN permisos p ON r.id = p.rol_id
        WHERE u.id = :usuario_id AND p.modulo = :modulo
    ";

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT);
    $stmt->bindParam(':modulo', $modulo, PDO::PARAM_STR);
    $stmt->execute();

    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result && $result['permiso'] == 1;
}
?>
