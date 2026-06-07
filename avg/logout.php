<?php
session_start();

// Xóa session
session_destroy();

// Xóa cookie ghi nhớ
setcookie('remember_user', '', time() - 3600, '/');
setcookie('remember_pass', '', time() - 3600, '/');

header("Location: login.php");
exit();
?>