<?php
require_once '../Modelos/Conexion.php';

try {
    $db = (new Conexion())->getConexion();
    echo "Conexion Exitosa!!";

    // Crear tabla config si no existe
    $db->exec("CREATE TABLE IF NOT EXISTS configuracion (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nombre_sitio VARCHAR(100) DEFAULT 'TechFix',
        color_primario VARCHAR(20) DEFAULT '#1f56a5',
        color_secundario VARCHAR(20) DEFAULT '#9340c7',
        logo_url VARCHAR(255) DEFAULT '',
        fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )");

    // Insertar fila inicial si está vacía
    $db->exec("INSERT INTO configuracion (nombre_sitio) SELECT 'TechFix' WHERE NOT EXISTS (SELECT 1 FROM configuracion)");
    $config = $db->query("SELECT * FROM configuracion LIMIT 1")->fetch(PDO::FETCH_ASSOC);

    // Guardar cambios de apariencia
    if (isset($_POST['guardar_config'])) {
        $nombre = $_POST['nombre_sitio'];
        $color1 = $_POST['color_primario'];
        $color2 = $_POST['color_secundario'];
        $logo = $_POST['logo_url'];

        $update = $db->prepare("UPDATE configuracion SET nombre_sitio=?, color_primario=?, color_secundario=?, logo_url=? WHERE id=?");
        $update->execute([$nombre, $color1, $color2, $logo, $config['id']]);
        header("Location: configuracionGeneral.php");
        exit();
    }

    // 🔹 SECCIÓN DE LIMPIEZA DE DATOS MEJORADA
    $mensaje = "";
    $tablaSeleccionada = $_POST['tabla'] ?? "";
    $datosTabla = [];

    if ($tablaSeleccionada) {
        // Obtener datos de la tabla seleccionada
        $stmt = $db->query("SELECT * FROM $tablaSeleccionada LIMIT 50");
        $datosTabla = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Eliminar registro individual
    if (isset($_POST['eliminar_registro'])) {
        $tabla = $_POST['tabla'];
        $id = $_POST['id'];
        $columnaID = $db->query("SHOW KEYS FROM $tabla WHERE Key_name = 'PRIMARY'")->fetch()['Column_name'];
        $delete = $db->prepare("DELETE FROM $tabla WHERE $columnaID = ?");
        $delete->execute([$id]);
        $mensaje = "🗑 Registro con ID $id eliminado correctamente.";
        $tablaSeleccionada = $tabla;
        $datosTabla = $db->query("SELECT * FROM $tabla LIMIT 50")->fetchAll(PDO::FETCH_ASSOC);
    }

    // Vaciar tabla completa
    if (isset($_POST['vaciar_tabla'])) {
        $tabla = $_POST['tabla'];
        $db->exec("DELETE FROM $tabla");
        $mensaje = "✅ Todos los registros de '$tabla' han sido eliminados.";
        $tablaSeleccionada = "";
        $datosTabla = [];
    }

} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Configuración General - TechFix</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body style="background: linear-gradient(180deg, <?= $config['color_primario'] ?>, <?= $config['color_secundario'] ?>); color: white;">
<nav class="navbar navbar-dark bg-dark p-3">
    <div class="container-fluid">
        <span class="navbar-brand mb-0 h1">⚙️ Configuración General - <?= htmlspecialchars($config['nombre_sitio']) ?></span>
        <a href="adminPanel.php" class="btn btn-outline-light">Volver al Panel</a>
    </div>
</nav>

<div class="container mt-5">
    <h2 class="text-center mb-4">Panel de Configuración del Sistema</h2>

    <?php if ($mensaje): ?>
        <div class="alert alert-success text-dark text-center"><?= $mensaje ?></div>
    <?php endif; ?>

    <!-- Apariencia -->
    <div class="card p-4 bg-dark text-white shadow mb-4">
        <h4>🛠 Personalizar Apariencia</h4>
        <form method="POST">
            <div class="row g-3">
                <div class="col-md-6">
                    <label>Nombre del Sitio</label>
                    <input type="text" name="nombre_sitio" class="form-control" value="<?= htmlspecialchars($config['nombre_sitio']) ?>" required>
                </div>
                <div class="col-md-3">
                    <label>Color Primario</label>
                    <input type="color" name="color_primario" class="form-control form-control-color" value="<?= htmlspecialchars($config['color_primario']) ?>">
                </div>
                <div class="col-md-3">
                    <label>Color Secundario</label>
                    <input type="color" name="color_secundario" class="form-control form-control-color" value="<?= htmlspecialchars($config['color_secundario']) ?>">
                </div>
                <div class="col-md-6 mt-3">
                    <label>URL del Logo</label>
                    <input type="text" name="logo_url" class="form-control" value="<?= htmlspecialchars($config['logo_url']) ?>">
                </div>
            </div>
            <div class="text-center mt-3">
                <button type="submit" name="guardar_config" class="btn btn-success px-4">💾 Guardar Cambios</button>
            </div>
        </form>
    </div>

    <!-- Limpieza -->
    <div class="card p-4 bg-dark text-white shadow mb-4">
        <h4>🧹 Limpieza de Datos</h4>
        <form method="POST" class="mb-3">
            <div class="row g-3 align-items-end">
                <div class="col-md-6">
                    <label>Selecciona una tabla:</label>
                    <select name="tabla" class="form-select text-dark" onchange="this.form.submit()">
                        <option value="">-- Seleccionar tabla --</option>
                        <option value="usuarios" <?= $tablaSeleccionada == 'usuarios' ? 'selected' : '' ?>>Usuarios</option>
                        <option value="solicitud" <?= $tablaSeleccionada == 'solicitud' ? 'selected' : '' ?>>Solicitudes</option>
                        <option value="resena" <?= $tablaSeleccionada == 'resena' ? 'selected' : '' ?>>Reseñas</option>
                        <option value="pago" <?= $tablaSeleccionada == 'pago' ? 'selected' : '' ?>>Pagos</option>
                    </select>
                </div>
                <?php if ($tablaSeleccionada): ?>
                <div class="col-md-3">
                    <button type="submit" name="vaciar_tabla" class="btn btn-danger">🗑 Vaciar Tabla</button>
                </div>
                <?php endif; ?>
            </div>
        </form>

        <?php if (!empty($datosTabla)): ?>
            <div class="table-responsive mt-3">
                <table class="table table-dark table-striped align-middle text-center">
                    <thead>
                        <tr>
                            <?php foreach (array_keys($datosTabla[0]) as $col): ?>
                                <th><?= htmlspecialchars($col) ?></th>
                            <?php endforeach; ?>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($datosTabla as $fila): ?>
                            <tr>
                                <?php foreach ($fila as $valor): ?>
                                    <td><?= htmlspecialchars($valor) ?></td>
                                <?php endforeach; ?>
                                <td>
                                    <form method="POST" style="display:inline;">
                                        <input type="hidden" name="tabla" value="<?= htmlspecialchars($tablaSeleccionada) ?>">
                                        <input type="hidden" name="id" value="<?= htmlspecialchars(reset($fila)) ?>">
                                        <button type="submit" name="eliminar_registro" class="btn btn-danger btn-sm">Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php elseif ($tablaSeleccionada): ?>
            <p class="mt-3 text-center text-light">📭 No hay registros en la tabla seleccionada.</p>
        <?php endif; ?>
    </div>

    <!-- Información -->
    <div class="card p-4 bg-dark text-white shadow">
        <h4>ℹ️ Información del Sistema</h4>
        <p><strong>Servidor:</strong> <?= $_SERVER['SERVER_SOFTWARE'] ?></p>
        <p><strong>PHP:</strong> <?= phpversion() ?></p>
        <p><strong>Base de Datos:</strong> <?= $db->getAttribute(PDO::ATTR_DRIVER_NAME) ?></p>
        <p><strong>Última actualización de Configuración:</strong> <?= htmlspecialchars($config['fecha_actualizacion']) ?></p>
    </div>
</div>

<footer class="text-center mt-5 p-3 bg-dark">
    <p class="mb-0">© 2025 TechFix | Administrador</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

