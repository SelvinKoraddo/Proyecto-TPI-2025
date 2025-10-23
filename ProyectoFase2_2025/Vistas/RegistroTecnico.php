<?php
require_once '../Modelos/Conexion.php';
$mensaje = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'] ?? '';
    $correo = $_POST['correo'] ?? '';
    $telefono = $_POST['telefono'] ?? '';
    $contrasena = $_POST['contrasena'] ?? '';
    $id_especialidad = $_POST['id_especialidad'] ?? null;
    $tarifa = $_POST['tarifa'] ?? 0.00;
    $zona_trabajo = $_POST['zona_trabajo'] ?? '';

    $hash = password_hash($contrasena, PASSWORD_BCRYPT);

    try {
        $db = (new Conexion())->getConexion();
        $db->beginTransaction();

        //Insertar en tabla usuarios
        $stmt = $db->prepare("INSERT INTO usuarios (nombre_completo, contrasena_hash, rol, telefono, correo, fecha_creado)
                              VALUES (:nombre, :hash, 'tecnico', :telefono, :correo, NOW())");
        $stmt->execute([
            'nombre' => $nombre,
            'hash' => $hash,
            'telefono' => $telefono,
            'correo' => $correo
        ]);
        $id_usuario = $db->lastInsertId();

        //Insertar en perfil_tecnico
        $stmt2 = $db->prepare("INSERT INTO perfil_tecnico (id_usuario, descripcion, tarifa_hora, zona_trabajo, estado, fecha_aprobado)
                               VALUES (:id_usuario, :descripcion, :tarifa, :zona, 'pendiente', NULL)");
        $descripcion = "Especialidad en " . obtenerNombreEspecialidad($db, $id_especialidad);
        $stmt2->execute([
            'id_usuario' => $id_usuario,
            'descripcion' => $descripcion,
            'tarifa' => $tarifa,
            'zona' => $zona_trabajo
        ]);
        $id_tecnico = $db->lastInsertId(); // id del perfil_tecnico

        //Insertar relación en tecnico_especialidad
        $stmt3 = $db->prepare("INSERT INTO tecnico_especialidad (id_tecnico, id_especialidad)
                               VALUES (:id_tecnico, :id_especialidad)");
        $stmt3->execute([
            'id_tecnico' => $id_tecnico,
            'id_especialidad' => $id_especialidad
        ]);

        $db->commit();
        $mensaje = "Registro exitoso. Tu perfil será revisado por un administrador.";
    } catch (Exception $e) {
        $db->rollBack();
        $mensaje = "Error: " . $e->getMessage();
    }
}

//Función para obtener el nombre de la especialidad (para descripción)
function obtenerNombreEspecialidad($db, $id) {
    $stmt = $db->prepare("SELECT nombre FROM especialidad WHERE id_especialidad = :id");
    $stmt->execute(['id' => $id]);
    $esp = $stmt->fetch(PDO::FETCH_ASSOC);
    return $esp['nombre'] ?? '';
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Registro de Técnico - TechFix</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
  <div class="container d-flex justify-content-center align-items-center" style="height:100vh;">
    <div class="card shadow p-4" style="width: 420px;">
      <h3 class="text-center mb-3">Registro de Técnico</h3>

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

        <div class="mb-3">
          <label for="id_especialidad" class="form-label">Especialidad</label>
          <select name="id_especialidad" id="id_especialidad" class="form-select" required>
            <option value="">Seleccione una especialidad</option>
            <?php
              try {
                $db = (new Conexion())->getConexion();
                $esp = $db->query("SELECT id_especialidad, nombre FROM especialidad");
                foreach ($esp as $e) {
                  echo "<option value='{$e['id_especialidad']}'>{$e['nombre']}</option>";
                }
              } catch (Exception $e) {
                echo "<option disabled>Error al cargar</option>";
              }
            ?>
          </select>
        </div>

        <div class="mb-3">
          <label for="tarifa" class="form-label">Tarifa por hora ($)</label>
          <input type="number" name="tarifa" id="tarifa" class="form-control" step="0.01" required>
        </div>

        <div class="mb-3">
          <label for="zona_trabajo" class="form-label">Zona de trabajo</label>
          <select name="zona_trabajo" id="zona_trabajo" class="form-select" required>
            <option value="">Seleccione una zona</option>
            <option value="Ahuachapán">Ahuachapán</option>
            <option value="Sonsonate">Sonsonate</option>
            <option value="Santa Ana">Santa Ana</option>
            <option value="La Libertad">La Libertad</option>
            <option value="Chalatenango">Chalatenango</option>
            <option value="San Salvador">San Salvador</option>
            <option value="Cuscatlán">Cuscatlán</option>
            <option value="La Paz">La Paz</option>
            <option value="San Vicente">San Vicente</option>
            <option value="Cabañas">Cabañas</option>
            <option value="Usulután">Usulután</option>
            <option value="San Miguel">San Miguel</option>
            <option value="Morazán">Morazán</option>
            <option value="La Unión">La Unión</option>
          </select>
        </div>

        <button type="submit" class="btn btn-primary w-100">Registrar Técnico</button>
      </form>

      <hr>
      <p class="text-center">
        ¿Ya tienes cuenta? <a href="Login.php">Inicia sesión</a>
      </p>
    </div>
  </div>
</body>
</html>
