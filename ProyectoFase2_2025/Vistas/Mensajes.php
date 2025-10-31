<?php
session_start();
require_once '../Controladores/mensajeControlador.php';

// Validar sesion y solicitud
$id_usuario = $_SESSION['Id'] ?? null;
$id_solicitud = $_GET['id_solicitud'] ?? null;

if (!$id_usuario || !$id_solicitud) {
    echo "<p style='color:red; text-align:center;'>Error: faltan datos.</p>";
    exit;
}

$controlador = new mensajeControlador();

// Enviar mensaje nuevo
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['contenido'])) {
    $contenido = trim($_POST['contenido']);
    $controlador->enviarMensaje($id_solicitud, $id_usuario, $contenido);
    header("Location: Mensajes.php?id_solicitud=$id_solicitud");
    exit;
}

// Obtener historial
$mensajes = $controlador->listarMensajesPorSolicitud($id_solicitud);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mensajes con el Cliente</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(180deg, #1f56a5, #9340c7);
            font-family: "Segoe UI", sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
        }

        .chat-container {
            background: rgba(255,255,255,0.1);
            width: 90%;
            max-width: 700px;
            border-radius: 20px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.3);
            padding: 20px;
            backdrop-filter: blur(8px);
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .chat-header {
            text-align: center;
            font-size: 1.6rem;
            font-weight: bold;
            border-bottom: 2px solid rgba(255,255,255,0.3);
            padding-bottom: 10px;
        }

        .chat-box {
            background: rgba(255,255,255,0.1);
            padding: 15px;
            border-radius: 15px;
            max-height: 400px;
            overflow-y: auto;
        }

        .mensaje {
            margin-bottom: 12px;
            padding: 10px 15px;
            border-radius: 15px;
            display: inline-block;
            max-width: 80%;
            word-wrap: break-word;
        }

        .mensaje-cliente {
            background-color: rgba(255,255,255,0.85);
            color: #222;
            align-self: flex-start;
            border-bottom-left-radius: 0;
        }

        .mensaje-tecnico {
            background-color: #5a67d8;
            align-self: flex-end;
            border-bottom-right-radius: 0;
        }

        .chat-input {
            display: flex;
            gap: 10px;
        }

        textarea {
            flex: 1;
            border-radius: 10px;
            border: none;
            padding: 10px;
            resize: none;
            font-size: 1rem;
        }

        .btn-enviar {
            background: linear-gradient(45deg, #3659f3, #764ba2);
            color: #fff;
            border: none;
            border-radius: 10px;
            padding: 10px 20px;
            transition: all 0.2s;
        }

        .btn-enviar:hover {
            background: linear-gradient(45deg, #4d6df7, #8f5bcc);
        }

        .btn-volver {
            background: #ff4d4d;
            border: none;
            border-radius: 10px;
            color: #fff;
            padding: 8px 20px;
            text-decoration: none;
            display: inline-block;
            transition: all 0.2s;
        }

        .btn-volver:hover {
            background: #ff6666;
        }
    </style>
</head>
<body>
    <div class="chat-container">
        <div class="chat-header">
            <i class="bi bi-chat-dots-fill"></i> Mensajes con el Cliente
        </div>

        <div class="chat-box d-flex flex-column">
            <?php if (empty($mensajes)): ?>
                <p style="text-align:center; opacity:0.8;">No hay mensajes aún.</p>
            <?php else: ?>
                <?php foreach ($mensajes as $m): ?>
                    <?php
                        $esTecnico = ($m['id_usuario'] != $_SESSION['Id']); 
                        $clase = $esTecnico ? 'mensaje-cliente' : 'mensaje-tecnico';
                    ?>
                    <div class="mensaje <?php echo $clase; ?>">
                        <strong><?php echo $esTecnico ? 'Cliente:' : 'Tú:'; ?></strong><br>
                        <?php echo htmlspecialchars($m['contenido']); ?><br>
                        <small style="opacity:0.7;"><?php echo $m['fecha_creado']; ?></small>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <form method="post" class="chat-input">
            <textarea name="contenido" rows="2" placeholder="Escribe tu respuesta..." required></textarea>
            <button type="submit" class="btn-enviar"><i class="bi bi-send"></i></button>
        </form>

        <div style="text-align:center;">
            <a href="HomeTecnicos.php" class="btn-volver mt-2">Volver</a>
        </div>
    </div>
</body>
</html>
