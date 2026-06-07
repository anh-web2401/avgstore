<?php
require_once 'check_session.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "avg_store");
$conn->set_charset("utf8mb4");

$user_id = $_SESSION['user_id'] ?? 0;
$msg = "";
$error = "";

$user = $conn->query("SELECT * FROM users WHERE username = '{$_SESSION['user']}'")->fetch_assoc();
if($user) $user_id = $user['id'];
$_SESSION['user_id'] = $user_id;

if(isset($_POST['change_password'])) {
    $old = $_POST['old_password'];
    $new = $_POST['new_password'];
    $confirm = $_POST['confirm_password'];
    
    if($user['password'] != $old) {
        $error = "❌ Mật khẩu cũ không đúng!";
    } elseif($new != $confirm) {
        $error = "❌ Mật khẩu mới xác nhận không khớp!";
    } elseif(strlen($new) < 4) {
        $error = "❌ Mật khẩu phải có ít nhất 4 ký tự!";
    } else {
        $conn->query("UPDATE users SET password = '$new' WHERE id = $user_id");
        $msg = "✅ Đổi mật khẩu thành công!";
    }
}

if(isset($_POST['update_email'])) {
    $email = $_POST['email'];
    $conn->query("UPDATE users SET email = '$email' WHERE id = $user_id");
    $msg = "✅ Cập nhật email thành công!";
    $user['email'] = $email;
}

