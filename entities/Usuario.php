<?php

// A classe Usuario implementa a interface JsonSerializable,
// o que permite definir um formato de saída customizado quando a função json_encode() é usada em um objeto desta classe.
class Usuario implements JsonSerializable
{

    // --- Propriedades Privadas ---
    // Estas são as variáveis que armazenam os dados de cada objeto Usuario.
    // Sendo 'private', elas só podem ser acessadas pelos métodos dentro desta classe (getters e setters).
    private $id_usuario;
    private $nome;
    private $email;
    private $senha;
    private $dataNascimento;
    private $cpf;
    private $sexo;
    private $telefone;

    // --- Serialização para JSON ---
    // Este método é chamado automaticamente quando se usa json_encode() em um objeto Usuario.
    // Ele define exatamente quais dados serão incluídos na resposta JSON para o frontend.
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


    // --- Construtor ---
    // O método __construct é chamado automaticamente quando um novo objeto Usuario é criado (ex: new Usuario(...)).
    // Ele serve para inicializar o objeto, atribuindo os valores passados como parâmetro às propriedades da classe.
    // Parâmetros com "= """ são opcionais; se não forem fornecidos, assumirão o valor de uma string vazia.
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
    // São métodos públicos que permitem acessar (get) e modificar (set) as propriedades privadas de forma controlada.

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