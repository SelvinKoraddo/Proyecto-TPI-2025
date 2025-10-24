<?php
session_start();
require_once "../Modelos/Conexion.php";
$conexion = new Conexion();

// Validar que llegue el ID del técnico
$id_tecnico = $_GET['id_tecnico'] ?? null;
if (!$id_tecnico) {
    echo "<p>ID de técnico no especificado.</p>";
    exit;
}

// Usuario logueado
$usuario_logueado = $_SESSION['id_usuario'] ?? null;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Contactar Técnico</title>
    <link rel="stylesheet" href="../Vistas/css/contactar.css">
</head>
<body>
    <h2>Contactar Técnico</h2>

    <?php if (!$usuario_logueado): ?>
        <p>Debes iniciar sesión para enviar mensajes.</p>
    <?php else: ?>
        <form id="contactForm" data-tecnico="<?php echo $id_tecnico; ?>">
            <label for="mensaje">Mensaje</label>
            <textarea id="mensaje" required placeholder="Escribe tu mensaje aquí"></textarea>
            <button type="submit">Enviar Mensaje</button>
        </form>
        <div id="respuesta" style="margin-top:10px;color:green;"></div>
    <?php endif; ?>

    <script src="../Vistas/script/contactar.js"></script>
</body>
</html>
