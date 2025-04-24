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
        // Consulta para obtener registros de pacientes_dietas donde nocturno_id > 1 y estado = 1
        $sql = "SELECT pd.internacion_id, pd.nocturno_id, pd.acompaniante, i.sector_id
                FROM pacientes_dietas pd
                JOIN internaciones i ON pd.internacion_id = i.id
                WHERE pd.estado = 1 AND pd.nocturno_id > 1";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $pacientesDietas = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $registrados = 0;
        $actualizados = 0;
        $errores = [];

        foreach ($pacientesDietas as $consumo) {
            $internacion_id = $consumo['internacion_id'];
            $nocturno_id = $consumo['nocturno_id'];
            $sector_id = $consumo['sector_id'];

            // Verificar si ya existe un consumo para el internacion_id y nocturno_id en las últimas 3 horas
            $verificarSql = "SELECT id, cantidad 
                             FROM consumos_nocturnos 
                             WHERE internacion_id = :internacion_id 
                             AND nocturno_id = :nocturno_id 
                             AND TIMESTAMPDIFF(HOUR, fecha_consumo, NOW()) < 3 
                             ORDER BY fecha_consumo DESC 
                             LIMIT 1";
            $stmtVerificar = $conn->prepare($verificarSql);
            $stmtVerificar->execute([
                ':internacion_id' => $internacion_id,
                ':nocturno_id' => $nocturno_id
            ]);

            $registroExistente = $stmtVerificar->fetch(PDO::FETCH_ASSOC);

            if ($registroExistente) {
                // Actualizar el registro existente
                $updateSql = "UPDATE consumos_nocturnos 
                              SET cantidad = :nueva_cantidad, usuario_id = :usuario_id, fecha_consumo = NOW() 
                              WHERE id = :id";
                $stmtUpdate = $conn->prepare($updateSql);
                $stmtUpdate->execute([
                    ':nueva_cantidad' => $registroExistente['cantidad'],
                    ':usuario_id' => $usuario_id,
                    ':id' => $registroExistente['id']
                ]);
                $actualizados++;
            } else {
                // Insertar un nuevo registro
                $insertSql = "INSERT INTO consumos_nocturnos 
                              (internacion_id, sector_id, nocturno_id, cantidad, fecha_consumo, usuario_id, estado)
                              VALUES 
                              (:internacion_id, :sector_id, :nocturno_id, 1, NOW(), :usuario_id, 1)";
                $stmtInsert = $conn->prepare($insertSql);
                $stmtInsert->execute([
                    ':internacion_id' => $internacion_id,
                    ':sector_id' => $sector_id,
                    ':nocturno_id' => $nocturno_id,
                    ':usuario_id' => $usuario_id
                ]);
                $registrados++;
            }
        }

        // Respuesta JSON con resultados
        echo json_encode([
            'status' => 'success',
            'message' => "Consumos actualizados: $actualizados, Consumos registrados: $registrados."
        ]);
    } catch (PDOException $e) {
        error_log("Error al procesar consumos: " . $e->getMessage());
        echo json_encode(['status' => 'error', 'message' => 'Error al procesar la solicitud.']);
    }
}
?>
