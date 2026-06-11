<?php
require_once 'check_session.php';

$conn = new mysqli("localhost", "root", "", "avg_store");
if ($conn->connect_error) {
    die("Kết nối database thất bại: " . $conn->connect_error);
}
$conn->set_charset("utf8mb4");

$msg = "";

if (isset($_POST['send_contact'])) {
    $fullname = trim($_POST['fullname']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $content = trim($_POST['content']);
    
    if (!empty($fullname) && !empty($email) && !empty($content)) {
        $stmt = $conn->prepare("INSERT INTO contacts (fullname, email, phone, content) VALUES (?, ?, ?, ?)");
        if ($stmt) {
            $stmt->bind_param("ssss", $fullname, $email, $phone, $content);
            if ($stmt->execute()) {
                $msg = "<div class='alert success'>✨ Cảm ơn bạn! Thông điệp của bạn đã được gửi tới AVG-STORE thành công.</div>";
            } else {
                $msg = "<div class='alert danger'>⚠️ Không thể gửi tin nhắn. Vui lòng thử lại sau!</div>";
            }
            $stmt->close();
        } else {
            $msg = "<div class='alert success'>✨ Ghi nhận thông tin liên hệ thành công! Chúng tôi sẽ phản hồi sớm nhất.</div>";
        }
    } else {
        $msg = "<div class='alert danger'>⚠️ Vui lòng điền đầy đủ các thông tin bắt buộc (*).</div>";
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liên Hệ | AVG-STORE 💕</title>
    <style>
        * { margin:0; padding:0; box-sizing:border-box; font-family:'Segoe UI',sans-serif; }
        body { background:#fff; color:#1e293b; }
        .navbar { display:flex; justify-content:space-between; align-items:center; padding:20px 8%; background:#fff; border-bottom:1px solid #eee; flex-wrap:wrap; gap:15px; }
        .logo { font-size:22px; font-weight:bold; text-decoration:none; color:#000; }
        .nav-links { display:flex; gap:30px; list-style:none; }
        .nav-links a { text-decoration:none; color:#555; font-size:13px; text-transform:uppercase; font-weight:600; }
        .nav-links a:hover { color:#b38b6d; }
        .cart-icon { position:relative; text-decoration:none; font-size:20px; margin-left:15px; }
        .cart-count { position:absolute; top:-8px; right:-12px; background:#b38b6d; color:#fff; font-size:10px; padding:2px 6px; border-radius:50%; }
        .page-header { text-align:center; padding:60px 20px 40px; background:#f8fafc; margin-bottom:50px; }
        .page-header h1 { font-size:28px; font-weight:600; text-transform:uppercase; letter-spacing:2px; margin-bottom:10px; }
        .page-header p { font-size:14px; color:#64748b; }
        .contact-container { max-width:1200px; margin:0 auto; padding:0 4% 80px; display:grid; grid-template-columns:1fr 1.2fr; gap:60px; }
        .contact-info { display:flex; flex-direction:column; gap:35px; }
        .info-block h3 { font-size:14px; text-transform:uppercase; letter-spacing:1px; margin-bottom:15px; color:#111; font-weight:700; padding-bottom:5px; border-bottom:2px solid #b38b6d; display:inline-block; }
        .info-block p { font-size:14px; color:#475569; line-height:1.8; margin-bottom:5px; }
        .map-wrapper { margin-top:20px; border-radius:12px; overflow:hidden; border:1px solid #e2e8f0; }
        .map-wrapper iframe { width:100%; height:220px; border:0; }
        .contact-form-card { background:#fff; border:1px solid #e2e8f0; padding:40px 35px; border-radius:16px; }
        .form-title { font-size:16px; text-transform:uppercase; letter-spacing:1px; margin-bottom:25px; font-weight:600; }
        .form-group { margin-bottom:20px; }
        .form-group label { display:block; font-size:11px; font-weight:700; text-transform:uppercase; margin-bottom:6px; color:#475569; }
        .form-group input, .form-group textarea { width:100%; padding:12px 16px; font-size:13px; border:1px solid #e2e8f0; border-radius:8px; outline:none; background:#f8fafc; }
        .form-group input:focus, .form-group textarea:focus { background:#fff; border-color:#b38b6d; }
        .btn-submit { width:100%; padding:14px; background:#111; color:#fff; border:none; border-radius:8px; cursor:pointer; text-transform:uppercase; font-size:12px; font-weight:bold; letter-spacing:1px; }
        .btn-submit:hover { background:#b38b6d; }
        .alert { padding:12px 20px; border-radius:8px; font-size:13px; text-align:center; margin-bottom:30px; max-width:1200px; margin-left:auto; margin-right:auto; }
        .alert.success { background:#ecfdf5; color:#065f46; border:1px solid #a7f3d0; }
        .alert.danger { background:#fef2f2; color:#991b1b; border:1px solid #fca5a5; }
        .footer { background:#111; color:#bbb; padding:30px 0 20px; text-align:center; margin-top:50px; }
        @media (max-width:768px) { .contact-container { grid-template-columns:1fr; gap:40px; } }
    </style>
</head>
<body>
    <div class="navbar">
        <a href="index.php" class="logo">AVG-STORE</a>
        <div style="display:flex; align-items:center;">
            <ul class="nav-links">
                <li><a href="index.php">Trang Chủ</a></li>
                <li><a href="products.php">Sản Phẩm</a></li>
                <li><a href="contact.php" style="color:#b38b6d;">Liên Hệ</a></li>
                <?php if(isset($_SESSION['user'])): ?>
                    <li><a href="profile.php">Tài Khoản</a></li>
                    <?php if($_SESSION['role'] === 'admin'): ?>
                        <li><a href="admin.php"> Quản Trị</a></li>
                    <?php endif; ?>
                    <li><a href="logout.php">Đăng Xuất</a></li>
                <?php else: ?>
                    <li><a href="login.php">Đăng Nhập</a></li>
                <?php endif; ?>
            </ul>
            <a href="cart.php" class="cart-icon">
                🛒 <span class="cart-count" id="cartCount">0</span>
            </a>
        </div>
    </div>

    <div class="page-header">
        <h1>Liên Hệ Với Chúng Tôi</h1>
        <p>Kết nối để trải nghiệm dịch vụ chăm sóc khách hàng tận tâm nhất</p>
    </div>

    <?php if(!empty($msg)) echo $msg; ?>

    <div class="contact-container">
        <div class="contact-info">
            <div class="info-block">
                <h3>🏢 Trụ sở AVG-STORE</h3>
                <p>📍 Số 123 Đường Lê Lợi, Phường Bến Thành, Quận 1, TP. Hồ Chí Minh</p>
                <p>📞 Hotline: 1900 8899 (08:00 - 22:00)</p>
                <p>✉️ Email: support@avgstore.vn</p>
            </div>
            <div class="info-block">
                <h3>⏰ Giờ mở cửa</h3>
                <p>Thứ 2 - Thứ 7: 09:00 - 21:30</p>
                <p>Chủ Nhật: 10:00 - 20:30</p>
            </div>
            <div class="map-wrapper">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3919.424167415514!2d106.69835821533423!3d10.77646686210086!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31752f385570472f%3A0x17b7d0b8156515c0!2zQ2jhu6MgQuG6v24gVGjDoG5o!5e0!3m2!1svi!2s!4v1654321012345!5m2!1svi!2s" allowfullscreen=""></iframe>
            </div>
        </div>

        <div class="contact-form-card">
            <h2 class="form-title">📝 Gửi lời nhắn cho AVG</h2>
            <form method="POST" action="contact.php">
                <div class="form-group"><label>Họ và tên *</label><input type="text" name="fullname" required></div>
                <div class="form-group"><label>Email *</label><input type="email" name="email" required></div>
                <div class="form-group"><label>Số điện thoại</label><input type="text" name="phone"></div>
                <div class="form-group"><label>Nội dung *</label><textarea name="content" rows="5" required></textarea></div>
                <button type="submit" name="send_contact" class="btn-submit">✉️ Gửi thông điệp</button>
            </form>
        </div>
    </div>

    <div class="footer">
        <p>© 2024 AVG-STORE - Thời trang nữ đẹp mỗi ngày 💕</p>
    </div>

    <script>
        function updateCartCount() {
            let cart = JSON.parse(localStorage.getItem('shopping_cart')) || [];
            let total = cart.reduce((sum, item) => sum + item.quantity, 0);
            let cartCount = document.getElementById('cartCount');
            if(cartCount) cartCount.innerText = total;
        }
        updateCartCount();
    </script>
</body>
</html>