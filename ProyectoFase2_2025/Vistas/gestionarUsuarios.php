<?php
require_once '../Modelos/Conexion.php';

try {
    $db = (new Conexion())->getConexion();
    echo "Conexion Exitosa!!";

    // Obtener todos los usuarios
    $stmt = $db->prepare("SELECT id_usuario, nombre_completo, correo, rol, estado, telefono, fecha_creado FROM usuarios ORDER BY id_usuario ASC");
    $stmt->execute();
    $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Actualizar usuario
    if (isset($_POST['editar'])) {
        $id = $_POST['id_usuario'];
        $nombre = $_POST['nombre_completo'];
        $correo = $_POST['correo'];
        $rol = $_POST['rol'];
        $estado = $_POST['estado'];
        $telefono = $_POST['telefono'];

        $update = $db->prepare("UPDATE usuarios SET nombre_completo=?, correo=?, rol=?, estado=?, telefono=? WHERE id_usuario=?");
        $update->execute([$nombre, $correo, $rol, $estado, $telefono, $id]);

        header("Location: gestionarUsuarios.php");
        exit();
    }

    // Eliminar usuario
    if (isset($_POST['eliminar'])) {
        $id = $_POST['id_usuario'];
        $delete = $db->prepare("DELETE FROM usuarios WHERE id_usuario = ?");
        $delete->execute([$id]);
        header("Location: gestionarUsuarios.php");
        exit();
    }
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestionar Usuarios - TechFix</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body style="background: linear-gradient(180deg, #1f56a5, #9340c7); color: white;">
<nav class="navbar navbar-dark bg-dark p-3">
    <div class="container-fluid">
        <span class="navbar-brand mb-0 h1">👥 Gestionar Usuarios - TechFix</span>
        <a href="adminPanel.php" class="btn btn-outline-light">Volver al Panel</a>
    </div>
</nav>

<div class="container mt-5">
    <h2 class="text-center mb-4">Gestión de Usuarios Registrados</h2>

    <div class="table-responsive">
        <table class="table table-dark table-hover align-middle text-center">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre Completo</th>
                    <th>Correo</th>
                    <th>Rol</th>
                    <th>Estado</th>
                    <th>Teléfono</th>
                    <th>Fecha Creado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($usuarios as $u): ?>
                <tr>
                    <td><?= htmlspecialchars($u['id_usuario']) ?></td>
                    <td><?= htmlspecialchars($u['nombre_completo']) ?></td>
                    <td><?= htmlspecialchars($u['correo']) ?></td>
                    <td><?= htmlspecialchars($u['rol']) ?></td>
                    <td>
                        <?php if ($u['estado'] == 'activo'): ?>
                            <span class="badge bg-success">Activo</span>
                        <?php else: ?>
                            <span class="badge bg-danger">Suspendido</span>
                        <?php endif; ?>
                    </td>
                    <td><?= htmlspecialchars($u['telefono']) ?></td>
                    <td><?= htmlspecialchars($u['fecha_creado']) ?></td>
                    <td>
                        <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editar<?= $u['id_usuario'] ?>">Editar</button>
                        <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#eliminar<?= $u['id_usuario'] ?>">Eliminar</button>
                    </td>
                </tr>

                <!-- Modal Editar -->
                <div class="modal fade" id="editar<?= $u['id_usuario'] ?>" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content text-dark">
                            <form method="POST">
                                <div class="modal-header bg-primary text-white">
                                    <h5 class="modal-title">Editar Usuario</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <input type="hidden" name="id_usuario" value="<?= $u['id_usuario'] ?>">
                                    <div class="mb-3">
                                        <label>Nombre Completo</label>
                                        <input type="text" name="nombre_completo" class="form-control" value="<?= htmlspecialchars($u['nombre_completo']) ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label>Correo</label>
                                        <input type="email" name="correo" class="form-control" value="<?= htmlspecialchars($u['correo']) ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label>Teléfono</label>
                                        <input type="text" name="telefono" class="form-control" value="<?= htmlspecialchars($u['telefono']) ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label>Rol</label>
                                        <select name="rol" class="form-select">
                                            <option value="admin" <?= $u['rol']=='admin'?'selected':'' ?>>Admin</option>
                                            <option value="cliente" <?= $u['rol']=='cliente'?'selected':'' ?>>Cliente</option>
                                            <option value="tecnico" <?= $u['rol']=='tecnico'?'selected':'' ?>>Técnico</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label>Estado</label>
                                        <select name="estado" class="form-select">
                                            <option value="activo" <?= $u['estado']=='activo'?'selected':'' ?>>Activo</option>
                                            <option value="suspendido" <?= $u['estado']=='suspendido'?'selected':'' ?>>Suspendido</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                    <button type="submit" name="editar" class="btn btn-primary">Guardar Cambios</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Modal Eliminar -->
                <div class="modal fade" id="eliminar<?= $u['id_usuario'] ?>" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content text-dark">
                            <form method="POST">
                                <div class="modal-header bg-danger text-white">
                                    <h5 class="modal-title">Eliminar Usuario</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <p>¿Seguro que deseas eliminar a <strong><?= htmlspecialchars($u['nombre_completo']) ?></strong>?</p>
                                    <input type="hidden" name="id_usuario" value="<?= $u['id_usuario'] ?>">
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                    <button type="submit" name="eliminar" class="btn btn-danger">Eliminar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<footer class="text-center mt-5 p-3 bg-dark">
    <p class="mb-0">© 2025 TechFix | Administrador</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
