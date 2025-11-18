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
        // Hash da senha antes de armazenar
        $sql = "INSERT INTO Empresa (cnpj, nomeFantasia, telefone, atividadeEconomica, email, senha, porteEmpresarial)
            VALUES (:cnpj, :nomeFantasia, :telefone, :atividadeEconomica, :email, :senha, :porteEmpresarial)";

        $senhaHash = password_hash($empresa->getSenha(), PASSWORD_DEFAULT);

        $parametros = array(
            ":cnpj"=> $empresa->getCnpj(),
            ":nomeFantasia" => $empresa->getNomeFantasia(),
            ":telefone" => $empresa ->getTelefone(),
            ":atividadeEconomica" => $empresa->getAtividadeEconomica(),
            ":email" => $empresa->getEmail(),
            ":senha" => $senhaHash, 
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
        // Buscar senha armazenada e validar com password_verify
        $sql = "SELECT id_empresa, nomeFantasia, cnpj, senha FROM empresa WHERE cnpj = :cnpj";

        $parametros = array(
            ":cnpj" => $cnpj
        );

        $stmt = $this->executaComParametros($sql, $parametros);
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($resultado) {
            $senhaArmazenada = $resultado['senha'];
            if (password_verify($senha, $senhaArmazenada)) {
                $cadastrado = new Empresa(
                    $resultado['id_empresa'],
                    $resultado['cnpj'],
                    $resultado['nomeFantasia']
                );
                return $cadastrado;
            }
            // Compatibilidade com senha em texto: aceita e migra para hash
            if ($senha === $senhaArmazenada) {
                $novoHash = password_hash($senha, PASSWORD_DEFAULT);
                $this->atualizarSenha($resultado['id_empresa'], $novoHash);
                $cadastrado = new Empresa(
                    $resultado['id_empresa'],
                    $resultado['cnpj'],
                    $resultado['nomeFantasia']
                );
                return $cadastrado;
            }
        }
        return null;
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
    // Atualizado para novo esquema (sql04.sql): usuario -> PlanoTreino -> portifolio -> empresa
    $sql = "SELECT DISTINCT e.*
        FROM empresa e
        INNER JOIN portifolio p ON p.empresa_id_empresa = e.id_empresa
        INNER JOIN PlanoTreino pt ON pt.portifolio_idPortifolio = p.idPortifolio
        WHERE pt.usuario_id_usuario = :id_usuario";

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

    /**
     * Seleciona empresa pelo ID (inclui a senha para operações seguras).
     */
    public function selecionarPorId($id_empresa)
    {
        $sql = "SELECT * FROM empresa WHERE id_empresa = :id_empresa";
        $parametros = array(':id_empresa' => $id_empresa);
        $stmt = $this->executaComParametros($sql, $parametros);
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($resultado) {
            return new Empresa(
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
        return null;
    }

    /**
     * Atualiza a senha da empresa (armazenar hash).
     */
    public function atualizarSenha($id_empresa, $senhaHash)
    {
        $sql = "UPDATE empresa SET senha = :senha WHERE id_empresa = :id_empresa";
        $parametros = array(':senha' => $senhaHash, ':id_empresa' => $id_empresa);
        $this->executaComParametros($sql, $parametros);
    }

    /**
     * Atualiza email e telefone da empresa.
     */
    public function atualizarContato($id_empresa, $email, $telefone)
    {
        $sql = "UPDATE empresa SET email = :email, telefone = :telefone WHERE id_empresa = :id_empresa";
        $parametros = array(':email' => $email, ':telefone' => $telefone, ':id_empresa' => $id_empresa);
        $this->executaComParametros($sql, $parametros);
    }

    /**
     * Exclui a empresa do banco (remoção física). Se preferir, altere para soft-delete.
     */
    public function excluir($id_empresa)
    {
        $sql = "DELETE FROM empresa WHERE id_empresa = :id_empresa";
        $parametros = array(':id_empresa' => $id_empresa);
        $this->executaComParametros($sql, $parametros);
    }

}
?>