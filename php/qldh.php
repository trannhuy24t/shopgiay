<?php
session_start();
include "../php/check_admin.php";
include "../php/config.php";

$sql = "SELECT * FROM orders ORDER BY id DESC";
$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản Lý Đơn Hàng</title>
    <link rel="stylesheet" href="../css/qldh.css">
</head>
<body>

<!-- HEADER -->
 <header>
    <div class="container">
        <div class="nav">
            <a href="./trangchu.php"><h2>SNEAKERZONE</h2></a>
            <div class="menu-right">
                <a href="#">Sản phẩm</a>
                <a href="#">Liên hệ</a>
                <a href="#">Giỏ hàng</a>

               <!-- MENU ADMIN (CHỈ ADMIN THẤY) -->
<?php if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin') { ?>
    <a href="../php/qldh.php">Quản lý đơn hàng</a>
    <a href="#">Quản lý khách hàng</a>
    <a href="#">Quản lý sản phẩm</a>
    <a href="../php/thongke.php">Thống kê</a>
<?php } ?>
</header>

<h1>QUẢN LÝ ĐƠN HÀNG</h1>

<table>
    <tr>
        <th>Mã ĐH</th>
        <th>Khách Hàng</th>
        <th>Ngày Đặt</th>
        <th>Tổng Tiền</th>
        <th>Trạng Thái</th>
        <th>Thao Tác</th>
    </tr>

    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
    <tr>
        <td>DH<?= $row['id'] ?></td>
        <td><?= htmlspecialchars($row['user_name']) ?></td>
        <td><?= $row['order_date'] ?></td>
        <td><?= number_format($row['total_price']) ?>đ</td>
        <td>
            
            <select class="order-status" data-id="<?= $row['id'] ?>">
                <option value="Chờ xử lý" <?= $row['status']=='Chờ xử lý'?'selected':'' ?>>Chờ xử lý</option>
                <option value="Đang giao" <?= $row['status']=='Đang giao'?'selected':'' ?>>Đang giao</option>
                <option value="Hoàn thành" <?= $row['status']=='Hoàn thành'?'selected':'' ?>>Hoàn thành</option>
                <option value="Hủy" <?= $row['status']=='Hủy'?'selected':'' ?>>Hủy</option>
            </select>
        </td>
        <td>
            <button class="btn view">Xem</button>
            <button class="btn edit">Sửa</button>
            <button class="btn delete">Xóa</button>
        </td>
    </tr>
    <?php } ?>
</table>

<!-- JS xử lý cập nhật trạng thái -->
<script src="../js/qldh.js"></script>

</body>
</html>
