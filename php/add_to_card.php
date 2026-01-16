<?php
session_start();
include "config.php";

if (
    !isset($_POST['id'], $_POST['size'], $_POST['color'], $_POST['qty'])
) {
    die("Thiếu dữ liệu");
}

$id    = (int)$_POST['id'];
$size  = trim($_POST['size']);
$color = trim($_POST['color']);
$qty   = max(1, (int)$_POST['qty']); // đảm bảo >= 1

// Lấy thông tin sản phẩm
$sql = "SELECT name, price, image FROM products WHERE id = $id";
$res = mysqli_query($conn, $sql);
$p = mysqli_fetch_assoc($res);

if (!$p) {
    die("Sản phẩm không tồn tại");
}

// 👉 LẤY ẢNH ĐẦU TIÊN
$imgs = explode('|', $p['image']);
$first_img = $imgs[0] ?? 'default.jpg';

// Key duy nhất theo sp + size + màu
$key = $id . "_" . $size . "_" . $color;

// Thêm vào giỏ
if (isset($_SESSION['cart'][$key])) {
    $_SESSION['cart'][$key]['quantity'] += $qty;
} else {
    $_SESSION['cart'][$key] = [
        'product_id' => $id,
        'name'       => $p['name'],
        'price'      => $p['price'],
        'image'      => $first_img, // ✅ ẢNH ĐÚNG
        'size'       => $size,
        'color'      => $color,
        'quantity'   => $qty
    ];
}

// Chuyển sang giỏ hàng
header("Location: ../php/giohang.php");
exit;
?>