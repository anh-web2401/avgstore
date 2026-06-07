<?php
require_once 'check_session.php';

$conn = new mysqli("localhost", "root", "", "avg_store");
if ($conn->connect_error) {
    die("Kết nối database thất bại: " . $conn->connect_error);
}
$conn->set_charset("utf8mb4");

$error_msg = "";
$success_msg = "";

// Nếu đã đăng nhập rồi thì chuyển hướng luôn
if (isset($_SESSION['user'])) {
    if ($_SESSION['role'] === 'admin') {
        header("Location: admin.php");
    } else {
        header("Location: index.php");
    }
    exit();
}

// XỬ LÝ ĐĂNG NHẬP
if (isset($_POST['login_submit'])) {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $remember = isset($_POST['remember']) ? true : false;
    
    if (!empty($username) && !empty($password)) {
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
        $stmt->bind_param("ss", $username, $password);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            $_SESSION['user'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['user_id'] = $user['id'];
            
            // Nếu chọn "Ghi nhớ", set cookie 30 ngày
            if ($remember) {
                setcookie('remember_user', $username, time() + 2592000, '/');
                setcookie('remember_pass', $password, time() + 2592000, '/');
            }
            
            if ($user['role'] === 'admin') {
                header("Location: admin.php");
            } else {
                header("Location: index.php");
            }
            exit();
        } else {
            $error_msg = "❌ Tài khoản hoặc mật khẩu không chính xác!";
        }
        $stmt->close();
    } else {
        $error_msg = "❌ Vui lòng điền đầy đủ thông tin đăng nhập.";
    }
}

