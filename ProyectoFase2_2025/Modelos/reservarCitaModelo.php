<?php
require_once "Conexion.php";

class reservarCitaModelo {
    private $db;

    public function __construct() {
        $conexion = new Conexion();
        $this->db = $conexion->getConexion();
    }

    // Crear solicitud y devolver su ID
    public function crearSolicitud($id_usuario, $id_tecnico, $descripcion, $estado, $fecha_programacion, $direccion_servicio, $monto) {
        $sql = "INSERT INTO solicitud (id_usuario, id_tecnico, descripcion, estado, fecha_programacion, direccion_servicio, monto) 
                VALUES (:id_usuario, :id_tecnico, :descripcion, :estado, :fecha_programacion, :direccion_servicio, :monto)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':id_usuario' => $id_usuario,
            ':id_tecnico' => $id_tecnico,
            ':descripcion' => $descripcion,
            ':estado' => $estado,
            ':fecha_programacion' => $fecha_programacion,
            ':direccion_servicio' => $direccion_servicio,
            ':monto' => $monto
        ]);
        return $this->db->lastInsertId();
    }

    // para insertar cita usando la solicitud recién creada
    public function insertarCita($id_usuario, $id_tecnico, $fecha_inicio, $fecha_fin, $notas = "") {
        if (strtotime($fecha_inicio) >= strtotime($fecha_fin)) {
            return "La fecha de inicio debe ser anterior a la fecha de fin.";
        }

        $id_solicitud = $this->crearSolicitud(
            $id_usuario,
            $id_tecnico,
            $notas ?: "Solicitud generada automáticamente",
            "pendiente",
            $fecha_inicio,
            "Dirección pendiente",
            0.00
        );

        $sql = "INSERT INTO cita (id_solicitud, fecha_inicio, fecha_fin, estado, notas)
                VALUES (:id_solicitud, :fecha_inicio, :fecha_fin, 'pendiente', :notas)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':id_solicitud' => $id_solicitud,
            ':fecha_inicio' => $fecha_inicio,
            ':fecha_fin' => $fecha_fin,
            ':notas' => $notas
        ]);

        return true;
    }

    // para todas las citas
    public function listarCitas() {
        $sql = "SELECT * FROM cita ORDER BY fecha_inicio DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // citas filtradas por usuario
    public function listarCitasPorUsuario($id_usuario) {
        $sql = "SELECT c.* FROM cita c
                INNER JOIN solicitud s ON c.id_solicitud = s.id_solicitud
                WHERE s.id_usuario = :id_usuario
                ORDER BY c.fecha_inicio DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id_usuario' => $id_usuario]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
