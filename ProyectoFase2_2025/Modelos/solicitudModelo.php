<?php
require_once 'Conexion.php';

class solicitudModelo {
    private $db;

    public function __construct() {
        $conexion = new Conexion();
        $this->db = $conexion->getConexion();
    }

    public function crearSolicitud($id_usuario, $id_tecnico, $descripcion, $estado, $fecha_programacion, $direccion_servicio, $monto) {
        $sql = "INSERT INTO solicitud (id_usuario, id_tecnico, descripcion, estado, fecha_programacion, direccion_servicio, monto)
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id_usuario, $id_tecnico, $descripcion, $estado, $fecha_programacion, $direccion_servicio, $monto]);
        return $this->db->lastInsertId();
    }

    public function obtenerSolicitudPorId($id_solicitud) {
        $sql = "SELECT * FROM solicitud WHERE id_solicitud = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id_solicitud]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function listarSolicitudes() {
        $sql = "SELECT * FROM solicitud ORDER BY fecha_programacion DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
