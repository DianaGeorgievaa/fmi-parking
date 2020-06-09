<?php
class Database
{

    private $host = "localhost";
    private $db_name = "fmi_parking";
    private $username = "root";
    private $password = "";
    public $conn;

    public function getConnection()
    {

        $this->conn = null;

        try
        {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password, array(
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
            ));

        }
        catch(PDOException $exception)
        {
            echo "Connection error: " . $exception->getMessage();
        }
        return $this->conn;
    }
}
?>