<?php
session_start();

$conn = new mysqli("localhost", "root", "", "avg_store");
if ($conn->connect_error) {
    die("Kết nối database thất bại: " . $conn->connect_error);
}
$conn->set_charset("utf8mb4");

$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;
$fullname = $_POST['fullname'];
$phone = $_POST['phone'];
$address = $_POST['address'];
$product_name = $_POST['product_name'];
$total_price = $_POST['total_price'];
$payment_method = $_POST['payment_method'];

$sql = "INSERT INTO orders (user_id, customer_name, phone, address, product_name, total_price, payment_method, status, created_at) 
        VALUES ('$user_id', '$fullname', '$phone', '$address', '$product_name', '$total_price', '$payment_method', 'Chờ xử lý', NOW())";

if ($conn->query($sql) === TRUE) {
    // Lấy ID đơn hàng vừa tạo
    $order_id = $conn->insert_id;
    
    // Hiển thị thông báo và xóa giỏ hàng
    echo "<script>
        alert('✅ Đặt hàng thành công! Mã đơn hàng: #" . $order_id . "');
        localStorage.removeItem('shopping_cart');
        window.location.href = 'cart.php';
    </script>";
} else {
    echo "<script>
        alert('❌ Lỗi: " . addslashes($conn->error) . "');
        window.location.href = 'cart.php';
    </script>";
}

$conn->close();
?>