<?php
require_once '../Modelos/reservarCitaModelo.php';

class reservarCitaControlador
{
    private $modelo;

    public function __construct()
    {
        $this->modelo = new reservarCitaModelo();
    }

    public function crearCita($id_usuario, $id_tecnico, $fecha_inicio, $fecha_fin, $notas, $direccion_servicio)
    {
        return $this->modelo->insertarCita($id_usuario, $id_tecnico, $fecha_inicio, $fecha_fin, $notas, $direccion_servicio);
    }


    public function listarCitasPorUsuario($id_usuario)
    {
        return $this->modelo->listarCitasPorUsuario($id_usuario);
    }

    public function listarCitasPorTecnico($id_tecnico)
    {
        return $this->modelo->listarCitasPorTecnico($id_tecnico);
    }


    public function actualizarEstado($id_cita, $estado)
    {
        return $this->modelo->actualizarEstadoCita($id_cita, $estado);
    }

    public function finalizarCita($id_cita, $horas, $tarifa)
    {
        return $this->modelo->finalizarCita($id_cita, $horas, $tarifa);
    }

}
?>