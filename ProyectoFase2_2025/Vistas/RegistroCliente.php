<?php
require_once '../Modelos/Conexion.php';
$mensaje = '';

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
    } catch (Exception $e) {
        $mensaje = "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Registro de Cliente - TechFix</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
  <div class="container d-flex justify-content-center align-items-center" style="height:100vh;">
    <div class="card shadow p-4" style="width: 400px;">
      <h3 class="text-center mb-3">Registro de Cliente</h3>

      <?php if ($mensaje): ?>
        <div class="alert alert-info"><?= htmlspecialchars($mensaje) ?></div>
      <?php endif; ?>

      <form method="POST">
        <div class="mb-3">
          <label for="nombre" class="form-label">Nombre Completo</label>
          <input type="text" name="nombre" id="nombre" class="form-control" required>
        </div>

        <div class="mb-3">
          <label for="correo" class="form-label">Correo Electrónico</label>
          <input type="email" name="correo" id="correo" class="form-control" required>
        </div>

        <div class="mb-3">
          <label for="telefono" class="form-label">Teléfono</label>
          <input type="text" name="telefono" id="telefono" class="form-control" required>
        </div>

        <div class="mb-3">
          <label for="contrasena" class="form-label">Contraseña</label>
          <input type="password" name="contrasena" id="contrasena" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-success w-100">Registrar</button>
      </form>

      <hr>
      <p class="text-center">
        ¿Ya tienes cuenta? <a href="Login.php">Inicia sesión</a>
      </p>
    </div>
  </div>
</body>
</html>
