<?php
$servername = "localhost";  // เปลี่ยนตามการตั้งค่าของเซิร์ฟเวอร์ MySQL
$username = "root";  // เปลี่ยนตามชื่อผู้ใช้ของคุณ
$password = "";  // เปลี่ยนตามรหัสผ่านของคุณ
$dbname = "automatic_watering";  // เปลี่ยนตามชื่อฐานข้อมูลของคุณ

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("การเชื่อมต่อกับฐานข้อมูลล้มเหลว: " . $conn->connect_error);
}
?>
