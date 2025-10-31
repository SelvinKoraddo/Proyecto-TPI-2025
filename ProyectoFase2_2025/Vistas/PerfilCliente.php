<?php
session_start();
require_once '../Modelos/Conexion.php';

//  Verificar sesion y rol
if (!isset($_SESSION['Rol']) || $_SESSION['Rol'] !== 'cliente') {
    header('Location: Login.php');
    exit;
}

$id_usuario = $_SESSION['Id'];
$mensaje = '';

$db = (new Conexion())->getConexion();

//Obtener datos del cliente
$sql = "SELECT nombre_completo, correo, telefono, rol, fecha_creado
        FROM usuarios
        WHERE id_usuario = :id";
$stmt = $db->prepare($sql);
$stmt->execute(['id' => $id_usuario]);
$cliente = $stmt->fetch(PDO::FETCH_ASSOC);
$primerNombre = explode(" ", $cliente['nombre_completo'])[0];

if (!$cliente) {
    die("No se encontró el perfil del cliente.");
}

//Procesar formulario al enviar
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre_completo = $_POST['nombre_completo'] ?? '';
    $correo = $_POST['correo'] ?? '';
    $telefono = $_POST['telefono'] ?? '';

    try {
        $db->beginTransaction();

        // Actualizar datos del cliente
        $stmt = $db->prepare("UPDATE usuarios 
                              SET nombre_completo = :nombre, correo = :correo, telefono = :tel 
                              WHERE id_usuario = :id");
        $stmt->execute([
            'nombre' => $nombre_completo,
            'correo' => $correo,
            'tel' => $telefono,
            'id' => $id_usuario
        ]);

        $db->commit();
        $mensaje = "√ Datos actualizados correctamente.";
    } catch (Exception $e) {
        $db->rollBack();
        $mensaje = "X Error al actualizar los datos. Verifica la información ingresada.";
    }

    // Recargar los datos actualizados
    $stmt = $db->prepare($sql);
    $stmt->execute(['id' => $id_usuario]);
    $cliente = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Perfil del Cliente - TechFix</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../Vistas/css/estilos.css">
</head>
<body class="bg-light">
<main>
<div class="container mt-5">
  <div class="card shadow p-4">
    <h3 class="mb-4 text-center">Perfil del Cliente <?= htmlspecialchars($primerNombre) ?></h3>

    <?php if ($mensaje): ?>
      <div class="alert alert-info text-center"><?= htmlspecialchars($mensaje) ?></div>
    <?php endif; ?>

    <form method="POST">
      <h5 class="mb-3">Datos Personales</h5>

      <div class="mb-3">
        <label class="form-label">Nombre completo</label>
        <input type="text" name="nombre_completo" class="form-control" 
               value="<?= htmlspecialchars($cliente['nombre_completo']) ?>" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Correo electrónico</label>
        <input type="email" name="correo" class="form-control" 
               value="<?= htmlspecialchars($cliente['correo']) ?>" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Teléfono</label>
        <input type="text" name="telefono" class="form-control" 
               value="<?= htmlspecialchars($cliente['telefono']) ?>" required>
      </div>

      <hr>

      <div class="mb-3">
        <label class="form-label">Rol</label>
        <input type="text" class="form-control" 
               value="<?= ucfirst(htmlspecialchars($cliente['rol'])) ?>" readonly>
      </div>

      <div class="mb-3">
        <label class="form-label">Fecha de creación</label>
        <input type="text" class="form-control" 
               value="<?= htmlspecialchars($cliente['fecha_creado']) ?>" readonly>
      </div>

      <div class="text-center mt-4">
        <button type="submit" class="btn btn-primary px-4">Guardar cambios</button>
        <a href="Home.php" class="btn btn-secondary px-4">Volver</a>
      </div>
    </form>
  </div>
</div>
</main>
</body>
</html>
