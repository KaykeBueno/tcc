<?php

class Portifolio implements JsonSerializable
{
    private $idPortifolio;
    private $empresa_id_empresa;
    private $treino_idTreino;
    private $descricao;

    public function jsonSerialize(): mixed
    {
        return [
            'idPortifolio' => $this->idPortifolio,
            'empresa_id_empresa' => $this->empresa_id_empresa,
            'treino_idTreino' => $this->treino_idTreino,
            'descricao' => $this->descricao,
        ];
    }
    function __construct($idPortifolio = "", $empresa_id_empresa = "", $treino_idTreino = "", $descricao = "")
    {
        $this->idPortifolio = $idPortifolio;
        $this->empresa_id_empresa = $empresa_id_empresa;
        $this->treino_idTreino = $treino_idTreino;
        $this->descricao = $descricao;
    }

    // Getters / Setters
    public function getIdPortifolio()
    {
        return $this->idPortifolio;
    }

    public function setIdPortifolio($idPortifolio)
    {
        $this->idPortifolio = $idPortifolio;
    }

    public function getEmpresa_id_empresa()
    {
        return $this->empresa_id_empresa;
    }

    public function setEmpresa_id_empresa($empresa_id_empresa)
    {
        $this->empresa_id_empresa = $empresa_id_empresa;
    }

    public function getTreino_idTreino()
    {
        return $this->treino_idTreino;
    }

    public function setTreino_idTreino($treino_idTreino)
    {
        $this->treino_idTreino = $treino_idTreino;
    }

    public function getDescricao()
    {
        return $this->descricao;
    }

    public function setDescricao($descricao)
    {
        $this->descricao = $descricao;
    }
}
?>