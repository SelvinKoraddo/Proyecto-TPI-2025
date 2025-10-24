<?php
include "../Modelos/Conexion.php";
$connObj = new Conexion();
$conn = $connObj->getConexion();

$mensaje = "";
$tipoMensaje = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['fecha_inicio'])) {
    $id_solicitud = intval($_POST['id_solicitud'] ?? 0);
    $fecha_inicio = $_POST['fecha_inicio'] ?? '';
    $fecha_fin    = $_POST['fecha_fin'] ?? '';
    $notas        = trim($_POST['notas'] ?? '');

    if ($id_solicitud && $fecha_inicio && $fecha_fin) {
        try {
            $stmt = $conn->prepare("INSERT INTO cita (id_solicitud, fecha_inicio, fecha_fin, estado, notas)
                                    VALUES (?, ?, ?, 'pendiente', ?)");
            $stmt->execute([$id_solicitud, $fecha_inicio, $fecha_fin, $notas]);
            $mensaje = "✅ ¡Cita reservada exitosamente!";
            $tipoMensaje = "success";
        } catch (Exception $e) {
            $mensaje = " Error al guardar la cita. Intenta nuevamente.";
            $tipoMensaje = "error";
        }
    } else {
        $mensaje = " Faltan datos para reservar la cita.";
        $tipoMensaje = "error";
    }
}

$id_solicitud = isset($_POST['id_tecnico']) ? intval($_POST['id_tecnico']) : 0;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reservar Cita</title>
    <link rel="stylesheet" href="css/citas.css">
</head>
<body>
    <div class="container">
        <h1>Reservar Cita</h1>

        <?php if($mensaje): ?>
            <div class="mensaje <?php echo $tipoMensaje; ?>">
                <?php echo htmlspecialchars($mensaje); ?>
            </div>
            <?php if($tipoMensaje === "success"): ?>
                <div style="text-align:center; margin-top:20px;">
                    <a href="Home.php" class="btnVolver">Volver al inicio</a>
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <?php if($id_solicitud == 0 && !$mensaje): ?>
            <p style="color:red;">ID de solicitud o técnico no especificado.</p>
        <?php elseif(!$mensaje || $tipoMensaje === "error"): ?>
            <form id="cita-form" action="ReservarCita.php" method="POST">
                <input type="hidden" name="id_solicitud" value="<?php echo $id_solicitud; ?>">

                <label for="fecha_inicio">Fecha de Inicio:</label>
                <input type="datetime-local" name="fecha_inicio" id="fecha_inicio" required>

                <label for="fecha_fin">Fecha de Fin:</label>
                <input type="datetime-local" name="fecha_fin" id="fecha_fin" required>

                <label for="notas">Notas (opcional):</label>
                <textarea name="notas" id="notas" rows="4" placeholder="Deja alguna nota..."></textarea>

                <button type="submit" class="btnEnviarCita">Enviar Cita</button>
            </form>
        <?php endif; ?>
    </div>
    <script src="citas.js" defer></script>
</body>
</html>
