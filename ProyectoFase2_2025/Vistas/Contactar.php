<?php
session_start();
require_once '../Controladores/mensajeControlador.php';

$id_usuario = $_SESSION['Id'] ?? null;
$id_solicitud = $_GET['id_solicitud'] ?? null;

if (!$id_usuario || !$id_solicitud) {
    echo "<p style='color:red;'>Error: faltan datos.</p>";
    exit;
}

$controlador = new mensajeControlador();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['contenido'])) {
    $contenido = trim($_POST['contenido']);
    $controlador->enviarMensaje($id_solicitud, $id_usuario, $contenido);

    
    echo "<!DOCTYPE html>
    <html lang='es'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>Mensaje Enviado</title>
        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    </head>
    <body style='background:#1f56a5; display:flex; align-items:center; justify-content:center; height:100vh;'>
        <script>
            Swal.fire({
                title: '✅ Mensaje enviado correctamente',
                text: 'Tu mensaje ha sido enviado con éxito.',
                icon: 'success',
                confirmButtonText: 'Aceptar',
                confirmButtonColor: '#3659f3',
                background: '#fff',
                color: '#333'
            }).then(() => {
                window.location.href = 'http://localhost/Proyecto-TPI-2025/ProyectoFase2_2025/Vistas/Home.php';
            });
        </script>
    </body>
    </html>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contactar Técnico - TechFix</title>
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

        .contact-container {
            background: rgba(255,255,255,0.1);
            border-radius: 20px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.2);
            padding: 40px 30px;
            width: 100%;
            max-width: 500px;
            text-align: center;
            backdrop-filter: blur(6px);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .contact-container:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 30px rgba(0,0,0,0.3);
        }

        h2 {
            font-size: 1.8rem;
            margin-bottom: 20px;
        }

        textarea {
            width: 100%;
            border-radius: 10px;
            border: none;
            outline: none;
            padding: 12px;
            resize: none;
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
            border-radius: 25px;
            padding: 10px 25px;
            margin-top: 15px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-enviar:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        }

        .btn-volver, .btn-mensajes {
            border: none;
            border-radius: 25px;
            color: #fff;
            padding: 10px 25px;
            font-weight: 500;
            margin-top: 15px;
            display: inline-block;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .btn-volver {
            background: #ff4d4d;
        }

        .btn-mensajes {
            background: #00c853;
            margin-left: 10px;
        }

        .btn-volver:hover, .btn-mensajes:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        }
    </style>
</head>
<body>
    <div class="contact-container">
        <h2><i class="bi bi-chat-dots-fill"></i> Contactar Técnico</h2>
        <form method="post">
            <textarea name="contenido" rows="4" placeholder="Escribe tu mensaje..." required></textarea><br>
            <button type="submit" class="btn-enviar">Enviar</button>
        </form>

        <div class="mt-3">
            <a href="javascript:history.back()" class="btn-volver">Volver</a>
            <a href="http://localhost/Proyecto-TPI-2025/ProyectoFase2_2025/Vistas/MensajeriaCliente.php" class="btn-mensajes">
                <i class="bi bi-chat-left-text"></i> Ver mensajes
            </a>
        </div>
    </div>
</body>
</html>
