<?php
session_start();

// Verificar si ya está logueado
if (isset($_SESSION['admin'])) {
    header("Location: adminPanel.php");
    exit();
}

// Procesar el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST["usuario"];
    $clave = $_POST["clave"];

    if ($usuario === "admin" && $clave === "admin123*") {
        $_SESSION['admin'] = $usuario;
        header("Location: adminPanel.php");
        exit();
    } else {
        $error = "Usuario o contraseña incorrectos.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TechFix | Login Administrador</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./css/estilos.css">
</head>

<body style="background: linear-gradient(0deg, #9340c7,#1f56a5);">
    <div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
        <div class="card p-5 shadow-lg" style="max-width: 400px; width: 100%; border-radius: 20px;">
            <h2 class="text-center mb-4 text-primary">Panel de Administrador</h2>

            <?php if (!empty($error)) : ?>
                <div class="alert alert-danger"><?= $error ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="mb-3">
                    <label for="usuario" class="form-label">Usuario</label>
                    <input type="text" class="form-control" id="usuario" name="usuario" required>
                </div>
                <div class="mb-3">
                    <label for="clave" class="form-label">Contraseña</label>
                    <input type="password" class="form-control" id="clave" name="clave" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Iniciar Sesión</button>
                <a href="./Home.php" class="btn btn-outline w-100 mt-3">Volver al Inicio</a>
            </form>
        </div>
    </div>
</body>
</html>
