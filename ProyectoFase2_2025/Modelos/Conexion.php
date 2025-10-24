<?php
class Conexion
{
    private $host = "localhost";
    private $user = "root";
    private $pass = ""; // Contraseña vacía para XAMPP
    private $db   = "proyectofinal2025";
    private $conBD;

    public function __construct()
    {
        $cadenaConexion = "mysql:host=".$this->host.";dbname=".$this->db.";charset=utf8";
        try
        {
            $this->conBD = new PDO($cadenaConexion, $this->user, $this->pass);
            $this->conBD->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            // echo "Conexion Exitosa!!"; // Comentado para no mostrar mensaje en pantalla
        }
        catch(Exception $e)
        {
            $this->conBD = null;
            // Para desarrollo puedes usar: echo "Error: ".$e->getMessage();
            // En producción, lo ideal es registrar el error en un log sin mostrarlo al usuario
        }
    }

    public function getConexion()
    {
        return $this->conBD;
    }
}

// Crear la conexión (opcional, solo si quieres instanciar aquí)
// $Conexion = new Conexion();
?>
