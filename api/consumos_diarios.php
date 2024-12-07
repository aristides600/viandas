<?php 
include('db.php');

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$usuario_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Depuración: Verificar que el usuario está autenticado
        error_log("Usuario ID autenticado: " . $usuario_id);

        // Consulta para obtener pacientes_dietas
        $sql = "SELECT DISTINCT internacion_id, paciente_id, dieta_id FROM pacientes_dietas WHERE estado = 1";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $pacientesDietas = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Depuración: Verificar el contenido de $pacientesDietas
        error_log("PacientesDietas obtenidos: " . json_encode($pacientesDietas));

        $registrados = 0;
        $errores = [];

        foreach ($pacientesDietas as $consumo) {
            $internacion_id = $consumo['internacion_id'];
            $paciente_id = $consumo['paciente_id'];
            $dieta_id = $consumo['dieta_id'];

            // Depuración: Verificar datos actuales en el bucle principal
            error_log("Procesando internación: $internacion_id, paciente: $paciente_id, dieta: $dieta_id");

            foreach ([1 => 'ALMUERZO', 2 => 'CENA'] as $comida_id => $comida_nombre) {
                $verificarSql = "SELECT COUNT(*) FROM consumos_diarios WHERE internacion_id = :internacion_id AND fecha_consumo = CURRENT_DATE AND comida_id = :comida_id";
                $stmtVerificar = $conn->prepare($verificarSql);
                $stmtVerificar->execute([
                    ':internacion_id' => $internacion_id,
                    ':comida_id' => $comida_id
                ]);

                // Depuración: Verificar si ya existe el consumo
                $existeConsumo = $stmtVerificar->fetchColumn();
                error_log("Verificación de consumo ($comida_nombre) para internación $internacion_id: $existeConsumo");

                if ($existeConsumo == 0) {
                    $insertSql = "INSERT INTO consumos_diarios (internacion_id, paciente_id, dieta_id, fecha_consumo, comida_id, usuario_id, estado) 
                                  VALUES (:internacion_id, :paciente_id, :dieta_id, CURRENT_DATE, :comida_id, :usuario_id, 1)";
                    $stmtInsert = $conn->prepare($insertSql);
                    $stmtInsert->execute([
                        ':internacion_id' => $internacion_id,
                        ':paciente_id' => $paciente_id,
                        ':dieta_id' => $dieta_id,
                        ':comida_id' => $comida_id,
                        ':usuario_id' => $usuario_id
                    ]);

                    // Depuración: Confirmar inserción exitosa
                    error_log("Consumo registrado: Internación $internacion_id, Comida $comida_nombre");
                    $registrados++;
                } else {
                    $errores[] = "Ya existe un registro para $comida_nombre en la internación $internacion_id.";
                }
            }
        }

        // Respuesta de éxito
        error_log("Consumos registrados: $registrados, Errores: " . json_encode($errores));
        echo json_encode([
            'status' => 'success',
            'message' => "$registrados consumos registrados exitosamente.",
            'errores' => $errores
        ]);
    } catch (PDOException $e) {
        // Depuración: Log del error de excepción
        error_log("Error al registrar consumos diarios: " . $e->getMessage());
        echo json_encode(['status' => 'error', 'message' => 'Error al registrar consumos diarios.']);
    }
}
