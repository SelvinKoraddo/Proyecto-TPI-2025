<?php
session_start();
require_once "../Modelos/Conexion.php";
$conexion = new Conexion();

// Validar sesión
$id_usuario = $_SESSION['id_usuario'] ?? null;
if (!$id_usuario) {
    echo json_encode(["success" => false, "error" => "Debes iniciar sesión."]);
    exit;
}

// Validar POST
$id_tecnico = $_POST['id_tecnico'] ?? null;
$mensaje = trim($_POST['mensaje'] ?? '');

if (!$id_tecnico || !$mensaje) {
    echo json_encode(["success" => false, "error" => "Datos incompletos."]);
    exit;
}

// Buscar solicitud entre usuario y técnico
$stmt = $conexion->getConexion()->prepare("
    SELECT id_solicitud FROM solicitud 
    WHERE id_usuario = :id_usuario AND id_tecnico = :id_tecnico 
    LIMIT 1
");
$stmt->execute([
    ":id_usuario" => $id_usuario,
    ":id_tecnico" => $id_tecnico
]);
$solicitud = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$solicitud) {
    echo json_encode(["success" => false, "error" => "No se encontró ninguna solicitud asociada."]);
    exit;
}

$id_solicitud = $solicitud['id_solicitud'];

try {
    $insert = $conexion->getConexion()->prepare("
        INSERT INTO mensaje (id_solicitud, id_usuario, contenido, fecha_creado)
        VALUES (:id_solicitud, :id_usuario, :contenido, NOW())
    ");
    $insert->execute([
        ":id_solicitud" => $id_solicitud,
        ":id_usuario" => $id_usuario,
        ":contenido" => $mensaje
    ]);

    echo json_encode(["success" => true]);
} catch (PDOException $e) {
    echo json_encode(["success" => false, "error" => $e->getMessage()]);
}
?>
