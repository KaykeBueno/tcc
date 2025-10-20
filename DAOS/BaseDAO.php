<?php
class BaseDAO
{
    // A variável $connection é privada, o que significa que só pode ser acessada
    // pelos métodos dentro desta classe. Ela vai armazenar o objeto de conexão (PDO).
    private $connection;

    // O construtor é um método especial que é executado automaticamente sempre que
    // um objeto DAO (como UsuarioDAO ou EmpresaDAO) é criado.
    public function __construct()
    {
        // --- PONTO DE ATENÇÃO: DADOS DA CONEXÃO ---
        // Esta string define para qual banco de dados o sistema vai se conectar.
        // O 'dbname=tcc' está CORRETO para o seu projeto.
        // O 'port=3306' é a porta padrão do MySQL. Se o seu XAMPP/WAMP usa uma porta diferente, ajuste aqui.
        $connection_string = "mysql:host=localhost;dbname=tcc;port=3306";
        
        // O usuário do banco de dados. 'root' é o padrão para ambientes de desenvolvimento.
        $db_user = "root";
        
        // A senha do banco de dados. Uma string vazia "" é o padrão na maioria das instalações do XAMPP/WAMP.
        // Se você definiu uma senha para o usuário 'root', precisa colocá-la aqui.
        $db_pass = "";

        // O bloco try...catch é usado para tratar erros de forma segura.
        try {
            // Tenta criar uma nova conexão com o banco (um objeto PDO) usando os dados acima.
            // O resultado da conexão bem-sucedida é armazenado em $this->connection.
            $this->connection = new PDO(
                $connection_string,
                $db_user,
                $db_pass
            );
            // Configura o PDO para lançar exceções sempre que ocorrer um erro no banco.
            // CORRETO: Esta é a melhor prática, pois facilita muito a identificação de problemas.
            $this->connection->setAttribute(
                PDO::ATTR_ERRMODE,
                PDO::ERRMODE_EXCEPTION
            );
        // Se a conexão falhar (ex: senha errada, banco não existe), o bloco 'catch' é executado.
        } catch (PDOException $e) {
            // 'die()' interrompe completamente a execução do script e exibe uma mensagem de erro clara.
            die("Erro fatal de conexão com o banco de dados: " . $e->getMessage());
        }
    }

    /**
     * Executa uma instrução SQL (como INSERT, UPDATE, DELETE) que usa parâmetros.
     * Esta é a forma mais segura de interagir com o banco, pois previne ataques de SQL Injection.
     */
    public function executaComParametros($sql, $parametros) 
    {
        // Prepara a instrução SQL para execução, verificando a sintaxe.
        $stmt = $this->connection->prepare($sql);
        
        // Itera sobre cada parâmetro (ex: ':cpf', ':nome') e associa seu valor correspondente de forma segura.
        foreach ($parametros as $chave => $valor) {
            $stmt->bindValue($chave, $valor);
        }
        
        // Executa a instrução já com os valores seguros.
        $stmt->execute();
        
        // Retorna o statement, que pode ser usado para obter informações (como o ID inserido) se necessário.
        return $stmt;
    }

    /**
     * Executa uma instrução SQL que não precisa de parâmetros.
     * Geralmente usado para consultas simples como 'SELECT * FROM tabela'.
     */
    public function executar($sql)
    {
        // Prepara e executa a instrução SQL diretamente.
        $stmt = $this->connection->prepare($sql);
        $stmt->execute();
        
        // Retorna o statement. Para consultas SELECT, você usará os métodos ->fetch() ou ->fetchAll() a partir deste retorno.
        return $stmt;
    }
}
?>