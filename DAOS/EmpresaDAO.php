<?php
// Requer os arquivos da classe BaseDAO e da Entidade Empresa.
// CORRETO: Garante que a classe possa herdar de BaseDAO e usar o objeto Empresa.
require_once('BaseDAO.php');
require_once('../entities/Empresa.php');

class EmpresaDAO extends BaseDAO
{
    // Método para inserir uma nova empresa no banco de dados.
    public function inserir(Empresa $empresa)
    {
        // A instrução SQL para inserir os dados na tabela Empresa.
        $sql = "INSERT INTO Empresa (cnpj, nomeFantasia, atividadeEconomica, email, senha, porteEmpresarial)
            VALUES (:cnpj, :nomeFantasia, :atividadeEconomica, :email, :senha, :porteEmpresarial)";
        
        // Prepara um array com os valores a serem inseridos no banco.
        $parametros = array(
            ":cnpj"=> $empresa->getCnpj(),
            ":nomeFantasia" => $empresa->getNomeFantasia(),
            ":atividadeEconomica" => $empresa->getAtividadeEconomica(),
            ":email" => $empresa->getEmail(),
            ":senha" => $empresa->getSenha(), 
            ":porteEmpresarial" => $empresa->getPorteEmpresarial(),
        );

        // Executa a instrução SQL com os parâmetros seguros.
        $this->executaComParametros($sql, $parametros);
    }
    
    // Método para buscar uma empresa pelo CNPJ.
    public function selecionarPorCnpj($empresa)
    {
        // A consulta SQL busca todos os dados da empresa correspondente ao CNPJ.
        $sql = "SELECT * FROM empresa WHERE cnpj = :cnpj";

        // Prepara o parâmetro para a consulta.
        $parametros = array(
            ":cnpj" => $empresa->getCnpj()
        );
        
        // Executa a consulta.
        $stmt = $this->executaComParametros($sql, $parametros);
        
        // Pega a primeira linha do resultado.
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        // Se um resultado foi encontrado...
        if ($resultado) {
            // ...cria um novo objeto 'Empresa' com os dados retornados do banco.
            $cadastrada = new Empresa(
                $resultado['id_empresa'],
                $resultado['cnpj'],
                $resultado['nomeFantasia'],
                $resultado['email'],
                $resultado['senha'],
                $resultado['atividadeEconomica'],
                $resultado['porteEmpresarial']
            );
            // Retorna o objeto Empresa preenchido.
            return $cadastrada;
        } else {
            // Se não encontrou nenhuma empresa, retorna nulo.
            return null;
        }
    }

    public function autenticar($cnpj, $senha)
    {
        $sql = "SELECT id_empresa, nomeFantasia, cnpj
                FROM empresa
                WHERE cnpj = :cnpj AND senha = :senha";

        $parametros = array(
            ":cnpj" => $cnpj,
            ":senha" => $senha
        );

        $stmt = $this->executaComParametros($sql, $parametros);
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        // Se encontrou, retorna objeto Usuario com os dados básicos
        if ($resultado) {
            $cadastrado = new Empresa(
                $resultado['id_empresa'],
                $resultado['cnpj'],
                $resultado['nomeFantasia']
            );
            return $cadastrado;
        } else {
            return null;
        }
}
}
?>