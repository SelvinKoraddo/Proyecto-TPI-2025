<?php
session_start();
require_once '../Modelos/Conexion.php';

// Verificación de sesión
if (!isset($_SESSION['Rol']) || $_SESSION['Rol'] !== 'admin') {
    header("Location: Login.php");
    exit();
}

// Conexión
$db = (new Conexion())->getConexion();

// 🔹 Obtener todas las solicitudes de servicios registradas
$stmt = $db->prepare("
    SELECT s.id_solicitud, 
           u.nombre_completo AS cliente, 
           COALESCE(tu.nombre_completo, 'N/A') AS tecnico,
           s.descripcion,
           s.estado,
           s.fecha_programacion,
           s.direccion_servicio,
           s.monto
    FROM solicitud s
    LEFT JOIN usuarios u ON s.id_usuario = u.id_usuario
    LEFT JOIN perfil_tecnico pt ON s.id_tecnico = pt.id_tecnico
    LEFT JOIN usuarios tu ON pt.id_usuario = tu.id_usuario
    ORDER BY s.id_solicitud DESC
");
$stmt->execute();
$solicitudes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestionar Solicitudes | TechFix</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body style="background: linear-gradient(180deg, #1f56a5, #9340c7); color: white;">
<nav class="navbar navbar-dark bg-dark p-3">
    <div class="container-fluid">
        <span class="navbar-brand mb-0 h1">📋 Gestionar Solicitudes - TechFix</span>
        <a href="adminPanel.php" class="btn btn-outline-light">⬅ Volver al Panel</a>
    </div>
</nav>

<div class="container mt-5">
    <h2 class="text-center mb-4">Solicitudes de Servicios</h2>

    <div class="card shadow bg-light text-dark p-4">
        <?php if (empty($solicitudes)): ?>
            <p class="text-center">No hay solicitudes registradas actualmente.</p>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Cliente</th>
                            <th>Técnico</th>
                            <th>Descripción</th>
                            <th>Estado</th>
                            <th>Fecha Programación</th>
                            <th>Monto</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($solicitudes as $fila): ?>
                        <tr id="fila-<?= $fila['id_solicitud'] ?>">
                            <td><?= $fila['id_solicitud'] ?></td>
                            <td><?= htmlspecialchars($fila['cliente'] ?? 'N/A') ?></td>
                            <td><?= htmlspecialchars($fila['tecnico'] ?? 'N/A') ?></td>
                            <td><?= htmlspecialchars($fila['descripcion']) ?></td>
                            <td>
                                <span class="badge bg-<?= 
                                    $fila['estado'] === 'pendiente' ? 'warning' : 
                                    ($fila['estado'] === 'aprobado' ? 'success' : 'secondary') 
                                ?>">
                                    <?= ucfirst($fila['estado']) ?>
                                </span>
                            </td>
                            <td><?= $fila['fecha_programacion'] ?: '—' ?></td>
                            <td>$<?= number_format($fila['monto'] ?? 0, 2) ?></td>
                            <td>
                                <?php if ($fila['estado'] === 'pendiente'): ?>
                                    <button class="btn btn-success btn-sm" onclick="actualizarEstado(<?= $fila['id_solicitud'] ?>, 'aprobado')">✅ Aprobar</button>
                                    <button class="btn btn-danger btn-sm" onclick="actualizarEstado(<?= $fila['id_solicitud'] ?>, 'rechazado')">❌ Rechazar</button>
                                <?php else: ?>
                                    <button class="btn btn-secondary btn-sm" disabled>Sin acción</button>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<footer class="text-center mt-5 p-3 bg-dark text-white">
    © 2025 TechFix | Administrador
</footer>

<script>
function actualizarEstado(id, nuevoEstado) {
    let texto = (nuevoEstado === 'aprobado')
        ? '¿Deseas aprobar esta solicitud de servicio?'
        : '¿Deseas rechazar esta solicitud?';

    Swal.fire({
        title: texto,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Confirmar',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: (nuevoEstado === 'aprobado') ? '#28a745' : '#d33'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch('../Modelos/gestionarSolicitudes.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'accion=' + nuevoEstado + '&id=' + id
            })
            .then(res => res.text())
            .then(data => {
                document.getElementById('fila-' + id).remove();
                Swal.fire('Hecho', 'La solicitud fue ' + nuevoEstado + ' correctamente.', 'success');
            });
        }
    });
}
</script>
</body>
</html>

