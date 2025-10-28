<?php
session_start();
require_once '../Modelos/Conexion.php';

// 🔐 Verificar sesión y rol
if (!isset($_SESSION['Rol']) || $_SESSION['Rol'] !== 'tecnico') {
    header('Location: Login.php');
    exit;
}

$id_usuario = $_SESSION['Id'];
$mensaje = '';

$db = (new Conexion())->getConexion();


//Obtener datos personales y técnicos del usuario
$sql = "SELECT u.nombre_completo, u.telefono, u.correo,
               p.id_tecnico, p.tarifa_hora, p.zona_trabajo,
               p.descripcion, p.estado
        FROM usuarios u
        INNER JOIN perfil_tecnico p ON u.id_usuario = p.id_usuario
        WHERE u.id_usuario = :id_usuario";

$stmt = $db->prepare($sql);
$stmt->execute(['id_usuario' => $id_usuario]);
$tecnico = $stmt->fetch(PDO::FETCH_ASSOC);
$primerNombre = explode(" ", $tecnico['nombre_completo'])[0];//obtener primer nombre

if (!$tecnico) {
    die("No se encontró el perfil del técnico.");
}

//Procesar formulario al enviar de los texbox y etc
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre_completo = $_POST['nombre_completo'] ?? '';
    $correo = $_POST['correo'] ?? '';
    $telefono = $_POST['telefono'] ?? '';
    $zona_trabajo = $_POST['zona_trabajo'] ?? '';
    $tarifa_hora = $_POST['tarifa_hora'] ?? 0.00;
    $descripcion = $_POST['descripcion'] ?? '';
    $especialidades = $_POST['especialidades'] ?? [];
    


    try {
        $db->beginTransaction();

        //Actualizar datos personales
        $stmt1 = $db->prepare("UPDATE usuarios 
                       SET nombre_completo = :nombre, correo = :correo, telefono = :tel 
                       WHERE id_usuario = :id");
        $stmt1->execute(['nombre' => $nombre_completo,
                                 'correo' => $correo,
                                 'tel'    => $telefono,
                                 'id'     => $id_usuario]);

        //Actualizar perfil técnico
        $stmt2 = $db->prepare("UPDATE perfil_tecnico 
                               SET zona_trabajo = :zona, tarifa_hora = :tarifa, descripcion = :desc 
                               WHERE id_usuario = :id");
        $stmt2->execute([
            'zona' => $zona_trabajo,
            'tarifa' => $tarifa_hora,
            'desc' => $descripcion,
            'id' => $id_usuario
        ]);

        //Actualizar especialidades (eliminar y volver a insertar)
        $del = $db->prepare("DELETE FROM tecnico_especialidad WHERE id_tecnico = ?");
        $del->execute([$tecnico['id_tecnico']]);

        $ins = $db->prepare("INSERT INTO tecnico_especialidad (id_tecnico, id_especialidad) VALUES (?, ?)");
        foreach ($especialidades as $esp) {
            $ins->execute([$tecnico['id_tecnico'], $esp]);
        }

        $db->commit();
        $mensaje = "√ Datos actualizados correctamente.";
    } catch (Exception $e) {
        $db->rollBack();
        $mensaje = "X Error al actualizar los datos. Verifica la información ingresada.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Perfil del Técnico - TechFix</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../Vistas/css/estilos.css">

</head>
<body class="bg-light">

<div class="container mt-5">
  <div class="card shadow p-4">
    <h3 class="mb-4 text-center">Perfil del Técnico <?= htmlspecialchars($primerNombre) ?></h3>

    <?php if ($mensaje): ?>
      <div class="alert alert-info text-center"><?= htmlspecialchars($mensaje) ?></div>
    <?php endif; ?>

    <form method="POST">
      <h5 class="mb-3">Datos Personales</h5>

      <div class="mb-3">
        <label class="form-label">Nombre completo</label>
        <input type="text" name="nombre_completo" class="form-control" value="<?= htmlspecialchars($tecnico['nombre_completo']) ?>" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Correo electrónico</label>
        <input type="email" name="correo" class="form-control" value="<?= htmlspecialchars($tecnico['correo']) ?>" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Teléfono</label>
        <input type="text" name="telefono" class="form-control" value="<?= htmlspecialchars($tecnico['telefono']) ?>" required>
      </div>

      <hr>

      <h5 class="mb-3">Datos Profesionales</h5>

      <!--Especialidades con su checkbox -->
      <div class="mb-3">
        <label class="form-label">Especialidades</label>
        <div class="form-check bg-light p-3 rounded">
          <?php
            $espQuery = $db->query("SELECT id_especialidad, nombre FROM especialidad ORDER BY nombre");
            $espDelTecnico = $db->prepare("SELECT id_especialidad FROM tecnico_especialidad WHERE id_tecnico = ?");
            $espDelTecnico->execute([$tecnico['id_tecnico']]);
            $especialidadesActuales = $espDelTecnico->fetchAll(PDO::FETCH_COLUMN);

            while ($esp = $espQuery->fetch(PDO::FETCH_ASSOC)) {
                $checked = in_array($esp['id_especialidad'], $especialidadesActuales) ? 'checked' : '';
                echo "
                  <div class='form-check'>
                    <input class='form-check-input' type='checkbox' name='especialidades[]' value='{$esp['id_especialidad']}' $checked>
                    <label class='form-check-label'>{$esp['nombre']}</label>
                  </div>
                ";
            }
          ?>
          <small class="text-muted">Selecciona todas las que apliquen</small>
        </div>
      </div>

      <div class="mb-3">
        <label class="form-label">Zona de trabajo</label>
        <select name="zona_trabajo" class="form-select" required>
          <?php
          $zonas = ["Ahuachapán", "Sonsonate", "Santa Ana", "La Libertad", "Chalatenango", 
                    "San Salvador", "Cuscatlán", "La Paz", "San Vicente", "Cabañas", 
                    "Usulután", "San Miguel", "Morazán", "La Unión"];
          foreach ($zonas as $z) {
              $sel = ($z == $tecnico['zona_trabajo']) ? 'selected' : '';
              echo "<option value='$z' $sel>$z</option>";
          }
          ?>
        </select>
      </div>

      <div class="mb-3">
        <label class="form-label">Tarifa por hora ($)</label>
        <input type="number" name="tarifa_hora" step="0.01" class="form-control"
               value="<?= htmlspecialchars($tecnico['tarifa_hora']) ?>" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Descripción</label>
        <textarea name="descripcion" class="form-control" rows="3"><?= htmlspecialchars($tecnico['descripcion']) ?></textarea>
      </div>

      <div class="mb-3">
        <label class="form-label">Estado del perfil</label>
        <input type="text" class="form-control" value="<?= htmlspecialchars(ucfirst($tecnico['estado'])) ?>" readonly>
      </div>

      <div class="text-center mt-4">
        <button type="submit" class="btn btn-primary px-4">Guardar cambios</button>
        <a href="HomeTecnicos.php" class="btn btn-secondary px-4">Volver</a>
      </div>
    </form>
  </div>
</div>
<style>
    .container{
        padding-bottom: 25px;
    }
</style>

</body>
</html>
