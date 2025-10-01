<?php
// Habilita a exibição de todos os erros, útil para desenvolvimento.
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

// Requer os arquivos da classe DAO e da Entidade, que são necessários para o controller funcionar.
require_once("../DAOS/UsuarioDAO.php");
require_once("../entities/Usuario.php");

class UsuarioController
{
    // A variável $dao agora é uma propriedade da classe, acessível por todos os métodos.
    private $dao;

    // O construtor é chamado automaticamente quando a classe é instanciada.
    // Ele cria o objeto DAO e o armazena na propriedade $this->dao.
    function __construct()
    {
        $this->dao = new UsuarioDAO();
    }

    // Função para inserir um novo usuário.
    function inserir()
    {
        // Define que a resposta para o JavaScript será em formato JSON.
        header('Content-Type: application/json');

        // Pega os dados JSON enviados pelo JavaScript.
        $json = file_get_contents("php://input");
        $data = json_decode($json, true);

        // --- ERRO LÓGICO 1 AQUI ---
        // O construtor da sua classe 'Usuario' espera 8 parâmetros, começando com 'id_usuario' (que é um número).
        // Ao chamar new Usuario("", $data["cpf"], ...), você está passando:
        // 1. Uma string vazia "" para o id_usuario.
        // 2. O CPF para o parâmetro 'nome'.
        // 3. E assim por diante, todos os dados ficam na posição errada, causando uma inconsistência.
        $usuarioParaVerificar = new Usuario("", $data["cpf"], "", "", "", "", "", "");
        
        // Verifica se já existe um usuário com o mesmo CPF no banco.
        $cadastrado = $this->dao->selecionarPorCpf($usuarioParaVerificar);

        // Se o resultado da busca for nulo (ou seja, não encontrou nenhum usuário com aquele CPF)...
        if ($cadastrado == null) {
            // ...cria o objeto Usuario completo com todos os dados vindos do formulário.
            // --- ERRO LÓGICO 2 AQUI ---
            // O construtor da sua classe Usuario espera 8 parâmetros, mas você está passando 7.
            // Falta o primeiro parâmetro, que é o 'id_usuario'. Para um novo cadastro, ele deve ser 'null'.
            $usuario = new Usuario(
                // null, // <--- Faltou este parâmetro para o id_usuario.
                $data["cpf"], 
                $data["nome"], 
                $data["email"],
                $data["senha"], 
                $data["dataNascimento"],
                $data["sexo"], 
                $data["telefone"],
            );
            
            // Chama o método do DAO para inserir o objeto completo no banco.
            $this->dao->inserir($usuario);
            
            // Envia uma resposta de sucesso para o frontend.
            $resposta = [
                "mensagem" => "Usuário cadastrado com sucesso."
            ];
            echo json_encode($resposta);
        } else {
            // Se o CPF já existe, envia uma mensagem de erro clara para o frontend.
            $resposta = [
                "mensagem" => "Já existe um usuário com este CPF."
            ];
            http_response_code(400); 
            echo json_encode($resposta);
        }
    }

    // Função para autenticar um usuário (login).
    function autenticar()
    {
        // Lê os dados do corpo da requisição.
        $json = file_get_contents("php://input");
        $data = json_decode($json, true);
        $cpf = $data['cpf'];
        $senha = $data['senha'];

        // --- POSSÍVEL ERRO FUTURO ---
        // Esta chamada vai causar um erro fatal se o método 'autenticar($cpf, $senha)'
        // não existir dentro do seu arquivo 'UsuarioDAO.php'.
        $usuario = $this->dao->autenticar($cpf, $senha);

        if ($usuario != null) {
            // Se o usuário for encontrado, inicia sessão e armazena informações.
            session_start();
            $_SESSION['id_usuario'] = $usuario->getId_usuario();
            $_SESSION['nome'] = $usuario->getNome();

            $retorno = array(
                "mensagem" => "Usuário autenticado com sucesso"
            );
            echo json_encode($retorno);
        } else {
            // Caso usuário não seja autenticado.
            $retorno = array(
                "mensagem" => "Usuário/senha inexistentes"
            );
            http_response_code(400);
            echo json_encode($retorno);
        }
    }
}

// Roteador de Ações: Este bloco lê o parâmetro 'acao' da URL e chama o método correspondente.
$acao = $_GET["acao"] ?? ''; // Pega a ação da URL, com '' como padrão se não existir.
$controller = new UsuarioController(); // Cria o objeto do controller.

if ($acao == "inserir") {
    $controller->inserir();
}
else if ($acao == "autenticar") {
    $controller->autenticar();
}
?>