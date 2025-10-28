<?php
session_start();
require_once '../Modelos/Conexion.php';
$mensaje = '';
$tipoMensaje = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'] ?? '';
    $correo = $_POST['correo'] ?? '';
    $telefono = $_POST['telefono'] ?? '';
    $contrasena = $_POST['contrasena'] ?? '';

    $hash = password_hash($contrasena, PASSWORD_BCRYPT);

    try {
        $db = (new Conexion())->getConexion();
        $stmt = $db->prepare("INSERT INTO usuarios (nombre_completo, contrasena_hash, rol, telefono, correo, fecha_creado)
                              VALUES (:nombre, :hash, 'cliente', :telefono, :correo, NOW())");
        $stmt->execute([
            'nombre' => $nombre,
            'hash' => $hash,
            'telefono' => $telefono,
            'correo' => $correo
        ]);
        $mensaje = "Registro exitoso. ¡Ahora puedes iniciar sesión!";
        $tipoMensaje = "success";
        $_POST = [];
        
    } catch (Exception $e) {
        $mensaje = "Error al registrar: " . $e->getMessage();
        $tipoMensaje = "danger";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Cliente - TechFix</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="../Vistas/css/estilos.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    
</head>

<body>   

    <main class="container my-5">
        <section class="hero text-center">
            <h1>Registro de Clientes</h1>
            <p class="lead">Únete a TechFix y encuentra técnicos profesionales para tus necesidades</p>
        </section>

        <?php if ($mensaje): ?>
        <div class="alert alert-<?= $tipoMensaje ?> text-center mx-auto" style="max-width: 600px;">
            <?= htmlspecialchars($mensaje) ?>
        </div>
        <?php endif; ?>

        <section class="register-section d-flex justify-content-center align-items-center mt-4">
            <div class="card p-4 shadow-lg" style="max-width: 600px; width: 100%; border-radius: 15px;">
                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">Nombre Completo</label>
                        <input type="text" name="nombre" class="form-control" 
                               value="<?= htmlspecialchars($_POST['nombre'] ?? '') ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Correo Electrónico</label>
                        <input type="email" name="correo" class="form-control" 
                               value="<?= htmlspecialchars($_POST['correo'] ?? '') ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Teléfono</label>
                        <input type="text" name="telefono" class="form-control" 
                               value="<?= htmlspecialchars($_POST['telefono'] ?? '') ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Contraseña</label>
                        <input type="password" name="contrasena" class="form-control" required>
                        <small class="text-muted">Mínimo 6 caracteres</small>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-person-check-fill me-2"></i>Registrar Cliente
                    </button>
                </form>

                <hr class="my-4">
                <p class="text-center mb-0">
                    ¿Ya tienes cuenta? <a href="Login.php">Inicia sesión aquí</a>
                </p>
            </div>
        </section>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>
</body>
</html>

