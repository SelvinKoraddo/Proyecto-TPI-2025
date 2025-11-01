<?php
session_start();
require_once "../Controladores/reservarCitaControlador.php";

$controlador = new reservarCitaControlador();
$mensaje = "";

// Verifica si el usuario está autenticado
if (!isset($_SESSION['Id'])) {
    $mensaje = "Error: Usuario no autenticado.";
} elseif ($_SERVER['REQUEST_METHOD'] === "POST") {
    $id_usuario = $_SESSION['Id'];
    $id_tecnico = $_POST['id_tecnico'] ?? null;
    $fecha_inicio = $_POST['fecha_inicio'] ?? null;
    $fecha_fin = $_POST['fecha_fin'] ?? null;
    $direccion_servicio = $_POST['direccion_servicio'] ?? "Dirección pendiente";
    $notas = $_POST['notas'] ?? "";

    if ($id_tecnico && $fecha_inicio && $fecha_fin) {
        $resultado = $controlador->crearCita($id_usuario, $id_tecnico, $fecha_inicio, $fecha_fin, $notas, $direccion_servicio);
        if ($resultado === true) {
            header("Location: ConfirmacionCita.php");
            exit();
        } else {
            $mensaje = $resultado;
        }
    } else {
        $mensaje = "Faltan datos obligatorios para reservar la cita.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservar Cita - TechFix</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            font-family: "Segoe UI", Tahoma, Verdana, sans-serif;
            background: linear-gradient(0deg, #9340c7, #1f56a5);
            color: #fff;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center; 
            padding: 20px;
        }

        .card-reserva {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.2);
            backdrop-filter: blur(6px);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            width: 100%;
            max-width: 600px;
            margin: auto; 
        }

        .card-reserva:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 30px rgba(0,0,0,0.3);
        }

        .card-header-reserva {
            background: linear-gradient(45deg, #3659f3, #764ba2);
            color: #fff;
            font-weight: 600;
            border-top-left-radius: 20px;
            border-top-right-radius: 20px;
            text-align: center;
            padding: 20px;
            font-size: 1.4rem;
        }

        label {
            font-weight: 500;
            color: #fff;
        }

        .form-control {
            border-radius: 10px;
            border: none;
            outline: none;
            padding: 10px;
            font-size: 1rem;
        }

        .form-control:focus {
            box-shadow: 0 0 10px rgba(118, 75, 162, 0.8);
        }

        textarea {
            resize: none;
        }

        
        .btn-reserva, .btn-volver {
            flex: 1; /* mismo ancho */
            min-width: 130px;
            border: none;
            border-radius: 25px;
            padding: 10px 0;
            font-weight: 500;
            color: #fff;
            transition: all 0.3s ease;
            text-align: center;
        }

        .btn-reserva {
            background: linear-gradient(45deg, #3659f3, #764ba2);
        }

        .btn-volver {
            background: #ff4d4d;
            text-decoration: none;
        }

        .btn-reserva:hover, .btn-volver:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        }

        .card-footer {
            background: transparent;
            color: rgba(255,255,255,0.8);
            text-align: center;
            font-size: 0.9rem;
            padding-bottom: 15px;
        }

        .alert {
            border-radius: 10px;
        }

        
        main.container {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 90vh;
        }
    </style>
</head>
<body>
<main class="container">
    <div class="card card-reserva">
        <div class="card-header-reserva">
            <i class="bi bi-calendar-plus"></i> Reservar Cita
        </div>
        <div class="card-body p-4">
            <?php if($mensaje) : ?>
                <div class="alert alert-danger text-center"><?= $mensaje ?></div>
            <?php endif; ?>

            <form method="POST" action="">
                <input type="hidden" name="id_tecnico" value="<?= $_GET['id_tecnico'] ?? '' ?>">

                <div class="mb-3">
                    <label for="fecha_inicio" class="form-label">Fecha Inicio:</label>
                    <input type="datetime-local" class="form-control" name="fecha_inicio" required>
                </div>

                <div class="mb-3">
                    <label for="fecha_fin" class="form-label">Fecha Fin:</label>
                    <input type="datetime-local" class="form-control" name="fecha_fin" required>
                </div>
                <div class="mb-3">
                    <label for="direccion_servicio" class="form-label">Direccion del servicio:</label>
                    <textarea class="form-control" name="direccion_servicio" rows="1" placeholder="Indique su direccion..."></textarea>
                </div>
                <div class="mb-3">
                    <label for="notas" class="form-label">Notas:</label>
                    <textarea class="form-control" name="notas" rows="3" placeholder="Opcional..."></textarea>
                </div>

                <div class="d-flex justify-content-center gap-3">
                    <button type="submit" class="btn-reserva"><i class="bi bi-check-circle"></i> Reservar</button>
                    <a href="javascript:history.back()" class="btn-volver"><i class="bi bi-arrow-left"></i> Volver</a>
                </div>
            </form>
        </div>
        <div class="card-footer">
            TechFix - Servicios Técnicos Profesionales
        </div>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
