<?php
class ConnectionDatabase
{
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

คำอธิบาย

class ConnectionDatabase
{
    // ประกาศตัวแปรสาธารณะ (public) ที่ใช้เก็บค่าการเชื่อมต่อฐานข้อมูล
    public $servername = "localhost";  // ชื่อเซิร์ฟเวอร์ฐานข้อมูล (เช่น localhost เมื่อเชื่อมต่อในเครื่อง)
    public $username = "root";  // ชื่อผู้ใช้สำหรับเข้าถึงฐานข้อมูล (ในเครื่องมักใช้ "root")
    public $password = "";  // รหัสผ่านของผู้ใช้ (ค่าเริ่มต้นสำหรับ root มักจะเป็นค่าว่าง)
    public $dbname = "datadb";  // ชื่อฐานข้อมูลที่ต้องการเชื่อมต่อ
    public $conn;  // ตัวแปรที่เก็บการเชื่อมต่อฐานข้อมูล (ใช้เก็บ object ของ mysqli)

    // ฟังก์ชัน connect() ทำหน้าที่สร้างการเชื่อมต่อกับฐานข้อมูล
    public function connect()
    {
        // สร้างการเชื่อมต่อกับฐานข้อมูลโดยใช้ class mysqli ของ PHP
        // ค่า $this หมายถึงการเข้าถึงคุณสมบัติของคลาสนี้ เช่น $this->servername จะอ้างอิงถึง 'localhost'
        $this->conn = new mysqli($this->servername, $this->username, $this->password, $this->dbname);
        
        // คืนค่าการเชื่อมต่อ (object mysqli) เพื่อให้ใช้ในการทำงานกับฐานข้อมูลต่อไป
        return $this->conn;
    }

    // ฟังก์ชัน close() ทำหน้าที่ปิดการเชื่อมต่อฐานข้อมูล
    public function close($conn)
    {
        // เรียกใช้ฟังก์ชัน close() ของ object mysqli เพื่อตัดการเชื่อมต่อกับฐานข้อมูล
        $conn->close();
    }

    // ฟังก์ชัน isConnectionValid() ใช้เพื่อตรวจสอบว่าการเชื่อมต่อกับฐานข้อมูลสำเร็จหรือไม่
    public function isConnectionValid($conn)
    {
        // ตรวจสอบว่าเกิดข้อผิดพลาดในการเชื่อมต่อหรือไม่ (เช็คค่า $conn->connect_error)
        // ถ้าไม่มีข้อผิดพลาดให้คืนค่า true (การเชื่อมต่อสำเร็จ) ถ้ามีข้อผิดพลาดจะคืนค่า false
        return $conn->connect_error ? false : true;
    }

    // ฟังก์ชัน executeQuery() ใช้เพื่อรันคำสั่ง SQL
    public function executeQuery($conn, $sql)
    {
        // รันคำสั่ง SQL ที่ถูกส่งเข้ามาในตัวแปร $sql โดยใช้ฟังก์ชัน query() ของ mysqli
        // คืนค่าผลลัพธ์ของคำสั่ง SQL นั้น ๆ (ผลลัพธ์อาจเป็น true/false หรือชุดข้อมูล)
        return $conn->query($sql);
    }
}

