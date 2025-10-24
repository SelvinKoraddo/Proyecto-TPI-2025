<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: loginAdmin.php");
    exit();
}

if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: loginAdmin.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TechFix | Panel de Administración</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./css/estilos.css">
</head>

<body style="background: linear-gradient(180deg, #1f56a5, #9340c7); color: white;">

    <nav class="navbar navbar-dark bg-dark p-3">
        <div class="container-fluid">
            <span class="navbar-brand mb-0 h1">⚙️ Panel de Administración - TechFix</span>
            <a href="?logout=true" class="btn btn-outline-light">Cerrar Sesión</a>
        </div>
    </nav>

    <div class="container mt-5">
        <h2 class="text-center mb-4">Bienvenido, Administrador</h2>

        <div class="row g-4">
            <div class="col-md-6 col-lg-4">
                <div class="card text-center shadow p-4">
                    <h4>📋 Gestionar Solicitudes</h4>
                    <p>Revisa, aprueba o rechaza solicitudes de técnicos y clientes.</p>
                    <a href="#" class="btn btn-primary">Entrar</a>
                </div>
            </div>

            <div class="col-md-6 col-lg-4">
                <div class="card text-center shadow p-4">
                    <h4>🚫 Suspender Cuentas</h4>
                    <p>Bloquea o reactiva cuentas con actividad irregular.</p>
                    <a href="#" class="btn btn-primary">Entrar</a>
                </div>
            </div>

            <div class="col-md-6 col-lg-4">
                <div class="card text-center shadow p-4">
                    <h4>📊 Reportes y Estadísticas</h4>
                    <p>Consulta métricas sobre servicios y usuarios.</p>
                    <a href="#" class="btn btn-primary">Entrar</a>
                </div>
            </div>

            <div class="col-md-6 col-lg-4">
                <div class="card text-center shadow p-4">
                    <h4>👥 Gestionar Usuarios</h4>
                    <p>Edita, elimina o asigna roles a los usuarios registrados.</p>
                    <a href="#" class="btn btn-primary">Entrar</a>
                </div>
            </div>

            <div class="col-md-6 col-lg-4">
                <div class="card text-center shadow p-4">
                    <h4>⚙️ Configuración General</h4>
                    <p>Administra parámetros del sistema y base de datos.</p>
                    <a href="#" class="btn btn-primary">Entrar</a>
                </div>
            </div>
        </div>
    </div>

    <footer class="text-center mt-5 p-3 bg-dark">
        <p class="mb-0">© 2025 TechFix | Administrador</p>
    </footer>

</body>
</html>
