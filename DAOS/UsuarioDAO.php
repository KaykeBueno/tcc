<?php
require_once('BaseDAO.php');
require_once('../entities/Usuario.php');

class UsuarioDAO extends BaseDAO
{
    // Insere um novo usuário no banco de dados
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

    // Busca um usuário pelo CPF para verificar se ele já existe
    public function selecionarPorCpf($usuario)
{
    // A consulta SQL busca todos os dados do usuário correspondente ao CPF.
    $sql = "SELECT * FROM Usuario WHERE cpf = :cpf";

    // O array de parâmetros agora pega o CPF de dentro do objeto $usuario.
    $parametros = array(
        ":cpf" => $usuario->getCpf()
    );
    
    // Usa o método 'executaComParametros' e pega o statement retornado.
    $stmt = $this->executaComParametros($sql, $parametros);
    
    // Pega a primeira linha do resultado como um array associativo.
    $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

    // Se um resultado foi encontrado no banco...
    if ($resultado) {
        // ...cria um novo objeto 'Usuario' com os dados retornados do banco.
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
        // Retorna o objeto recém-criado.
        return $cadastrado;
    } else {
        // Se não encontrou nenhum usuário, retorna nulo.
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

        // Se encontrou, retorna objeto Usuario com os dados básicos
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
}
?>