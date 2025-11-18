<?php
require_once(__DIR__ . '/BaseDAO.php');
require_once(__DIR__ . '/../entities/PlanoTreino.php');

class PlanoTreinoDAO extends BaseDAO
{
    public function inserir(PlanoTreino $plano)
    {
        $sql = "INSERT INTO PlanoTreino (usuario_id_usuario, portifolio_idPortifolio) VALUES (:usuario_id_usuario, :portifolio_idPortifolio)";
        $params = [
            ':usuario_id_usuario' => $plano->getUsuario_id_usuario(),
            ':portifolio_idPortifolio' => $plano->getPortifolio_idPortifolio()
        ];
        $this->executaComParametros($sql, $params);
        return $this->lastInsertId();
    }

    public function selecionarPorUsuario($id_usuario)
    {
        $sql = "SELECT * FROM PlanoTreino WHERE usuario_id_usuario = :usuario_id_usuario";
        $params = [':usuario_id_usuario' => $id_usuario];
        $stmt = $this->executaComParametros($sql, $params);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $out = [];
        foreach ($rows as $r) {
            $out[] = new PlanoTreino($r['idPlano'], $r['usuario_id_usuario'], $r['portifolio_idPortifolio']);
        }
        return $out;
    }

    public function selecionarPorId($idPlano)
    {
        $sql = "SELECT * FROM PlanoTreino WHERE idPlano = :idPlano";
        $params = [':idPlano' => $idPlano];
        $stmt = $this->executaComParametros($sql, $params);
        $r = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($r) {
            return new PlanoTreino($r['idPlano'], $r['usuario_id_usuario'], $r['portifolio_idPortifolio']);
        }
        return null;
    }

    public function excluir($idPlano)
    {
        $sql = "DELETE FROM PlanoTreino WHERE idPlano = :idPlano";
        $params = [':idPlano' => $idPlano];
        $this->executaComParametros($sql, $params);
    }

    public function selecionarTodos()
    {
        $sql = "SELECT * FROM PlanoTreino";
        $stmt = $this->executar($sql);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $out = [];
        foreach ($rows as $r) {
            $out[] = new PlanoTreino($r['idPlano'], $r['usuario_id_usuario'], $r['portifolio_idPortifolio']);
        }
        return $out;
    }
}

?>
