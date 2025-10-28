<?php
session_start();
require_once '../Modelos/Conexion.php';

$mensaje = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $correo = $_POST['correo'] ?? '';
    $contrasena = $_POST['contrasena'] ?? '';

    $db = (new Conexion())->getConexion();
    $stmt = $db->prepare("SELECT * FROM usuarios WHERE correo = :correo");
    $stmt->execute(['correo' => $correo]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario && password_verify($contrasena, $usuario['contrasena_hash'])) {
        $_SESSION['Id'] = $usuario['id_usuario'];
        $_SESSION['Rol'] = $usuario['rol'];
        $_SESSION['Nombre'] = $usuario['nombre_completo'];

        // para redirigir según el rol
          switch ($usuario['rol']) {
              case 'cliente':
                  header('Location: Home.php');
                  break;
              case 'tecnico':
                  header('Location: HomeTecnicos.php');
                  break;
              case 'admin':
                  header('Location: PanelAdmin.php');
                  break;
              default:
                  header('Location: Home.php');
                  break;
          }
        exit;
    } else {
        $mensaje = 'Correo o contraseña incorrectos.';
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Iniciar sesión - TechFix</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
  <div class="container d-flex justify-content-center align-items-center" style="height:100vh;">
    <div class="card shadow p-4" style="width: 360px;">
      <h3 class="text-center mb-4">Iniciar Sesión</h3>

      <?php if ($mensaje): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($mensaje) ?></div>
      <?php endif; ?>

      <form method="POST">
        <div class="mb-3">
          <label for="correo" class="form-label">Correo electrónico</label>
          <input type="email" name="correo" id="correo" class="form-control" required>
        </div>

        <div class="mb-3">
          <label for="contrasena" class="form-label">Contraseña</label>
          <input type="password" name="contrasena" id="contrasena" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary w-100">Ingresar</button>
      </form>

      <hr>
      <p class="text-center">
        ¿No tienes cuenta?<br>
        <a href="RegistroCliente.php">Registrarme como cliente</a><br>
        <a href="RegistroTecnico.php">Registrarme como técnico</a>
      </p>
    </div>
  </div>
  <style>
    body{
      background-image: url("./imagenes/loginFon.jpg");
      background-size: cover;
      background-position: center;
      background-repeat: no-repeat;
      height: 100vh;
      margin: 0;
    }
  </style>
</body>
</html>
