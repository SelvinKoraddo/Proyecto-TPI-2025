<?php
require_once '../Modelos/Conexion.php';

try {
    $db = (new Conexion())->getConexion();

    // Obtener usuarios por rol
    $admins = $db->query("SELECT * FROM usuarios WHERE rol='admin' ORDER BY id_usuario ASC")->fetchAll(PDO::FETCH_ASSOC);
    $tecnicos = $db->query("SELECT * FROM usuarios WHERE rol='tecnico' ORDER BY id_usuario ASC")->fetchAll(PDO::FETCH_ASSOC);
    $clientes = $db->query("SELECT * FROM usuarios WHERE rol='cliente' ORDER BY id_usuario ASC")->fetchAll(PDO::FETCH_ASSOC);

    // Editar usuario
    if (isset($_POST['editar'])) {
        $id = $_POST['id_usuario'];
        $nombre = $_POST['nombre_completo'];
        $correo = $_POST['correo'];
        $rol = $_POST['rol'];
        $telefono = $_POST['telefono'];

        $stmt = $db->prepare("UPDATE usuarios SET nombre_completo=?, correo=?, rol=?, telefono=? WHERE id_usuario=?");
        $stmt->execute([$nombre, $correo, $rol, $telefono, $id]);
        header("Location: gestionarUsuarios.php");
        exit();
    }

    // Eliminar usuario
    if (isset($_POST['eliminar'])) {
        $id = $_POST['id_usuario'];
        $stmt = $db->prepare("DELETE FROM usuarios WHERE id_usuario=?");
        $stmt->execute([$id]);
        header("Location: gestionarUsuarios.php");
        exit();
    }

} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestionar Usuarios - TechFix</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        html, body {
            height: 100%;
            display: flex;
            flex-direction: column;
            margin: 0;
        }

        body {
            background: linear-gradient(180deg, #1f56a5, #9340c7);
            color: white;
        }

        main {
            flex: 1;
        }

        footer {
            margin-top: auto;
            background-color: #212529;
            color: white;
            text-align: center;
            padding: 15px 0;
        }

        .card {
            background-color: #1a1a1a;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.3);
        }

        table {
            background-color: #212529;
            color: white;
        }

        .search-input {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 8px;
            border: none;
            outline: none;
        }

        .badge { font-size: 0.9rem; }
    </style>
</head>

<body>
<nav class="navbar navbar-dark bg-dark p-3">
    <div class="container-fluid">
        <span class="navbar-brand mb-0 h1">👥 Gestionar Usuarios - TechFix</span>
        <a href="adminPanel.php" class="btn btn-outline-light">⬅ Volver</a>
    </div>
</nav>

<main class="container mt-5">
    <h2 class="text-center mb-4">Gestión de Usuarios Registrados</h2>

    <!-- 🔍 Barra de búsqueda -->
    <input type="text" id="buscarUsuario" class="search-input" placeholder="🔍 Buscar por nombre o correo...">

    <!-- =================== ADMINISTRADORES =================== -->
    <div class="card p-3 mb-4">
        <h4 class="text-center text-light mb-3">Administradores</h4>
        <?php if (count($admins) > 0): ?>
        <div class="table-responsive">
            <table class="table table-dark table-hover text-center align-middle tablaUsuarios">
                <thead class="table-secondary text-dark">
                    <tr>
                        <th>ID</th><th>Nombre</th><th>Correo</th><th>Teléfono</th><th>Rol</th><th>Fecha</th><th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($admins as $u): ?>
                    <tr>
                        <td><?= $u['id_usuario'] ?></td>
                        <td><?= htmlspecialchars($u['nombre_completo']) ?></td>
                        <td><?= htmlspecialchars($u['correo']) ?></td>
                        <td><?= htmlspecialchars($u['telefono']) ?></td>
                        <td><span class="badge bg-primary"><?= ucfirst($u['rol']) ?></span></td>
                        <td><?= $u['fecha_creado'] ?></td>
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
                                        <h5>Editar Usuario</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <input type="hidden" name="id_usuario" value="<?= $u['id_usuario'] ?>">
                                        <div class="mb-3"><label>Nombre</label>
                                            <input type="text" name="nombre_completo" class="form-control" value="<?= htmlspecialchars($u['nombre_completo']) ?>" required>
                                        </div>
                                        <div class="mb-3"><label>Correo</label>
                                            <input type="email" name="correo" class="form-control" value="<?= htmlspecialchars($u['correo']) ?>" required>
                                        </div>
                                        <div class="mb-3"><label>Teléfono</label>
                                            <input type="text" name="telefono" class="form-control" value="<?= htmlspecialchars($u['telefono']) ?>" required>
                                        </div>
                                        <div class="mb-3"><label>Rol</label>
                                            <select name="rol" class="form-select">
                                                <option value="admin" <?= $u['rol']=='admin'?'selected':'' ?>>Admin</option>
                                                <option value="tecnico" <?= $u['rol']=='tecnico'?'selected':'' ?>>Técnico</option>
                                                <option value="cliente" <?= $u['rol']=='cliente'?'selected':'' ?>>Cliente</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                        <button type="submit" name="editar" class="btn btn-primary">Guardar</button>
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
                                        <h5>Eliminar Usuario</h5>
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
        <?php else: ?>
            <p class="text-center">No hay administradores registrados.</p>
        <?php endif; ?>
    </div>

    <!-- =================== TÉCNICOS =================== -->
    <div class="card p-3 mb-4">
        <h4 class="text-center text-light mb-3">Técnicos</h4>
        <div class="table-responsive">
            <table class="table table-dark table-hover text-center align-middle tablaUsuarios">
                <thead class="table-secondary text-dark">
                    <tr>
                        <th>ID</th><th>Nombre</th><th>Correo</th><th>Teléfono</th><th>Fecha</th><th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($tecnicos as $u): ?>
                    <tr>
                        <td><?= $u['id_usuario'] ?></td>
                        <td><?= htmlspecialchars($u['nombre_completo']) ?></td>
                        <td><?= htmlspecialchars($u['correo']) ?></td>
                        <td><?= htmlspecialchars($u['telefono']) ?></td>
                        <td><?= $u['fecha_creado'] ?></td>
                        <td>
                            <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editar<?= $u['id_usuario'] ?>">Editar</button>
                            <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#eliminar<?= $u['id_usuario'] ?>">Eliminar</button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- =================== CLIENTES =================== -->
    <div class="card p-3 mb-4">
        <h4 class="text-center text-light mb-3">Clientes</h4>
        <div class="table-responsive">
            <table class="table table-dark table-hover text-center align-middle tablaUsuarios">
                <thead class="table-secondary text-dark">
                    <tr>
                        <th>ID</th><th>Nombre</th><th>Correo</th><th>Teléfono</th><th>Fecha</th><th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($clientes as $u): ?>
                    <tr>
                        <td><?= $u['id_usuario'] ?></td>
                        <td><?= htmlspecialchars($u['nombre_completo']) ?></td>
                        <td><?= htmlspecialchars($u['correo']) ?></td>
                        <td><?= htmlspecialchars($u['telefono']) ?></td>
                        <td><?= $u['fecha_creado'] ?></td>
                        <td>
                            <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editar<?= $u['id_usuario'] ?>">Editar</button>
                            <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#eliminar<?= $u['id_usuario'] ?>">Eliminar</button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<footer>
    © 2025 TechFix | Administrador
</footer>

<script>
// 🔍 Búsqueda global entre todas las tablas
document.getElementById('buscarUsuario').addEventListener('input', function() {
    const valor = this.value.toLowerCase().trim();
    document.querySelectorAll('.tablaUsuarios tbody tr').forEach(fila => {
        const textoFila = fila.textContent.toLowerCase();
        fila.style.display = textoFila.includes(valor) ? '' : 'none';
    });
});
</script>
</body>
</html>






