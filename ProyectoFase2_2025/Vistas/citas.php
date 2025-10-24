<?php
include "../Modelos/Conexion.php";
$connObj = new Conexion();
$conn = $connObj->getConexion();

$id_solicitud = isset($_GET['id']) ? intval($_GET['id']) : 0;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reservar Cita</title>
    <link rel="stylesheet" href="citas.css">
</head>
<body>
    <div class="container">
        <h1>Reservar Cita</h1>
        <?php if($id_solicitud == 0): ?>
            <p style="color:red;">ID de solicitud no especificado.</p>
        <?php else: ?>
        <form id="cita-form" action="ReservarCita.php" method="POST">
            <input type="hidden" name="id_solicitud" value="<?php echo htmlspecialchars($id_solicitud); ?>">

            <label for="fecha_inicio">Fecha de Inicio:</label>
            <input type="datetime-local" name="fecha_inicio" id="fecha_inicio" required>

            <label for="fecha_fin">Fecha de Fin:</label>
            <input type="datetime-local" name="fecha_fin" id="fecha_fin" required>

            <label for="notas">Notas:</label>
            <textarea name="notas" id="notas" rows="4" placeholder="Deja alguna nota..."></textarea>

            <button type="submit" class="btnEnviarCita">Reservar Cita</button>
        </form>
        <?php endif; ?>
    </div>
    <script src="citas.js" defer></script>
</body>
</html>
