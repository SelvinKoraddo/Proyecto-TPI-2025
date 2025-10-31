<?php
require_once "../Modelos/buscarTecnicoModelo.php";

class buscarTecnicosControlador {

   
    public function obtenerTecnicos($especialidad) {
        if (empty($especialidad)) {
            return []; // Retorna arreglo vacio si no hay especialidad
        }

        $modelo = new buscarTecnicoModelo();
        $tecnicos = $modelo->buscarPorEspecialidad($especialidad);

        
        if (!$tecnicos) {
            return [];
        }

        return $tecnicos;
    }
}
?>
