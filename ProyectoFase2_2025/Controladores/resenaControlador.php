<?php
session_start();
require_once("../Modelos/resenaModelo.php");

if (!isset($_SESSION['Id'])) {
    header("Location: ../Vistas/Login.php");
    exit;
}

$resenaModelo = new ResenaModelo();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_usuario = $_SESSION['Id']; 
    $id_tecnico = $_POST['tecnico_id'];
    $id_solicitud = $_POST['id_solicitud'];
    $calificacion = $_POST['calificacion'];
    $comentario = $_POST['comentario'];

    $resultado = $resenaModelo->registrarResena(
        $id_usuario,
        $id_tecnico,
        $id_solicitud,
        $calificacion,
        $comentario
    );

    if ($resultado) {
        header("Location: ../Vistas/resenaTecnico.php?exito=1&id_tecnico=$id_tecnico");
    } else {
        header("Location: ../Vistas/resenaTecnico.php?error=1&id_tecnico=$id_tecnico");
    }
    exit;
}
?>
