<?php

// Usa caminhos absolutos relativos ao arquivo atual para evitar problemas
// quando o DAO for incluído a partir de diferentes diretórios.
require_once(__DIR__ . '/BaseDAO.php');
require_once(__DIR__ . '/../entities/Empresa.php');

/**
 * DAO para operações relacionadas à entidade Empresa.
 * Extende BaseDAO que contém a conexão PDO e métodos utilitários.
 *
 * Observações:
 * - Os métodos retornam instâncias de Empresa ou arrays quando apropriado.
 * - Use os métodos executaComParametros/ executar do BaseDAO para preparar/execução.
 */

class EmpresaDAO extends BaseDAO
{

    public function inserir(Empresa $empresa)
    {

        $sql = "INSERT INTO Empresa (cnpj, nomeFantasia, telefone, atividadeEconomica, email, senha, porteEmpresarial)
            VALUES (:cnpj, :nomeFantasia, :telefone, :atividadeEconomica, :email, :senha, :porteEmpresarial)";
        

        $parametros = array(
            ":cnpj"=> $empresa->getCnpj(),
            ":nomeFantasia" => $empresa->getNomeFantasia(),
            ":telefone" => $empresa ->getTelefone(),
            ":atividadeEconomica" => $empresa->getAtividadeEconomica(),
            ":email" => $empresa->getEmail(),
            ":senha" => $empresa->getSenha(), 
            ":porteEmpresarial" => $empresa->getPorteEmpresarial(),
        );


        $this->executaComParametros($sql, $parametros);
    }
    

    public function selecionarPorCnpj($empresa)
    {

        $sql = "SELECT * FROM empresa WHERE cnpj = :cnpj";


        $parametros = array(
            ":cnpj" => $empresa->getCnpj()
        );
        

        $stmt = $this->executaComParametros($sql, $parametros);
        

        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);


        if ($resultado) {

            $cadastrada = new Empresa(
                $resultado['id_empresa'],
                $resultado['cnpj'],
                $resultado['nomeFantasia'],
                $resultado['telefone'],
                $resultado['email'],
                $resultado['senha'],
                $resultado['atividadeEconomica'],
                $resultado['porteEmpresarial']
            );

            return $cadastrada;
        } else {

            return null;
        }
    }

    public function autenticar($cnpj, $senha)
    {
        $sql = "SELECT id_empresa, nomeFantasia, cnpj
                FROM empresa
                WHERE cnpj = :cnpj AND senha = :senha";

        $parametros = array(
            ":cnpj" => $cnpj,
            ":senha" => $senha
        );

        $stmt = $this->executaComParametros($sql, $parametros);
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);


        if ($resultado) {
            $cadastrado = new Empresa(
                $resultado['id_empresa'],
                $resultado['cnpj'],
                $resultado['nomeFantasia']
            );
            return $cadastrado;
        } else {
            return null;
        }
    }

    public function selecionarTodos(){
        // Seleciona todas as empresas do banco e retorna um array de objetos Empresa.
        // Usamos PDO::FETCH_ASSOC para obter os resultados como arrays associativos
        // e depois mapeamos para instâncias de Empresa para manter consistência
        // com os outros métodos do DAO.
        $sql = "SELECT * FROM empresa";
        $stmt = $this->executar($sql);
        $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

      $empresas = [];
        foreach ($resultados as $resultado) {
            $empresas[] = new Empresa(
                $resultado['id_empresa'],
                $resultado['cnpj'],
                $resultado['nomeFantasia'],
                $resultado['telefone'],
                $resultado['email'], 
                $resultado['senha'],
                $resultado['atividadeEconomica'],
                $resultado['porteEmpresarial']
            );
        }

        return $empresas;

    }

    /**
     * Seleciona empresas relacionadas a um usuário (aluno) através de treinos
     * vinculados ao usuário (Treino_has_Usuario -> Treino -> Empresa).
     * Retorna um array de objetos Empresa.
     */
    public function selecionarPorUsuario($id_usuario)
    {
        // O esquema definido em doc/sql03.sql liga usuario -> usuarioTreino -> ofertaTreino -> empresa
        $sql = "SELECT DISTINCT e.*
                FROM empresa e
                INNER JOIN ofertaTreino ot ON ot.empresa_id_empresa = e.id_empresa
                INNER JOIN usuarioTreino ut ON ut.ofertaTreino_idOferta = ot.idOferta
                WHERE ut.usuario_id_usuario = :id_usuario";

        $parametros = array(
            ':id_usuario' => $id_usuario
        );

        $stmt = $this->executaComParametros($sql, $parametros);
        $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $empresas = [];
        foreach ($resultados as $resultado) {
            $empresas[] = new Empresa(
                $resultado['id_empresa'],
                $resultado['cnpj'],
                $resultado['nomeFantasia'],
                $resultado['telefone'],
                $resultado['email'],
                $resultado['senha'] ?? '',
                $resultado['atividadeEconomica'] ?? '',
                $resultado['porteEmpresarial'] ?? ''
            );
        }

        return $empresas;
    }

}
?>