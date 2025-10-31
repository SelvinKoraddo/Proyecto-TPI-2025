<?php
session_start();
require_once '../Controladores/reservarCitaControlador.php';
require_once '../Modelos/Conexion.php';

if (!isset($_SESSION['Id']) || !isset($_SESSION['Rol'])) {
    echo "<p style='color:red;'>Error: Usuario no autenticado.</p>";
    exit;
}

$id_usuario = $_SESSION['Id'];
$rol = $_SESSION['Rol'];
$controlador = new reservarCitaControlador();
$db = (new Conexion())->getConexion();

// Obtener citas según rol
if ($rol === 'cliente') {
    $citas = $controlador->listarCitasPorUsuario($id_usuario);
} elseif ($rol === 'tecnico') {
    $stmt = $db->prepare("SELECT id_tecnico FROM perfil_tecnico WHERE id_usuario = ?");
    $stmt->execute([$id_usuario]);
    $id_tecnico = $stmt->fetchColumn();
    $citas = $id_tecnico ? $controlador->listarCitasPorTecnico($id_tecnico) : [];
} else {
    $citas = [];
}

// Procesar acciones del técnico (Aprobar, Rechazar, Finalizar)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $rol === 'tecnico') {
    $id_cita = $_POST['id_cita'] ?? null;
    $accion = $_POST['accion'] ?? '';
    if ($id_cita) {
        switch ($accion) {
            case 'aprobar':
                $controlador->actualizarEstado($id_cita, 'confirmada');
                break;
            case 'rechazar':
                $controlador->actualizarEstado($id_cita, 'cancelada');
                break;
            case 'finalizar':
                $horas = floatval($_POST['horas']);
                // Obtener tarifa del técnico
                $stmt = $db->prepare("SELECT tarifa_hora FROM perfil_tecnico WHERE id_usuario = ?");
                $stmt->execute([$id_usuario]);
                $tarifa = floatval($stmt->fetchColumn());
                $controlador->finalizarCita($id_cita, $horas, $tarifa);
                break;
        }
        header("Location: ListaCitas.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Mis Citas - TechFix</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: "Segoe UI", Tahoma, Verdana, sans-serif;
            background: linear-gradient(0deg, #9340c7, #1f56a5);
            color: #fff;
            padding: 20px;
            min-height: 100vh;
        }

        .card-citas {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 15px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
            margin-top: 40px;
            overflow: hidden;
        }

        .card-header-citas {
            background: linear-gradient(45deg, #3659f3, #764ba2);
            text-align: center;
            padding: 20px;
            font-size: 1.5rem;
            font-weight: bold;
            color: #fff;
        }

        table {
            background: rgba(255, 255, 255, 0.15);
            border-radius: 10px;
        }

        th,
        td {
            text-align: center;
            color: #fff;
            vertical-align: middle;
        }

        .btn-volver {
            background: #ff4d4d;
            color: #fff;
            border: none;
            border-radius: 25px;
            padding: 10px 25px;
            font-weight: 500;
        }

        .btn-volver:hover {
            background: #ff6666;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="card card-citas">
            <div class="card-header-citas">
                <?= ($rol === 'tecnico') ? 'Citas Asignadas (Técnico)' : 'Mis Citas Reservadas' ?>
            </div>
            <div class="card-body p-4">
                <?php if (empty($citas)): ?>
                    <div class="alert alert-info text-center">No hay citas registradas.</div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>ID Cita</th>
                                    <th>Fecha Inicio</th>
                                    <th>Fecha Fin</th>
                                    <th>Estado</th>
                                    <th>Notas</th>
                                    <th>Monto</th>
                                    <?php if ($rol === 'tecnico'): ?>
                                        <th>Acciones</th>
                                    <?php else: ?>
                                        <th>Opciones</th>
                                    <?php endif; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($citas as $c): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($c['id_cita']) ?></td>
                                        <td><?= htmlspecialchars($c['fecha_inicio']) ?></td>
                                        <td><?= htmlspecialchars($c['fecha_fin']) ?></td>
                                        <td><?= ucfirst(htmlspecialchars($c['estado'])) ?></td>
                                        <td><?= htmlspecialchars($c['notas']) ?></td>


                                        <td>
                                            <?php
                                            // Buscar monto si está disponible (desde tabla solicitud)
                                            $stmtMonto = $db->prepare("SELECT monto FROM solicitud WHERE id_solicitud = ?");
                                            $stmtMonto->execute([$c['id_solicitud'] ?? null]);
                                            $monto = $stmtMonto->fetchColumn();

                                            if ($c['estado'] === 'finalizada' && $monto !== null) {
                                                echo "<span class='fw-bold text-success'>$" . number_format($monto, 2) . "</span>";
                                            } else {
                                                echo "<span class='text-muted'>—</span>";
                                            }
                                            ?>
                                        </td>

                                        <?php if ($rol === 'tecnico'): ?>
                                            <td>
                                                <?php if ($c['estado'] === 'pendiente'): ?>
                                                    <form method="POST" style="display:inline;">
                                                        <input type="hidden" name="id_cita" value="<?= $c['id_cita'] ?>">
                                                        <button name="accion" value="aprobar"
                                                            class="btn btn-success btn-sm">Aprobar</button>
                                                        <button name="accion" value="rechazar"
                                                            class="btn btn-danger btn-sm">Rechazar</button>
                                                    </form>
                                                <?php elseif ($c['estado'] === 'confirmada'): ?>
                                                    <form method="POST">
                                                        <input type="hidden" name="id_cita" value="<?= $c['id_cita'] ?>">
                                                        <input type="number" name="horas" class="form-control mb-2"
                                                            placeholder="Horas trabajadas" min="1" step="0.5" required>
                                                        <button name="accion" value="finalizar"
                                                            class="btn btn-warning btn-sm w-100">Finalizar</button>
                                                    </form>
                                                <?php else: ?>
                                                    <span class="text-muted">Sin acciones</span>
                                                <?php endif; ?>
                                            </td>
                                        <?php else: ?>
                                            <td>
                                                <?php
                                                // Buscar monto y verificar si ya existe un pago completado
                                                $stmtMonto = $db->prepare("SELECT monto FROM solicitud WHERE id_solicitud = ?");
                                                $stmtMonto->execute([$c['id_solicitud'] ?? null]);
                                                $monto = $stmtMonto->fetchColumn();

                                                $stmtPago = $db->prepare("SELECT estado FROM pago WHERE id_solicitud = ? LIMIT 1");
                                                $stmtPago->execute([$c['id_solicitud'] ?? null]);
                                                $estadoPago = $stmtPago->fetchColumn();

                                                if ($c['estado'] === 'finalizada' && $monto > 0) {
                                                    if ($estadoPago === 'completed') {
                                                        echo "<span class='badge bg-success'><i class='bi bi-check-circle'></i> Pago completado</span>";
                                                    } else {
                                                        echo '<a href="Pago.php?id_solicitud=' . $c['id_solicitud'] . '" class="btn btn-success btn-sm">Pagar</a>';
                                                    }
                                                } else {
                                                    echo "<span class='text-muted'>En proceso...</span>";
                                                }
                                                ?>

                                            </td>
                                        <?php endif; ?>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                <?php endif; ?>
            </div>
            <div class="card-footer text-center">
                <?php if ($rol === 'tecnico'): ?>
                    <a href="HomeTecnicos.php" class="btn-volver"><i class="bi bi-arrow-left"></i> Volver</a>
                <?php else: ?>
                    <a href="Home.php" class="btn-volver"><i class="bi bi-arrow-left"></i> Volver</a>
                <?php endif; ?>
            </div>

        </div>
    </div>
</body>

</html>