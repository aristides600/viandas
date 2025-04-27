<?php
require_once 'db.php';
header('Content-Type: application/json');
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Usuario no autenticado.']);
    exit();
}

$usuario_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Obtener todos los recargos activos
        $sql = "SELECT id, dieta_id, comida_id, cantidad, usuario_id, estado 
                FROM recargos 
                WHERE estado = 1";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $recargos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $registrados = 0;
        $actualizados = 0;

        foreach ($recargos as $recargo) {
            $dieta_id = $recargo['dieta_id'];
            $comida_id = $recargo['comida_id'];

            // Verificar si ya existe un consumo en las últimas 3 horas
            $verificarSql = "SELECT id, cantidad 
                             FROM consumos_recargos 
                             WHERE dieta_id = :dieta_id 
                             AND comida_id = :comida_id 
                             AND TIMESTAMPDIFF(HOUR, fecha_consumo, NOW()) < 3 
                             ORDER BY fecha_consumo DESC 
                             LIMIT 1";
            $stmtVerificar = $conn->prepare($verificarSql);
            $stmtVerificar->execute([
                ':dieta_id' => $dieta_id,
                ':comida_id' => $comida_id
            ]);

            $registroExistente = $stmtVerificar->fetch(PDO::FETCH_ASSOC);

            if ($registroExistente) {
                // Actualizar el consumo existente
                $updateSql = "UPDATE consumos_recargos 
                              SET cantidad = :cantidad, usuario_id = :usuario_id, fecha_consumo = NOW() 
                              WHERE id = :id";
                $stmtUpdate = $conn->prepare($updateSql);
                $stmtUpdate->execute([
                    ':cantidad' => $recargo['cantidad'],
                    ':usuario_id' => $usuario_id,
                    ':id' => $registroExistente['id']
                ]);
                $actualizados++;
            } else {
                // Insertar nuevo consumo
                $insertSql = "INSERT INTO consumos_recargos 
                              (fecha_consumo, dieta_id, comida_id, cantidad, usuario_id, estado) 
                              VALUES 
                              (NOW(), :dieta_id, :comida_id, :cantidad, :usuario_id, 1)";
                $stmtInsert = $conn->prepare($insertSql);
                $stmtInsert->execute([
                    ':dieta_id' => $dieta_id,
                    ':comida_id' => $comida_id,
                    ':cantidad' => $recargo['cantidad'],
                    ':usuario_id' => $usuario_id
                ]);
                $registrados++;
            }
        }

        echo json_encode([
            'status' => 'success',
            'message' => "Consumos registrados: $registrados, actualizados: $actualizados."
        ]);
    } catch (PDOException $e) {
        error_log("Error al procesar consumos desde recargos: " . $e->getMessage());
        echo json_encode(['status' => 'error', 'message' => 'Error al procesar la solicitud.']);
    }
}
?>
