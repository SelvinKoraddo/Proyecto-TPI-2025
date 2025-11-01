<?php
session_start();
require_once '../Modelos/Conexion.php';

// --- Verificar sesión de administrador correctamente ---
if (!isset($_SESSION['Rol']) || strtolower($_SESSION['Rol']) !== 'admin') {
    header("Location: login.php"); 
    exit();
}

// --- Conexión a la base de datos ---
$db = (new Conexion())->getConexion();

// --- Suspender o reactivar usuario ---
if (isset($_GET['accion']) && isset($_GET['id'])) {
    $id = $_GET['id'];
    $accion = $_GET['accion'];

    if ($accion === 'suspender') {
        $stmt = $db->prepare("UPDATE usuarios SET estado = 'suspendido' WHERE id_usuario = ?");
    } elseif ($accion === 'reactivar') {
        $stmt = $db->prepare("UPDATE usuarios SET estado = 'activo' WHERE id_usuario = ?");
    }
    $stmt->execute([$id]);
    header("Location: suspenderCuentas.php");
    exit();
}

// --- Obtener lista de usuarios ---
$stmt = $db->query("SELECT id_usuario, nombre_completo, correo, rol, estado, fecha_creado FROM usuarios ORDER BY fecha_creado DESC");
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Suspender Cuentas | TechFix</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body style="background: linear-gradient(180deg,#1f56a5,#9340c7); color:white;">
    <nav class="navbar navbar-dark bg-dark p-3 fixed-top">
        <div class="container-fluid">
            <span class="navbar-brand mb-0 h1">🚫 Suspender / Reactivar Cuentas</span>
            <a href="adminPanel.php" class="btn btn-outline-light">⬅ Volver al Panel</a>
        </div>
    </nav>

    <div class="container mt-5 pt-5">
        <h2 class="text-center mb-4">Gestión de Cuentas de Usuarios</h2>

        <div class="table-responsive">
            <table class="table table-dark table-striped align-middle text-center shadow">
                <thead class="table-primary text-dark">
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Correo</th>
                        <th>Rol</th>
                        <th>Estado</th>
                        <th>Fecha Creado</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($usuarios) > 0): ?>
                        <?php foreach ($usuarios as $u): ?>
                            <tr>
                                <td><?= htmlspecialchars($u['id_usuario']) ?></td>
                                <td><?= htmlspecialchars($u['nombre_completo']) ?></td>
                                <td><?= htmlspecialchars($u['correo']) ?></td>
                                <td><?= htmlspecialchars($u['rol']) ?></td>
                                <td>
                                    <?php if ($u['estado'] === 'activo'): ?>
                                        <span class="badge bg-success">Activo</span>
                                    <?php elseif ($u['estado'] === 'suspendido'): ?>
                                        <span class="badge bg-danger">Suspendido</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Desconocido</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= htmlspecialchars($u['fecha_creado']) ?></td>
                                <td>
                                    <?php if ($u['estado'] === 'activo'): ?>
                                        <a href="?accion=suspender&id=<?= $u['id_usuario'] ?>" class="btn btn-danger btn-sm">Suspender</a>
                                    <?php else: ?>
                                        <a href="?accion=reactivar&id=<?= $u['id_usuario'] ?>" class="btn btn-success btn-sm">Reactivar</a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="7">No hay usuarios registrados.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <footer class="text-center mt-5 p-3 bg-dark fixed-bottom">
        <p class="mb-0">© 2025 TechFix | Administrador</p>
    </footer>

    <script>
    // Confirmación antes de suspender/reactivar
    const links = document.querySelectorAll('a.btn-danger, a.btn-success');
    links.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const url = this.href;
            const accion = this.classList.contains('btn-danger') ? 'suspender' : 'reactivar';
            Swal.fire({
                title: `¿Seguro que deseas ${accion} esta cuenta?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, continuar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = url;
                }
            });
        });
    });
    </script>

</body>
</html>
