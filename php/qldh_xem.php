<?php
session_start();
include "config.php";
include "check_admin.php";

if (!isset($_GET['id'])) {
    die("Thiếu mã đơn hàng");
}

$order_id = (int)$_GET['id'];

/* LẤY ĐƠN HÀNG */
$order = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT * FROM orders WHERE id = $order_id")
);

/* LẤY SẢN PHẨM */
$items = mysqli_query(
    $conn,
    "SELECT * FROM order_items WHERE order_id = $order_id"
);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Chi tiết đơn hàng</title>
<link rel="stylesheet" href="../css/qldh.css">
</head>
<body>

<h2>CHI TIẾT ĐƠN HÀNG DH<?= $order['id'] ?></h2>

<p><b>Khách hàng:</b> <?= $order['customer_name'] ?></p>
<p><b>SĐT:</b> <?= $order['phone'] ?></p>
<p><b>Địa chỉ:</b> <?= $order['address'] ?></p>
<p><b>Ngày đặt:</b> <?= $order['order_date'] ?></p>
<p><b>Trạng thái:</b> <?= $order['status'] ?></p>

<table class="qldh-table">
<tr>
    <th>Sản phẩm</th>
    <th>Giá</th>
    <th>Số lượng</th>
    <th>Thành tiền</th>
</tr>

<?php while ($item = mysqli_fetch_assoc($items)) { ?>
<tr>
    <td><?= $item['product_name'] ?></td>
    <td><?= number_format($item['price']) ?>đ</td>
    <td><?= $item['quantity'] ?></td>
    <td><?= number_format($item['price'] * $item['quantity']) ?>đ</td>
</tr>
<?php } ?>
</table>

<h3>Tổng tiền: <?= number_format($order['total_price']) ?>đ</h3>

<a href="qldh.php" class="btn">← Quay lại</a>

</body>
</html>
