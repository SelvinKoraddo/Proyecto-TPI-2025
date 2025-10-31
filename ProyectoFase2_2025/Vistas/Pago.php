<?php
session_start();
require_once '../Modelos/Conexion.php';
require_once '../Modelos/PagosModel.php';

// Verificar usuario logueado
if (!isset($_SESSION['Id'])) {
  header('Location: ../Vistas/Home.php');
  exit;
}

$id_usuario = $_SESSION['Id']; //user logeueado

$id_solicitud = $_GET['id_solicitud'] ?? null;

if (!$id_solicitud) {
  die("ID de solicitud no proporcionado.");
}
// Buscar monto de la solicitud 
try {
  $db = (new Conexion())->getConexion();
  $stmt = $db->prepare(query: "
    SELECT s.id_solicitud, s.id_tecnico, s.monto
    FROM solicitud s
    WHERE s.id_solicitud = ? AND s.id_usuario = ?");

  $stmt->execute([$id_solicitud, $id_usuario]);
  $result = $stmt->fetch(PDO::FETCH_ASSOC);

  if (!$result) {
    die("Solicitud no encontrada o no pertenece al usuario.");
  }

  $id_tecnico = $result['id_tecnico'];
  $monto = number_format($result['monto'], 2, '.', '');

} catch (Exception $e) {
  die("Error al obtener datos: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pago de Solicitud #<?php echo $id_solicitud; ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

  <script>
    const PAGO_DATA = {
      id_solicitud: <?php echo (int) $id_solicitud; ?>,
      id_usuario: <?php echo (int) $id_usuario; ?>,
      id_tecnico: <?php echo (int) $id_tecnico; ?>,
      monto: "<?php echo $monto; ?>"
    };
  </script>

  <!--API KEY de paypal sandbox-->
  <script
    src="https://www.sandbox.paypal.com/sdk/js?client-id=AcnvAuRUHCSfyGklwfBfZ7awhGfT96MVCIdYMvpJ4tOwvVMlu770IzLz0beuIt80-0OpyoXHW9Xd-i9d&currency=USD&components=buttons&intent=capture&debug=true"></script>
  <script src="../Vistas/script/scriptPago.js" defer></script>
  <link rel="stylesheet" href="../Vistas/css/estilosPago.css">

</head>

<body>
  <div class="card-container">
    <div class="header">
      <h1>Pago de servicio</h1>
      <p>Monto a pagar: <strong>$<?php echo $monto; ?></strong></p>
    </div>
    <div class="content-box">
      <div id="paypal-button-container"></div>
    </div>
        <a href="ListaCitas.php" class="btn-volver"><i class="bi bi-arrow-left"></i> Volver</a>             

    <div class="footer">
      <p>@TechFix S.A. de C.V.</p>
    </div>
  </div>
   <style>
    * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Arial, sans-serif;
            background-color:  #c5d4e0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .card-container {
            width: 100%;
            max-width: 900px;
            background: #003087;
            border-radius: 24px;
            padding: 40px 30px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .header h1 {
            color: white;
            font-size: 28px;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .header p {
            color: rgba(255, 255, 255, 0.8);
            font-size: 14px;
            font-weight: 300;
        }

        .content-box {
            background: white;
            border-radius: 16px;
            padding: 60px 40px;
            text-align: center;
            margin-bottom: 30px;
        }

        .content-box h2 {
            color: #2c2e2f;
            font-size: 24px;
            font-weight: 600;
        }

        .footer {
            text-align: center;
        }

        .footer p {
            color: rgba(255, 255, 255, 0.7);
            font-size: 13px;
            font-weight: 300;
        }
        .btn-volver {
            background: #ff4d4d;
            color: #fff;
            border: none;
            border-radius: 25px;
            padding: 10px 25px;
            font-weight: 500;
           text-align: center;
        }

        .btn-volver:hover {
            background: #006ab1;
        }
   </style>
</body>

</html>