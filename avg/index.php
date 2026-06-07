<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$conn = new mysqli("localhost", "root", "", "avg_store");
if ($conn->connect_error) {
    die("Kết nối database thất bại: " . $conn->connect_error);
}
$conn->set_charset("utf8mb4");

$prods = $conn->query("SELECT * FROM products ORDER BY id DESC LIMIT 8");
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>AOS.init({ duration: 1000, once: true });</script>
    <meta charset="UTF-8">
    <title>AVG-STORE | Trang Chủ 🛍️</title>
    <style>
        @keyframes floating {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
            100% { transform: translateY(0px); }
        }
        .product-card { animation: floating 4s ease-in-out infinite; }
        .product-card:nth-child(2n) { animation-delay: 0.5s; }
        .product-card:nth-child(3n) { animation-delay: 1s; }
        * { margin:0; padding:0; box-sizing:border-box; font-family:'Segoe UI', sans-serif; }
        .navbar { display:flex; justify-content:space-between; align-items:center; padding:20px 8%; background:#fff; border-bottom:1px solid #eee; flex-wrap:wrap; gap:15px; }
        .logo { font-size:22px; font-weight:bold; letter-spacing:2px; text-decoration:none; color:#000; }
        .nav-links { display:flex; gap:30px; list-style:none; }
        .nav-links a { text-decoration:none; color:#555; font-size:13px; text-transform:uppercase; font-weight:600; transition:0.2s; }
        .nav-links a:hover { color:#b38b6d; }
        .cart-icon { position:relative; text-decoration:none; font-size:20px; margin-left:15px; }
        .cart-count { position:absolute; top:-8px; right:-12px; background:#b38b6d; color:#fff; font-size:10px; padding:2px 6px; border-radius:50%; }
        .banner { position:relative; background:linear-gradient(rgba(0,0,0,0.3), rgba(0,0,0,0.3)), url('https://images.unsplash.com/photo-1441986300917-64674bd600d8?q=80&w=1600') center/cover; height:70vh; display:flex; justify-content:center; align-items:center; text-align:center; color:#fff; }
        .banner-content { position:relative; z-index:1; }
        .banner h1 { font-size:42px; margin-bottom:15px; letter-spacing:2px; text-transform:uppercase; }
        .banner p { font-size:16px; margin-bottom:25px; font-style:italic; }
        .btn-shop { padding:12px 30px; background:#fff; color:#111; text-decoration:none; font-size:13px; font-weight:bold; text-transform:uppercase; letter-spacing:1px; transition:0.3s; border-radius:30px; }
        .btn-shop:hover { background:#b38b6d; color:#fff; }
        .section-title { text-align:center; margin:50px 0 30px; font-size:20px; text-transform:uppercase; letter-spacing:2px; }
        .product-grid { display:grid; grid-template-columns:repeat(4, 1fr); gap:30px; padding:0 8% 60px; }
        .product-card { border:1px solid #f0f0f0; padding:15px; background:#fff; text-align:center; transition:0.3s; border-radius:12px; }
        .product-card:hover { box-shadow:0 6px 15px rgba(0,0,0,0.05); transform:translateY(-5px); }
        .product-card img { width:100%; height:280px; object-fit:cover; margin-bottom:15px; border-radius:8px; }
        .product-name { font-size:14px; font-weight:600; color:#333; margin-bottom:8px; }
        .product-price { color:#b38b6d; font-weight:bold; font-size:14px; }
        .footer { background:#111; color:#bbb; padding:40px 0 20px; text-align:center; margin-top:50px; }
        @media (max-width: 768px) { .product-grid { grid-template-columns:repeat(2, 1fr); } .banner h1 { font-size:28px; } }
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
                <?php if(isset($_SESSION['user'])): ?>
                    <li><a href="profile.php">Tài Khoản</a></li>
                    <?php if($_SESSION['role'] === 'admin'): ?>
                        <li><a href="admin.php" style="color:#b38b6d;"> Quản Trị</a></li>
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

    <div class="banner">
        <div class="banner-content">
            <h1>AVG - MINIMALISM STYLE</h1>
            <p>Khám phá bộ sưu tập thời trang tối giản và thời thượng mới nhất</p>
            <a href="products.php" class="btn-shop">🛍️ Mua Sắm Ngay</a>
        </div>
    </div>

    <h2 class="section-title">✨ Sản Phẩm Mới Về ✨</h2>
    <div class="product-grid">
        <?php if($prods && $prods->num_rows > 0): ?>
            <?php while($row = $prods->fetch_assoc()): ?>
                <div class="product-card" data-aos="fade-up">
                    <img src="<?php echo htmlspecialchars($row['image']); ?>" onerror="this.src='https://placehold.co/300x350?text=AVG'">
                    <div class="product-name"><?php echo htmlspecialchars($row['name']); ?></div>
                    <div class="product-price"><?php echo htmlspecialchars($row['price']); ?></div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div style="grid-column: span 4; text-align:center; color:#999;">📭 Chưa có sản phẩm nào</div>
        <?php endif; ?>
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
        
        window.addEventListener('storage', function() {
            updateCartCount();
        });
    </script>
</body>
</html>