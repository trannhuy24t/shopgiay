<?php
session_start();

$id = $_GET['id'] ?? null;

if ($id === null) {
    header("Location: giohang.php");
    exit;
}

// Nếu sản phẩm tồn tại trong giỏ thì xóa
if (isset($_SESSION['cart'][$id])) {
    unset($_SESSION['cart'][$id]);
}

// Quay lại giỏ hàng
header("Location: giohang.php");
exit;
?>