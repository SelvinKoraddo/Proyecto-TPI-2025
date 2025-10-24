<?php
require_once("../Modelos/Conexion.php");

class ResenaModelo {
    private $db;

    public function __construct() {
        $this->db = (new Conexion())->getConexion();
    }

    public function registrarResena($id_usuario, $id_tecnico, $id_solicitud, $calificacion, $comentario) {
        try {
            $sql = "INSERT INTO resena (id_solicitud, id_usuario, id_tecnico, calificacion, comentario)
                    VALUES (?, ?, ?, ?, ?)";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$id_solicitud, $id_usuario, $id_tecnico, $calificacion, $comentario]);
        } catch (PDOException $e) {
            error_log("Error al registrar reseña: " . $e->getMessage());
            return false;
        }
    }

    public function obtenerResenasPorTecnico($id_tecnico) {
        $sql = "SELECT r.calificacion, r.comentario, r.fecha_creada, u.nombre_completo
                FROM resena r
                JOIN usuarios u ON r.id_usuario = u.id_usuario
                WHERE r.id_tecnico = ?
                ORDER BY r.fecha_creada DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id_tecnico]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
