<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$conn = new mysqli("localhost", "root", "", "avg_store");
if ($conn->connect_error) { 
    die("Kết nối database thất bại: " . $conn->connect_error); 
}
$conn->set_charset("utf8mb4");
?>