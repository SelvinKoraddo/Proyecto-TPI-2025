<?php
require_once 'Conexion.php';

class Pago extends Conexion 
{
    private $conexion;

     public function __construct()
    {
        $this->conexion=new Conexion();
        $this->conexion=$this->conexion->getConexion();
    }

    public function registrarPago($id_solicitud, $id_usuario, $id_tecnico, $paypal_id_orden, $estado, $monto)
    {
        try {
            $sql = "INSERT INTO pago (id_solicitud, id_usuario, id_tecnico, paypal_id_orden, estado, monto, fecha_pago)
                    VALUES (?, ?, ?, ?, ?, ?, NOW())";
    
            $stmt = $this->conexion->prepare($sql);
            $resultado = $stmt->execute([$id_solicitud, $id_usuario, $id_tecnico, $paypal_id_orden, $estado, $monto]);
    
            if ($resultado) {
                return ['success' => true, 'id_pago' => $this->conexion->lastInsertId()];
            } else {
                return ['success' => false];
            }
        } catch (Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
}
?>