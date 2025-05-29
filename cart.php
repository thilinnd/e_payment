<?php
session_start();
require 'db.php'; // kết nối CSDL

// Lấy giỏ hàng từ session
$cart = $_SESSION['cart'] ?? [];
$total = 0;

// Lấy dữ liệu sản phẩm từ DB dựa trên ID
$productData = [];
if (!empty($cart)) {
    $ids = implode(',', array_map('intval', array_keys($cart)));
    $stmt = $pdo->query("SELECT * FROM products WHERE id IN ($ids)");
    foreach ($stmt as $row) {
        $productData[$row['id']] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8" />
    <title>Giỏ hàng</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet" />
    <link rel="stylesheet" href="assets/style.css" />
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
                    if (!isset($productData[$id])) continue;
                    $p = $productData[$id];
                    $subtotal = $p['price'] * $qty;
                    $total += $subtotal;
                ?>
                    <tr>
                        <td><?= htmlspecialchars($p['name']) ?></td>
                        <td><?= (int)$qty ?></td>
                        <td><?= number_format($p['price']) ?> VNĐ</td>
                        <td><?= number_format($subtotal) ?> VNĐ</td>
                        <td>
                            <a href="remove_from_cart.php?id=<?= urlencode($id) ?>" class="btn btn-danger btn-sm" onclick="return confirmDelete()">
                                <i class="bi bi-trash"></i> Xoá
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <h4 class="mt-3">Tổng cộng: <strong><?= number_format($total) ?> VNĐ</strong></h4>

        <form action="checkout.php" method="POST" onsubmit="return confirmCheckout()" class="mt-3">
            <input type="hidden" name="amount" value="<?= htmlspecialchars($total) ?>" />
            <div class="mb-3">
                <label class="form-label fw-bold">Chọn cổng thanh toán:</label><br />
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="gateway" value="momo" id="payMomo" checked required />
                    <label class="form-check-label" for="payMomo">MoMo</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="gateway" value="vnpay" id="payVnpay" />
                    <label class="form-check-label" for="payVnpay">VNPay</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="gateway" value="paypal" id="payPaypal" />
                    <label class="form-check-label" for="payPaypal">PayPal</label>
                </div>
            </div>
            <button type="submit" class="btn btn-success">
                <i class="bi bi-credit-card"></i> Thanh toán
            </button>
        </form>
    <?php endif; ?>

    <div class="mt-4">
        <a href="index.php" class="btn btn-secondary">← Tiếp tục mua hàng</a>
    </div>
</body>
</html>
