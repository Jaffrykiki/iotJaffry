<?php
header("Content-type: application/json");
include("db_connection.php");

// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    $data = array("error" => "การเชื่อมต่อล้มเหลว: " . $conn->connect_error);
    echo json_encode($data);
    exit;
}

// สร้างคำสั่ง SQL เพื่อดึงข้อมูล
$sql = "SELECT id,humidity FROM settings";
$result = $conn->query($sql);

if ($result) {
    $data = array();

    while ($row = $result->fetch_assoc()) {
        $data[] = array(
            "id" => $row["id"],
            "humidity" => $row["humidity"]
        );
    }

    echo json_encode($data);
} else {
    $data = array("error" => "เกิดข้อผิดพลาดในการสอบถามฐานข้อมูล");
    echo json_encode($data);
}

$conn->close();
?>
