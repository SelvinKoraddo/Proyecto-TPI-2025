<?php
require_once("../Modelos/tecnicoModelo.php");

$tecnicoModelo = new TecnicoModelo();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $correo = $_POST['correo'];
    $telefono = $_POST['telefono'];
    $contrasena = $_POST['contrasena'];
    $zona = $_POST['zona'];
    $tarifa = $_POST['tarifa'];
    $descripcion = $_POST['descripcion'];
    $especialidades = isset($_POST['especialidades']) ? $_POST['especialidades'] : [];

    $resultado = $tecnicoModelo->registrarTecnico($nombre, $correo, $contrasena, $telefono, $tarifa, $zona, $descripcion, $especialidades);

    if ($resultado) {
        header("Location: ../Vistas/Registro_tecnico.php?exito=1");
    } else {
        header("Location: ../Vistas/Registro_tecnico.php?error=1");
    }
    exit;
}
?>
