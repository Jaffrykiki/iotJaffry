<?php

// เชื่อมต่อกับฐานข้อมูลโดยใช้ไฟล์ db_connection.php
require_once("db_connection.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $humidity = $_POST["humidity"];

    // เตรียมคำสั่ง SQL เพื่ออัปเดตข้อมูลและฟิลด์ updated_at
    $sql = "UPDATE settings SET humidity = $humidity, updated_at = CURRENT_TIMESTAMP ORDER BY created_at DESC LIMIT 1";

    if ($conn->query($sql) === TRUE) {
        echo "อัปเดตข้อมูลสำเร็จ";
    } else {
        echo "เกิดข้อผิดพลาดในการอัปเดตข้อมูล: " . $conn->error;
    }

    // ปิดการเชื่อมต่อกับฐานข้อมูล
    $conn->close();

    // เมื่อทำเสร็จสิ้น สามารถ redirect กลับไปยังหน้าหลักได้
    header("Location: index.php");
    exit;
}
?>