if(isset($_POST['add_address'])) {
    $fullname = $_POST['fullname'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $is_default = isset($_POST['is_default']) ? 1 : 0;
    
    if($is_default) {
        $conn->query("UPDATE user_addresses SET is_default = 0 WHERE user_id = $user_id");
    }
    
    $conn->query("INSERT INTO user_addresses (user_id, fullname, phone, address, is_default) 
                  VALUES ($user_id, '$fullname', '$phone', '$address', $is_default)");
    $msg = "✅ Thêm địa chỉ thành công!";
}

if(isset($_GET['delete_addr'])) {
    $addr_id = intval($_GET['delete_addr']);
    $conn->query("DELETE FROM user_addresses WHERE id = $addr_id AND user_id = $user_id");
    header("Location: profile.php");
    exit();
}

if(isset($_GET['set_default'])) {
    $addr_id = intval($_GET['set_default']);
    $conn->query("UPDATE user_addresses SET is_default = 0 WHERE user_id = $user_id");
    $conn->query("UPDATE user_addresses SET is_default = 1 WHERE id = $addr_id AND user_id = $user_id");
    header("Location: profile.php");
    exit();
}

$addresses = $conn->query("SELECT * FROM user_addresses WHERE user_id = $user_id ORDER BY is_default DESC, id DESC");
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Tài khoản của tôi | AVG-STORE 💕</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', sans-serif; }
        .navbar { display: flex; justify-content: space-between; align-items: center; padding: 20px 8%; background: #fff; border-bottom: 1px solid #eee; flex-wrap: wrap; gap: 15px; }
        .logo { font-size: 22px; font-weight: bold; text-decoration: none; color: #000; }
        .nav-links { display: flex; gap: 30px; list-style: none; }
        .nav-links a { text-decoration: none; color: #555; font-size: 13px; text-transform: uppercase; font-weight: 600; }
        .nav-links a:hover { color: #b38b6d; }
        .cart-icon { position: relative; text-decoration: none; font-size: 20px; margin-left: 15px; }
        .cart-count { position: absolute; top: -8px; right: -12px; background: #b38b6d; color: #fff; font-size: 10px; padding: 2px 6px; border-radius: 50%; }
        .profile-container { max-width: 1200px; margin: 40px auto; padding: 0 20px; display: flex; gap: 30px; flex-wrap: wrap; }
        .profile-sidebar { width: 280px; background: #fff; border: 1px solid #eee; border-radius: 12px; padding: 20px; height: fit-content; }
        .profile-sidebar h3 { font-size: 16px; margin-bottom: 20px; padding-bottom: 10px; border-bottom: 2px solid #b38b6d; }
        .profile-sidebar a { display: block; padding: 12px 15px; color: #555; text-decoration: none; font-size: 14px; border-radius: 8px; margin-bottom: 5px; }
        .profile-sidebar a:hover, .profile-sidebar a.active { background: #f5f5f5; color: #b38b6d; }
        .profile-content { flex: 1; background: #fff; border: 1px solid #eee; border-radius: 12px; padding: 30px; }
        .section-title { font-size: 20px; margin-bottom: 25px; padding-bottom: 10px; border-bottom: 1px solid #eee; }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; font-size: 13px; font-weight: 600; margin-bottom: 8px; color: #333; }
        .form-group input, .form-group textarea, .form-group select { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px; }
        .btn { padding: 12px 25px; background: #111; color: #fff; border: none; border-radius: 8px; cursor: pointer; font-size: 13px; font-weight: 600; }
        .btn:hover { background: #b38b6d; }
        .address-card { border: 1px solid #eee; border-radius: 12px; padding: 15px; margin-bottom: 15px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px; }
        .address-card.default { background: #f9f9f9; border-color: #b38b6d; }
        .badge-default { background: #b38b6d; color: #fff; padding: 2px 8px; border-radius: 20px; font-size: 10px; margin-left: 10px; }
        .alert { padding: 12px 20px; border-radius: 8px; margin-bottom: 20px; }
        .alert-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .alert-error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        table { width: 100%; border-collapse: collapse; }
        table th, table td { padding: 12px; border-bottom: 1px solid #eee; text-align: left; }
        @media (max-width: 768px) { .profile-container { flex-direction: column; } .profile-sidebar { width: 100%; } }
    </style>
</head>
<body>
    <div class="navbar">
        <a href="index.php" class="logo">AVG-STORE</a>
        <div style="display:flex; align-items:center;">
            <ul class="nav-links">
                <li><a href="index.php">Trang Chủ</a></li>
                <li><a href="products.php">Sản Phẩm</a></li>
                <li><a href="contact.php">Liên Hệ</a></li>
                <li><a href="profile.php" style="color:#b38b6d;">Tài Khoản</a></li>
                <?php if($_SESSION['role'] === 'admin'): ?>
                    <li><a href="admin.php"> Quản Trị</a></li>
                <?php endif; ?>
                <li><a href="logout.php">Đăng Xuất</a></li>
            </ul>
            <a href="cart.php" class="cart-icon">
                🛒 <span class="cart-count" id="cartCount">0</span>
            </a>
        </div>
    </div>

    <div class="profile-container">
        <div class="profile-sidebar">
            <h3>👤 <?php echo htmlspecialchars($_SESSION['user']); ?></h3>
            <a href="profile.php" class="<?php echo (!isset($_GET['tab']) || $_GET['tab']=='info') ? 'active' : ''; ?>">📋 Thông tin</a>
            <a href="profile.php?tab=address" class="<?php echo ($_GET['tab']=='address') ? 'active' : ''; ?>">📍 Sổ địa chỉ</a>
            <a href="profile.php?tab=orders" class="<?php echo ($_GET['tab']=='orders') ? 'active' : ''; ?>">📦 Đơn hàng</a>
            <a href="profile.php?tab=security" class="<?php echo ($_GET['tab']=='security') ? 'active' : ''; ?>">🔒 Bảo mật</a>
        </div>

        <div class="profile-content">
            <?php if($msg): ?>
                <div class="alert alert-success"><?php echo $msg; ?></div>
            <?php endif; ?>
            <?php if($error): ?>
                <div class="alert alert-error"><?php echo $error; ?></div>
            <?php endif; ?>

            <?php $tab = $_GET['tab'] ?? 'info'; ?>

            <?php if($tab == 'info'): ?>
                <h2 class="section-title">📋 Thông tin cá nhân</h2>
                <form method="POST">
                    <div class="form-group">
                        <label>Tên đăng nhập</label>
                        <input type="text" value="<?php echo htmlspecialchars($user['username']); ?>" disabled>
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>">
                    </div>
                    <button type="submit" name="update_email" class="btn">💾 Cập nhật email</button>
                </form>

            <?php elseif($tab == 'address'): ?>
                <h2 class="section-title">📍 Sổ địa chỉ</h2>
                <button onclick="showAddAddress()" class="btn" style="margin-bottom: 20px;">+ Thêm địa chỉ mới</button>
                
                <div id="addressForm" style="display:none; background:#f9f9f9; padding:20px; border-radius:12px; margin-bottom:20px;">
                    <form method="POST">
                        <div class="form-group"><label>Họ tên người nhận</label><input type="text" name="fullname" required></div>
                        <div class="form-group"><label>Số điện thoại</label><input type="text" name="phone" required></div>
                        <div class="form-group"><label>Địa chỉ chi tiết</label><textarea name="address" rows="2" required></textarea></div>
                        <div class="form-group"><label><input type="checkbox" name="is_default"> Đặt làm địa chỉ mặc định</label></div>
                        <button type="submit" name="add_address" class="btn">💾 Lưu địa chỉ</button>
                        <button type="button" onclick="hideAddAddress()" class="btn" style="background:#ccc;">Hủy</button>
                    </form>
                </div>

                <?php while($addr = $addresses->fetch_assoc()): ?>
                    <div class="address-card <?php echo $addr['is_default'] ? 'default' : ''; ?>">
                        <div>
                            <strong><?php echo htmlspecialchars($addr['fullname']); ?></strong>
                            <?php if($addr['is_default']): ?><span class="badge-default">Mặc định</span><?php endif; ?>
                            <p>📞 <?php echo htmlspecialchars($addr['phone']); ?></p>
                            <p>📍 <?php echo htmlspecialchars($addr['address']); ?></p>
                        </div>
                        <div>
                            <?php if(!$addr['is_default']): ?>
                                <a href="?tab=address&set_default=<?php echo $addr['id']; ?>" class="btn" style="padding:5px 12px; font-size:12px;">⭐ Đặt mặc định</a>
                                <a href="?tab=address&delete_addr=<?php echo $addr['id']; ?>" class="btn" style="padding:5px 12px; font-size:12px; background:#dc3545;" onclick="return confirm('Xóa địa chỉ này?')">🗑️ Xóa</a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endwhile; ?>

            <?php elseif($tab == 'orders'): ?>
                <h2 class="section-title">📦 Lịch sử đơn hàng</h2>
                <?php
                $orders = $conn->query("SELECT * FROM orders WHERE user_id = $user_id ORDER BY id DESC");
                if($orders->num_rows > 0):
                ?>
                <table>
                    <thead><tr style="background:#f5f5f5;"><th>Mã đơn</th><th>Sản phẩm</th><th>Tổng tiền</th><th>Phương thức</th><th>Trạng thái</th><th>Ngày đặt</th></tr></thead>
                    <tbody>
                    <?php while($order = $orders->fetch_assoc()): ?>
                        <tr>
                            <td>#<?php echo $order['id']; ?></td>
                            <td><?php echo htmlspecialchars($order['product_name']); ?></td>
                            <td><?php echo number_format($order['total_price']); ?>đ</span></td>
                            <td><?php echo $order['payment_method'] ?? 'COD'; ?></td>
                            <td><?php echo $order['status']; ?></td>
                            <td><?php echo $order['created_at'] ?? '--'; ?></td>
                        </tr>
                    <?php endwhile; ?>
                    </tbody>
                </table>
                <?php else: echo "<p style='color:#999;'>📭 Bạn chưa có đơn hàng nào.</p>"; endif; ?>

            <?php elseif($tab == 'security'): ?>
                <h2 class="section-title">🔒 Đổi mật khẩu</h2>
                <form method="POST">
                    <div class="form-group"><label>Mật khẩu hiện tại</label><input type="password" name="old_password" required></div>
                    <div class="form-group"><label>Mật khẩu mới</label><input type="password" name="new_password" required></div>
                    <div class="form-group"><label>Xác nhận mật khẩu mới</label><input type="password" name="confirm_password" required></div>
                    <button type="submit" name="change_password" class="btn">🔄 Đổi mật khẩu</button>
                </form>
            <?php endif; ?>
        </div>
    </div>

    <script>
        function showAddAddress() { document.getElementById('addressForm').style.display = 'block'; }
        function hideAddAddress() { document.getElementById('addressForm').style.display = 'none'; }
        
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