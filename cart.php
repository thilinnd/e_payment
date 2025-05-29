<?php
session_start();
require 'db.php'; // Kết nối CSDL

$cart = $_SESSION['cart'] ?? [];
$total = 0;

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
    <h1 class="mb-4 text-center">🛒 GIỎ HÀNG</h1>

    <?php if (empty($cart)): ?>
        <div class="alert alert-warning text-center"><strong>GIỎ HÀNG ĐANG TRỐNG</strong></div>
    <?php else: ?>
        <form action="checkout.php" method="POST" onsubmit="return confirmCheckout()">
            <input type="hidden" name="amount" value="<?= htmlspecialchars($total) ?>" />
            <div class="row">
                <!-- Cột trái: Thông tin cá nhân -->
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input style="background-color: #ffffff; border-color: #0a9548;" type="email" class="form-control" id="email" name="email" required />
                    </div>
                    <div class="mb-3">
                        <label for="first_name" class="form-label">Họ</label>
                        <input style="background-color: #ffffff; border-color: #0a9548; " type="text" class="form-control" id="first_name" name="first_name" required />
                    </div>
                    <div class="mb-3">
                        <label for="last_name" class="form-label">Tên</label>
                        <input style="background-color: #ffffff; border-color: #0a9548;" type="text" class="form-control" id="last_name" name="last_name" required />
                    </div>
                    <div class="mb-3">
                        <label for="address" class="form-label">Địa chỉ</label>
                        <input style="background-color: #ffffff; border-color: #0a9548;" type="text" class="form-control" id="address" name="address" required />
                    </div>
                    <div class="mb-3">
                        <label for="phone" class="form-label">Số điện thoại</label>
                        <input style="background-color: #ffffff; border-color: #0a9548;" type="tel" class="form-control" id="phone" name="phone" required />
                    </div>
                </div>

                <!-- Cột phải: Phương thức thanh toán và hóa đơn -->
                <div class="col-md-6">
                    <div class="p-4 shadow-md mb-4" style="background-color: #f5f5dc; border-radius: 10px;">
                        <table class="table" style="background-color: #f5f5dc; border: 1px solid #0a9548; border-radius: 10px; border-collapse: collapse;">
                            <thead>
                                <tr class="text-center">
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
                                <tr class="text-center">
                                    <td style="display: flex; align-items: center; gap: 10px;">
                                        <img src="<?= htmlspecialchars($p['image']) ?>" alt="<?= htmlspecialchars($p['name']) ?>" style="height: 50px; width: 50px; object-fit: cover; border-radius: 5px;">
                                        <span><?= htmlspecialchars($p['name']) ?></span>
                                    </td>
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
                                <tr>
                                    <td colspan="3"><strong>TỔNG CỘNG:</strong></td>
                                    <td class="text-center"><strong><?= number_format($total) ?> VNĐ</strong></td>
                                    <td></td>
                                </tr>
                            </tbody>
                        </table>

                        <div class="mb-3 text-center fw-bold">Chọn phương thức thanh toán:</div>
                        <div class="d-flex justify-content-around flex-wrap gap-3">
                            <button type="submit" name="gateway" value="momo" class="btn btn-success px-4">
                                <img src="images/momo-logo.png" alt="MoMo" style="height: 25px; margin-right: 8px;"> MoMo
                            </button>
                            <button type="submit" name="gateway" value="vnpay" class="btn btn-success px-4">
                                <img src="images/vnpay-logo.png" alt="VNPay" style="height: 25px; margin-right: 8px;"> VNPAY
                            </button>
                            <button type="submit" name="gateway" value="paypal" class="btn btn-success px-4">
                                <img src="images/paypal-logo.png" alt="PayPal" style="height: 25px; margin-right: 8px;"> PayPal
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    <?php endif; ?>

    <div class="mt-4">
        <a href="index.php" class="btn btn-success">← Tiếp tục mua hàng</a>
    </div>
</body>
</html>
