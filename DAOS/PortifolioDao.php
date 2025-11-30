<?php

// Usa caminhos absolutos relativos ao arquivo atual para evitar problemas
// quando o DAO for incluído a partir de diferentes diretórios.
require_once(__DIR__ . '/BaseDAO.php');
require_once(__DIR__ . '/../entities/Portifolio.php');

/**
 * DAO para a entidade Portifolio (tabela `portifolio`).
 */
class PortifolioDAO extends BaseDAO
{
    public function inserir(Portifolio $portifolio)
    {
        $sql = "INSERT INTO portifolio (empresa_id_empresa, treino_idTreino, descricao, metodologia, dificuldade)
                VALUES (:empresa_id_empresa, :treino_idTreino, :descricao, :metodologia, :dificuldade)";

        $parametros = array(
            ":empresa_id_empresa" => $portifolio->getEmpresa_id_empresa(),
            ":treino_idTreino" => $portifolio->getTreino_idTreino(),
            ":descricao" => $portifolio->getDescricao(),
            ":metodologia" => $portifolio->getMetodologia(),
            ":dificuldade" => $portifolio->getDificuldade(),
        );

        $this->executaComParametros($sql, $parametros);

        // Retorna o id inserido para feedback
        return $this->lastInsertId();
    }

    public function selecionarPorId($idPortifolio)
    {
        $sql = "SELECT * FROM portifolio WHERE idPortifolio = :idPortifolio";
        $parametros = array(":idPortifolio" => $idPortifolio);
        $stmt = $this->executaComParametros($sql, $parametros);
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($resultado) {
            return new Portifolio(
                $resultado['idPortifolio'],
                $resultado['empresa_id_empresa'],
                $resultado['treino_idTreino'],
                $resultado['descricao'] ?? '',
                $resultado['metodologia'] ?? '',
                $resultado['dificuldade'] ?? ''
            );
        }

        return null;
    }

    public function selecionarPorEmpresa($empresa_id)
    {
        $sql = "SELECT * FROM portifolio WHERE empresa_id_empresa = :empresa_id_empresa";
        $parametros = array(":empresa_id_empresa" => $empresa_id);
        $stmt = $this->executaComParametros($sql, $parametros);
        $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $lista = [];
        foreach ($resultados as $r) {
            $lista[] = new Portifolio(
                $r['idPortifolio'],
                $r['empresa_id_empresa'],
                $r['treino_idTreino'],
                $r['descricao'] ?? '',
                $r['metodologia'] ?? '',
                $r['dificuldade'] ?? ''
            );
        }

        return $lista;
    }

    public function selecionarTodos()
    {
        $sql = "SELECT * FROM portifolio";
        $stmt = $this->executar($sql);
        $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $lista = [];
        foreach ($resultados as $r) {
            $lista[] = new Portifolio(
                $r['idPortifolio'],
                $r['empresa_id_empresa'],
                $r['treino_idTreino'],
                $r['descricao'] ?? '',
                $r['metodologia'] ?? '',
                $r['dificuldade'] ?? ''
            );
        }

        return $lista;
    }

}

?>