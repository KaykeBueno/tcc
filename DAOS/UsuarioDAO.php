<?php
require_once('BaseDAO.php');
require_once('../entities/Usuario.php');

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
}
?>