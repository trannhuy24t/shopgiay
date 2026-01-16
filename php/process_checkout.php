<?php
session_start();
include "config.php";

// 1️⃣ Kiểm tra giỏ hàng
if (empty($_SESSION['cart'])) {
    die("Giỏ hàng trống");
}

// 2️⃣ Lấy thông tin khách
$customer_name = trim($_POST['customer_name'] ?? '');
$phone         = trim($_POST['phone'] ?? '');
$email = trim($_POST['email'] ?? '');
$email = $email === '' ? null : $email;
$address       = trim($_POST['address'] ?? '');

// Validate
if ($customer_name === '' || $phone === '' || $address === '') {
    die("Vui lòng nhập đầy đủ thông tin");
}

// 3️⃣ Tính tổng tiền
$total = 0;
foreach ($_SESSION['cart'] as $item) {
    $total += $item['price'] * $item['quantity'];
}

// 4️⃣ Lấy user_id (nếu đăng nhập)
$user_id = $_SESSION['user']['id'] ?? null;

// 5️⃣ Lưu đơn hàng
$sql = "INSERT INTO orders (user_id, customer_name, phone, email,address, total_price, status, order_date)
        VALUES (?, ?, ?, ?, ?, ?, 'Chờ xử lý', NOW())";

$stmt = $conn->prepare($sql);
$stmt->bind_param("issssi", $user_id, $customer_name, $phone, $email, $address, $total);
$stmt->execute();

// Lấy order_id vừa tạo
$order_id = $conn->insert_id;

// 6️⃣ Lưu chi tiết đơn hàng & TRỪ SỐ LƯỢNG TRONG KHO
foreach ($_SESSION['cart'] as $item) {

    $product_id   = $item['product_id'];
    $product_name = $item['name']; // ✅ TÊN SP
    $quantity     = $item['quantity'];
    $price        = $item['price'];

    $sqlItem = "INSERT INTO order_items 
        (order_id, product_id, product_name, quantity, price)
        VALUES (?, ?, ?, ?, ?)";

    $stmtItem = $conn->prepare($sqlItem);
    $stmtItem->bind_param(
        "iisid",
        $order_id,
        $product_id,
        $product_name,
        $quantity,
        $price
    );
    $stmtItem->execute();
}


// 7️⃣ Xóa giỏ hàng
unset($_SESSION['cart']);

// 8️⃣ Thông báo + chuyển trang
echo "
<script>
    alert('Đặt hàng thành công!');
    window.location.href = 'trangchu.php';
</script>
";
exit;
?>