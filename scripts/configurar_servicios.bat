@ECHO OFF & SETLOCAL

ECHO Instalando TMPLABS...

ECHO Instalando servidor web...
xampp_cli.exe installservice apache

IF NOT ERRORLEVEL 1 (
    ECHO Iniciando servidor web...
    xampp_cli.exe startservice apache
)

ECHO Instalando gestor de base de datos...
xampp_cli.exe installservice mysql

IF NOT ERRORLEVEL 1 (
    ECHO Iniciando gestor de base de datos...
    xampp_cli.exe startservice mysql
)

ECHO TMPLABS ha sido instalado correctamente

PAUSE