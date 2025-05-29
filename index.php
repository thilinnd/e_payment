<?php
session_start();
require 'db.php'; // Kết nối CSDL

// Lấy tất cả sản phẩm từ bảng products
$products = [];
$stmt = $pdo->query("SELECT * FROM products");
foreach ($stmt as $row) {
    $products[$row['id']] = [
        "name" => $row['name'],
        "price" => $row['price'],
        "image" => $row['image'],
    ];
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Snacko - Danh sách sản phẩm</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-color: #f5f5dc;
        }

        .container {
            padding-top: 4px !important;
            padding-bottom: 8px !important;
            max-width: 960px;
        }

        h1.title {
            margin-top: 2px !important;
            margin-bottom: 6px !important;
            font-size: 2.5rem;
            font-weight: bold;
            color: #644535;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        h1.title img {
            height: 56px;
            border-radius: 6px;
        }

        .card-img-top {
            height: 200px;
            object-fit: cover;
            border-radius: 12px 12px 0 0;
        }

        .product-name {
            color: #644535;
            font-weight: bold;
        }

        .price {
            color: #39b54a;
            font-weight: bold;
        }

        .card {
            border-radius: 12px;
            overflow: hidden;
        }

        .btn-success {
            background-color: #39b54a;
            border-color: #39b54a;
        }

        .btn-success:hover {
            background-color: #2a963c;
            border-color: #2a963c;
        }

        .btn-pink-peach {
            background-color: #ff6f91;
            color: white;
            border: none;
            font-weight: 600;
            box-shadow: 0 4px 8px rgba(255, 111, 145, 0.5);
            transition: background-color 0.3s ease, box-shadow 0.3s ease;
        }

        .btn-pink-peach:hover {
            background-color: #e65c7a;
            box-shadow: 0 6px 12px rgba(230, 92, 122, 0.7);
            color: white;
        }

        .btn-container {
            margin-bottom: 40px;
        }
    </style>
</head>

<body class="container py-4">
    <h1 class="title">
        <img src="images/preloader.png" alt="Snacko logo">
        Danh sách sản phẩm
    </h1>

    <div class="row">
        <?php foreach ($products as $id => $p): ?>
            <div class="col-md-4">
                <div class="card shadow-sm mb-4">
                    <img src="<?= $p['image']; ?>" class="card-img-top" alt="<?= $p['name']; ?>">
                    <div class="card-body text-center">
                        <h5 class="product-name"><?= $p['name']; ?></h5>
                        <p class="price"><?= number_format($p['price']); ?> VNĐ</p>
                        <a href="add_to_cart.php?id=<?= $id; ?>" class="btn btn-success">
                            <i class="bi bi-cart-plus"></i> Thêm vào giỏ
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="text-center btn-container mt-4">
        <a href="cart.php" class="btn btn-pink-peach">
            <i class="bi bi-bag-check"></i> Xem giỏ hàng
        </a>
    </div>
</body>

</html>
