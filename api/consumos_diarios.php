<?php
require_once 'db.php';
header('Content-Type: application/json');
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$usuario_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Obtener datos enviados desde el frontend
        $input = json_decode(file_get_contents('php://input'), true);
        $comida_id = $input['comida_id'];

        // Validar datos requeridos
        if (!$comida_id) {
            echo json_encode(['status' => 'error', 'message' => 'Debe seleccionar una comida.']);
            exit();
        }

        // Consulta para obtener pacientes_dietas
        $sql = "SELECT DISTINCT internacion_id, paciente_id, dieta_id FROM pacientes_dietas WHERE estado = 1";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $pacientesDietas = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $registrados = 0;
        $errores = [];

        foreach ($pacientesDietas as $consumo) {
            $internacion_id = $consumo['internacion_id'];
            $paciente_id = $consumo['paciente_id'];
            $dieta_id = $consumo['dieta_id'];

            // Verificar si ya existe un consumo para la comida seleccionada
            $verificarSql = "SELECT dieta_id FROM consumos_diarios WHERE internacion_id = :internacion_id AND fecha_consumo = CURRENT_DATE AND comida_id = :comida_id";
            $stmtVerificar = $conn->prepare($verificarSql);
            $stmtVerificar->execute([
                ':internacion_id' => $internacion_id,
                ':comida_id' => $comida_id
            ]);

            $existeConsumo = $stmtVerificar->fetch(PDO::FETCH_ASSOC);

            if ($existeConsumo) {
                // Actualizar si la dieta es diferente
                if ($existeConsumo['dieta_id'] != $dieta_id) {
                    $updateSql = "UPDATE consumos_diarios SET dieta_id = :dieta_id WHERE internacion_id = :internacion_id AND fecha_consumo = CURRENT_DATE AND comida_id = :comida_id";
                    $stmtUpdate = $conn->prepare($updateSql);
                    $stmtUpdate->execute([
                        ':dieta_id' => $dieta_id,
                        ':internacion_id' => $internacion_id,
                        ':comida_id' => $comida_id
                    ]);
                }
            } else {
                // Insertar nuevo registro si no existe
                $insertSql = "INSERT INTO consumos_diarios (internacion_id, paciente_id, dieta_id, fecha_consumo, comida_id, usuario_id, estado) 
                              VALUES (:internacion_id, :paciente_id, :dieta_id, NOW(), :comida_id, :usuario_id, 1)";
                $stmtInsert = $conn->prepare($insertSql);
                $stmtInsert->execute([
                    ':internacion_id' => $internacion_id,
                    ':paciente_id' => $paciente_id,
                    ':dieta_id' => $dieta_id,
                    ':comida_id' => $comida_id,
                    ':usuario_id' => $usuario_id
                ]);
                $registrados++;
            }
        }

        echo json_encode([
            'status' => 'success',
            'message' => "$registrados consumo(s) registrado(s) exitosamente.",
        ]);
    } catch (PDOException $e) {
        error_log("Error al registrar consumos diarios: " . $e->getMessage());
        echo json_encode(['status' => 'error', 'message' => 'Error al registrar consumos diarios.']);
    }
}

?>
