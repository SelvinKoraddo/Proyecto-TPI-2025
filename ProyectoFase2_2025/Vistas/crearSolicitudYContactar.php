<?php
session_start();
require_once '../Modelos/solicitudModelo.php';

if (!isset($_SESSION['Id']) || !isset($_GET['id_tecnico'])) {
    echo "<p style='color:red;'>Error: datos faltantes.</p>";
    exit;
}

$id_usuario = $_SESSION['Id'];
$id_tecnico = $_GET['id_tecnico'];

$modelo = new solicitudModelo();
$id_solicitud = $modelo->crearSolicitud(
    $id_usuario,
    $id_tecnico,
    "Contacto directo sin cita",
    "pendiente",
    date("Y-m-d H:i:s"),
    "Sin dirección",
    0.00
);

header("Location: Contactar.php?id_solicitud=$id_solicitud");
exit;
?>
