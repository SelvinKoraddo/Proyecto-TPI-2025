<?php
require_once 'Conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_POST['accion'] ?? '';
    $id = $_POST['id'] ?? 0;

    if (!$id) exit('Error: ID inválido.');

    $db = (new Conexion())->getConexion();

    if ($accion === 'aprobado' || $accion === 'rechazado') {
        $stmt = $db->prepare("UPDATE solicitud SET estado = :estado WHERE id_solicitud = :id");
        $stmt->execute(['estado' => $accion, 'id' => $id]);
        echo "Solicitud actualizada a estado '$accion'";
    } else {
        echo "Acción no reconocida.";
    }
}
?>
