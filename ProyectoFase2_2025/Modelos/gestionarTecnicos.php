<?php
require_once 'Conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_POST['accion'] ?? '';
    $id = $_POST['id'] ?? 0;

    if (!$id) exit('Error: ID inválido.');

    $db = (new Conexion())->getConexion();

    $stmt = $db->prepare("UPDATE perfil_tecnico SET estado = :estado WHERE id_tecnico = :id");
    $stmt->execute(['estado' => $accion, 'id' => $id]);

    echo "Estado actualizado correctamente.";
}
?>
