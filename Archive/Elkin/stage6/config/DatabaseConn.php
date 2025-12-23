<?php
class DatabaseConn {
    private $credentials;
    private $conn;
    private static $instance;


    private function __construct() {

        $json = file_get_contents(__DIR__ . '/app.json');
        $this->credentials = json_decode($json, true);

        if (!$this->credentials || !isset($this->credentials['db'])) {
            throw new Exception("No se pudo cargar la configuración de la base de datos.");
        }

        $dbConfig = $this->credentials['db'];

        $username = $dbConfig['user'];
        $password = $dbConfig['password'];
        $database = $dbConfig['dbname'];
        $host     = $dbConfig['host'];
        $port     = $dbConfig['port'] ?? 3306;

        try {
            $this->conn = new PDO(
                "mysql:host={$host};port={$port};dbname={$database}",
                $username,
                $password
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, true);

        } catch (PDOException $e) {
            exit('Error connecting to database: ' . $e->getMessage());
        }
    }


    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new DatabaseConn();
        }
        return self::$instance;
    }


    public function getConnection() {
        return $this->conn;
    }

}