<?php
require_once("../Modelos/resenaModelo.php");

$resenaModelo = new ResenaModelo();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_usuario = 2; //Placeholder
    $id_tecnico = $_POST['tecnico_id'];
    $id_solicitud = 3; //Placeholder
    $calificacion = $_POST['calificacion'];
    $comentario = $_POST['comentario'];

    $resultado = $resenaModelo->registrarResena($id_usuario, $id_tecnico, $id_solicitud, $calificacion, $comentario);

    if ($resultado) {
        header("Location: ../Vistas/resenaTecnico.php?exito=1");
    } else {
        header("Location: ../Vistas/resenaTecnico.php?error=1");
    }
    exit;
}
?>
