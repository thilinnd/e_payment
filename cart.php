<?php
session_start();

$products = [
    1 => ["name" => "Sản phẩm A", "price" => 1000],
    2 => ["name" => "Sản phẩm B", "price" => 200000],
    3 => ["name" => "Sản phẩm C", "price" => 300000],
];

$cart = $_SESSION['cart'] ?? [];
$total = 0;
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Giỏ hàng</title>
    <!-- Thay thế -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/style.css">

    <!-- Có thể thêm icon đẹp: -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <script>
        function confirmDelete() {
            return confirm("Bạn có chắc chắn muốn xoá sản phẩm này?");
        }

        function confirmCheckout() {
            return confirm("Xác nhận đặt hàng?");
        }
    </script>
</head>

<body class="container py-4">
    <h1 class="mb-4">🛒 Giỏ hàng</h1>
    <?php if (empty($cart)): ?>
        <div class="alert alert-warning">Giỏ hàng đang trống.</div>
    <?php else: ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Sản phẩm</th>
                    <th>Số lượng</th>
                    <th>Đơn giá</th>
                    <th>Thành tiền</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cart as $id => $qty):
                    $p = $products[$id];
                    $subtotal = $p['price'] * $qty;
                    $total += $subtotal;
                ?>
                    <tr>
                        <td><?= $p['name']; ?></td>
                        <td><?= $qty; ?></td>
                        <td><?= number_format($p['price']); ?> VNĐ</td>
                        <td><?= number_format($subtotal); ?> VNĐ</td>
                        <td>
                            <a href="remove_from_cart.php?id=<?= $id; ?>" class="btn btn-danger btn-sm" onclick="return confirmDelete()">Xoá</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <h4>Tổng cộng: <?= number_format($total); ?> VNĐ</h4>
        <form action="checkout.php" method="POST" onsubmit="return confirmCheckout()">
            <p>Chọn phương thức thanh toán:</p>
            <label>
                <input type="radio" name="gateway" value="momo" required> MoMo
            </label><br>
            <label>
                <input type="radio" name="gateway" value="vnpay"> VNPay
            </label><br>
            <label>
                <input type="radio" name="gateway" value="paypal"> PayPal
            </label><br><br>

            <input type="hidden" name="checkout" value="1">
            <button type="submit" class="btn btn-success">✅ Đặt hàng</button>
        </form>

        <script>
            function confirmCheckout() {
                return confirm("Bạn có chắc chắn muốn đặt hàng?");
            }
        </script>


    <?php endif; ?>
    <br><br>
    <a href="index.php" class="btn btn-secondary">← Tiếp tục mua hàng</a>
</body>

</html>