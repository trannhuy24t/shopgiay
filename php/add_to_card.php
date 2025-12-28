<?php
session_start();
include "config.php";

$id = intval($_GET['id'] ?? $_POST['id'] ?? 0);

if ($id <= 0) {
    die("ID sản phẩm không hợp lệ");
}

$sql = "SELECT * FROM products WHERE id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$product = mysqli_fetch_assoc($result);

if (!$product) {
    die("Sản phẩm không tồn tại");
}

if (!isset($_SESSION['cart'][$id])) {
    $_SESSION['cart'][$id] = [
        'product_id' => $product['id'],
        'name'       => $product['name'],
        'price'      => $product['price'],
        'image'      => $product['image'],
        'quantity'   => 1
    ];
} else {
    $_SESSION['cart'][$id]['quantity']++;
}

header("Location: giohang.php");
exit;
?>