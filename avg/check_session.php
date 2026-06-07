<?php
// Tăng thời gian sống của session - ĐẶT ĐẦU TIÊN, TRƯỚC MỌI THỨ
ini_set('session.gc_maxlifetime', 2592000);  // 30 ngày
ini_set('session.cookie_lifetime', 2592000); // 30 ngày

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Nếu chưa đăng nhập nhưng có cookie ghi nhớ -> tự động đăng nhập lại
if (!isset($_SESSION['user']) && isset($_COOKIE['remember_user']) && isset($_COOKIE['remember_pass'])) {
    $conn = new mysqli("localhost", "root", "", "avg_store");
    $conn->set_charset("utf8mb4");
    
    $username = $conn->real_escape_string($_COOKIE['remember_user']);
    $password = $conn->real_escape_string($_COOKIE['remember_pass']);
    
    $result = $conn->query("SELECT * FROM users WHERE username = '$username' AND password = '$password'");
    if ($result && $result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $_SESSION['user'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['user_id'] = $user['id'];
    }
    $conn->close();
}
?>