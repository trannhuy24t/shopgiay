<?php
session_start();
include "config.php";
include "check_admin.php";

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Thiếu mã đơn hàng");
}

$order_id = (int)$_GET['id'];

/* ===== ĐƠN HÀNG ===== */
$order_rs = mysqli_query($conn, "SELECT * FROM orders WHERE id = $order_id");
if (mysqli_num_rows($order_rs) == 0) die("Đơn hàng không tồn tại");
$order = mysqli_fetch_assoc($order_rs);

/* ===== CHI TIẾT SẢN PHẨM ===== */
$items = mysqli_query($conn, "
    SELECT
        oi.product_name,
        oi.price,
        oi.quantity,
        p.image
    FROM order_items oi
    JOIN products p ON oi.product_id = p.id
    WHERE oi.order_id = $order_id
");
?>

<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Chi tiết đơn hàng</title>
<link rel="stylesheet" href="../css/qldh.css">
<style>
.qldh-table img{
    border-radius:6px;
    object-fit:cover;
}
</style>
</head>
<body>

<h2>CHI TIẾT ĐƠN HÀNG DH<?= $order['id'] ?></h2>

<p><b>Khách hàng:</b> <?= htmlspecialchars($order['customer_name']) ?></p>
<p><b>SĐT:</b> <?= htmlspecialchars($order['phone']) ?></p>
<p><b>Địa chỉ:</b> <?= htmlspecialchars($order['address']) ?></p>
<p><b>Ngày đặt:</b> <?= date("d/m/Y H:i", strtotime($order['order_date'])) ?></p>
<p><b>Trạng thái:</b> <?= htmlspecialchars($order['status']) ?></p>

<table class="qldh-table">
<tr>
    <th>Ảnh</th>
    <th>Sản phẩm</th>
    <th>Giá</th>
    <th>Số lượng</th>
    <th>Thành tiền</th>
</tr>

<?php while ($item = mysqli_fetch_assoc($items)):

    // ===== XỬ LÝ ẢNH =====
    $img = $item['image'] ?? '';
    $img_arr = explode('|', $img);
    $img_src = !empty($img_arr[0])
        ? "../images/" . $img_arr[0]
        : "../images/no-image.png";

    $sub = $item['price'] * $item['quantity'];
?>
<tr>
    <td>
        <img src="<?= $img_src ?>" width="70" height="70">
    </td>
    <td><?= htmlspecialchars($item['product_name']) ?></td>
    <td><?= number_format($item['price']) ?>đ</td>
    <td><?= $item['quantity'] ?></td>
    <td><?= number_format($sub) ?>đ</td>
</tr>
<?php endwhile; ?>
</table>

<h3>
    Tổng tiền:
    <span style="color:red"><?= number_format($order['total_price']) ?>đ</span>
</h3>

<a href="qldh.php" class="btn">← Quay lại</a>

</body>
</html>
