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
        $sql = "SELECT pd.internacion_id, pd.postre_id, pd.dieta_id, pd.acompaniante, i.sector_id
                FROM pacientes_dietas pd
                JOIN internaciones i ON pd.internacion_id = i.id
                WHERE pd.estado = 1";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $pacientesDietas = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $registrados = 0;
        $errores = [];

        foreach ($pacientesDietas as $consumo) {
            $internacion_id = $consumo['internacion_id'];
            $postre_id = $consumo['postre_id'];
            $dieta_id = $consumo['dieta_id'];
            $acompaniante = $consumo['acompaniante'];
            $sector_id = $consumo['sector_id']; // Obtener sector_id de la consulta

            // Verificar si ya existe un consumo para la comida seleccionada
            $verificarSql = "SELECT dieta_id, postre_id FROM consumos_diarios WHERE internacion_id = :internacion_id AND fecha_consumo = CURRENT_DATE AND comida_id = :comida_id";
            $stmtVerificar = $conn->prepare($verificarSql);
            $stmtVerificar->execute([
                ':internacion_id' => $internacion_id,
                ':comida_id' => $comida_id
            ]);

            $existeConsumo = $stmtVerificar->fetch(PDO::FETCH_ASSOC);

            if ($existeConsumo) {
                // Actualizar si la dieta o el postre son diferentes
                if ($existeConsumo['dieta_id'] != $dieta_id || $existeConsumo['postre_id'] != $postre_id) {
                    $updateSql = "UPDATE consumos_diarios SET dieta_id = :dieta_id, postre_id = :postre_id WHERE internacion_id = :internacion_id AND fecha_consumo = CURRENT_DATE AND comida_id = :comida_id";
                    $stmtUpdate = $conn->prepare($updateSql);
                    $stmtUpdate->execute([
                        ':dieta_id' => $dieta_id,
                        ':postre_id' => $postre_id,
                        ':internacion_id' => $internacion_id,
                        ':comida_id' => $comida_id
                    ]);
                }
            } else {
                // Insertar nuevo registro si no existe
                $insertSql = "INSERT INTO consumos_diarios (internacion_id, postre_id, dieta_id, fecha_consumo, comida_id, cantidad, acompaniante, usuario_id, estado, sector_id) 
                              VALUES (:internacion_id, :postre_id, :dieta_id, NOW(), :comida_id, 1, :acompaniante, :usuario_id, 1, :sector_id)";
                $stmtInsert = $conn->prepare($insertSql);
                $stmtInsert->execute([
                    ':internacion_id' => $internacion_id,
                    ':postre_id' => $postre_id,
                    ':dieta_id' => $dieta_id,
                    ':comida_id' => $comida_id,
                    ':acompaniante' => $acompaniante,
                    ':usuario_id' => $usuario_id,
                    ':sector_id' => $sector_id // Agregar sector_id en la inserción
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
