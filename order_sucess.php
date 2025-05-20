<?php
session_start();

if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    echo '<!DOCTYPE html>
    <html lang="vi">
    <head>
        <meta charset="UTF-8">
        <title>Đặt hàng</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body class="container py-5">
        <div class="alert alert-warning text-center">
            <h4>⚠️ Không có sản phẩm trong giỏ hàng để đặt hàng.</h4>
            <a href="index.php" class="btn btn-primary mt-3">Quay lại trang mua hàng</a>
        </div>
    </body>
    </html>';
    exit;
}

// Giả lập tổng đơn hàng
$total = 0;
foreach ($_SESSION['cart'] as $item) {
    $total += $item['price'] * $item['quantity'];
}

// Lưu thông tin đơn hàng tạm
$order_id = rand(1000, 9999);
$_SESSION['last_order'] = [
    'id' => $order_id,
    'items' => $_SESSION['cart'],
    'amount' => $total,
    'created_at' => date('Y-m-d H:i:s')
];

// Gọi file xử lý MoMo
header("Location: momo_payment.php");
exit;
?>
