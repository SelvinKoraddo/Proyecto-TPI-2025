
<?php
require_once '../Modelos/PagosModel.php';
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['Id'])) {
    echo json_encode(['success' => false, 'error' => 'Sesión no válida']);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true) ?? [];

$paypal_id_orden = $data['paypal_id_orden'] ?? null;
$id_solicitud    = $data['id_solicitud']    ?? null;
$id_tecnico      = $data['id_tecnico']      ?? null;
$estado = ($data['estado'] === 'COMPLETED') ? 'completed' : 'pending';
$monto           = $data['monto']           ?? 0.00;

// Identidad SIEMPRE desde la sesión
$id_usuario = $_SESSION['Id'];

// Validaciones básicas
if (!$paypal_id_orden) {
    echo json_encode(['success' => false, 'error' => 'Falta paypal_id_orden']);
    exit;
}

// TODO: aquí valida que la solicitud/tecnico correspondan al usuario o estén autorizados
// TODO: opcionalmente recalcula $monto desde el servidor (precio del servicio) para evitar manipulación
// TODO: verifica la orden en PayPal por servidor (capture/details) y compara estado/monto/moneda

$pago = new Pago();
$resultado = $pago->registrarPago($id_solicitud, $id_usuario, $id_tecnico,$paypal_id_orden, $estado, $monto);
                                  

echo json_encode($resultado);
