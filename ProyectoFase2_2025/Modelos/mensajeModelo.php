<?php
require_once 'Conexion.php';

class mensajeModelo {
    private $db;

    public function __construct() {
        $conexion = new Conexion();
        $this->db = $conexion->getConexion();
    }

    public function obtenerMensajes($id_solicitud) {
        $sql = "SELECT * FROM mensaje WHERE id_solicitud = :id_solicitud ORDER BY fecha_creado ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id_solicitud' => $id_solicitud]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function crearMensaje($id_solicitud, $id_usuario, $contenido) {
        $sql = "INSERT INTO mensaje (id_solicitud, id_usuario, contenido, fecha_creado)
                VALUES (:id_solicitud, :id_usuario, :contenido, NOW())";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':id_solicitud' => $id_solicitud,
            ':id_usuario' => $id_usuario,
            ':contenido' => $contenido
        ]);
        return true;
    }
}
?>
