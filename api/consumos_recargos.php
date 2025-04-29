<?php
require_once 'db.php';
header('Content-Type: application/json');
session_start();

// Verificar sesión
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Usuario no autenticado.']);
    exit();
}

$usuario_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Recibir JSON del body
        $input = json_decode(file_get_contents("php://input"), true);

        if (!isset($input['comida_id'])) {
            echo json_encode(['status' => 'error', 'message' => 'ID de comida no recibido.']);
            exit();
        }

        $comida_id_post = intval($input['comida_id']);

        // Obtener la suma total de cantidad agrupada por dieta_id para la comida_id específica
        $sqlSuma = "SELECT dieta_id, SUM(cantidad) AS total_cantidad 
                    FROM recargos 
                    WHERE estado = 1 AND controlado = 0 AND comida_id = :comida_id 
                    GROUP BY dieta_id";
        $stmtSuma = $conn->prepare($sqlSuma);
        $stmtSuma->bindParam(':comida_id', $comida_id_post, PDO::PARAM_INT);
        $stmtSuma->execute();
        $resultados = $stmtSuma->fetchAll(PDO::FETCH_ASSOC);

        if (!$resultados) {
            echo json_encode(['status' => 'error', 'message' => 'No hay recargos para esta comida.']);
            exit();
        }

        $registrados = 0;
        $actualizados = 0;

        foreach ($resultados as $resultado) {
            $dieta_id = $resultado['dieta_id'];
            $comida_id = $comida_id_post;
            $cantidad_total = $resultado['total_cantidad'];

            // Verificar existencia de consumo reciente
            $verificarSql = "SELECT id 
                             FROM consumos_recargos 
                             WHERE dieta_id = :dieta_id AND comida_id = :comida_id 
                             AND TIMESTAMPDIFF(HOUR, fecha_consumo, NOW()) < 3 
                             ORDER BY fecha_consumo DESC LIMIT 1";
            $stmtVerificar = $conn->prepare($verificarSql);
            $stmtVerificar->execute([
                ':dieta_id' => $dieta_id,
                ':comida_id' => $comida_id
            ]);
            $existe = $stmtVerificar->fetch(PDO::FETCH_ASSOC);

            if ($existe) {
                $stmtUpdate = $conn->prepare("UPDATE consumos_recargos 
                    SET cantidad = :cantidad, usuario_id = :usuario_id, fecha_consumo = NOW() 
                    WHERE id = :id");
                $stmtUpdate->execute([
                    ':cantidad' => $cantidad_total,
                    ':usuario_id' => $usuario_id,
                    ':id' => $existe['id']
                ]);
                $actualizados++;
            } else {
                $stmtInsert = $conn->prepare("INSERT INTO consumos_recargos 
                    (fecha_consumo, dieta_id, comida_id, cantidad, usuario_id, estado) 
                    VALUES (NOW(), :dieta_id, :comida_id, :cantidad, :usuario_id, 1)");
                $stmtInsert->execute([
                    ':dieta_id' => $dieta_id,
                    ':comida_id' => $comida_id,
                    ':cantidad' => $cantidad_total,
                    ':usuario_id' => $usuario_id
                ]);
                $registrados++;
            }
        }

        echo json_encode([
            'status' => 'success',
            'message' => "Consumos registrados: $registrados, actualizados: $actualizados."
        ]);
    } catch (Exception $e) {
        error_log("Error: " . $e->getMessage());
        echo json_encode(['status' => 'error', 'message' => 'Error al procesar los datos.']);
    }
}

?>
