@echo off
REM Levanta Hypeo en http://localhost:8000 usando Docker (no requiere PHP instalado).
REM Requiere Docker Desktop abierto. Ctrl+C para detener.
cd /d "%~dp0"
docker rm -f hypeo-dev >nul 2>&1
echo Hypeo en http://localhost:8000  (usuario: valentina@luzdecerro.cl / password)
docker run --rm -it --name hypeo-dev -p 8000:8000 -v "%cd%:/app" -w /app composer:2 php artisan serve --host=0.0.0.0 --port=8000
