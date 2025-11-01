<?php
session_start();

// Verificar si hay sesión iniciada
if (!isset($_SESSION['Rol']) || strtolower($_SESSION['Rol']) !== 'admin') {
    header("Location: login.php"); // 🔹 Redirige al login correcto
    exit();
}

// Cierre de sesión
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: login.php"); // 🔹 Cierre limpio y correcto
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

    <!-- NAVBAR -->
    <nav class="navbar navbar-dark bg-dark p-3 fixed-top">
        <div class="container-fluid">
            <span class="navbar-brand mb-0 h1">⚙️ Panel de Administración - TechFix</span>
            <a href="?logout=true" class="btn btn-outline-light">Cerrar Sesión</a>
        </div>
    </nav>

    <!-- CONTENIDO PRINCIPAL -->
    <div class="container mt-5 pt-5">
        <h2 class="text-center mb-4">Bienvenido, Administrador</h2>

        <div class="row g-4 justify-content-center">

            <!-- Gestionar Solicitudes -->
            <div class="col-md-6 col-lg-4">
                <div class="card text-center shadow p-4 h-100">
                    <h4>📋 Gestionar Solicitudes</h4>
                    <p>Revisa, aprueba o rechaza solicitudes de cientes a tecnicos.</p>
                    <a href="gestionarSolicitudes.php" class="btn btn-primary">Entrar</a>
                </div>
            </div>

            <!-- Suspender Cuentas -->
            <div class="col-md-6 col-lg-4">
                <div class="card text-center shadow p-4 h-100">
                    <h4>🚫Cuentas Tecnicos</h4>
                    <p>Aprueba o rechaza solicitudes de ingreso de los tecnicos.</p>
                    <a href="suspenderCuentas.php" class="btn btn-primary">Entrar</a>
                </div>
            </div>

            <!-- Reportes y Estadísticas -->
            <div class="col-md-6 col-lg-4">
                <div class="card text-center shadow p-4 h-100">
                    <h4>📊 Reportes y Estadísticas</h4>
                    <p>Consulta métricas sobre servicios y usuarios.</p>
                    <a href="reportesEstadisticas.php" class="btn btn-primary">Entrar</a>
                </div>
            </div>

            <!-- Gestionar Usuarios -->
            <div class="col-md-6 col-lg-4">
                <div class="card text-center shadow p-4 h-100">
                    <h4>👥 Gestionar Usuarios</h4>
                    <p>Edita, elimina o asigna roles a los usuarios registrados.</p>
                    <a href="gestionarUsuarios.php" class="btn btn-primary">Entrar</a>
                </div>
            </div>

            <!-- Configuración General -->
            <div class="col-md-6 col-lg-4">
                <div class="card text-center shadow p-4 h-100">
                    <h4>⚙️ Configuración General</h4>
                    <p>Administra parámetros del sistema y base de datos.</p>
                    <a href="configuracionGeneral.php" class="btn btn-primary">Entrar</a>
                </div>
            </div>
        </div>
    </div>

    <!-- FOOTER FIJO -->
    <footer class="text-center mt-5 p-3 bg-dark fixed-bottom">
        <p class="mb-0">© 2025 TechFix | Administrador</p>
    </footer>

</body>
</html>

