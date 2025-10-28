<?php
require_once '../Modelos/reservarCitaModelo.php';

class reservarCitaControlador {
    private $modelo;

    public function __construct() {
        $this->modelo = new reservarCitaModelo();
    }

    public function crearCita($id_usuario, $id_tecnico, $fecha_inicio, $fecha_fin, $notas) {
        return $this->modelo->insertarCita($id_usuario, $id_tecnico, $fecha_inicio, $fecha_fin, $notas);
    }

    // Listar citas por usuario
    public function listarCitasPorUsuario($id_usuario) {
        return $this->modelo->listarCitasPorUsuario($id_usuario);
    }
}
?>
