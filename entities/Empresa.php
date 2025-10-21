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


        public function jsonSerialize()
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

        // "Getter" para o id_empresa: retorna o valor do ID da empresa.
        public function getId_empresa()
        {
            return $this->id_empresa;
        }

        // "Setter" para o id_empresa: define um novo valor para o ID da empresa.
        public function setId_empresa($id_empresa)
        {
            $this->id_empresa = $id_empresa;
        }

        // "Getter" para o nomeFantasia.
        public function getNomeFantasia()
        {
            return $this->nomeFantasia;
        }

        // "Setter" para o nomeFantasia.
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

        // "Getter" para o porteEmpresarial.
        public function getPorteEmpresarial()
        {
            return $this->porteEmpresarial;
        }

        // "Setter" para o porteEmpresarial.
        public function setPorteEmpresarial($porteEmpresarial)
        {
            $this->porteEmpresarial = $porteEmpresarial;
        }

        // "Getter" para o email.
        public function getEmail()
        {
            return $this->email;
        }

        // "Setter" para o email.
        public function setEmail($email)
        {
            $this->email = $email;
        }

        // "Getter" para a senha.
        public function getSenha()
        {
            return $this->senha;
        }

        // "Setter" para a senha.
        public function setSenha($senha)
        {
            $this->senha = $senha;
        }

        // "Getter" para a atividadeEconomica.
        public function getAtividadeEconomica()
        {
            return $this->atividadeEconomica;
        }

        // "Setter" para a atividadeEconomica.
        public function setAtividadeEconomica($atividadeEconomica)
        {
            $this->atividadeEconomica = $atividadeEconomica;
        }

        // "Getter" para o cnpj.
        public function getCnpj()
        {
            return $this->cnpj;
        }

        // "Setter" para o cnpj.
        public function setCnpj($cnpj)
        {
            $this->cnpj = $cnpj;
        }
    }
?>