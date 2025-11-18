<?php 

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL & ~E_NOTICE);


require_once("../DAOS/EmpresaDAO.php");
require_once("../entities/Empresa.php");

class EmpresaController
{

    private $dao;

   
    function __construct()
    {
        $this->dao = new EmpresaDAO();
    }


    function inserir()
    {
        
        header('Content-Type: application/json');

 
        $json = file_get_contents("php://input");
        $data = json_decode($json, true);


        $empresaParaVerificar = new Empresa("", $data["cnpj"],"", "", "", "", "", "");
        

        $cadastrada = $this->dao->selecionarPorCnpj($empresaParaVerificar);

      
        if ($cadastrada == null) {
      
            $empresa = new Empresa(
                "", 
                $data["cnpj"], 
                $data["nomeFantasia"], 
                $data["telefone"],
                $data["email"],
                $data["senha"], 
                $data["atividadeEconomica"], 
                $data["porteEmpresarial"],
            );
            
       
            $this->dao->inserir($empresa);
            

            $resposta = [
                "mensagem" => "Empresa cadastrada com sucesso."
            ];
            echo json_encode($resposta);
        } else {

            $resposta = [
                "mensagem" => "Já existe uma empresa com este CNPJ."
            ];
            http_response_code(400); 
            echo json_encode($resposta);
        }
    }

    function autenticar()
    {
        
        $json = file_get_contents("php://input");
        $data = json_decode($json, true);
        $cnpj = $data['cnpj'];
        $senha = $data['senha'];

        $usuario = $this->dao->autenticar($cnpj, $senha);

        if ($usuario != null) {
  
            session_start();
            $_SESSION['id_empresa'] = $usuario->getId_empresa();
            $_SESSION['nomeFantasia'] = $usuario->getNomeFantasia();
            // Tipo de sessão para facilitar verificações no frontend e APIs
            $_SESSION['tipoUsuario'] = 'empresa';

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

    /**
     * Altera a senha da empresa autenticada.
     * Espera JSON: { senhaAtual: string, novaSenha: string }
     */
    function alterarSenha()
    {
        header('Content-Type: application/json');
        session_start();
        if (!isset($_SESSION['id_empresa'])) {
            http_response_code(401);
            echo json_encode(["error" => "Empresa não autenticada"]);
            return;
        }

        $id_empresa = $_SESSION['id_empresa'];
        $json = file_get_contents("php://input");
        $data = json_decode($json, true);
        $senhaAtual = $data['senhaAtual'] ?? '';
        $novaSenha = $data['novaSenha'] ?? '';

        if (!$senhaAtual || !$novaSenha) {
            http_response_code(400);
            echo json_encode(["error" => "Parâmetros inválidos"]);
            return;
        }

        $empresa = $this->dao->selecionarPorId($id_empresa);
        if (!$empresa) {
            http_response_code(404);
            echo json_encode(["error" => "Empresa não encontrada"]);
            return;
        }

        $senhaArmazenada = $empresa->getSenha();
        $senhaValida = false;
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
        $this->dao->atualizarSenha($id_empresa, $hash);
        echo json_encode(["mensagem" => "Senha alterada com sucesso"]);
    }

    /**
     * Altera email e telefone da empresa autenticada.
     * Espera JSON: { email: string, telefone: string }
     */
    function alterarContato()
    {
        header('Content-Type: application/json');
        session_start();
        if (!isset($_SESSION['id_empresa'])) {
            http_response_code(401);
            echo json_encode(["error" => "Empresa não autenticada"]);
            return;
        }

        $id_empresa = $_SESSION['id_empresa'];
        $json = file_get_contents("php://input");
        $data = json_decode($json, true);
        $email = $data['email'] ?? null;
        $telefone = $data['telefone'] ?? null;

        if (!$email && !$telefone) {
            http_response_code(400);
            echo json_encode(["error" => "Nenhum dado para atualizar"]);
            return;
        }

        if ($email && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            http_response_code(400);
            echo json_encode(["error" => "Email inválido"]);
            return;
        }

        $this->dao->atualizarContato($id_empresa, $email, $telefone);
        echo json_encode(["mensagem" => "Contato atualizado com sucesso"]);
    }

    /**
     * Exclui a conta da empresa autenticada.
     * Espera JSON: { senha: string }
     */
    function excluir()
    {
        header('Content-Type: application/json');
        session_start();
        if (!isset($_SESSION['id_empresa'])) {
            http_response_code(401);
            echo json_encode(["error" => "Empresa não autenticada"]);
            return;
        }

        $id_empresa = $_SESSION['id_empresa'];
        $json = file_get_contents("php://input");
        $data = json_decode($json, true);
        $senha = $data['senha'] ?? '';

        if (!$senha) {
            http_response_code(400);
            echo json_encode(["error" => "Senha não informada"]);
            return;
        }

        $empresa = $this->dao->selecionarPorId($id_empresa);
        if (!$empresa) {
            http_response_code(404);
            echo json_encode(["error" => "Empresa não encontrada"]);
            return;
        }

        $senhaArmazenada = $empresa->getSenha();
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

        $this->dao->excluir($id_empresa);
        session_unset();
        session_destroy();
        echo json_encode(["mensagem" => "Conta excluída com sucesso"]);
    }
}


$acao = $_GET["acao"] ?? ''; 
$controller = new EmpresaController(); 

if ($acao == "inserir") {
    $controller->inserir();
}elseif ($acao == "autenticar") {
    $controller->autenticar();
}
elseif ($acao == "alterarSenha") {
    $controller->alterarSenha();
} elseif ($acao == "alterarContato") {
    $controller->alterarContato();
} elseif ($acao == "excluir") {
    $controller->excluir();
}
?>