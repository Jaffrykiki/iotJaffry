<!DOCTYPE html>
<html>
<head>
    <title>ระบบรดน้ำอัตโนมัติ</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
    <style>
    form {
        text-align: center;
    }

    form input[type="submit"] {
        margin-top: 15px;
    }
</style>

</head>
<body>
    <h1>ระบบรดน้ำอัตโนมัติ</h1>
    <form action="process.php" method="POST">

        <label for="temperature">ช่วงอุณหภูมิ (°C):</label>
        <input type="text" name="temperature" id="temperature" required placeholder="กรุณากรอกช่วงอุณหภูมิ"><br>

        <label for="humidity">ช่วงความชื้น (%):</label>
        <input type="text" name="humidity" id="humidity" required placeholder="กรุณากรอกช่วงความชิ้น"><br>

        <input type="submit" value="บันทึกการตั้งค่า">
    </form>

    <h2>ค่าข้อมูลปัจจุบัน:</h2>
    <?php
    // เชื่อมต่อกับฐานข้อมูลโดยใช้ไฟล์ db_connection.php
    require_once("db_connection.php");

    // เตรียมคำสั่ง SQL เพื่อดึงข้อมูล
    $sql = "SELECT * FROM settings ORDER BY created_at DESC LIMIT 1";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // แสดงข้อมูลที่ดึงมาจากฐานข้อมูล
        while ($row = $result->fetch_assoc()) {
            echo "<p>อุณหภูมิ: " . $row["temperature"] . " °C</p>";
            echo "<p>ความชื้น: " . $row["humidity"] . " %</p>";
            echo "<p>เวลาบันทึก: " . $row["updated_at"] . "</p>";
        }
    } else {
        echo "ไม่พบข้อมูลในฐานข้อมูล";
    }

    // ปิดการเชื่อมต่อกับฐานข้อมูล
    $conn->close();
    ?>
</body>
</html>
