<?php
session_start();
require_once '../Modelos/Conexion.php';

// Verificar sesion y rol
if (!isset($_SESSION['Rol']) || $_SESSION['Rol'] !== 'tecnico') {
    header('Location: Login.php');
    exit;
}

$db = (new Conexion())->getConexion();
$id_usuario = $_SESSION['Id'];


$stmt = $db->prepare("SELECT id_tecnico FROM perfil_tecnico WHERE id_usuario = ?");
$stmt->execute([$id_usuario]);
$id_tecnico = $stmt->fetchColumn();

if (!$id_tecnico) {
    echo "<p style='color:red;'>Error: No se encontró el perfil del técnico.</p>";
    exit;
}

// para ver las solicitudes de ese tecnico
$sql = "SELECT s.*, 
               u.nombre_completo AS cliente 
        FROM solicitud s
        INNER JOIN usuarios u ON s.id_usuario = u.id_usuario
        WHERE s.id_tecnico = ?
        ORDER BY s.fecha_programacion DESC";
$stmt = $db->prepare($sql);
$stmt->execute([$id_tecnico]);
$solicitudes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Mensajería - Solicitudes del Técnico</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="../Vistas/css/estilos.css">
    <style>
        body {
            background-color: #f0f2f5;
            min-height: 100vh;
            padding: 20px 0;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #5a67d8;
            font-weight: bold;
            text-align: center;
            padding: 15px 0;
            border-bottom: 3px solid #5a67d8;
            margin-bottom: 30px;
        }

        .btn-mensajes {
            background-color: #5a67d8;
            color: white;
            border-radius: 20px;
            padding: 6px 15px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-mensajes:hover {
            background-color: #434190;
            transform: translateY(-2px);
        }

        .btn-volver-home {
            background-color: #ff4d4d;
            color: white;
            border-radius: 8px;
            padding: 8px 16px;
            font-weight: 500;
            text-decoration: none;
        }
        .btn-volver-home:hover { background-color: #ff6666; }

        @media (max-width: 768px) {
            h1 {
                font-size: 1.5em;
                padding: 20px;
            }

            th,
            td {
                padding: 10px;
                font-size: 0.9em;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Solicitudes con Mensajería</h1>
        <br>
        <?php if (empty($solicitudes)): ?>
            <div class="alert alert-info text-center">No tienes solicitudes asignadas aún.</div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-striped align-middle">
                    <thead>
                        <tr>
                            <th>ID Solicitud</th>
                            <th>Cliente</th>
                            <th>Descripción</th>
                            <th>Estado</th>
                            <th>Fecha Programación</th>
                            <th>Dirección</th>
                            <th>Monto</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($solicitudes as $s): ?>
                            <tr>
                                <td><?= htmlspecialchars($s['id_solicitud']) ?></td>
                                <td><?= htmlspecialchars($s['cliente']) ?></td>
                                <td><?= htmlspecialchars($s['descripcion']) ?></td>
                                <td><?= htmlspecialchars($s['estado']) ?></td>
                                <td><?= htmlspecialchars($s['fecha_programacion']) ?></td>
                                <td><?= htmlspecialchars($s['direccion_servicio']) ?></td>
                                <td><?= $s['monto'] ? '$' . number_format($s['monto'], 2) : '—' ?></td>
                                <td>
                                    <a href="MensajeriaTecnico.php?id_solicitud=<?= $s['id_solicitud'] ?>" 
                                       class="btn btn-mensajes btn-sm">
                                       Ver Mensajes
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>

        <div class="mt-3 text-end">
            
            <a href="/Proyecto-TPI-2025/ProyectoFase2_2025/Vistas/HomeTecnicos.php" class="btn-volver-home">
                ← Volver al inicio
            </a>
        </div>
    </div>
</body>

</html>
