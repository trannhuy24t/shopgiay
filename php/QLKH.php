<?php
session_start();
include "../php/check_admin.php";
include "../php/config.php";

// Lấy danh sách khách hàng từ orders
$sql = "
    SELECT 
        phone,
        MAX(customer_name) AS customer_name,
        MAX(email) AS email,
        MAX(address) AS address,
        COUNT(id) AS total_orders,
        SUM(total_price) AS total_spent,
        MAX(order_date) AS last_order
    FROM orders
    WHERE status != 'Hủy'
    GROUP BY phone
    ORDER BY last_order DESC
";


$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản Lý Khách Hàng</title>
    <link rel="stylesheet" href="../css/qlkh.css">
</head>
<body>

<!-- HEADER (GIỐNG QLDH) -->
<header>
    <div class="container">
        <div class="nav">
            <a href="./trangchu.php"><h2>SNEAKERZONE</h2></a>
            <div class="menu-right">
                <a href="../php/sanpham.php">Sản phẩm</a>
                <a href="#">Liên hệ</a>
                <a href="../php/giohang.php">Giỏ hàng</a>

                <?php if ($_SESSION['user']['role'] === 'admin') { ?>
                    <a href="../php/qldh.php">Quản lý đơn hàng</a>
                    <a href="../php/qlkh.php">Quản lý khách hàng</a>
                    <a href="../php/qlsp.php">Quản lý sản phẩm</a>
                    <a href="../php/thongke.php">Thống kê</a>
                <?php } ?>
            </div>
        </div>
    </div>
</header>

<div class="qlkh-container">
    <h1>QUẢN LÝ KHÁCH HÀNG</h1>

<table class="qlkh-table">
    <tr>
        <th>#</th>
        <th>Tên khách</th>
        <th>SĐT</th>
        <th>Email</th>
        <th>Địa chỉ</th>
        <th>Số đơn</th>
        <th>Tổng chi</th>
        <th>Lần mua cuối</th>
        <th>Thao tác</th>
    </tr>

<?php $i = 1; while ($row = mysqli_fetch_assoc($result)) { ?>
<tr>
    <td><?= $i++ ?></td>

    <td><?= htmlspecialchars($row['customer_name']) ?></td>

    <td><?= htmlspecialchars($row['phone']) ?></td>

    <td>
    <?= !empty($row['email']) ? htmlspecialchars($row['email']) : '<i>Không có</i>' ?>
</td>

<td><?= htmlspecialchars($row['address']) ?></td>


    <td><?= $row['total_orders'] ?></td>

    <td style="color:red">
        <?= number_format($row['total_spent']) ?>đ
    </td>

    <td><?= date("d/m/Y", strtotime($row['last_order'])) ?></td>

    <td>
        <a href="qlkh_xem.php?phone=<?= $row['phone'] ?>" class="btn view">
            Xem
        </a>
    </td>
</tr>
<?php } ?>
</table>
</div>
</body>
</html>
