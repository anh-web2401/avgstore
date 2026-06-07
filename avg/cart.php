<?php
require_once 'check_session.php';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Giỏ Hàng Của Bạn | AVG-STORE 💕</title>
    <style>
        * { margin:0; padding:0; box-sizing:border-box; font-family:'Segoe UI',sans-serif; }
        .navbar { display:flex; justify-content:space-between; align-items:center; padding:20px 8%; background:#fff; border-bottom:1px solid #eee; flex-wrap:wrap; gap:15px; }
        .logo { font-size:22px; font-weight:bold; text-decoration:none; color:#000; }
        .nav-links { display:flex; gap:30px; list-style:none; }
        .nav-links a { text-decoration:none; color:#555; font-size:13px; text-transform:uppercase; font-weight:600; }
        .nav-links a:hover { color:#b38b6d; }
        .cart-icon { position:relative; text-decoration:none; font-size:20px; margin-left:15px; }
        .cart-count { position:absolute; top:-8px; right:-12px; background:#b38b6d; color:#fff; font-size:10px; padding:2px 6px; border-radius:50%; }
        .container { max-width:1200px; margin:40px auto; padding:0 20px; }
        .cart-table { width:100%; border-collapse:collapse; margin-top:30px; }
        .cart-table th, .cart-table td { border-bottom:1px solid #eee; padding:15px; text-align:left; }
        .cart-table th { color:#666; font-weight:500; }
        .btn-qty { padding:5px 12px; background:#f4f4f4; border:1px solid #ddd; cursor:pointer; border-radius:6px; }
        .btn-delete { background:none; border:none; color:#dc3545; cursor:pointer; font-size:14px; }
        .cart-summary { margin-top:30px; text-align:right; padding:20px; background:#f9f9f9; border-radius:12px; }
        .cart-summary h3 { margin-bottom:10px; }
        .btn-checkout { padding:12px 30px; background:#222; color:#fff; border:none; cursor:pointer; font-weight:600; border-radius:8px; margin-left:10px; }
        .btn-clear { padding:12px 20px; background:#eee; border:none; cursor:pointer; border-radius:8px; }
        .modal { display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:1000; justify-content:center; align-items:center; }
        .modal-content { background:#fff; max-width:500px; width:90%; border-radius:16px; padding:30px; max-height:80vh; overflow-y:auto; }
        .form-group { margin-bottom:15px; }
        .form-group label { display:block; font-size:12px; font-weight:600; margin-bottom:5px; text-transform:uppercase; }
        .form-group input, .form-group textarea, .form-group select { width:100%; padding:10px; border:1px solid #ddd; border-radius:8px; }
        .payment-info { background:#f5f5f5; padding:15px; border-radius:8px; margin:15px 0; display:none; }
        .footer { background:#111; color:#bbb; padding:30px 0 20px; text-align:center; margin-top:50px; }
        @media (max-width:768px) { .cart-table th, .cart-table td { padding:10px; font-size:12px; } }
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

    <div class="container">
        <h2>🛒 Giỏ Hàng Của Bạn</h2>
        
        <table class="cart-table">
            <thead>
                <tr><th>Sản phẩm</th><th>Giá tiền</th><th>Số lượng</th><th>Thành tiền</th><th>Xóa</th></tr>
            </thead>
            <tbody id="cart-table-body"></tbody>
        </table>

        <div id="cart-summary" class="cart-summary"></div>
        
        <div style="margin-top:20px; text-align:right;">
            <button onclick="clearCart()" class="btn-clear">🗑️ XÓA TRỐNG GIỎ HÀNG</button>
            <button onclick="showCheckout()" class="btn-checkout">💳 TIẾN HÀNH THANH TOÁN</button>
        </div>
    </div>

    <div id="checkoutModal" class="modal">
        <div class="modal-content">
            <h2 style="margin-bottom:20px;">📝 Thanh toán đơn hàng</h2>
            <form id="checkoutForm">
                <div class="form-group"><label>Họ tên người nhận *</label><input type="text" id="fullname" required></div>
                <div class="form-group"><label>Số điện thoại *</label><input type="text" id="phone" required></div>
                <div class="form-group"><label>Địa chỉ giao hàng *</label><textarea id="address" rows="2" required></textarea></div>
                <div class="form-group"><label>Phương thức thanh toán *</label>
                    <select id="paymentMethod">
                        <option value="COD">💰 COD - Thanh toán khi nhận hàng</option>
                        <option value="BANK_TRANSFER">🏦 Chuyển khoản ngân hàng</option>
                        <option value="MOMO">📱 Ví Momo</option>
                    </select>
                </div>
                <div id="bankInfo" class="payment-info">
                    <p><strong>🏦 Thông tin chuyển khoản:</strong></p>
                    <p>Ngân hàng: <strong>Vietcombank</strong><br>
                    Số TK: <strong>1234567890</strong><br>
                    Chủ TK: <strong>AVG STORE</strong><br>
                    Nội dung: <strong>AVG_[MÃ ĐƠN HÀNG]</strong></p>
                </div>
                <div id="momoInfo" class="payment-info">
                    <p><strong>📱 Quét mã Momo:</strong></p>
                    <p>Số điện thoại: <strong>0987654321</strong><br>
                    Nội dung: <strong>AVG_[TÊN_BẠN]</strong></p>
                </div>
                <div id="totalDisplay" style="border-top:1px solid #eee; padding-top:15px; margin-top:10px;">
                    <p><strong>Tổng cộng:</strong> <span id="totalAmount" style="font-size:20px; color:#b38b6d;">0đ</span></p>
                </div>
                <button type="submit" style="width:100%; padding:14px; background:#111; color:#fff; border:none; border-radius:8px; font-weight:600; margin-top:15px;">✅ Xác nhận đặt hàng</button>
                <button type="button" onclick="closeCheckout()" style="width:100%; padding:12px; background:#ccc; border:none; border-radius:8px; margin-top:10px;">Hủy</button>
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
        
        function renderCart() {
            let cart = JSON.parse(localStorage.getItem('shopping_cart')) || [];
            const tbody = document.getElementById('cart-table-body');
            const summaryDiv = document.getElementById('cart-summary');

            if(cart.length === 0) {
                tbody.innerHTML = `<tr><td colspan="5" style="text-align:center; padding:40px; color:#888;">🛍️ Giỏ hàng của bạn đang trống rỗng. Hãy quay lại mục Sản phẩm để chọn đồ nhé!</td></tr>`;
                summaryDiv.innerHTML = '';
                return;
            }

            let total = 0;
            tbody.innerHTML = cart.map((item, index) => {
                let priceNum = parseInt(item.price.replace(/[^0-9]/g, ''));
                let itemTotal = priceNum * item.quantity;
                total += itemTotal;
                return `
                    <tr>
                        <td style="font-weight:500;">${item.name}</td>
                        <td style="color:#b38b6d; font-weight:600;">${item.price}</td>
                        <td>
                            <button class="btn-qty" onclick="changeQty(${index}, -1)">-</button>
                            <span style="margin: 0 10px; font-weight:600;">${item.quantity}</span>
                            <button class="btn-qty" onclick="changeQty(${index}, 1)">+</button>
                        </td>
                        <td style="color:#333; font-weight:600;">${itemTotal.toLocaleString()}đ</td>
                        <td><button class="btn-delete" onclick="removeItem(${index})">🗑️</button></td>
                    </tr>
                `;
            }).join('');
            
            summaryDiv.innerHTML = `<h3>Tổng cộng: <span style="color:#b38b6d;">${total.toLocaleString()}đ</span></h3>`;
        }

        function changeQty(index, delta) {
            let cart = JSON.parse(localStorage.getItem('shopping_cart')) || [];
            cart[index].quantity += delta;
            if(cart[index].quantity <= 0) cart.splice(index, 1);
            localStorage.setItem('shopping_cart', JSON.stringify(cart));
            renderCart();
            updateCartCount();
        }

        function removeItem(index) {
            let cart = JSON.parse(localStorage.getItem('shopping_cart')) || [];
            cart.splice(index, 1);
            localStorage.setItem('shopping_cart', JSON.stringify(cart));
            renderCart();
            updateCartCount();
        }

        function clearCart() {
            if(confirm("Bạn có chắc chắn muốn xóa toàn bộ giỏ hàng không?")) {
                localStorage.removeItem('shopping_cart');
                renderCart();
                updateCartCount();
            }
        }

        function showCheckout() {
            let cart = JSON.parse(localStorage.getItem('shopping_cart')) || [];
            if(cart.length === 0) {
                alert('⚠️ Giỏ hàng trống!');
                return;
            }
            let total = cart.reduce((sum, item) => sum + (parseInt(item.price.replace(/[^0-9]/g, '')) * item.quantity), 0);
            document.getElementById('totalAmount').innerHTML = total.toLocaleString() + 'đ';
            document.getElementById('checkoutModal').style.display = 'flex';
        }

        function closeCheckout() {
            document.getElementById('checkoutModal').style.display = 'none';
        }

        document.getElementById('paymentMethod').addEventListener('change', function() {
            document.getElementById('bankInfo').style.display = this.value === 'BANK_TRANSFER' ? 'block' : 'none';
            document.getElementById('momoInfo').style.display = this.value === 'MOMO' ? 'block' : 'none';
        });

        document.getElementById('checkoutForm').addEventListener('submit', function(e) {
            e.preventDefault();
            let cart = JSON.parse(localStorage.getItem('shopping_cart')) || [];
            if(cart.length === 0) return alert('⚠️ Giỏ hàng trống!');
            
            let fullname = document.getElementById('fullname').value;
            let phone = document.getElementById('phone').value;
            let address = document.getElementById('address').value;
            let paymentMethod = document.getElementById('paymentMethod').value;
            let total = cart.reduce((sum, item) => sum + (parseInt(item.price.replace(/[^0-9]/g, '')) * item.quantity), 0);
            let productNames = cart.map(item => item.name + ' x' + item.quantity).join(', ');
            
            let order = {
                id: Date.now(),
                customer_name: fullname,
                phone: phone,
                address: address,
                product_name: productNames,
                total_price: total,
                payment_method: paymentMethod,
                status: 'Chờ xử lý',
                created_at: new Date().toISOString()
            };
            
            let orders = JSON.parse(localStorage.getItem('orders_history')) || [];
            orders.unshift(order);
            localStorage.setItem('orders_history', orders);
            
            let msg = '✅ Đặt hàng thành công! ';
            if(paymentMethod !== 'COD') {
                if(paymentMethod === 'BANK_TRANSFER') {
                    msg += '\n🏦 Chuyển khoản đến:\nVietcombank - 1234567890\nNội dung: AVG_' + order.id;
                } else if(paymentMethod === 'MOMO') {
                    msg += '\n📱 Momo: 0987654321\nNội dung: AVG_' + fullname;
                }
            }
            alert(msg);
            
            localStorage.removeItem('shopping_cart');
            closeCheckout();
            renderCart();
            updateCartCount();
            alert('🎉 Cảm ơn bạn đã mua sắm!');
        });

        renderCart();
        updateCartCount();
    </script>
</body>
</html>