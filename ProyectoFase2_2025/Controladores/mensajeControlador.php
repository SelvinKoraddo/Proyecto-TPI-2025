<?php
require_once '../Modelos/mensajeModelo.php';

class mensajeControlador {
    private $modelo;

    public function __construct() {
        $this->modelo = new mensajeModelo();
    }

    public function listarMensajesPorSolicitud($id_solicitud) {
        return $this->modelo->obtenerMensajes($id_solicitud);
    }

    public function enviarMensaje($id_solicitud, $id_usuario, $contenido) {
        return $this->modelo->crearMensaje($id_solicitud, $id_usuario, $contenido);
    }
}
?>
