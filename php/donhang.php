<?php
session_start();
include "config.php";

// bắt buộc đăng nhập
if (!isset($_SESSION['user'])) {
    header("Location: ../pages/dangnhap.html");
    exit;
}

$user_id = $_SESSION['user']['id'];

// lấy đơn hàng của user
$sql = "SELECT * FROM orders WHERE user_id = ? ORDER BY order_date DESC";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result_orders = mysqli_stmt_get_result($stmt);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đơn hàng của tôi</title>
    <link rel="stylesheet" href="../css/donhang.css">
</head>
<body>

<header class="headerrr">
    <div class="container">
        <div class="nav">
            <a href="./trangchu.php"><h2>SNEAKERZONE</h2></a>
            <div class="menu-right">
                <a href="../php/sanpham.php">Sản phẩm</a>
                <a href="#">Liên hệ</a>
                <a href="../php/giohang.php">Giỏ hàng</a>

                <?php if ($_SESSION['user']['role'] === 'admin') { ?>
                    <a href="../php/qldh.php">Quản lý đơn hàng</a>
                    <a href="#">Quản lý khách hàng</a>
                    <a href="#">Quản lý sản phẩm</a>
                    <a href="../php/thongke.php">Thống kê</a>
                <?php } ?>
            </div>
        </div>
    </div>
</header>

<h1>ĐƠN HÀNG CỦA TÔI</h1>

<?php if (mysqli_num_rows($result_orders) == 0): ?>
    <p>Bạn chưa có đơn hàng nào.</p>
<?php endif; ?>

<?php while ($order = mysqli_fetch_assoc($result_orders)): ?>
<div class="order-box">

    <div class="order-header">
        <b>Mã đơn:</b> DH<?= $order['id'] ?> |
        <b>Ngày đặt:</b> <?= date("d/m/Y", strtotime($order['order_date'])) ?> |
        <b>Trạng thái:</b> <?= $order['status'] ?>
    </div>

    <table>
        <tr>
            <th>Sản phẩm</th>
            <th>Giá</th>
            <th>Số lượng</th>
            <th>Tạm tính</th>
        </tr>

        <?php
        // 🔥 GỘP SẢN PHẨM + SỐ LƯỢNG
        $sql_items = "
            SELECT 
                product_name,
                price,
                SUM(quantity) AS qty,
                SUM(quantity * price) AS subtotal
            FROM order_items
            WHERE order_id = ?
            GROUP BY product_name, price
        ";

        $stmt_items = mysqli_prepare($conn, $sql_items);
        mysqli_stmt_bind_param($stmt_items, "i", $order['id']);
        mysqli_stmt_execute($stmt_items);
        $items = mysqli_stmt_get_result($stmt_items);
        ?>

        <?php while ($item = mysqli_fetch_assoc($items)): ?>
        <tr>
            <td><?= htmlspecialchars($item['product_name']) ?></td>
            <td><?= number_format($item['price']) ?>đ</td>
            <td><?= $item['qty'] ?></td>
            <td><?= number_format($item['subtotal']) ?>đ</td>
        </tr>
        <?php endwhile; ?>
    </table>

    <div class="order-total">
        <b>Tổng tiền:</b> <?= number_format($order['total_price']) ?>đ
    </div>

</div>
<?php endwhile; ?>

</body>
</html>
