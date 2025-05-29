<?php
session_start();
require 'db.php'; // file chứa kết nối PDO

if (!isset($_POST['gateway']) || empty($_SESSION['cart'])) {
    header("Location: cart.php");
    exit;
}

$orderCode = 'OD' . time();
$gateway = $_POST['gateway'];
$total = 0;

// Lấy danh sách sản phẩm từ DB
$productIds = array_keys($_SESSION['cart']);
$placeholders = implode(',', array_fill(0, count($productIds), '?'));

$stmt = $pdo->prepare("SELECT * FROM products WHERE id IN ($placeholders)");
$stmt->execute($productIds);
$products = [];
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $products[$row['id']] = $row;
}

// Chuẩn bị câu lệnh lưu đơn hàng
$insert = $pdo->prepare("
    INSERT INTO orders (
        order_code, product_id, product_name, quantity, price, total_price, payment_method
    ) VALUES (?, ?, ?, ?, ?, ?, ?)
");

foreach ($_SESSION['cart'] as $id => $qty) {
    if (!isset($products[$id])) continue;

    $product = $products[$id];
    $price = $product['price'];
    $subtotal = $price * $qty;
    $total += $subtotal;

    $insert->execute([
        $orderCode,
        $id,
        $product['name'],
        $qty,
        $price,
        $subtotal,
        $gateway
    ]);
}

// Lưu session
$_SESSION['last_order'] = [
    'code' => $orderCode,
    'amount' => $total,
    'gateway' => $gateway,
    'created_at' => date('Y-m-d H:i:s')
];

// Xoá cart nếu không phải PayPal
if ($gateway !== 'paypal') {
    unset($_SESSION['cart']);
}

// Chuyển trang
$redirectMap = [
    'momo' => 'momo_payment.php',
    'vnpay' => 'vnpay_payment.php',
    'paypal' => 'paypal_payment.php'
];

if (isset($redirectMap[$gateway])) {
    header("Location: " . $redirectMap[$gateway]);
} else {
    header("Location: cart.php?error=invalid_gateway");
}
exit;
