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

    public function jsonSerialize()
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

    // "Getter" para o id_usuario: retorna o valor do ID do usuário.
    public function getId_usuario()
    {
        return $this->id_usuario;
    }

    // "Setter" para o id_usuario: define um novo valor para o ID do usuário.
    public function setId_usuario($id_usuario)
    {
        $this->id_usuario = $id_usuario;
    }

    // "Getter" para o nome.
    public function getNome()
    {
        return $this->nome;
    }

    // "Setter" para o nome.
    public function setNome($nome)
    {
        $this->nome = $nome;
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

    // "Getter" para a dataNascimento.
    public function getDataNascimento()
    {
        return $this->dataNascimento;
    }

    // "Setter" para a dataNascimento.
    public function setDataNascimento($dataNascimento)
    {
        $this->dataNascimento = $dataNascimento;
    }

    // "Getter" para o cpf.
    public function getcpf()
    {
        return $this->cpf;
    }

    // "Setter" para o cpf.
    public function setcpf($cpf)
    {
        $this->cpf = $cpf;
    }

    // "Getter" para o telefone.
    public function getTelefone()
    {
        return $this->telefone;
    }
    
    // "Setter" para o telefone.
    public function setTelefone($telefone)
    {
        $this->telefone = $telefone;
    }

    // "Getter" para o sexo.
    public function getSexo()
    {
        return $this->sexo;
    }

    // "Setter" para o sexo.
    public function setSexo($sexo)
    {
        $this->sexo = $sexo;
    }
}
?>