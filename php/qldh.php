<?php
session_start();
include "../php/check_admin.php";
include "../php/config.php";

$sql = "
    SELECT *
    FROM orders
    ORDER BY id DESC
";
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

<header>
    <div class="container">
        <div class="nav">
            <a href="./trangchu.php"><h2>SNEAKERZONE</h2></a>
            <div class="menu-right">
                <a href="../php/sanpham.php">Sản phẩm</a>
                <a href="../php/contact.php">Liên hệ</a>
                <a href="../php/giohang.php">Giỏ hàng</a>

                <?php if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin') { ?>
                    <a href="../php/qldh.php">Quản lý đơn hàng</a>
                    <a href="../php/qldkh.php">Quản lý khách hàng</a>
                    <a href="../php/qldsp.php">Quản lý sản phẩm</a>
                    <a href="../php/qllh.php">Quản lí liên hệ</a>
                    <a href="../php/thongke.php">Thống kê</a>
                <?php } ?>
            </div>
        </div>
    </div>
</header>

<h1>QUẢN LÝ ĐƠN HÀNG</h1>

<table class="qldh-table">
<tr>
    <th>Mã ĐH</th>
    <th>Khách hàng</th>
    <th>SĐT</th>
    <th>Địa chỉ</th>
    <th>Ngày đặt</th>
    <th>Tổng tiền</th>
    <th>Trạng thái</th>
    <th>Thao tác</th>
</tr>

<?php while ($row = mysqli_fetch_assoc($result)) { ?>
<tr>
    <td>DH<?= $row['id'] ?></td>

    <td><?= htmlspecialchars($row['customer_name'] ?? '') ?></td>

    <td><?= htmlspecialchars($row['phone'] ?? '') ?></td>

    <td><?= htmlspecialchars($row['address'] ?? '') ?></td>

    <td><?= date("d/m/Y H:i", strtotime($row['order_date'])) ?></td>
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
        <a href="qldh_xem.php?id=<?= $row['id'] ?>" class="btn view">Xem</a>
        <a href="qldh_xoa.php?id=<?= $row['id'] ?>"
           onclick="return confirm('Xóa đơn hàng?')"
           class="btn delete">Xóa</a>
    </td>
</tr>
<?php } ?>
</table>

<script src="../js/qldh.js"></script>
</body>
</html>
