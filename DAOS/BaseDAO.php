<?php
class BaseDAO
{

    private $connection;

    public function __construct()
    {

    
        $connection_string = "mysql:host=localhost;dbname=tcc;port=3306";
        

        $db_user = "root";
        

        $db_pass = "";


        try {

            $this->connection = new PDO(
                $connection_string,
                $db_user,
                $db_pass
            );

            $this->connection->setAttribute(
                PDO::ATTR_ERRMODE,
                PDO::ERRMODE_EXCEPTION
            );

        } catch (PDOException $e) {

            die("Erro fatal de conexão com o banco de dados: " . $e->getMessage());
        }
    }


    public function executaComParametros($sql, $parametros) 
    {

        $stmt = $this->connection->prepare($sql);
        

        foreach ($parametros as $chave => $valor) {
            $stmt->bindValue($chave, $valor);
        }
        

        $stmt->execute();
        

        return $stmt;
    }

 
    public function executar($sql)
    {

        $stmt = $this->connection->prepare($sql);
        $stmt->execute();
        

        return $stmt;
    }
}
?>