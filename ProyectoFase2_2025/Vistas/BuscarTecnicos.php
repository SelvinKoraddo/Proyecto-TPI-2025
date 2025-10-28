<?php
require_once "../Controladores/buscarTecnicosControlador.php";

$especialidad = $_GET['especialidad'] ?? '';
$tecnicos = [];

if (!empty($especialidad)) {
    $controlador = new buscarTecnicosControlador();
    $tecnicos = $controlador->obtenerTecnicos($especialidad);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Técnicos - <?= htmlspecialchars($especialidad) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            font-family: "Segoe UI", Tahoma, Verdana, sans-serif;
            background: linear-gradient(0deg, #9340c7,#1f56a5);
            color: #fff;
            padding: 20px 0;
        }

        h2 {
            text-align: center;
            margin: 30px 0;
        }

        .tech-card {
            background: rgba(255,255,255,0.1);
            border-radius: 16px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            padding: 20px;
            margin-bottom: 20px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .tech-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.15);
        }

        .tech-info p {
            margin: 5px 0;
            font-size: 1rem;
        }

        .btn-custom {
            padding: 0.5rem 1rem;
            border-radius: 25px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
            color: #fff;
            border: none;
            cursor: pointer;
        }

        .btn-reservar {
            background: linear-gradient(45deg, #3659f3, #764ba2);
        }

        .btn-contactar {
            background: linear-gradient(45deg, #667eea, #89f7fe);
        }

        .btn-volver {
            background: #ff4d4d;
            margin-top: 20px;
            display: inline-block;
        }

        .btn-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        }

        .no-results {
            text-align: center;
            color: #ff6666;
            font-size: 1.3rem;
            font-weight: bold;
            margin-top: 50px;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.2);
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Técnicos para: <?= htmlspecialchars($especialidad) ?></h2>

        <?php if (!empty($tecnicos)): ?>
            <div class="row justify-content-center">
                <?php foreach ($tecnicos as $tec): ?>
                    <div class="col-md-4">
                        <div class="tech-card text-center">
                            <div class="tech-info">
                                <h4><?= htmlspecialchars($tec['nombre_completo']) ?></h4>
                                <p><i class="bi bi-telephone-fill"></i> <?= htmlspecialchars($tec['telefono']) ?></p>
                                <p>⚒️ <?= htmlspecialchars($tec['especialidad']) ?></p>
                                <p>📍 <?= htmlspecialchars($tec['zona_trabajo']) ?></p>
                                <p>💵 $<?= htmlspecialchars($tec['tarifa_hora']) ?>/hora</p>
                            </div>
                            <div class="d-flex justify-content-center gap-2 mt-2">
                                <a href="ReservarCita.php?id_tecnico=<?= $tec['id_tecnico'] ?>" class="btn-custom btn-reservar">Reservar</a>

                               
                                <a href="crearSolicitudYContactar.php?id_tecnico=<?= $tec['id_tecnico'] ?>" class="btn-custom btn-contactar">Contactar</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p class="no-results">No se encontraron técnicos disponibles.</p>
        <?php endif; ?>

        <div class="text-center">
            <button class="btn-custom btn-volver" onclick="history.back()">Volver</button>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
