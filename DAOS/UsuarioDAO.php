<?php
// uso __DIR__ para construir caminhos absolutos relativos a este arquivo. Isso evita
// problemas quando o DAO for incluído a partir de diferentes diretórios.
require_once(__DIR__ . '/BaseDAO.php');
require_once(__DIR__ . '/../entities/Usuario.php');

class UsuarioDAO extends BaseDAO
{

    public function inserir(Usuario $usuario)
    {
        $sql = "INSERT INTO usuario (cpf, nome, senha, telefone, dataNascimento, email, sexo)
        VALUES (:cpf, :nome, :senha, :telefone, :dataNascimento, :email, :sexo)";
    
        
        $parametros = array(
            ":cpf"=> $usuario->getCpf(),
            ":nome" => $usuario->getNome(),
            ":senha" => $usuario->getSenha(),
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
        $sql = "SELECT id_usuario, nome, cpf
                FROM usuario 
                WHERE cpf = :cpf AND senha = :senha";

        $parametros = array(
            ":cpf" => $cpf,
            ":senha" => $senha
        );

        $stmt = $this->executaComParametros($sql, $parametros);
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);


        if ($resultado) {
            $cadastrado = new Usuario(
                $resultado['id_usuario'],
                $resultado['cpf'],
                $resultado['nome']
            );
            return $cadastrado;
        } else {
            return null;
        }
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
        $sql = "SELECT DISTINCT u.*
                FROM usuario u
                INNER JOIN usuarioTreino ut ON ut.usuario_id_usuario = u.id_usuario
                INNER JOIN ofertaTreino ot ON ot.idOferta = ut.ofertaTreino_idOferta
                WHERE ot.empresa_id_empresa = :id_empresa";

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
}
?>