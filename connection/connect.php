<?php
class DatabaseConnection
{
    private $servername;
    private $username;
    private $password;
    private $db_name;
    private $db_port;
    private $conn;
    private $options;

    public static function getDefaultConfig()
    {
        return [
            'host' => 'localhost',
            'username' => 'root',
            'password' => '',
            'database' => 'olive',
            'port' => 3306
        ];
    }

    public function __construct($config = null)
    {
       
        $config = $config ?? self::getDefaultConfig();

        
        $this->servername = $config['host'] ?? '127.0.0.1';
        $this->username = $config['username'] ?? 'root';
        $this->password = $config['password'] ?? 'oliveroot';
        $this->db_name = $config['database'] ?? 'olive';
        $this->db_port = $config['port'] ?? 3306;

       
        $this->options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_TIMEOUT => 5,
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci"
        ];
    }

    public function startConnection()
    {
        if ($this->conn === null) {
            try {
                $dsn = sprintf(
                    "mysql:host=%s;port=%d;dbname=%s;charset=utf8mb4",
                    $this->servername,
                    $this->db_port,
                    $this->db_name
                );

                $this->conn = new PDO($dsn, $this->username, $this->password, $this->options);
                return $this->conn;
            } catch (PDOException $e) {
                
                error_log("Database connection failed: " . $e->getMessage());
                throw new Exception("Database connection failed. Please check the logs or contact support.");
            }
        }
        return $this->conn;
    }

    public function closeConnection()
    {
        $this->conn = null;
    }

    public function getConnection()
    {
        return $this->conn;
    }
}
