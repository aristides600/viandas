@echo off

:: Establecer la fecha en formato dd/mm/yyyy
for /f "tokens=2 delims==" %%I in ('wmic os get localdatetime /value') do set datetime=%%I
set fecha=%datetime:~6,2%-%datetime:~4,2%-%datetime:~0,4%

:: Establecer la hora en formato HHMMSS
set hora=%time:~0,2%%time:~3,2%%time:~6,2%
set hora=%hora: =0%

:: Realizar el backup
"C:\xampp\mysql\bin\mysqldump.exe" -u root fraser > "C:\xampp\htdocs\vianda\backup\backup_cocina_%fecha%_%hora%.sql"

exit
