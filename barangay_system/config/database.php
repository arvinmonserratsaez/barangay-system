<?php
$host     = "localhost";
$dbuser   = "root";
$dbpass   = "";
$dbname   = "barangay_system";

$conn = new mysqli($host, $dbuser, $dbpass, $dbname);

if ($conn->connect_error) {
    die("
    <div style='font-family:Arial;padding:40px;text-align:center;'>
        <h2 style='color:#c0392b;'>Database Connection Failed</h2>
        <p style='color:#555;margin-top:10px;'>Error: " . $conn->connect_error . "</p>
        <p style='color:#888;margin-top:6px;font-size:13px;'>Make sure XAMPP MySQL is running and the database <b>barangay_system</b> exists.</p>
    </div>");
}

$conn->set_charset("utf8mb4");
?>
