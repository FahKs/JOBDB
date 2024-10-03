<?php
class ConnectionDatabase
{
    // test
    public $servername = "localhost";
    public $username = "root";
    public $password = "";
    public $dbname = "datadb";
    public $conn;

    public function connect()
    {
        $this->conn = new mysqli($this->servername, $this->username, $this->password, $this->dbname);
        return $this->conn;
    }

    public function close($conn)
    {
        $conn->close();
    }

    public function isConnectionValid($conn)
    {
        return $conn->connect_error ? false : true;
    }

    public function executeQuery($conn, $sql)
    {
        
        return $conn->query($sql);
    }
}

