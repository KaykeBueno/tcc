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
$controller = new EmpresaController(); 

if ($acao == "inserir") {
    $controller->inserir();
}

if ($acao == "autenticar") {
    $controller->autenticar();
}
?>