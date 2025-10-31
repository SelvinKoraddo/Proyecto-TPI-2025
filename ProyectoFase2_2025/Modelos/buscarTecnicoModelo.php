<?php
require_once __DIR__ . '/conexion.php';

class buscarTecnicoModelo {
    private $db;

    public function __construct() {
        global $Conexion;
        $this->db = $Conexion->getConexion();
    }

    public function buscarPorEspecialidad($especialidad) {
        if (empty($especialidad)) {
            return []; // Retorna el arreglo vacio si no hay especialidad
        }

        $sql = "SELECT 
            pt.id_tecnico,
            u.nombre_completo,
            u.telefono,
            e.nombre AS especialidad,
            pt.zona_trabajo,
            pt.tarifa_hora
        FROM perfil_tecnico pt
        INNER JOIN usuarios u ON pt.id_usuario = u.id_usuario
        INNER JOIN tecnico_especialidad te ON pt.id_tecnico = te.id_tecnico
        INNER JOIN especialidad e ON te.id_especialidad = e.id_especialidad
        WHERE LOWER(TRIM(e.nombre)) = LOWER(TRIM(:especialidad))
          AND pt.estado = 'aprobado'
        ORDER BY pt.tarifa_hora ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':especialidad', $especialidad, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);

    }
}
?>
