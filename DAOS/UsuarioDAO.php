<?php
// uso __DIR__ para construir caminhos absolutos relativos a este arquivo. Isso evita
// problemas quando o DAO for incluído a partir de diferentes diretórios.
require_once(__DIR__ . '/BaseDAO.php');
require_once(__DIR__ . '/../entities/Usuario.php');

class UsuarioDAO extends BaseDAO
{

    public function inserir(Usuario $usuario)
    {
        // Armazenar senha como hash para segurança
        $sql = "INSERT INTO usuario (cpf, nome, senha, telefone, dataNascimento, email, sexo)
        VALUES (:cpf, :nome, :senha, :telefone, :dataNascimento, :email, :sexo)";

        $senhaHash = password_hash($usuario->getSenha(), PASSWORD_DEFAULT);

        $parametros = array(
            ":cpf"=> $usuario->getCpf(),
            ":nome" => $usuario->getNome(),
            ":senha" => $senhaHash,
            ":telefone" => $usuario->getTelefone(),
            ":dataNascimento" => $usuario->getDataNascimento(),
            ":email" => $usuario->getEmail(),
            ":sexo" => $usuario->getSexo(),
        );

        $this->executaComParametros($sql, $parametros);
    }


    public function selecionarPorCpf($usuario)
{

    $sql = "SELECT * FROM Usuario WHERE cpf = :cpf";


    $parametros = array(
        ":cpf" => $usuario->getCpf()
    );
    

    $stmt = $this->executaComParametros($sql, $parametros);
    

    $resultado = $stmt->fetch(PDO::FETCH_ASSOC);


    if ($resultado) {

        $cadastrado = new Usuario(
            $resultado['id_usuario'],
            $resultado['cpf'],
            $resultado['nome'],
            $resultado['email'],
            $resultado['senha'], 
            $resultado['dataNascimento'],
            $resultado['sexo'],
            $resultado['telefone']
        );

        return $cadastrado;
    } else {

        return null;
    }
    
    }

    public function autenticar($cpf, $senha)
    {
        // Buscar a senha armazenada e validar com password_verify
        $sql = "SELECT id_usuario, nome, cpf, senha FROM usuario WHERE cpf = :cpf";

        $parametros = array(
            ":cpf" => $cpf
        );

        $stmt = $this->executaComParametros($sql, $parametros);
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($resultado) {
            $senhaArmazenada = $resultado['senha'];
            // Se estiver hashada, usa password_verify
            if (password_verify($senha, $senhaArmazenada)) {
                $cadastrado = new Usuario(
                    $resultado['id_usuario'],
                    $resultado['cpf'],
                    $resultado['nome']
                );
                return $cadastrado;
            }
            // Compatibilidade: se a senha no banco for texto puro, aceita e migra para hash
            if ($senha === $senhaArmazenada) {
                // migrar para hash para segurança
                $novoHash = password_hash($senha, PASSWORD_DEFAULT);
                // atualizar o registro para usar hash (silencioso)
                $this->atualizarSenha($resultado['id_usuario'], $novoHash);
                $cadastrado = new Usuario(
                    $resultado['id_usuario'],
                    $resultado['cpf'],
                    $resultado['nome']
                );
                return $cadastrado;
            }
        }
        return null;
}
    public function selecionarTodos(){
        $sql = "SELECT * FROM usuario";
        $stmt = $this->executar($sql);
        $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $usuarios = [];
        foreach ($resultados as $resultado) {
            $usuarios[] = new Usuario(
                $resultado['id_usuario'],
                $resultado['cpf'],
                $resultado['nome'],
                $resultado['email'],
                $resultado['senha'], 
                $resultado['dataNascimento'],
                $resultado['sexo'],
                $resultado['telefone']
            );
        }
        return $usuarios;
    }

    /**
     * Seleciona usuários vinculados a uma empresa através das tabelas
     * ofertaTreino (ofertaTreino.empresa_id_empresa) e usuarioTreino
     * (usuarioTreino.ofertaTreino_idOferta).
     * Retorna um array de objetos Usuario.
     */
    public function selecionarPorEmpresa($id_empresa)
    {
    // Novo esquema (sql04.sql): usuario -> PlanoTreino -> portifolio -> empresa
    // portifolio.idPortifolio, portifolio.empresa_id_empresa
    // PlanoTreino.usuario_id_usuario, PlanoTreino.portifolio_idPortifolio
    $sql = "SELECT DISTINCT u.*
        FROM usuario u
        INNER JOIN PlanoTreino pt ON pt.usuario_id_usuario = u.id_usuario
        INNER JOIN portifolio p ON p.idPortifolio = pt.portifolio_idPortifolio
        WHERE p.empresa_id_empresa = :id_empresa";

        $parametros = array(
            ':id_empresa' => $id_empresa
        );

        $stmt = $this->executaComParametros($sql, $parametros);
        $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $usuarios = [];
        foreach ($resultados as $resultado) {
            $usuarios[] = new Usuario(
                $resultado['id_usuario'],
                $resultado['cpf'],
                $resultado['nome'],
                $resultado['email'],
                $resultado['senha'] ?? '',
                $resultado['dataNascimento'] ?? '',
                $resultado['sexo'] ?? '',
                $resultado['telefone'] ?? ''
            );
        }

        return $usuarios;
    }

    /**
     * Seleciona um usuário pelo ID (inclui a senha para operações seguras).
     */
    public function selecionarPorId($id_usuario)
    {
        $sql = "SELECT * FROM usuario WHERE id_usuario = :id_usuario";
        $parametros = array(':id_usuario' => $id_usuario);
        $stmt = $this->executaComParametros($sql, $parametros);
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($resultado) {
            return new Usuario(
                $resultado['id_usuario'],
                $resultado['cpf'],
                $resultado['nome'],
                $resultado['email'],
                $resultado['senha'],
                $resultado['dataNascimento'],
                $resultado['sexo'],
                $resultado['telefone']
            );
        }
        return null;
    }

    /**
     * Atualiza a senha do usuário (armazenar hash).
     */
    public function atualizarSenha($id_usuario, $senhaHash)
    {
        $sql = "UPDATE usuario SET senha = :senha WHERE id_usuario = :id_usuario";
        $parametros = array(':senha' => $senhaHash, ':id_usuario' => $id_usuario);
        $this->executaComParametros($sql, $parametros);
    }

    /**
     * Atualiza email e telefone do usuário.
     */
    public function atualizarContato($id_usuario, $email, $telefone)
    {
        $sql = "UPDATE usuario SET email = :email, telefone = :telefone WHERE id_usuario = :id_usuario";
        $parametros = array(':email' => $email, ':telefone' => $telefone, ':id_usuario' => $id_usuario);
        $this->executaComParametros($sql, $parametros);
    }

    /**
     * Exclui o usuário do banco (remoção física). Se preferir, altere para soft-delete.
     */
    public function excluir($id_usuario)
    {
        $sql = "DELETE FROM usuario WHERE id_usuario = :id_usuario";
        $parametros = array(':id_usuario' => $id_usuario);
        $this->executaComParametros($sql, $parametros);
    }
}
?>