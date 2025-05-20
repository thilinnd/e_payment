<?php
session_start();

$products = [
    1 => ["name" => "Sản phẩm A", "price" => 1000],
    2 => ["name" => "Sản phẩm B", "price" => 200000],
    3 => ["name" => "Sản phẩm C", "price" => 300000],
];
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Sản phẩm</title>
    <!-- Thay thế -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/style.css">

    <!-- Có thể thêm icon đẹp: -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

</head>
<body class="container py-4">
    <h1 class="mb-4">🛍️ Danh sách sản phẩm</h1>
    <div class="row">
        <?php foreach ($products as $id => $p): ?>
        <div class="col-md-4">
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title"><?= $p['name']; ?></h5>
                    <p class="card-text"><?= number_format($p['price']); ?> VNĐ</p>
                    <a href="add_to_cart.php?id=<?= $id; ?>" class="btn btn-primary">Thêm vào giỏ</a>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <a href="cart.php" class="btn btn-outline-success">Xem giỏ hàng</a>
</body>
</html>
