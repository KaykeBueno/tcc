<?php

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);


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
}


$acao = $_GET["acao"] ?? ''; 
$controller = new UsuarioController(); 

if ($acao == "inserir") {
    $controller->inserir();
}
else if ($acao == "autenticar") {
    $controller->autenticar();
}
?>