<?php
session_start();
require_once '../Controladores/reservarCitaControlador.php';

if (!isset($_SESSION['Id'])) {
    echo "<p style='color:red;'>Error: Usuario no autenticado.</p>";
    exit;
}

$id_usuario = $_SESSION['Id'];
$controlador = new reservarCitaControlador();
$citas = $controlador->listarCitasPorUsuario($id_usuario);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Citas - TechFix</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            font-family: "Segoe UI", Tahoma, Verdana, sans-serif;
            background: linear-gradient(0deg, #9340c7, #1f56a5);
            color: #fff;
            min-height: 100vh;
            padding: 20px;
        }

        .card-citas {
            background: rgba(255,255,255,0.1);
            border-radius: 20px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.2);
            backdrop-filter: blur(6px);
            margin-top: 50px;
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card-citas:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 30px rgba(0,0,0,0.3);
        }

        .card-header-citas {
            background: linear-gradient(45deg, #3659f3, #764ba2);
            color: #fff;
            font-weight: 600;
            text-align: center;
            padding: 20px;
            font-size: 1.4rem;
        }

        table {
            background: rgba(255,255,255,0.15);
            border-radius: 10px;
            overflow: hidden;
        }

        thead {
            background: rgba(255,255,255,0.2);
            color: #fff;
        }

        table th, table td {
            text-align: center;
            vertical-align: middle;
            color: #fff;
        }

        table tbody tr:hover {
            background: rgba(255,255,255,0.1);
            transition: 0.3s;
        }

        .alert-info {
            background: rgba(255,255,255,0.2);
            color: #fff;
            border: none;
            font-weight: 500;
        }

        .btn-volver {
            background: #ff4d4d;
            border: none;
            border-radius: 25px;
            color: #fff;
            padding: 10px 25px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-volver:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        }

        .card-footer {
            background: transparent;
            color: rgba(255,255,255,0.8);
            text-align: center;
            padding: 15px 0 25px;
        }

        .table-responsive {
            border-radius: 15px;
            overflow: hidden;
        }
    </style>
</head>
<body>
<main class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card card-citas">
                <div class="card-header card-header-citas">
                    <i class="bi bi-calendar-check"></i> Mis Citas Reservadas
                </div>
                <div class="card-body p-4">
                    <?php if (empty($citas)): ?>
                        <div class="alert alert-info text-center">
                            No tienes citas registradas.
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>ID Cita</th>
                                        <th>Fecha Inicio</th>
                                        <th>Fecha Fin</th>
                                        <th>Estado</th>
                                        <th>Notas</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($citas as $c): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($c['id_cita']) ?></td>
                                        <td><?= htmlspecialchars($c['fecha_inicio']) ?></td>
                                        <td><?= htmlspecialchars($c['fecha_fin']) ?></td>
                                        <td><?= ucfirst(htmlspecialchars($c['estado'])) ?></td>
                                        <td><?= htmlspecialchars($c['notas']) ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="card-footer">
                    <a href="Home.php" class="btn-volver"><i class="bi bi-arrow-left"></i> Volver al inicio</a>
                </div>
            </div>
        </div>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
