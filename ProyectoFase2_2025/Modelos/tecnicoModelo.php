<?php
require_once("../Modelos/Conexion.php");

class TecnicoModelo {
    private $conexion;

    public function __construct() {
        $this->conexion = new Conexion();
        $this->db = $this->conexion->getConexion();
    }

    public function registrarTecnico($nombre, $correo, $contrasena, $telefono, $tarifa, $zona, $descripcion, $especialidades) {
        try {
            $this->db->beginTransaction();

            $sqlUser = "INSERT INTO usuarios (nombre_completo, contrasena_hash, rol, telefono, correo)
                        VALUES (?, ?, 'tecnico', ?, ?)";
            $stmtUser = $this->db->prepare($sqlUser);
            $hash = password_hash($contrasena, PASSWORD_BCRYPT);
            $stmtUser->execute([$nombre, $hash, $telefono, $correo]);
            $id_usuario = $this->db->lastInsertId();

            $sqlPerfil = "INSERT INTO perfil_tecnico (id_usuario, descripcion, tarifa_hora, zona_trabajo)
                          VALUES (?, ?, ?, ?)";
            $stmtPerfil = $this->db->prepare($sqlPerfil);
            $stmtPerfil->execute([$id_usuario, $descripcion, $tarifa, $zona]);
            $id_tecnico = $this->db->lastInsertId();

            if (!empty($especialidades)) {
                $sqlEsp = "INSERT INTO tecnico_especialidad (id_tecnico, id_especialidad) VALUES (?, ?)";
                $stmtEsp = $this->db->prepare($sqlEsp);
                foreach ($especialidades as $id_especialidad) {
                    $stmtEsp->execute([$id_tecnico, $id_especialidad]);
                }
            }

            $this->db->commit();
            return true;

        } catch (Exception $e) {
            $this->db->rollBack();
            error_log("Error al registrar técnico: " . $e->getMessage());
            return false;
        }
    }

    public function buscarEspecialidades() {
        $sql = "SELECT id_especialidad, nombre FROM especialidad ORDER BY nombre ASC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerTecnicos() {
        $sql = "SELECT t.id_tecnico, u.nombre_completo, t.zona_trabajo, t.tarifa_hora, t.estado
                FROM perfil_tecnico t
                JOIN usuarios u ON t.id_usuario = u.id_usuario";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
