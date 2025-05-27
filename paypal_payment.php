<?php
session_start();

use PayPalCheckoutSdk\Orders\OrdersCreateRequest;
require 'vendor/autoload.php'; // SDK PayPal đã cài bằng Composer

// Kiểm tra đơn hàng
if (!isset($_SESSION['last_order'])) {
    echo "Không tìm thấy đơn hàng để thanh toán.";
    exit;
}

// Cấu hình PayPal Sandbox
$clientId = "ActPpZUWz-RS670JzMs7zemeHbhPNb0mDCDTZ5V4DsmPye2VCmxPMd8UHjk_V9bD1qSWKF89_Ko-d_aN";
$clientSecret = "YOUR_SANDBOX_SECRET";

$environment = new \PayPalCheckoutSdk\Core\SandboxEnvironment($clientId, $clientSecret);
$client = new \PayPalCheckoutSdk\Core\PayPalHttpClient($environment);

// Lấy thông tin đơn hàng
$order = $_SESSION['last_order'];
$orderId = $order['code'] ?? ('OD' . time());
$amount = $order['amount'] ?? 0;

// Tạo yêu cầu tạo đơn PayPal
$request = new OrdersCreateRequest();
$request->prefer('return=representation');
$request->body = [
    "intent" => "CAPTURE",
    "purchase_units" => [[
        "reference_id" => $orderId,
        "amount" => [
            "value" => number_format($amount, 2, '.', ''),
            "currency_code" => "USD"
        ]
    ]],
    "application_context" => [
        "cancel_url" => "http://localhost/paypal_cancel.php",
        "return_url" => "http://localhost/paypal_return.php"
    ]
];

// Gửi yêu cầu
try {
    $response = $client->execute($request);
    foreach ($response->result->links as $link) {
        if ($link->rel === 'approve') {
            // Chuyển hướng người dùng đến PayPal để thanh toán
            header('Location: ' . $link->href);
            exit;
        }
    }
    echo "Không tìm thấy liên kết thanh toán.";
} catch (Exception $e) {
    echo "Lỗi kết nối PayPal: " . $e->getMessage();
}
?>
