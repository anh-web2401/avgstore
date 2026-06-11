<?php
session_start();
$conn = new mysqli("localhost", "root", "", "avg_store");
$conn->set_charset("utf8mb4");
$msg = "";
$error = "";
$step = $_GET['step'] ?? 'request';

// Bước 1: Gửi yêu cầu
if(isset($_POST['request_reset'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    
    $user = $conn->query("SELECT * FROM users WHERE username = '$username' AND email = '$email'")->fetch_assoc();
    if($user) {
        $token = bin2hex(random_bytes(32));
        $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));
        $conn->query("UPDATE users SET reset_token = '$token', reset_expires = '$expires' WHERE id = {$user['id']}");
        header("Location: forgot_password.php?step=reset&token=$token");
        exit();
    } else {
        $error = "❌ Không tìm thấy tài khoản với thông tin này!";
    }
}

// Bước 2: Đặt lại mật khẩu
if(isset($_POST['reset_password'])) {
    $token = $_POST['token'];
    $new_pass = $_POST['new_password'];
    $confirm = $_POST['confirm_password'];
    
    if($new_pass != $confirm) {
        $error = "❌ Mật khẩu xác nhận không khớp!";
    } elseif(strlen($new_pass) < 4) {
        $error = "❌ Mật khẩu phải có ít nhất 4 ký tự!";
    } else {
        $user = $conn->query("SELECT * FROM users WHERE reset_token = '$token' AND reset_expires > NOW()")->fetch_assoc();
        if($user) {
            $conn->query("UPDATE users SET password = '$new_pass', reset_token = NULL, reset_expires = NULL WHERE id = {$user['id']}");
            $msg = "✅ Đặt lại mật khẩu thành công! <a href='login.php'>Đăng nhập ngay</a>";
        } else {
            $error = "❌ Token không hợp lệ hoặc đã hết hạn!";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quên mật khẩu | AVG-STORE</title>
    <style>
        * { margin:0; padding:0; box-sizing:border-box; font-family:'Segoe UI',sans-serif; }
        body { background:#f8fafc; display:flex; justify-content:center; align-items:center; min-height:100vh; }
        .forgot-box { max-width:450px; width:90%; background:#fff; border-radius:16px; padding:40px; box-shadow:0 10px 30px rgba(0,0,0,0.05); border:1px solid #eee; }
        h2 { margin-bottom:20px; font-size:24px; text-align:center; }
        .form-group { margin-bottom:20px; }
        label { display:block; font-size:12px; font-weight:600; margin-bottom:8px; text-transform:uppercase; }
        input { width:100%; padding:12px; border:1px solid #ddd; border-radius:8px; font-size:14px; }
        .btn { width:100%; padding:12px; background:#111; color:#fff; border:none; border-radius:8px; font-weight:600; cursor:pointer; }
        .btn:hover { background:#b38b6d; }
        .alert { padding:12px; border-radius:8px; margin-bottom:20px; text-align:center; }
        .alert-success { background:#d4edda; color:#155724; }
        .alert-error { background:#f8d7da; color:#721c24; }
        .back-link { text-align:center; margin-top:20px; }
        .back-link a { color:#666; text-decoration:none; }
    </style>
</head>
<body>
    <div class="forgot-box">
        <h2>🔐 Quên mật khẩu</h2>
        
        <?php if($msg): ?>
            <div class="alert alert-success"><?php echo $msg; ?></div>
        <?php endif; ?>
        <?php if($error): ?>
            <div class="alert alert-error"><?php echo $error; ?></div>
        <?php endif; ?>

        <?php if($step == 'request'): ?>
            <form method="POST">
                <div class="form-group"><label>Tên đăng nhập</label><input type="text" name="username" required></div>
                <div class="form-group"><label>Email đã đăng ký</label><input type="email" name="email" required></div>
                <button type="submit" name="request_reset" class="btn">📧 Gửi yêu cầu</button>
            </form>
        <?php elseif($step == 'reset' && isset($_GET['token'])): ?>
            <form method="POST">
                <input type="hidden" name="token" value="<?php echo $_GET['token']; ?>">
                <div class="form-group"><label>Mật khẩu mới</label><input type="password" name="new_password" required></div>
                <div class="form-group"><label>Xác nhận mật khẩu</label><input type="password" name="confirm_password" required></div>
                <button type="submit" name="reset_password" class="btn">🔄 Đặt lại mật khẩu</button>
            </form>
        <?php endif; ?>
        
        <div class="back-link"><a href="login.php">← Quay lại đăng nhập</a></div>
    </div>
</body>
</html>