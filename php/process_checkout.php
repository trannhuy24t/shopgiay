<?php
session_start();
include "config.php";

if (!isset($_SESSION['user'])) {
    header("Location: ../pages/dangnhap.html");
    exit;
}

if (empty($_SESSION['cart'])) {
    die("Giỏ hàng trống");
}

$user_id = $_SESSION['user']['id'];
$name    = $_POST['customer_name'];
$phone   = $_POST['phone'];
$address = $_POST['address'];

$total = 0;
foreach ($_SESSION['cart'] as $item) {
    $total += $item['price'] * $item['quantity'];
}

/* 1️⃣ LƯU ĐƠN */
$sql = "INSERT INTO orders (user_id, customer_name, phone, address, total_price)
        VALUES (?, ?, ?, ?, ?)";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "isssi", $user_id, $name, $phone, $address, $total);
mysqli_stmt_execute($stmt);

$order_id = mysqli_insert_id($conn);

/* 2️⃣ LƯU CHI TIẾT */
$sql_item = "INSERT INTO order_items 
(order_id, product_id, product_name, quantity, price)
VALUES (?, ?, ?, ?, ?)";
$stmt_item = mysqli_prepare($conn, $sql_item);

foreach ($_SESSION['cart'] as $id => $item) {
    mysqli_stmt_bind_param(
        $stmt_item,
        "iisii",
        $order_id,
        $id,
        $item['name'],
        $item['quantity'],
        $item['price']
    );
    mysqli_stmt_execute($stmt_item);
}

/* 3️⃣ XÓA GIỎ */
unset($_SESSION['cart']);

echo "<script>
alert('🎉 Đặt hàng thành công!');
window.location='donhang.php';
</script>";
