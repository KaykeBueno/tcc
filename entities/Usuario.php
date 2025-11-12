<?php


class Usuario implements JsonSerializable
{


    private $id_usuario;
    private $nome;
    private $email;
    private $senha;
    private $dataNascimento;
    private $cpf;
    private $sexo;
    private $telefone;

    public function jsonSerialize(): mixed //Apresentava erro antes, adicionei ": mixed" para corrigir, pois a interface JsonSerializable exige essa assinatura. Oque tambem corrigiu um erro na hora de cadastrar um usuario.
    {
        return [
            'id_usuario' => $this->id_usuario,
            'cpf' => $this->cpf,
            'nome' => $this->nome,
            'telefone' => $this->telefone,
            'sexo' => $this->sexo,
            'email' => $this->email,
            'dataNascimento' => $this->dataNascimento
        ];
    }


 
    function __construct($id_usuario = "", $cpf = "", $nome = "", $email = "", $senha = "", $dataNascimento = "", $sexo = "", $telefone = "") 
    {
        $this->id_usuario = $id_usuario;
        $this->cpf = $cpf;
        $this->nome = $nome;
        $this->email = $email;
        $this->senha = $senha;
        $this->dataNascimento = $dataNascimento;
        $this->sexo = $sexo;
        $this->telefone = $telefone;
    }

    
    // --- Métodos Getters e Setters ---

    public function getId_usuario()
    {
        return $this->id_usuario;
    }

    public function setId_usuario($id_usuario)
    {
        $this->id_usuario = $id_usuario;
    }

    public function getNome()
    {
        return $this->nome;
    }

    public function setNome($nome)
    {
        $this->nome = $nome;
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

    public function getDataNascimento()
    {
        return $this->dataNascimento;
    }

    public function setDataNascimento($dataNascimento)
    {
        $this->dataNascimento = $dataNascimento;
    }

    public function getcpf()
    {
        return $this->cpf;
    }

    public function setcpf($cpf)
    {
        $this->cpf = $cpf;
    }

    public function getTelefone()
    {
        return $this->telefone;
    }
    
    public function setTelefone($telefone)
    {
        $this->telefone = $telefone;
    }

    public function getSexo()
    {
        return $this->sexo;
    }

    public function setSexo($sexo)
    {
        $this->sexo = $sexo;
    }
}
?>