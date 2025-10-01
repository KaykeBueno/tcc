<?php
// Habilita a exibição de todos os erros, útil para desenvolvimento
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

// Requer os arquivos da classe DAO e da Entidade, que são necessários para o controller funcionar
require_once("../DAOS/EmpresaDAO.php");
require_once("../entities/Empresa.php");

class EmpresaController
{
    // A variável $dao agora é uma propriedade da classe, acessível por todos os métodos
    private $dao;

    // O construtor é chamado automaticamente quando a classe é instanciada.
    // Ele cria o objeto DAO e o armazena na propriedade $this->dao.
    function __construct()
    {
        $this->dao = new EmpresaDAO();
    }

    // Função para inserir uma nova empresa
    function inserir()
    {
        // Define que a resposta para o JavaScript será em formato JSON
        header('Content-Type: application/json');

        // Pega os dados JSON enviados pelo JavaScript
        $json = file_get_contents("php://input");
        $data = json_decode($json, true);

        // --- ERRO LÓGICO AQUI ---
        // O construtor da sua classe 'Empresa' espera 7 parâmetros, começando com o 'id_empresa'.
        // Ao chamar new Empresa($data["cnpj"], ...), você está passando o CNPJ (que é um texto) para o primeiro parâmetro,
        // que deveria ser o id_empresa (que é um número). Isso causa uma inconsistência de dados.
        // A forma correta seria passar o ID primeiro, ou ajustar o construtor.
        $empresaParaVerificar = new Empresa("", $data["cnpj"],"", "", "", "", "");
        
        // Verifica se já existe uma empresa com o mesmo CNPJ
        $cadastrada = $this->dao->selecionarPorCnpj($empresaParaVerificar);

        // Se o resultado da busca for nulo (ou seja, não encontrou nenhuma empresa com aquele CNPJ)...
        if ($cadastrada == null) {
            // ...cria o objeto Empresa completo com todos os dados do formulário.
            $empresa = new Empresa(
                null, // Passa 'null' para o id_empresa, pois ele é AUTO_INCREMENT no banco.
                $data["cnpj"], 
                $data["nomeFantasia"], 
                $data["email"],
                $data["senha"], 
                $data["atividadeEconomica"], 
                $data["porteEmpresarial"]
            );
            
            // Chama o método do DAO para inserir o objeto completo no banco
            $this->dao->inserir($empresa);
            
            // Envia uma resposta de sucesso para o frontend
            $resposta = [
                "mensagem" => "Empresa cadastrada com sucesso."
            ];
            echo json_encode($resposta);
        } else {
            // Se o CNPJ já existe, envia uma mensagem de erro clara para o frontend
            $resposta = [
                "mensagem" => "Já existe uma empresa com este CNPJ."
            ];
            http_response_code(400); // Código de erro "Bad Request".
            echo json_encode($resposta);
        }
    }

    function autenticar()
    {
        // Lê os dados do corpo da requisição.
        $json = file_get_contents("php://input");
        $data = json_decode($json, true);
        $cpf = $data['cnpj'];
        $senha = $data['senha'];

        // --- POSSÍVEL ERRO FUTURO ---
        // Esta chamada vai causar um erro fatal se o método 'autenticar($cpf, $senha)'
        // não existir dentro do seu arquivo 'UsuarioDAO.php'.
        $usuario = $this->dao->autenticar($cnpj, $senha);

        if ($usuario != null) {
            // Se o usuário for encontrado, inicia sessão e armazena informações.
            session_start();
            $_SESSION['id_empresa'] = $usuario->getId_empresa();
            $_SESSION['nomeFantasia'] = $usuario->getNomeFantasia();

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
$acao = $_GET["acao"] ?? ''; // Pega a ação da URL, com '' como padrão se não existir
$controller = new EmpresaController(); // Cria o objeto do controller

if ($acao == "inserir") {
    $controller->inserir();
}

if ($acao == "autenticar") {
    $controller->autenticar();
}
?>