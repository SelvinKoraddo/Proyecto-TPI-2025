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

// Obtener solicitudes pendientes
$stmt = $db->prepare("SELECT id_usuario, nombre_completo, correo, telefono, rol, fecha_creado 
                      FROM usuarios 
                      WHERE estado = 'pendiente'");
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
    <h2 class="text-center mb-4">Solicitudes Pendientes</h2>

    <div class="card shadow bg-light text-dark p-4">
        <?php if (empty($solicitudes)): ?>
            <p class="text-center">No hay solicitudes pendientes por revisar.</p>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Correo</th>
                            <th>Teléfono</th>
                            <th>Rol</th>
                            <th>Fecha de Solicitud</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($solicitudes as $fila): ?>
                        <tr id="fila-<?= $fila['id_usuario'] ?>">
                            <td><?= $fila['id_usuario'] ?></td>
                            <td><?= htmlspecialchars($fila['nombre_completo']) ?></td>
                            <td><?= htmlspecialchars($fila['correo']) ?></td>
                            <td><?= htmlspecialchars($fila['telefono']) ?></td>
                            <td><?= htmlspecialchars($fila['rol']) ?></td>
                            <td><?= $fila['fecha_creado'] ?></td>
                            <td>
                                <button class="btn btn-success btn-sm" onclick="aprobarSolicitud(<?= $fila['id_usuario'] ?>)">✅ Aprobar</button>
                                <button class="btn btn-danger btn-sm" onclick="rechazarSolicitud(<?= $fila['id_usuario'] ?>)">❌ Rechazar</button>
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
function aprobarSolicitud(id) {
    Swal.fire({
        title: '¿Aprobar solicitud?',
        text: "El usuario pasará a estar activo.",
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Sí, aprobar',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#28a745'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch('../Modelos/gestionarSolicitudes.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'accion=aprobar&id=' + id
            })
            .then(res => res.text())
            .then(data => {
                document.getElementById('fila-' + id).remove();
                Swal.fire('Aprobada', 'La solicitud fue aprobada correctamente.', 'success');
            });
        }
    });
}

function rechazarSolicitud(id) {
    Swal.fire({
        title: '¿Rechazar solicitud?',
        text: "El usuario será eliminado del sistema.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, rechazar',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#d33'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch('../Modelos/gestionarSolicitudes.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'accion=rechazar&id=' + id
            })
            .then(res => res.text())
            .then(data => {
                document.getElementById('fila-' + id).remove();
                Swal.fire('Rechazada', 'La solicitud fue rechazada y eliminada.', 'info');
            });
        }
    });
}
</script>
</body>
</html>
