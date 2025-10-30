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
</head>
<body>
    <h1>Solicitudes Registradas</h1>
    <table border="1">
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
            <?php foreach($solicitudes as $s): ?>
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
</body>
</html>
