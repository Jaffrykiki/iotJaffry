<!DOCTYPE html>
<html>
<head>
    <title>ระบบรดน้ำอัตโนมัติ</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
    <style>
    form {
        text-align: center;
    }

     form input[type="text"] {
        width: 100%;
        padding: 5px;
        border: 1px solid #ccc;
        border-radius: 5px;
    }
    form label {
        display: block; /* แสดง label แบบ block เพื่อให้มีขอบข้อความแตกแยก */
        margin: 10px 0; /* เพิ่มระยะห่างขอบข้อความข้างบนและด้านล่าง */
    }
    form input[type="submit"] {
        margin-top: 15px;
        background-color: #4CAF50; /* สีพื้นหลังปุ่ม */
        color: white; /* สีข้อความบนปุ่ม */
        border: none;
        padding: 15px 20px;
        border-radius: 5px;
        cursor: pointer;
    }

    h1 {
        text-align: center;
        background-color: #333; /* สีพื้นหลังของหัวข้อ */
        color: white; /* สีข้อความของหัวข้อ */
        padding: 20px;
    }

    h2 {
        text-align: center;
    }

</style>

</head>
<body>
    <h1>ระบบรดน้ำอัตโนมัติ</h1>
    <form action="process.php" method="POST">

        <label for="humidity">ช่วงความชื้น (VWC):</label>
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
            echo "<p>ความชื้น: " . $row["humidity"] . " VWC</p>";
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
