<?php
require_once "../Modelos/buscarTecnicoModelo.php";

class buscarTecnicosControlador {

   
    public function obtenerTecnicos($especialidad) {
        if (empty($especialidad)) {
            return []; // Retorna arreglo vacío si no hay especialidad
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
