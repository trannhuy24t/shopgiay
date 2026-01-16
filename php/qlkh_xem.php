<?php
session_start();
include "../php/check_admin.php";
include "../php/config.php";

$phone = $_GET['phone'] ?? '';
$phone = mysqli_real_escape_string($conn, $phone);

/* =========================
   THÔNG TIN TỔNG HỢP KHÁCH
   (KHÔNG TÍNH ĐƠN HỦY)
========================= */
$info_sql = "
    SELECT 
        phone,
        MAX(customer_name) AS customer_name,
        MAX(email) AS email,
        MAX(address) AS address,
        COUNT(id) AS total_orders,
        SUM(total_price) AS total_spent
    FROM orders
    WHERE phone = '$phone'
      AND status != 'Hủy'
";

$info_result = mysqli_query($conn, $info_sql);
$customer = mysqli_fetch_assoc($info_result);

/* =========================
   LỊCH SỬ ĐƠN HÀNG
   (HIỂN THỊ TẤT CẢ, KỂ CẢ HỦY)
========================= */
$orders_sql = "
    SELECT *
    FROM orders
    WHERE phone = '$phone'
    ORDER BY order_date DESC
";
$orders = mysqli_query($conn, $orders_sql);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Chi tiết khách hàng</title>
    <link rel="stylesheet" href="../css/qlkh.css">
</head>
<body>

<h1>CHI TIẾT KHÁCH HÀNG</h1>

<div class="customer-info">
    <p><b>Tên:</b> <?= htmlspecialchars($customer['customer_name'] ?? '') ?></p>

    <p><b>SĐT:</b> <?= htmlspecialchars($customer['phone'] ?? '') ?></p>

    <p><b>Email:</b>
        <?= !empty($customer['email']) 
            ? htmlspecialchars($customer['email']) 
            : '<i>Không có</i>' ?>
    </p>

    <p><b>Địa chỉ:</b> <?= htmlspecialchars($customer['address'] ?? '') ?></p>

    <p><b>Số đơn hợp lệ:</b> <?= $customer['total_orders'] ?? 0 ?></p>

    <p><b>Tổng chi (không tính đơn hủy):</b>
        <span style="color:red">
            <?= number_format($customer['total_spent'] ?? 0) ?>đ
        </span>
    </p>
</div>

<h2>LỊCH SỬ ĐƠN HÀNG</h2>

<table class="qlkh-table">
    <tr>
        <th>Mã ĐH</th>
        <th>Ngày đặt</th>
        <th>Tổng tiền</th>
        <th>Trạng thái</th>
    </tr>

<?php while ($o = mysqli_fetch_assoc($orders)) { ?>
<tr>
    <td>DH<?= $o['id'] ?></td>

    <td><?= date("d/m/Y", strtotime($o['order_date'])) ?></td>

    <td><?= number_format($o['total_price']) ?>đ</td>

    <td 
      style="color:<?= $o['status']=='Hủy' ? 'red' : 'green' ?>">
      <?= $o['status'] ?>
    </td>
</tr>
<?php } ?>
</table>

<br>
<a href="qlkh.php" class="btn view">⬅ Quay lại</a>

</body>
</html>
