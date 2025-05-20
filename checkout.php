<?php
session_start();
require 'db.php'; // Kết nối CSDL

if (!isset($_POST['checkout']) || empty($_SESSION['cart'])) {
    header("Location: cart.php");
    exit;
}

// Danh sách sản phẩm tạm thời (có thể thay bằng bảng `products` trong DB)
$products = [
    1 => ["name" => "Sản phẩm A", "price" => 1000],
    2 => ["name" => "Sản phẩm B", "price" => 200000],
    3 => ["name" => "Sản phẩm C", "price" => 300000],
];

// Tạo mã đơn hàng
$orderCode = 'OD' . rand(1000, 9999);

$total = 0;

// Lưu từng sản phẩm trong giỏ hàng vào bảng `orders`
foreach ($_SESSION['cart'] as $id => $qty) {
    if (isset($products[$id])) {
        $product = $products[$id];
        $price = $product['price'];
        $subtotal = $price * $qty;
        $total += $subtotal;

        $stmt = $pdo->prepare("INSERT INTO orders (order_code, product_id, product_name, quantity, price, total_price) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $orderCode,
            $id,
            $product['name'],
            $qty,
            $price,
            $subtotal
        ]);
    }
}

// Lưu thông tin đơn vào session (dùng nếu cần hiển thị ở momo_payment)
$_SESSION['last_order'] = [
    'code' => $orderCode,
    'amount' => $total,
    'created_at' => date('Y-m-d H:i:s')
];

// Xoá giỏ hàng để làm mới
unset($_SESSION['cart']);

// Chuyển sang cổng thanh toán Momo
header("Location: momo_payment.php");
exit;