// XỬ LÝ ĐĂNG KÝ
if (isset($_POST['register_submit'])) {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $email = trim($_POST['email']);
    
    if (!empty($username) && !empty($password)) {
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();
        
        if ($stmt->num_rows > 0) {
            $error_msg = "❌ Tên tài khoản này đã tồn tại trên hệ thống!";
        } else {
            $stmt->close();
            $role = 'customer';
            $insert_stmt = $conn->prepare("INSERT INTO users (username, password, role, email) VALUES (?, ?, ?, ?)");
            $insert_stmt->bind_param("ssss", $username, $password, $role, $email);
            if ($insert_stmt->execute()) {
                $success_msg = "✅ Đăng ký thành công! Hãy chuyển sang tab Đăng nhập.";
            } else {
                $error_msg = "❌ Có lỗi xảy ra, vui lòng thử lại sau.";
            }
            $insert_stmt->close();
        }
    } else {
        $error_msg = "❌ Vui lòng điền đầy đủ thông tin đăng ký.";
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tài Khoản | AVG-STORE 💕</title>
    <style>
        :root {
            --primary: #111111;
            --accent: #b38b6d;
            --text-dark: #1e293b;
            --text-muted: #64748b;
            --bg-light: #f8fafc;
            --border: #e2e8f0;
            --radius: 12px;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', system-ui, sans-serif; }
        body { background: #f8fafc; color: var(--text-dark); display: flex; flex-direction: column; min-height: 100vh; }
        .header-brand { text-align: center; padding: 40px 0 20px; }
        .header-brand a { font-size: 26px; font-weight: 800; letter-spacing: 3px; text-decoration: none; color: var(--primary); }
        .auth-container { max-width: 450px; width: 90%; margin: 20px auto 60px; background: #ffffff; border: 1px solid var(--border); border-radius: var(--radius); box-shadow: 0 20px 40px rgba(0,0,0,0.04); overflow: hidden; padding: 35px 30px; }
        .tab-navigation { display: flex; border-bottom: 2px solid var(--border); margin-bottom: 30px; gap: 20px; }
        .tab-btn { background: none; border: none; padding: 10px 0; font-size: 14px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: var(--text-muted); cursor: pointer; position: relative; transition: color 0.2s; }
        .tab-btn:hover { color: var(--primary); }
        .tab-btn.active { color: var(--primary); }
        .tab-btn.active::after { content: ''; position: absolute; bottom: -2px; left: 0; width: 100%; height: 2px; background: var(--primary); }
        .auth-form { display: none; animation: fadeIn 0.4s ease; }
        .auth-form.active { display: block; }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px; color: #475569; }
        .form-group input { width: 100%; padding: 12px 14px; font-size: 13px; border: 1px solid var(--border); border-radius: 8px; outline: none; background: var(--bg-light); transition: all 0.2s; }
        .form-group input:focus { background: #fff; border-color: var(--accent); box-shadow: 0 0 0 3px rgba(179, 139, 109, 0.1); }
        .checkbox-label { display: flex !important; align-items: center; gap: 8px; cursor: pointer; }
        .checkbox-label input { width: 16px; height: 16px; margin: 0; }
        .checkbox-label span { font-size: 12px; font-weight: normal; text-transform: none; }
        .btn-submit { width: 100%; padding: 13px; background: var(--primary); color: #fff; border: none; border-radius: 8px; cursor: pointer; text-transform: uppercase; font-size: 12px; font-weight: 700; letter-spacing: 1px; transition: all 0.2s; margin-top: 10px; }
        .btn-submit:hover { background: var(--accent); transform: translateY(-1px); }
        .alert { padding: 12px; border-radius: 8px; font-size: 13px; text-align: center; margin-bottom: 20px; font-weight: 500; }
        .alert.danger { background: #fef2f2; color: #991b1b; border: 1px solid #fca5a5; }
        .alert.success { background: #ecfdf5; color: #065f46; border: 1px solid #a7f3d0; }
        .back-home { text-align: center; margin-top: 15px; }
        .back-home a { font-size: 13px; color: var(--text-muted); text-decoration: none; transition: color 0.2s; }
        .back-home a:hover { color: var(--accent); text-decoration: underline; }
        .forgot-link { text-align: right; margin-top: 10px; }
        .forgot-link a { font-size: 11px; color: #999; text-decoration: none; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(5px); } to { opacity: 1; transform: translateY(0); } }
    </style>
</head>
<body>

    <div class="header-brand">
        <a href="index.php">AVG-STORE</a>
    </div>

    <div class="auth-container">
        
        <?php if(!empty($error_msg)): ?>
            <div class="alert danger"><?php echo $error_msg; ?></div>
        <?php endif; ?>
        <?php if(!empty($success_msg)): ?>
            <div class="alert success"><?php echo $success_msg; ?></div>
        <?php endif; ?>

        <div class="tab-navigation">
            <button class="tab-btn active" onclick="switchTab('login-tab')">🔐 Đăng Nhập</button>
            <button class="tab-btn" onclick="switchTab('register-tab')">📝 Đăng Ký</button>
        </div>

        <div id="login-tab" class="auth-form active">
            <form method="POST" action="login.php">
                <div class="form-group">
                    <label>Tên đăng nhập</label>
                    <input type="text" name="username" placeholder="Nhập tài khoản..." required>
                </div>
                <div class="form-group">
                    <label>Mật khẩu</label>
                    <input type="password" name="password" placeholder="••••••••" required>
                </div>
                <div class="form-group">
                    <label class="checkbox-label">
                        <input type="checkbox" name="remember"> 
                        <span>💕 Ghi nhớ đăng nhập (30 ngày)</span>
                    </label>
                </div>
                <button type="submit" name="login_submit" class="btn-submit">Đăng Nhập</button>
                <div class="forgot-link"><a href="forgot_password.php">Quên mật khẩu?</a></div>
            </form>
        </div>

        <div id="register-tab" class="auth-form">
            <form method="POST" action="login.php">
                <div class="form-group">
                    <label>Tên tài khoản *</label>
                    <input type="text" name="username" placeholder="Ví dụ: khachhang123" required>
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" placeholder="example@gmail.com">
                </div>
                <div class="form-group">
                    <label>Mật khẩu *</label>
                    <input type="password" name="password" placeholder="Nhập mật khẩu..." required>
                </div>
                <button type="submit" name="register_submit" class="btn-submit">Tạo Tài Khoản</button>
            </form>
        </div>

        <div class="back-home">
            <a href="index.php">← Quay về Trang Chủ</a>
        </div>
    </div>

    <script>
        function switchTab(tabId) {
            const forms = document.querySelectorAll('.auth-form');
            forms.forEach(form => form.classList.remove('active'));
            const buttons = document.querySelectorAll('.tab-btn');
            buttons.forEach(btn => btn.classList.remove('active'));
            document.getElementById(tabId).classList.add('active');
            event.currentTarget.classList.add('active');
        }
    </script>
</body>
</html>