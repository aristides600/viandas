<?php

// Importar las clases de PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

// Incluir archivos de PHPMailer (asegúrate de que esta ruta sea correcta)
require 'C:/xampp/htdocs/vianda/PHPMailer-master/src/Exception.php';
require 'C:/xampp/htdocs/vianda/PHPMailer-master/src/PHPMailer.php';
require 'C:/xampp/htdocs/vianda/PHPMailer-master/src/SMTP.php';

// Directorio donde se almacenan los backups
$backupDirectory = 'C:/xampp/htdocs/vianda/backup';

// Obtener el archivo más reciente de la carpeta de backups
$files = glob($backupDirectory . '/*.sql'); // Asumiendo que los backups son archivos .sql
usort($files, function($a, $b) {
    return filemtime($b) - filemtime($a);
});
$backupFilePath = $files[0] ?? ''; // Tomar el más reciente o asignar vacío si no hay archivos

if (!$backupFilePath) {
    die('No se encontraron archivos de respaldo en la carpeta especificada.');
}

$backupFileName = basename($backupFilePath);

// Configuración del correo
$mail = new PHPMailer(true);
try {
    // Configuración del servidor SMTP
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com'; // Servidor SMTP de Gmail
    $mail->SMTPAuth = true;
    $mail->Username = 'infointegralsistemas@gmail.com'; // Tu correo de Gmail
    $mail->Password = 'uvmuwpldtihytwol'; // Contraseña de aplicación (considera almacenarla de forma segura)
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Encriptación STARTTLS
    $mail->Port = 587; // Puerto SMTP para STARTTLS

    // Destinatarios
    $mail->setFrom('infointegralsistemas@gmail.com', 'InfoSys');
    $mail->addAddress('infointegralsistemas@gmail.com'); // Correo destinatario

    // Archivos adjuntos
    if (file_exists($backupFilePath)) {
        $mail->addAttachment($backupFilePath, $backupFileName);
    } else {
        throw new Exception('El archivo de respaldo no existe.');
    }

    // Contenido del correo
    $mail->isHTML(true);
    $mail->Subject = 'Backup de la base de datos - ' . date('d/m/Y');
    $mail->Body    = 'Adjunto el backup realizado el ' . date('d/m/Y H:i:s') . '.';

    // Enviar el correo
    $mail->send();
    echo 'Correo enviado con éxito';
} catch (Exception $e) {
    echo "No se pudo enviar el correo. Error: {$mail->ErrorInfo}";
}

?>
