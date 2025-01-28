<?php
class DatabaseConnection {
    private $servername = "localhost";
    private $username = "root";
    private $password = "";  
    private $db_name = "olive"; 
    private $conn;

   
    public function __construct($servername = "localhost", $username = "root", $password = "", $db_name = "olive") {
        $this->servername = $servername;
        $this->username = $username;
        $this->password = $password;
        $this->db_name = $db_name;
    }

   
    public function startConnection() {
        if ($this->conn === null) {
            try {
                $this->conn = new PDO("mysql:host=$this->servername;dbname=$this->db_name", $this->username, $this->password);
                
               
                $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                echo "Lidhja me databazën deshtoi: " . $e->getMessage();
                return null;
            }
        }

        return $this->conn;
    }

    public function closeConnection() {
        $this->conn = null;
    }
}

?>
