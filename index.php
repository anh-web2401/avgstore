<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// KHÔNG cần kết nối database nữa vì không hiển thị sản phẩm
// Nếu vẫn muốn giữ session đăng nhập thì cần kết nối, nhưng tôi giữ nhẹ nhàng
$conn = new mysqli("localhost", "root", "", "avg_store");
if ($conn->connect_error) {
    // Lỗi nhưng không ảnh hưởng hiển thị blog
}
$conn->set_charset("utf8mb4");
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>AOS.init({ duration: 1000, once: true });</script>
    <meta charset="UTF-8">
    <title>AVG-STORE | Giới thiệu cửa hàng thời trang</title>
    <style>
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
        .btn-shop { padding:12px 30px; background:#fff; color:#111; text-decoration:none; font-size:13px; font-weight:bold; text-transform:uppercase; letter-spacing:1px; transition:0.3s; border-radius:30px; display:inline-block; }
        .btn-shop:hover { background:#b38b6d; color:#fff; }
        
        /* Phần blog giới thiệu */
        .blog-intro {
            padding: 60px 8%;
            background: #fff;
        }
        .blog-title {
            text-align: center;
            font-size: 28px;
            text-transform: uppercase;
            letter-spacing: 3px;
            margin-bottom: 15px;
            color: #2c2c2c;
        }
        .blog-sub {
            text-align: center;
            color: #b38b6d;
            font-size: 14px;
            letter-spacing: 2px;
            margin-bottom: 50px;
            text-transform: uppercase;
        }
        .about-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 50px;
            align-items: center;
            margin-bottom: 60px;
        }
        .about-text h3 {
            font-size: 32px;
            font-weight: 600;
            margin-bottom: 20px;
            color: #1a1a1a;
        }
        .about-text p {
            color: #5a5a5a;
            line-height: 1.7;
            margin-bottom: 18px;
            font-size: 15px;
        }
        .about-img {
            background: #f5f0eb;
            border-radius: 24px;
            height: 400px;
            background-image: url('https://images.unsplash.com/photo-1539109136881-3be0616acf4b?q=80&w=1374');
            background-size: cover;
            background-position: center;
        }
        .blog-posts {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 30px;
            margin-top: 40px;
        }
        .post-card {
            background: #fff;
            border: 1px solid #f0f0f0;
            border-radius: 16px;
            overflow: hidden;
            transition: 0.3s;
        }
        .post-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.05);
            border-color: #e0d6ce;
        }
        .post-img {
            height: 220px;
            background-size: cover;
            background-position: center;
        }
        .post-content {
            padding: 22px;
        }
        .post-cat {
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: #b38b6d;
            font-weight: 600;
            margin-bottom: 10px;
            display: inline-block;
        }
        .post-card h4 {
            font-size: 18px;
            margin-bottom: 12px;
            color: #222;
        }
        .post-card p {
            color: #6b6b6b;
            font-size: 13px;
            line-height: 1.55;
            margin-bottom: 15px;
        }
        .post-link {
            color: #b38b6d;
            text-decoration: none;
            font-size: 12px;
            font-weight: 600;
            border-bottom: 1px solid #e8dcd2;
            padding-bottom: 3px;
        }
        .values-show {
            background: #faf8f6;
            border-radius: 28px;
            padding: 45px 40px;
            margin-top: 60px;
            text-align: center;
        }
        .values-show h3 {
            font-size: 24px;
            margin-bottom: 35px;
        }
        .value-list {
            display: flex;
            justify-content: center;
            gap: 50px;
            flex-wrap: wrap;
        }
        .value-item {
            text-align: center;
            flex: 1;
            min-width: 150px;
        }
        .value-icon {
            font-size: 40px;
            display: block;
            margin-bottom: 15px;
        }
        
        .footer { background:#111; color:#bbb; padding:40px 0 20px; text-align:center; margin-top:50px; }
        
        @media (max-width: 768px) {
            .about-grid { grid-template-columns: 1fr; }
            .blog-posts { grid-template-columns: 1fr; }
            .banner h1 { font-size: 28px; }
            .navbar { flex-direction: column; }
        }
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
        <a href="products.php" class="btn-shop">🛍️ MUA SẮM NGAY</a>
    </div>
</div>

 <meta charset="UTF-8">
    <title>AVG - Chuyện nhà AVG</title>
    <style>
        * { margin:0; padding:0; box-sizing:border-box; font-family:'Segoe UI', sans-serif; }
        body { background: #fefcf9; }
        .header { text-align:center; padding:50px 20px 20px; }
        .header h1 { font-size:28px; letter-spacing:3px; color:#b38b6d; }
        .header p { color:#888; margin-top:10px; }
        .content { max-width:1000px; margin:40px auto; padding:0 20px; }
        .intro { background:#fff; padding:40px; border-radius:24px; box-shadow:0 4px 15px rgba(0,0,0,0.03); border:1px solid #eee; margin-bottom:40px; }
        .intro h2 { color:#333; margin-bottom:20px; }
        .intro p { line-height:1.7; color:#555; margin-bottom:15px; }
        .grid { display:grid; grid-template-columns:repeat(3,1fr); gap:30px; }
        .card { background:white; border-radius:20px; overflow:hidden; border:1px solid #eee; }
        .card-img { height:180px; background:#ddd; background-size:cover; background-position:center; }
        .card h3, .card p { padding:0 20px; }
        .card h3 { font-size:18px; margin:15px 0 10px; }
        .card p { font-size:13px; color:#666; padding-bottom:20px; }
        .footer { text-align:center; padding:40px; color:#aaa; border-top:1px solid #eee; margin-top:50px; }
        @media (max-width:700px){ .grid{ grid-template-columns:1fr; } }
    </style>
</head>
<body>
<div class="header">
    <h1>📖 CHUYỆN NHÀ AVG</h1>
    <p>NƠI PHONG CÁCH TỐI GIẢN KỂ CÂU CHUYỆN CỦA RIÊNG BẠN</p>
</div>
<div class="content">
    <div class="intro">
        <h2>✨ Về AVG – Minimalism trong từng đường may</h2>
        <p>AVG không chỉ là một cửa hàng thời trang; đó là tuyên ngôn về sự tối giản có chủ đích. Ra đời từ niềm đam mê với những thiết kế vượt thời gian, chúng tôi mong muốn mang đến tủ đồ thanh lịch, thoải mái và dễ phối hợp.</p>
        <p>Bộ sưu tập của AVG lấy cảm hứng từ kiến trúc Nordic, nghệ thuật đương đại và chất liệu tự nhiên. Mỗi sản phẩm đều được lựa chọn kỹ lưỡng, từ form dáng rơi tự nhiên đến gam màu trung tính, giúp bạn khẳng định phong cách một cách tinh tế nhất.</p>
        <p>AVG tin rằng thời trang đẹp nhất là khi bạn cảm thấy là chính mình – tự do, thoải mái và đầy đẳng cấp. Hãy cùng chúng tôi xây dựng một tủ đồ nhỏ gọn, chất lượng và bền vững.</p>
    </div>
    <div class="grid">
        <div class="card">
            <div class="card-img" style="background-image:url('https://images.unsplash.com/photo-1523381294911-8d3cead13475?w=500');"></div>
            <h3>✨ Phong cách Minimalism</h3>
            <p>"Ít hơn nhưng tinh tế hơn" – Áo sơ mi trắng, quần âu suông, trench coat dáng dài: những item chủ chốt giúp bạn ghi điểm.</p>
        </div>
        <div class="card">
            <div class="card-img" style="background-image:url('https://images.unsplash.com/photo-1539109136881-3be0616acf4b?w=500');"></div>
            <h3>🌿 Chất liệu bền vững</h3>
            <p>AVG ưu tiên cotton hữu cơ, linen, len tái chế. Mỗi sản phẩm đều được sản xuất có trách nhiệm với môi trường.</p>
        </div>
        <div class="card">
            <div class="card-img" style="background-image:url('https://images.unsplash.com/photo-1483985988355-763728e1935b?w=500');"></div>
            <h3>🤍 Cảm hứng từ bạn</h3>
            <p>Hàng trăm khách hàng đã yêu thích sự thoải mái khi mặc AVG. Hãy cùng chúng tôi lan tỏa vẻ đẹp tối giản mỗi ngày.</p>
        </div>
    </div>
</div>
<div class="footer">
    <p>© 2024 AVG-STORE - Nơi phong cách tối giản kể câu chuyện của riêng bạn</p>
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