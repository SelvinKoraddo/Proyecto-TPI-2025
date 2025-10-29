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
//$id_solicitud = $_GET['id_solicitud'] ?? null;
$id_solicitud = 2; // ← Descomenta esta línea solo para pruebas
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
    <div class="footer">
      <p>@TechFix S.A. de C.V.</p>
    </div>
  </div>

</body>

</html>