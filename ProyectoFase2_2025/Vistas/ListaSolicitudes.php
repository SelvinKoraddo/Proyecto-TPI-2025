<?php
require_once '../Controladores/solicitudControlador.php';

$controlador = new solicitudControlador();
$solicitudes = $controlador->listarSolicitudes();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Lista de Solicitudes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="../Vistas/css/estilos.css">

</head>

<body>
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
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        h1 {
            color: #5a67d8;
            font-weight: bold;
            text-align: center;
            padding: 15px 0;
            border-bottom: 3px solid #5a67d8;
            margin-bottom: 30px;
        }
        /* Responsive */
        @media (max-width: 768px) {
            h1 {
                font-size: 1.5em;
                padding: 20px;
            }

            th, td {
                padding: 10px;
                font-size: 0.9em;
            }
        }
    </style>
    <div class="container">
        <h1>Solicitudes Registradas</h1>
        <br>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Usuario</th>
                    <th>Técnico</th>
                    <th>Descripción</th>
                    <th>Estado</th>
                    <th>Fecha Programación</th>
                    <th>Dirección</th>
                    <th>Monto</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($solicitudes as $s): ?>
                    <tr>
                        <td><?php echo $s['id_solicitud']; ?></td>
                        <td><?php echo $s['id_usuario']; ?></td>
                        <td><?php echo $s['id_tecnico']; ?></td>
                        <td><?php echo $s['descripcion']; ?></td>
                        <td><?php echo $s['estado']; ?></td>
                        <td><?php echo $s['fecha_programacion']; ?></td>
                        <td><?php echo $s['direccion_servicio']; ?></td>
                        <td><?php echo $s['monto']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

</body>

</html>