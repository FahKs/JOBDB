<?php
class ConnectionDatabase
{
    public $servername = "localhost";
    public $username = "root";
    public $password = "";
    public $dbname = "datadb";
    public $conn;

    // ฟังก์ชันเชื่อมต่อฐานข้อมูล
    public function connect()
    {
        $this->conn = new mysqli($this->servername, $this->username, $this->password, $this->dbname);
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
        return $this->conn;
    }

    // ฟังก์ชันปิดการเชื่อมต่อฐานข้อมูล
    public function close()
    {
        if ($this->conn) {
            $this->conn->close();
        }
    }

    // ฟังก์ชันตรวจสอบการเชื่อมต่อ
    public function isConnectionValid()
    {
        return !$this->conn->connect_error;
    }

    // ฟังก์ชันรันคำสั่ง SQL
    public function executeQuery($sql)
    {
        $result = $this->conn->query($sql);
        if (!$result) {
            die("Query failed: " . $this->conn->error);
        }
        return $result;
    }
}
?>
