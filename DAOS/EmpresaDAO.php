<?php

require_once('BaseDAO.php');
require_once('../entities/Empresa.php');

class EmpresaDAO extends BaseDAO
{

    public function inserir(Empresa $empresa)
    {

        $sql = "INSERT INTO Empresa (cnpj, nomeFantasia, telefone, atividadeEconomica, email, senha, porteEmpresarial)
            VALUES (:cnpj, :nomeFantasia, :telefone, :atividadeEconomica, :email, :senha, :porteEmpresarial)";
        

        $parametros = array(
            ":cnpj"=> $empresa->getCnpj(),
            ":nomeFantasia" => $empresa->getNomeFantasia(),
            ":telefone" => $empresa ->getTelefone(),
            ":atividadeEconomica" => $empresa->getAtividadeEconomica(),
            ":email" => $empresa->getEmail(),
            ":senha" => $empresa->getSenha(), 
            ":porteEmpresarial" => $empresa->getPorteEmpresarial(),
        );


        $this->executaComParametros($sql, $parametros);
    }
    

    public function selecionarPorCnpj($empresa)
    {

        $sql = "SELECT * FROM empresa WHERE cnpj = :cnpj";


        $parametros = array(
            ":cnpj" => $empresa->getCnpj()
        );
        

        $stmt = $this->executaComParametros($sql, $parametros);
        

        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);


        if ($resultado) {

            $cadastrada = new Empresa(
                $resultado['id_empresa'],
                $resultado['cnpj'],
                $resultado['nomeFantasia'],
                $resultado['telefone'],
                $resultado['email'],
                $resultado['senha'],
                $resultado['atividadeEconomica'],
                $resultado['porteEmpresarial']
            );

            return $cadastrada;
        } else {

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