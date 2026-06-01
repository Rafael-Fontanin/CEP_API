<?php
class Conexao {
    private $host     = "localhost";
    private $db_name  = "pwiiib";       // mesmo banco do modelo do professor
    private $username = "root";
    private $password = "";
    public  $connection;

    public function conectar() {
        $this->connection = null;

        try {
            $dns = "mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";charset=utf8";

            $this->connection = new PDO($dns, $this->username, $this->password);

            $this->connection->setAttribute(PDO::ATTR_ERRMODE,            PDO::ERRMODE_EXCEPTION);
            $this->connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

        } catch (PDOException $exception) {
            http_response_code(500);
            echo json_encode(['erro' => 'Erro de conexão: ' . $exception->getMessage()]);
            exit;
        }

        return $this->connection;
    }
}
