<?php
session_start();
include "config.php";

/* ===== VALIDATE ===== */
if (!isset($_POST['id'], $_POST['size'], $_POST['color'], $_POST['qty'])) {
    die("Thiếu dữ liệu");
}

$id    = (int)$_POST['id'];
$size  = trim($_POST['size']);
$color = trim($_POST['color']);
$qty   = max(1, (int)$_POST['qty']);

/* ===== CHECK BIẾN THỂ + LẤY variant_id ===== */
$sqlCheck = "
    SELECT id, quantity
    FROM product_variants
    WHERE product_id = ?
      AND size = ?
      AND color = ?
      AND quantity >= ?
    LIMIT 1
";
$stmt = $conn->prepare($sqlCheck);
$stmt->bind_param("issi", $id, $size, $color, $qty);
$stmt->execute();
$variant = $stmt->get_result()->fetch_assoc();

if (!$variant) {
    die("Size hoặc màu không hợp lệ / không đủ hàng");
}

$variant_id = $variant['id'];

/* ===== LẤY SẢN PHẨM ===== */
$sql = "SELECT name, price, image FROM products WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$p = $stmt->get_result()->fetch_assoc();

if (!$p) {
    die("Sản phẩm không tồn tại");
}

/* ===== ẢNH ===== */
$first_img = 'default.jpg';
if (!empty($p['image'])) {
    $imgs = explode('|', $p['image']);
    $first_img = trim($imgs[0]);
}

/* ===== KEY (SP + SIZE + MÀU) ===== */
$key = $id . "_" . $size . "_" . $color;

/* ===== ADD CART ===== */
if (isset($_SESSION['cart'][$key])) {
    $_SESSION['cart'][$key]['quantity'] += $qty;
} else {
    $_SESSION['cart'][$key] = [
        'product_id' => $id,
        'variant_id' => $variant_id, // ✅ CỰC KỲ QUAN TRỌNG
        'name'       => $p['name'],
        'price'      => (int)$p['price'],
        'image'      => $first_img,
        'size'       => $size,
        'color'      => $color,
        'quantity'   => $qty
    ];
}

/* ===== REDIRECT ===== */
header("Location: giohang.php");
exit;
