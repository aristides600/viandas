@echo off
REM Especificar la ruta completa de php.exe
set PHP_PATH=C:\xampp\php\php.exe

REM Cambia a la carpeta donde está el script PHP (sin el nombre del archivo)
cd /d C:\xampp\htdocs\vianda\api

REM Ejecuta el script PHP usando la ruta completa a php.exe
%PHP_PATH% enviar_backup.php
