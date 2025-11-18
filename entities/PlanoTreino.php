<?php

class PlanoTreino implements JsonSerializable
{
    private $idPlano;
    private $usuario_id_usuario;
    private $portifolio_idPortifolio;

    public function jsonSerialize(): mixed
    {
        return [
            'idPlano' => $this->idPlano,
            'usuario_id_usuario' => $this->usuario_id_usuario,
            'portifolio_idPortifolio' => $this->portifolio_idPortifolio,
        ];
    }

    public function __construct($idPlano = "", $usuario_id_usuario = "", $portifolio_idPortifolio = "")
    {
        $this->idPlano = $idPlano;
        $this->usuario_id_usuario = $usuario_id_usuario;
        $this->portifolio_idPortifolio = $portifolio_idPortifolio;
    }

    // Getters / Setters
    public function getIdPlano()
    {
        return $this->idPlano;
    }

    public function setIdPlano($idPlano)
    {
        $this->idPlano = $idPlano;
    }

    public function getUsuario_id_usuario()
    {
        return $this->usuario_id_usuario;
    }

    public function setUsuario_id_usuario($usuario_id_usuario)
    {
        $this->usuario_id_usuario = $usuario_id_usuario;
    }

    public function getPortifolio_idPortifolio()
    {
        return $this->portifolio_idPortifolio;
    }

    public function setPortifolio_idPortifolio($portifolio_idPortifolio)
    {
        $this->portifolio_idPortifolio = $portifolio_idPortifolio;
    }
}

?>
