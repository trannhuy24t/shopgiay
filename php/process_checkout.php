<?php
session_start();
include "config.php";

/* ===== KIỂM TRA ===== */
if (empty($_SESSION['cart'])) {
    die("Giỏ hàng trống");
}

if (!isset($_SESSION['user'])) {
    die("Chưa đăng nhập");
}

$user_id = $_SESSION['user']['id'];

$customer_name = trim($_POST['customer_name'] ?? '');
$phone   = trim($_POST['phone'] ?? '');
$email   = trim($_POST['email'] ?? '') ?: null;
$address = trim($_POST['address'] ?? '');

if ($customer_name === '' || $phone === '' || $address === '') {
    die("Thiếu thông tin");
}

/* ===== TÍNH TỔNG ===== */
$total = 0;
foreach ($_SESSION['cart'] as $item) {
    $total += $item['price'] * $item['quantity'];
}

/* ===== TẠO ĐƠN HÀNG ===== */
$sqlOrder = "
INSERT INTO orders 
(user_id, customer_name, phone, email, address, total_price, status, order_date)
VALUES (?, ?, ?, ?, ?, ?, 'Chờ xử lý', NOW())
";
$stmtOrder = $conn->prepare($sqlOrder);
$stmtOrder->bind_param(
    "issssi",
    $user_id,
    $customer_name,
    $phone,
    $email,
    $address,
    $total
);
$stmtOrder->execute();

$order_id = $conn->insert_id;

/* ===== CHI TIẾT ĐƠN + TRỪ KHO ===== */
foreach ($_SESSION['cart'] as $item) {

    $product_id = $item['product_id'];
    $variant_id = $item['variant_id']; // ✅ LẤY TRỰC TIẾP
    $qty   = $item['quantity'];
    $price = $item['price'];

    /* 🔒 CHECK KHO (CHỐNG ÂM) */
    $sqlCheck = "
        SELECT quantity 
        FROM product_variants
        WHERE id = ?
        LIMIT 1
    ";
    $stmtCheck = $conn->prepare($sqlCheck);
    $stmtCheck->bind_param("i", $variant_id);
    $stmtCheck->execute();
    $stock = $stmtCheck->get_result()->fetch_assoc();

    if (!$stock || $stock['quantity'] < $qty) {
        die("Không đủ hàng cho sản phẩm: " . htmlspecialchars($item['name']));
    }

    /* ===== LƯU ORDER ITEM ===== */
    $sqlItem = "
        INSERT INTO order_items 
        (order_id, product_id, variant_id, price, quantity)
        VALUES (?, ?, ?, ?, ?)
    ";
    $stmtItem = $conn->prepare($sqlItem);
    $stmtItem->bind_param(
        "iiiii",
        $order_id,
        $product_id,
        $variant_id,
        $price,
        $qty
    );
    $stmtItem->execute();

    /* ===== TRỪ KHO ===== */
    $sqlStock = "
        UPDATE product_variants
        SET quantity = quantity - ?
        WHERE id = ? AND quantity >= ?
    ";
    $stmtStock = $conn->prepare($sqlStock);
    $stmtStock->bind_param("iii", $qty, $variant_id, $qty);
    $stmtStock->execute();

    if ($stmtStock->affected_rows == 0) {
        die("Lỗi trừ kho sản phẩm: " . htmlspecialchars($item['name']));
    }
}

/* ===== CLEAR CART ===== */
unset($_SESSION['cart']);

echo "<script>
    alert('Đặt hàng thành công');
    location.href='trangchu.php';
</script>";
exit;
