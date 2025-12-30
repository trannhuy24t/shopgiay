<?php
session_start();
include "config.php";

$id = (int)$_POST['id'];

// 1. Lấy đầy đủ thông tin sản phẩm thay vì chỉ lấy quantity
$check = mysqli_query($conn,
    "SELECT name, price, image, quantity FROM products WHERE id=$id"
);
$p = mysqli_fetch_assoc($check);

// Kiểm tra nếu hết hàng
if (!$p || $p['quantity'] <= 0) {
    header("Location: sanpham.php");
    exit;
}

// 2. Thêm vào session với đầy đủ các khóa (key)
if (isset($_SESSION['cart'][$id])) {
    // Nếu đã có trong giỏ, chỉ tăng số lượng
    $_SESSION['cart'][$id]['quantity'] += 1; 
} else {
    // Nếu chưa có, tạo mảng mới chứa đủ thông tin
    $_SESSION['cart'][$id] = [
        'name'     => $p['name'],
        'price'    => $p['price'],
        'image'    => $p['image'],
        'quantity' => 1
    ];
}

// 3. Trừ kho (Lưu ý: Thường người ta chỉ trừ kho khi đã "Thanh toán", 
// nhưng nếu bạn muốn trừ ngay khi bỏ vào giỏ thì giữ nguyên đoạn này)
// mysqli_query($conn,
//     "UPDATE products SET quantity = quantity - 1 WHERE id=$id"
// );

header("Location: giohang.php");
exit;
?>