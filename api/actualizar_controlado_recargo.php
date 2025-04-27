<?php
include 'db.php';

if (isset($_POST['id']) && isset($_POST['controlado'])) {
    $id = intval($_POST['id']);
    $controlado = ($_POST['controlado'] == 1) ? 1 : 0; // Asegura que solo sea 0 o 1

    try {
        $stmt = $conn->prepare("UPDATE recargos SET controlado = :controlado WHERE id = :id");
        $stmt->bindParam(":controlado", $controlado, PDO::PARAM_INT);
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            echo json_encode(["success" => true]);
        } else {
            echo json_encode(["success" => false, "error" => "No se pudo actualizar el estado"]);
        }
    } catch (PDOException $e) {
        echo json_encode(["success" => false, "error" => $e->getMessage()]);
    }
} else {
    echo json_encode(["success" => false, "error" => "Datos no válidos"]);
}
?>
