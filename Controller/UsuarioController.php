<?php

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL & ~E_NOTICE);


require_once("../DAOS/UsuarioDAO.php");
require_once("../entities/Usuario.php");

class UsuarioController
{
    
    private $dao;

    function __construct()
    {
        $this->dao = new UsuarioDAO();
    }


    function inserir()
    {
      
        header('Content-Type: application/json');

      
        $json = file_get_contents("php://input");
        $data = json_decode($json, true);


        $usuarioParaVerificar = new Usuario("", $data["cpf"], "", "", "", "", "", "");
      
        $cadastrado = $this->dao->selecionarPorCpf($usuarioParaVerificar);


        if ($cadastrado == null) {

            $usuario = new Usuario(
                "",
                $data["cpf"], 
                $data["nome"], 
                $data["email"],
                $data["senha"], 
                $data["dataNascimento"],
                $data["sexo"], 
                $data["telefone"],
            );
            
    
            $this->dao->inserir($usuario);
            

            $resposta = [
                "mensagem" => "Usuário cadastrado com sucesso."
            ];
            echo json_encode($resposta);
        } else {
    
            $resposta = [
                "mensagem" => "Já existe um usuário com este CPF."
            ];
            http_response_code(400); 
            echo json_encode($resposta);
        }
    }

    function autenticar()
    {

        $json = file_get_contents("php://input");
        $data = json_decode($json, true);
        $cpf = $data['cpf'];
        $senha = $data['senha'];

       
        $usuario = $this->dao->autenticar($cpf, $senha);

        if ($usuario != null) {
 
            session_start();
            $_SESSION['id_usuario'] = $usuario->getId_usuario();
            $_SESSION['nome'] = $usuario->getNome();
            // Tipo de sessão para facilitar verificações no frontend e APIs
            $_SESSION['tipoUsuario'] = 'usuario';

            $retorno = array(
                "mensagem" => "Usuário autenticado com sucesso"
            );
            echo json_encode($retorno);
        } else {
            $retorno = array(
                "mensagem" => "Usuário/senha inexistentes"
            );
            http_response_code(400);
            echo json_encode($retorno);
        }
    }

    function selecionarTodos(){
        $usuarios = $this->dao->selecionarTodos();
        header('Content-Type: application/json');
        // o dao retorna diretamente um array de usuários.
        echo json_encode($usuarios);
    }

    /**
     * Altera a senha do usuário autenticado.
     * Espera JSON: { senhaAtual: string, novaSenha: string }
     */
    function alterarSenha()
    {
        header('Content-Type: application/json');
        session_start();
        if (!isset($_SESSION['id_usuario'])) {
            http_response_code(401);
            echo json_encode(["error" => "Usuário não autenticado"]);
            return;
        }

        $id_usuario = $_SESSION['id_usuario'];
        $json = file_get_contents("php://input");
        $data = json_decode($json, true);

        $senhaAtual = $data['senhaAtual'] ?? '';
        $novaSenha = $data['novaSenha'] ?? '';

        if (!$senhaAtual || !$novaSenha) {
            http_response_code(400);
            echo json_encode(["error" => "Parâmetros inválidos"]);
            return;
        }

        $usuario = $this->dao->selecionarPorId($id_usuario);
        if (!$usuario) {
            http_response_code(404);
            echo json_encode(["error" => "Usuário não encontrado"]);
            return;
        }

        $senhaArmazenada = $usuario->getSenha();
        $senhaValida = false;
        // Suporta senhas em texto ou hashado: tenta password_verify, caso falhe compara texto
        if (password_verify($senhaAtual, $senhaArmazenada)) {
            $senhaValida = true;
        } elseif ($senhaAtual === $senhaArmazenada) {
            $senhaValida = true;
        }

        if (!$senhaValida) {
            http_response_code(401);
            echo json_encode(["error" => "Senha atual incorreta"]);
            return;
        }

        $hash = password_hash($novaSenha, PASSWORD_DEFAULT);
        $this->dao->atualizarSenha($id_usuario, $hash);
        echo json_encode(["mensagem" => "Senha alterada com sucesso"]);
    }

    /**
     * Altera email e telefone do usuário autenticado.
     * Espera JSON: { email: string, telefone: string }
     */
    function alterarContato()
    {
        header('Content-Type: application/json');
        session_start();
        if (!isset($_SESSION['id_usuario'])) {
            http_response_code(401);
            echo json_encode(["error" => "Usuário não autenticado"]);
            return;
        }

        $id_usuario = $_SESSION['id_usuario'];
        $json = file_get_contents("php://input");
        $data = json_decode($json, true);
        $email = $data['email'] ?? null;
        $telefone = $data['telefone'] ?? null;

        if (!$email && !$telefone) {
            http_response_code(400);
            echo json_encode(["error" => "Nenhum dado para atualizar"]);
            return;
        }

        // Validação básica de email
        if ($email && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            http_response_code(400);
            echo json_encode(["error" => "Email inválido"]);
            return;
        }

        $this->dao->atualizarContato($id_usuario, $email, $telefone);
        echo json_encode(["mensagem" => "Contato atualizado com sucesso"]);
    }

    /**
     * Exclui a conta do usuário autenticado.
     * Espera JSON: { senha: string }
     */
    function excluir()
    {
        header('Content-Type: application/json');
        session_start();
        if (!isset($_SESSION['id_usuario'])) {
            http_response_code(401);
            echo json_encode(["error" => "Usuário não autenticado"]);
            return;
        }

        $id_usuario = $_SESSION['id_usuario'];
        $json = file_get_contents("php://input");
        $data = json_decode($json, true);
        $senha = $data['senha'] ?? '';

        if (!$senha) {
            http_response_code(400);
            echo json_encode(["error" => "Senha não informada"]);
            return;
        }

        $usuario = $this->dao->selecionarPorId($id_usuario);
        if (!$usuario) {
            http_response_code(404);
            echo json_encode(["error" => "Usuário não encontrado"]);
            return;
        }

        $senhaArmazenada = $usuario->getSenha();
        $senhaValida = false;
        if (password_verify($senha, $senhaArmazenada)) {
            $senhaValida = true;
        } elseif ($senha === $senhaArmazenada) {
            $senhaValida = true;
        }

        if (!$senhaValida) {
            http_response_code(401);
            echo json_encode(["error" => "Senha incorreta"]);
            return;
        }

        $this->dao->excluir($id_usuario);
        // encerra sessão
        session_unset();
        session_destroy();
        echo json_encode(["mensagem" => "Conta excluída com sucesso"]);
    }
}


$acao = $_GET["acao"] ?? ''; 
$controller = new UsuarioController(); 

if ($acao == "inserir") {
    $controller->inserir();
}
else if ($acao == "autenticar") {
    $controller->autenticar();
}
elseif ($acao == "selecionarTodos"){
    $controller->selecionarTodos();

}
elseif ($acao == "alterarSenha"){
    $controller->alterarSenha();
}
elseif ($acao == "alterarContato"){
    $controller->alterarContato();
}
elseif ($acao == "excluir"){
    $controller->excluir();
}
?>