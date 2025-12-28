<?php
session_start();
include "config.php";
include "../php/check_admin.php";

$from_date   = $_GET['from_date'] ?? '';
$to_date     = $_GET['to_date'] ?? '';
$trangthai   = $_GET['trangthai'] ?? '';
$kieuthongke = $_GET['kieuthongke'] ?? 'Thống kê theo đơn';

$where = "WHERE 1=1";

if ($from_date != '') $where .= " AND order_date >= '$from_date'";
if ($to_date != '')   $where .= " AND order_date <= '$to_date'";
if ($trangthai != '') $where .= " AND status = '$trangthai'";

// Lấy tất cả đơn (dùng cho bảng)
$sql = "SELECT * FROM orders $where ORDER BY order_date DESC";
$result = mysqli_query($conn, $sql);

$tongdon  = 0;
$tongtien = 0;
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thống Kê Đơn Hàng</title>
    <link rel="stylesheet" href="../css/thongke.css">
</head>
<body>
<header>
    <div class="container">
        <div class="nav">
            <a href="./trangchu.php"><h2>SNEAKERZONE</h2></a>
            <div class="menu-right">
                <a href="../php/sanpham.php">Sản phẩm</a>
                <a href="#">Liên hệ</a>
                <a href="../php/giohang.php">Giỏ hàng</a>
<?php if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin') { ?>
    <a href="../php/qldh.php">Quản lý đơn hàng</a>
    <a href="#">Quản lý khách hàng</a>
    <a href="#">Quản lý sản phẩm</a>
    <a href="../php/thongke.php">Thống kê</a>
<?php } ?>
            </div>
        </div>
    </div>
</header>

<h1>THỐNG KÊ ĐƠN HÀNG</h1>

<form class="stat-box" method="get">
    <label>Từ ngày:</label>
    <input type="date" name="from_date" value="<?= $from_date ?>">

    <label>Đến ngày:</label>
    <input type="date" name="to_date" value="<?= $to_date ?>">

    <label>Trạng thái:</label>
    <select name="trangthai">
        <option value="">-- Tất cả --</option>
        <option <?= $trangthai=='Chờ xử lý'?'selected':'' ?>>Chờ xử lý</option>
        <option <?= $trangthai=='Đang giao'?'selected':'' ?>>Đang giao</option>
        <option <?= $trangthai=='Hoàn thành'?'selected':'' ?>>Hoàn thành</option>
        <option <?= $trangthai=='Hủy'?'selected':'' ?>>Hủy</option>
    </select>

    <label>Kiểu thống kê:</label>
    <select name="kieuthongke">
        <option <?= $kieuthongke=='Thống kê theo đơn'?'selected':'' ?>>Thống kê theo đơn</option>
        <option <?= $kieuthongke=='Thống kê doanh thu'?'selected':'' ?>>Thống kê doanh thu</option>
    </select>

    <button type="submit" class="btn-stat">Thống kê</button>
</form>

<table>
    <tr>
        <th>Mã ĐH</th>
        <th>Ngày Đặt</th>
        <th>Khách Hàng</th>
        <th>Tổng Tiền</th>
        <th>Trạng Thái</th>
    </tr>

<?php if (mysqli_num_rows($result) > 0): ?>
<?php while ($row = mysqli_fetch_assoc($result)):
    $tongdon++;
    // Chỉ tính tổng tiền các đơn không bị hủy
    if ($row['status'] != 'Hủy') $tongtien += $row['total_price'];
?>
<tr>
    <td>DH<?= $row['id'] ?></td>
    <td><?= date("d/m/Y", strtotime($row['order_date'])) ?></td>
    <td><?= $row['customer_name'] ?></td>
    <td><?= number_format($row['total_price']) ?>đ</td>
    <td><?= $row['status'] ?></td>
</tr>
<?php endwhile; ?>
<?php else: ?>
<tr>
    <td colspan="5">Không có dữ liệu</td>
</tr>
<?php endif; ?>
</table>

<div class="summary">
    Tổng số đơn: <b><?= $tongdon ?></b> |
    Tổng doanh thu (không tính đơn hủy): <b><?= number_format($tongtien) ?>đ</b>
</div>

</body>
</html>
