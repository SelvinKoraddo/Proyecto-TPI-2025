<?php
session_start();
require_once '../Modelos/Conexion.php';
require_once '../Controladores/mensajeControlador.php';

//  Verificamos si el usuario es cliente
if (!isset($_SESSION['Rol']) || $_SESSION['Rol'] !== 'cliente') {
    header("Location: Login.php");
    exit;
}

$id_cliente = $_SESSION['Id'];
$id_solicitud = $_GET['id_solicitud'] ?? null;

$db = (new Conexion())->getConexion();
$mensajeCtrl = new mensajeControlador();


if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['contenido']) && !empty($id_solicitud)) {
    $contenido = trim($_POST['contenido']);
    $mensajeCtrl->enviarMensaje($id_solicitud, $id_cliente, $contenido);
    header("Location: MensajeriaCliente.php?id_solicitud={$id_solicitud}");
    exit;
}

// Si ya hay una solicitud activa, obtenemos esa 
if ($id_solicitud) {
    $stmt = $db->prepare("
        SELECT 
            m.id_mensaje,
            m.contenido,
            m.fecha_creado AS fecha_envio,
            m.id_usuario AS remitente_id,
            u.nombre_completo AS tecnico,
            t.id_tecnico,
            t.id_usuario AS tecnico_user_id,
            c.nombre_completo AS cliente
        FROM mensaje m
        INNER JOIN solicitud s ON m.id_solicitud = s.id_solicitud
        INNER JOIN perfil_tecnico t ON s.id_tecnico = t.id_tecnico
        INNER JOIN usuarios u ON t.id_usuario = u.id_usuario
        INNER JOIN usuarios c ON s.id_usuario = c.id_usuario
        WHERE s.id_usuario = ? AND s.id_solicitud = ?
        ORDER BY m.fecha_creado ASC
    ");
    $stmt->execute([$id_cliente, $id_solicitud]);
    $mensajes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($mensajes)) {
        $stmtT = $db->prepare("
            SELECT u.nombre_completo, t.id_usuario AS tecnico_user_id
            FROM solicitud s
            INNER JOIN perfil_tecnico t ON s.id_tecnico = t.id_tecnico
            INNER JOIN usuarios u ON t.id_usuario = u.id_usuario
            WHERE s.id_solicitud = ?
            LIMIT 1
        ");
        $stmtT->execute([$id_solicitud]);
        $infoT = $stmtT->fetch(PDO::FETCH_ASSOC);
        $nombre_tecnico = $infoT['nombre_completo'] ?? 'Técnico';
        $tecnico_user_id = $infoT['tecnico_user_id'] ?? null;
    } else {
        $nombre_tecnico = $mensajes[0]['tecnico'];
        $tecnico_user_id = $mensajes[0]['tecnico_user_id'];
    }
} else {
   
    $stmt = $db->prepare("
        SELECT s.id_solicitud, u.nombre_completo AS tecnico
        FROM solicitud s
        INNER JOIN perfil_tecnico t ON s.id_tecnico = t.id_tecnico
        INNER JOIN usuarios u ON t.id_usuario = u.id_usuario
        WHERE s.id_usuario = ?
        ORDER BY s.fecha_programacion DESC
    ");
    $stmt->execute([$id_cliente]);
    $solicitudes = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Mensajería Cliente - TechFix</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            font-family: "Segoe UI", Tahoma, Verdana, sans-serif;
            background: linear-gradient(120deg, #9340c7, #1f56a5);
            color: #fff;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .container {
            background: rgba(255,255,255,0.1);
            border-radius: 20px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.2);
            padding: 30px;
            width: 100%;
            max-width: 900px;
            backdrop-filter: blur(6px);
        }

        h2 {
            text-align: center;
            margin-bottom: 25px;
            font-weight: 600;
            color: #fff;
        }

        .chat-box {
            max-height: 450px;
            overflow-y: auto;
            background: rgba(255,255,255,0.1);
            border-radius: 15px;
            padding: 15px;
            margin-bottom: 20px;
        }

        .msg {
            display: flex;
            margin-bottom: 12px;
            max-width: 85%;
        }

        .msg.enviado {
            justify-content: flex-end;
        }

        .msg.enviado .burbuja {
            background: linear-gradient(45deg, #3659f3, #764ba2);
            color: #fff;
            border-radius: 15px 15px 0 15px;
            padding: 10px 14px;
        }

        .msg.recibido {
            justify-content: flex-start;
        }

        .msg.recibido .burbuja {
            background: rgba(255,255,255,0.2);
            color: #fff;
            border-radius: 15px 15px 15px 0;
            padding: 10px 14px;
        }

        .meta {
            font-size: 0.78rem;
            opacity: 0.8;
            margin-top: 4px;
            text-align: right;
        }

        .compositor {
            display: flex;
            gap: 10px;
        }

        textarea {
            flex: 1;
            resize: none;
            border-radius: 12px;
            padding: 10px;
            border: none;
            outline: none;
            font-size: 1rem;
            color: #333;
        }

        textarea:focus {
            box-shadow: 0 0 10px rgba(118, 75, 162, 0.8);
        }

        .btn-enviar {
            background: linear-gradient(45deg, #3659f3, #764ba2);
            color: #fff;
            border: none;
            border-radius: 12px;
            padding: 10px 20px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-enviar:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        }

        .btn-volver, .btn-inicio {
            border: none;
            border-radius: 25px;
            color: #fff;
            padding: 10px 25px;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.3s ease;
            display: inline-block;
            margin: 5px;
        }

        .btn-volver {
            background: #ff4d4d;
        }

        .btn-inicio {
            background: #00c853;
        }

        .btn-volver:hover, .btn-inicio:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        }

        .tabla {
            color: #fff;
        }

        .tabla thead {
            background: rgba(255,255,255,0.2);
        }

        .tabla tbody tr:hover {
            background: rgba(255,255,255,0.1);
        }

        .btn-vermensajes {
            background: linear-gradient(45deg, #3659f3, #764ba2);
            color: #fff !important;
            border: none;
            border-radius: 12px;
            padding: 6px 16px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-vermensajes:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        }
    </style>

    <script>
        setInterval(() => {
            if (window.location.href.includes("id_solicitud")) {
                fetch(window.location.href)
                    .then(r => r.text())
                    .then(html => {
                        const doc = new DOMParser().parseFromString(html, "text/html");
                        const newBox = doc.querySelector(".chat-box");
                        document.querySelector(".chat-box").innerHTML = newBox.innerHTML;
                    });
            }
        }, 5000);
    </script>
</head>
<body>
<div class="container">
    <?php if (!$id_solicitud): ?>
        <h2><i class="bi bi-chat-dots-fill"></i> Conversaciones con Técnicos</h2>
        <?php if (empty($solicitudes)): ?>
            <div class="alert alert-warning text-dark text-center">No tienes solicitudes aún.</div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover align-middle text-light tabla">
                    <thead>
                        <tr><th>ID Solicitud</th><th>Técnico</th><th>Acción</th></tr>
                    </thead>
                    <tbody>
                        <?php foreach ($solicitudes as $s): ?>
                            <tr>
                                <td><?= htmlspecialchars($s['id_solicitud']) ?></td>
                                <td><?= htmlspecialchars($s['tecnico']) ?></td>
                                <td>
                                    <a href="MensajeriaCliente.php?id_solicitud=<?= urlencode($s['id_solicitud']) ?>" class="btn-vermensajes">
                                        <i class="bi bi-chat-dots"></i> Ver mensajes
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>

        <div class="text-center mt-3">
            <a href="/Proyecto-TPI-2025/ProyectoFase2_2025/Vistas/Home.php" class="btn-inicio">
                <i class="bi bi-house-door-fill"></i> Volver al inicio
            </a>
        </div>

    <?php else: ?>
        <h2><i class="bi bi-chat-fill"></i> Chat con <?= htmlspecialchars($nombre_tecnico) ?></h2>

        <div class="chat-box mb-3">
            <?php if (empty($mensajes)): ?>
                <p class="text-center small text-light opacity-75">Aún no hay mensajes en esta conversación.</p>
            <?php else: ?>
                <?php foreach ($mensajes as $msg): ?>
                    <?php
                        $esPropio = ($msg['remitente_id'] == $_SESSION['Id']);
                        $clase = $esPropio ? 'enviado' : 'recibido';
                        $etiqueta = $esPropio ? 'Tú' : htmlspecialchars($msg['tecnico']);
                    ?>
                    <div class="msg <?= $clase ?>">
                        <div class="burbuja">
                            <strong><?= $etiqueta ?></strong><br>
                            <?= nl2br(htmlspecialchars($msg['contenido'])) ?>
                            <div class="meta"><?= htmlspecialchars($msg['fecha_envio']) ?></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <form method="post" class="compositor">
            <textarea name="contenido" rows="3" placeholder="Escribe tu mensaje..." required></textarea>
            <button type="submit" class="btn-enviar"><i class="bi bi-send-fill"></i>Enviar</button>
        </form>

        <div class="text-center mt-3">
            <a href="MensajeriaCliente.php" class="btn-volver">
                <i class="bi bi-arrow-left-circle"></i> Volver a conversaciones
            </a>
            <a href="/Proyecto-TPI-2025/ProyectoFase2_2025/Vistas/Home.php" class="btn-inicio">
                <i class="bi bi-house-door-fill"></i> Volver al inicio
            </a>
        </div>
    <?php endif; ?>
</div>
</body>
</html>
