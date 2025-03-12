<?php
include 'db.php'; // Asegúrate de que la ruta sea correcta

try {
    $sql = "UPDATE pacientes_dietas SET controlado = 0";
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    echo "Registros actualizados correctamente.";
} catch (PDOException $e) {
    error_log("Error al actualizar los registros: " . $e->getMessage());
    echo "Error al actualizar los registros.";
}
?>
