<?php
require_once '../Modelos/solicitudModelo.php';

class solicitudControlador {
    private $modelo;

    public function __construct() {
        $this->modelo = new solicitudModelo();
    }

    public function crearSolicitud($id_usuario, $id_tecnico, $descripcion, $estado, $fecha_programacion, $direccion_servicio, $monto) {
        return $this->modelo->crearSolicitud($id_usuario, $id_tecnico, $descripcion, $estado, $fecha_programacion, $direccion_servicio, $monto);
    }

    public function obtenerSolicitud($id_solicitud) {
        return $this->modelo->obtenerSolicitudPorId($id_solicitud);
    }

    public function listarSolicitudes() {
        return $this->modelo->listarSolicitudes();
    }
}
?>
