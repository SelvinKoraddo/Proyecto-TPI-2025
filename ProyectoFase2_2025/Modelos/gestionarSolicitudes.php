<?php
require_once 'Conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_POST['accion'] ?? '';
    $id = $_POST['id'] ?? 0;

    $db = (new Conexion())->getConexion();

    if ($accion === 'aprobar') {
        $stmt = $db->prepare("UPDATE usuarios SET estado = 'activo' WHERE id_usuario = ?");
        $stmt->execute([$id]);
        echo "Aprobado";
    } elseif ($accion === 'rechazar') {
        $stmt = $db->prepare("DELETE FROM usuarios WHERE id_usuario = ?");
        $stmt->execute([$id]);
        echo "Rechazado";
    }
}
?>
