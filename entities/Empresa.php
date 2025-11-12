<?php

    class Empresa implements JsonSerializable
    {

        // Sendo 'private', elas só podem ser acessadas pelos métodos dentro desta classe (getters e setters).
        private $id_empresa;
        private $nomeFantasia;
        private $telefone;
        private $email;
        private $senha;
        private $atividadeEconomica;
        private $cnpj;
        private $porteEmpresarial;


        public function jsonSerialize(): mixed //Apresentava erro antes, adicionei ": mixed" para corrigir, pois a interface JsonSerializable exige essa assinatura. oque tambem corrigiu um erro na hora de cadastrar uma empresa.
        {
            return [
                'id_empresa' => $this->id_empresa,
                'cnpj' => $this->cnpj,
                'nomeFantasia' => $this->nomeFantasia,
                'telefone' => $this->telefone,
                'email' => $this->email,
                'atividadeEconomica' => $this->atividadeEconomica,
                'porteEmpresarial' => $this->porteEmpresarial,
            ];
        }
        
    
        function __construct($id_empresa = "", $cnpj = "", $nomeFantasia = "", $telefone = "", $email = "", $senha = "", $atividadeEconomica = "", $porteEmpresarial = "") 
        {
            $this->id_empresa = $id_empresa;
            $this->cnpj = $cnpj;
            $this->nomeFantasia = $nomeFantasia;
            $this->telefone = $telefone;
            $this->email = $email;
            $this->senha = $senha;
            $this->atividadeEconomica = $atividadeEconomica;
            $this->porteEmpresarial = $porteEmpresarial;
        }
        

        // --- Métodos Getters e Setters ---

        public function getId_empresa()
        {
            return $this->id_empresa;
        }

        public function setId_empresa($id_empresa)
        {
            $this->id_empresa = $id_empresa;
        }

        public function getNomeFantasia()
        {
            return $this->nomeFantasia;
        }

        public function setNomeFantasia($nomeFantasia)
        {
            $this->nomeFantasia = $nomeFantasia;
        }

        public function getTelefone()
        {
            return $this->telefone;
        }

        public function setTelefone($telefone)
        {
            $this->telefone = $telefone;
        }

        public function getPorteEmpresarial()
        {
            return $this->porteEmpresarial;
        }

        public function setPorteEmpresarial($porteEmpresarial)
        {
            $this->porteEmpresarial = $porteEmpresarial;
        }

        public function getEmail()
        {
            return $this->email;
        }

        public function setEmail($email)
        {
            $this->email = $email;
        }

        public function getSenha()
        {
            return $this->senha;
        }

        public function setSenha($senha)
        {
            $this->senha = $senha;
        }

        public function getAtividadeEconomica()
        {
            return $this->atividadeEconomica;
        }

        public function setAtividadeEconomica($atividadeEconomica)
        {
            $this->atividadeEconomica = $atividadeEconomica;
        }

        public function getCnpj()
        {
            return $this->cnpj;
        }

        public function setCnpj($cnpj)
        {
            $this->cnpj = $cnpj;
        }
    }
?>