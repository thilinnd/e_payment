<?php

session_start();
require 'db.php'; // Kết nối CSDL

if (!isset($_POST['gateway']) || empty($_SESSION['cart'])) {
    header("Location: cart.php");
    exit;
}

// Danh sách sản phẩm tạm thời
$products = [
    1 => ["name" => "Sản phẩm A", "price" => 1000],
    2 => ["name" => "Sản phẩm B", "price" => 200000],
    3 => ["name" => "Sản phẩm C", "price" => 300000],
];

$orderCode = 'OD' . rand(1000, 9999);
$total = 0;
$gateway = $_POST['gateway']; // lấy phương thức thanh toán

// Lưu đơn hàng vào DB
foreach ($_SESSION['cart'] as $id => $qty) {
    if (isset($products[$id])) {
        $product = $products[$id];
        $price = $product['price'];
        $subtotal = $price * $qty;
        $total += $subtotal;

        // CHỈNH SỬA: thêm payment_method vào SQL và execute
        $stmt = $pdo->prepare("INSERT INTO orders (order_code, product_id, product_name, quantity, price, total_price, payment_method) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $orderCode,
            $id,
            $product['name'],
            $qty,
            $price,
            $subtotal,
            $gateway // thêm phương thức thanh toán vào DB
        ]);
    }
}

// Lưu thông tin đơn vào session
$_SESSION['last_order'] = [
    'code' => $orderCode,
    'amount' => $total,
    'gateway' => $gateway,
    'created_at' => date('Y-m-d H:i:s')
];

// Xoá giỏ hàng
unset($_SESSION['cart']);

// Điều hướng đến cổng thanh toán tương ứng
switch ($gateway) {
    case 'momo':
        header("Location: momo_payment.php");
        break;
    case 'vnpay':
        header("Location: vnpay_payment.php");
        break;
    case 'paypal':
        header("Location: paypal_payment.php");
        break;
    default:
        header("Location: cart.php?error=invalid_gateway");
        break;
}
exit;
