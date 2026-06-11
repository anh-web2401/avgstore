<?php
require_once 'check_session.php';
$conn = new mysqli("localhost", "root", "", "avg_store");
$conn->set_charset("utf8mb4");
$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$product = $conn->query("SELECT * FROM products WHERE id = $product_id")->fetch_assoc();
$variants = $conn->query("SELECT * FROM product_variants WHERE product_id = $product_id");
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title><?php echo $product ? $product['name'] : 'Sản phẩm'; ?> | AVG-STORE</title>
    <style>
        * { margin:0; padding:0; box-sizing:border-box; font-family:'Segoe UI',sans-serif; }
        .navbar { display:flex; justify-content:space-between; align-items:center; padding:20px 8%; background:#fff; border-bottom:1px solid #eee; flex-wrap:wrap; gap:15px; }
        .logo { font-size:22px; font-weight:bold; text-decoration:none; color:#000; }
        .nav-links { display:flex; gap:30px; list-style:none; }
        .nav-links a { text-decoration:none; color:#555; font-size:13px; text-transform:uppercase; font-weight:600; }
        .nav-links a:hover { color:#b38b6d; }
        .cart-icon { position:relative; text-decoration:none; font-size:20px; margin-left:15px; }
        .cart-count { position:absolute; top:-8px; right:-12px; background:#b38b6d; color:#fff; font-size:10px; padding:2px 6px; border-radius:50%; }
        .detail-container { max-width:1200px; margin:50px auto; padding:0 20px; display:flex; gap:50px; flex-wrap:wrap; }
        .detail-img { flex:1; min-width:280px; }
        .detail-img img { width:100%; height:500px; object-fit:cover; border-radius:16px; }
        .detail-info { flex:1; }
        .category-badge { background:#b38b6d; color:#fff; padding:4px 12px; font-size:12px; display:inline-block; margin-bottom:15px; border-radius:20px; }
        h1 { font-size:28px; margin-bottom:15px; }
        .price { font-size:28px; color:#b38b6d; font-weight:bold; margin-bottom:20px; }
        .stock-info { margin:10px 0 15px; padding:8px 12px; background:#f0fdf4; border-radius:8px; display:inline-block; }
        .stock-status { color:#28a745; font-weight:600; }
        .stock-status.out { color:#dc3545; }
        .rating-section { margin:20px 0; padding:15px 0; border-top:1px solid #eee; border-bottom:1px solid #eee; }
        .stars { color:#ffc107; font-size:20px; letter-spacing:2px; }
        .variant-group { margin-bottom:20px; }
        .variant-group label { display:block; font-size:13px; font-weight:600; margin-bottom:8px; }
        .variant-group select { padding:10px 15px; border:1px solid #ddd; border-radius:8px; width:100%; max-width:250px; }
        .quantity { display:flex; align-items:center; gap:10px; margin-bottom:25px; }
        .quantity input { width:80px; padding:10px; text-align:center; border:1px solid #ddd; border-radius:8px; }
        .btn-add { background:#111; color:#fff; padding:14px 30px; border:none; border-radius:8px; font-weight:600; cursor:pointer; width:100%; font-size:16px; transition:0.3s; }
        .btn-add:hover { background:#b38b6d; }
        .shipping-info { margin:20px 0; padding:15px; background:#f9f9f9; border-radius:12px; }
        .shipping-info div { margin-top:8px; }
        .shipping-info span { font-weight:600; }
        .description { margin-top:30px; padding-top:20px; border-top:1px solid #eee; }
        .related-products { margin:30px 0; padding:20px 0; border-top:1px solid #eee; }
        .related-grid { display:flex; gap:20px; flex-wrap:wrap; margin-top:15px; }
        .related-item { width:calc(25% - 15px); min-width:140px; text-align:center; text-decoration:none; transition:0.3s; }
        .related-item:hover { transform:translateY(-5px); }
        .related-item img { width:100%; height:150px; object-fit:cover; border-radius:12px; margin-bottom:8px; }
        .related-item .name { font-size:13px; color:#333; margin:5px 0; }
        .related-item .price { font-size:12px; color:#b38b6d; font-weight:600; }
        .share-links { margin:20px 0; padding:15px 0; border-top:1px solid #eee; }
        .share-links a { margin-left:15px; text-decoration:none; font-size:14px; }
        .fb { color:#1877f2; } .tw { color:#1da1f2; } .ig { color:#e4405f; } .zl { color:#25d366; }
        @media (max-width:768px) { .detail-container { flex-direction:column; } .related-item { width:calc(50% - 10px); } }
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

    <div class="detail-container">
        <?php if($product): ?>
        <div class="detail-img">
            <img src="<?php echo htmlspecialchars($product['image']); ?>" onerror="this.src='https://placehold.co/500x500?text=AVG'">
        </div>
        <div class="detail-info">
            <span class="category-badge">🌸 <?php echo $product['category']; ?></span>
            <h1><?php echo htmlspecialchars($product['name']); ?></h1>
            <div class="price">💰 <?php echo htmlspecialchars($product['price']); ?></div>
            
            <div class="stock-info">
                📦 Tình trạng: <span id="stockStatus" class="stock-status">Còn hàng xinh tươi</span>
            </div>
            
            <div class="rating-section">
                <div style="display: flex; align-items: center; gap: 15px; flex-wrap: wrap;">
                    <span style="font-weight: 600;">⭐ Yêu thích:</span>
                    <div class="stars">★★★★★</div>
                    <span style="color: #666;">(12 đánh giá iu)</span>
                    <span style="color: #999; font-size: 13px;">| 250 đã mua</span>
                </div>
            </div>
            
            <?php if($variants->num_rows > 0): ?>
            <div class="variant-group">
                <label>🎨 Chọn size / màu sắc nè</label>
                <select id="variantSelect">
                    <option value="">-- Chọn iu ơi --</option>
                    <?php while($v = $variants->fetch_assoc()): ?>
                    <option value="<?php echo $v['id']; ?>" data-stock="<?php echo $v['stock']; ?>">
                        📏 <?php echo $v['size']; ?> - 🎨 <?php echo $v['color']; ?> (còn <?php echo $v['stock']; ?> cái)
                    </option>
                    <?php endwhile; ?>
                </select>
                <span id="stockMsg" style="font-size:12px; color:#666; display:block; margin-top:5px;"></span>
            </div>
            <?php endif; ?>
            
            <div class="quantity">
                <label style="font-weight:600;">📝 Số lượng:</label>
                <div style="display:flex; align-items:center; gap:5px;">
                    <button type="button" onclick="changeQty(-1)" style="width:32px; height:32px; border:1px solid #ddd; background:#fff; border-radius:6px; cursor:pointer;">➖</button>
                    <input type="number" id="quantity" value="1" min="1" max="99" style="width:70px; text-align:center;">
                    <button type="button" onclick="changeQty(1)" style="width:32px; height:32px; border:1px solid #ddd; background:#fff; border-radius:6px; cursor:pointer;">➕</button>
                </div>
            </div>
            
            <button class="btn-add" onclick="addToCart()">🛍️ THÊM VÀO GIỎ NGAY</button>
            
            <div class="shipping-info">
                <div>🚚 <span>Miễn phí ship</span> cho đơn từ 500k nha</div>
                <div>🔄 <span>Đổi trả miễn phí</span> trong 7 ngày đầu</div>
                <div>💳 <span>Thanh toán linh hoạt</span> | COD / Chuyển khoản / Momo</div>
                <div>✅ <span>Cam kết hàng chính hãng</span> | Bảo hành 30 ngày</div>
            </div>
            
            <div class="description">
                <h3>📖 Mô tả sản phẩm xinh</h3>
                <p><?php echo nl2br(htmlspecialchars($product['description'] ?? 'Chưa có mô tả chi tiết. Ib shop để được tư vấn thêm nha 💌')); ?></p>
            </div>
            
            <?php
            $related = $conn->query("SELECT * FROM products WHERE category = '{$product['category']}' AND id != $product_id ORDER BY RAND() LIMIT 4");
            if($related && $related->num_rows > 0):
            ?>
            <div class="related-products">
                <h3>💕 Có thể bạn cũng thích</h3>
                <div class="related-grid">
                    <?php while($rel = $related->fetch_assoc()): ?>
                    <a href="product-detail.php?id=<?php echo $rel['id']; ?>" class="related-item">
                        <img src="<?php echo htmlspecialchars($rel['image']); ?>" onerror="this.src='https://placehold.co/200x200?text=AVG'">
                        <div class="name"><?php echo htmlspecialchars($rel['name']); ?></div>
                        <div class="price"><?php echo htmlspecialchars($rel['price']); ?></div>
                    </a>
                    <?php endwhile; ?>
                </div>
            </div>
            <?php endif; ?>
            
            <div class="share-links">
                <span style="font-weight:600;">📢 Chia sẻ cho bạn bè nè:</span>
                <a href="#" class="fb">Facebook</a>
                <a href="#" class="tw">Twitter</a>
                <a href="#" class="ig">Instagram</a>
                <a href="#" class="zl">Zalo</a>
            </div>
            
        </div>
        <?php else: ?>
        <div style="text-align:center; width:100%;">
            <h2>😢 Không tìm thấy sản phẩm</h2>
            <p>Sản phẩm bạn tìm không có hoặc đã được chuyển đi nơi khác rùi 🥺</p>
            <a href="products.php" style="display:inline-block; margin-top:20px; padding:10px 20px; background:#111; color:#fff; text-decoration:none; border-radius:8px;">← Về trang sản phẩm</a>
        </div>
        <?php endif; ?>
    </div>

    <script>
        function updateCartCount() {
            let cart = JSON.parse(localStorage.getItem('shopping_cart')) || [];
            let total = cart.reduce((sum, item) => sum + item.quantity, 0);
            let cartCount = document.getElementById('cartCount');
            if(cartCount) cartCount.innerText = total;
        }
        
        function changeQty(delta) {
            let qty = document.getElementById('quantity');
            let newVal = parseInt(qty.value) + delta;
            if(newVal >= 1 && newVal <= 99) qty.value = newVal;
        }
        
        function addToCart() {
            let variantSelect = document.getElementById('variantSelect');
            let variantId = variantSelect ? variantSelect.value : '';
            let quantity = parseInt(document.getElementById('quantity').value);
            
            <?php if($product): ?>
            if(variantSelect && variantSelect.options.length > 1 && !variantId) {
                alert('💕 Iu ơi, hãy chọn size/màu trước nha!');
                return;
            }
            
            let variantText = '';
            if(variantSelect && variantSelect.options[variantSelect.selectedIndex] && variantSelect.value) {
                let fullText = variantSelect.options[variantSelect.selectedIndex].text;
                variantText = ' (' + fullText.split(' (còn')[0] + ')';
            }
            
            let item = {
                key: '<?php echo $product['id']; ?>' + (variantId ? '_' + variantId : ''),
                id: <?php echo $product['id']; ?>,
                name: '<?php echo addslashes($product['name']); ?>' + variantText,
                price: '<?php echo $product['price']; ?>',
                quantity: quantity,
                variant_id: variantId
            };
            
            let cart = JSON.parse(localStorage.getItem('shopping_cart')) || [];
            let existing = cart.find(i => i.key === item.key);
            if(existing) {
                existing.quantity += quantity;
            } else {
                cart.push(item);
            }
            localStorage.setItem('shopping_cart', JSON.stringify(cart));
            updateCartCount();
            alert('✅ Đã thêm ' + item.name + ' vào giỏ hàng iu ơi!');
            <?php endif; ?>
        }
        
        let variantSelect = document.getElementById('variantSelect');
        if(variantSelect) {
            variantSelect.addEventListener('change', function() {
                let selected = this.options[this.selectedIndex];
                let stock = selected ? parseInt(selected.dataset.stock) : 0;
                let stockStatus = document.getElementById('stockStatus');
                let stockMsg = document.getElementById('stockMsg');
                
                if(selected && selected.value) {
                    if(stock <= 0) {
                        stockStatus.innerHTML = 'Hết hàng rùi 🥺';
                        stockStatus.className = 'stock-status out';
                        stockMsg.innerHTML = '⚠️ Sản phẩm này đã hết, iu chọn biến thể khác nha!';
                    } else {
                        stockStatus.innerHTML = 'Còn ' + stock + ' cái xinh xinh';
                        stockStatus.className = 'stock-status';
                        stockMsg.innerHTML = '✅ Còn hàng - Giao ngay trong ngày nè';
                    }
                }
            });
        }
        
        updateCartCount();
    </script>
</body>
</html>