<?php
session_start();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pago Confirmado - TechFix</title>
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

        .confirm-card {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
            backdrop-filter: blur(6px);
            text-align: center;
            padding: 40px 30px;
            max-width: 500px;
            width: 100%;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .confirm-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.3);
        }

        .confirm-card h1 {
            color: #00ff9d;
            font-size: 1.8rem;
            margin-bottom: 15px;
            text-shadow: 0 0 6px rgba(0, 0, 0, 0.3);
        }

        .confirm-card p {
            font-size: 1.1rem;
            color: #f1f1f1;
            margin-bottom: 30px;
        }

        .btn-home {
            background: linear-gradient(45deg, #3659f3, #764ba2);
            color: #fff;
            border: none;
            border-radius: 25px;
            padding: 10px 25px;
            font-weight: 500;
            margin: 10px 8px;
            display: inline-block;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .btn-home:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        }

        .icon-check {
            font-size: 3rem;
            color: #00ff9d;
            margin-bottom: 15px;
            text-shadow: 0 0 10px rgba(0, 255, 157, 0.6);
        }
    </style>
</head>
<body>
    <div class="confirm-card">
        <div class="icon-check">
            <i class="bi bi-check-circle-fill"></i>
        </div>
        <h1>¡Pago realizado con éxito!</h1>
        <p>Tu pago ha sido completado, puedes ver mas detalles en el historial de pagos.</p>
        <a href="Home.php" class="btn-home"><i class="bi bi-house"></i> Volver al inicio</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
