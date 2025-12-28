<?php
session_start();
$cart = $_SESSION['cart'] ?? [];
$total = 0;
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Giỏ hàng</title>
    <link rel="stylesheet" href="../css/giohang.css">
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

<div class="cart-container">
<h1>🛒 Giỏ hàng</h1>

<?php if (empty($cart)) { ?>
    <p>Giỏ hàng trống</p>
<?php } else { ?>
<table class="cart-table">
<tr>
    <th>Ảnh</th>
    <th>Tên</th>
    <th>Giá</th>
    <th>Số lượng</th>
    <th>Thành tiền</th>
    <th>Xóa</th>
</tr>

<?php foreach ($cart as $id => $item) {
    $sub = $item['price'] * $item['quantity'];
    $total += $sub;
?>
<tr>
    <td><img src="../images/<?= $item['image'] ?>" width="80"></td>
    <td><?= $item['name'] ?></td>
    <td><?= number_format($item['price']) ?>đ</td>

    <td>
        <a href="update_cart.php?id=<?= $id ?>&type=minus">➖</a>
        <?= $item['quantity'] ?>
        <a href="update_cart.php?id=<?= $id ?>&type=plus">➕</a>
    </td>

    <td><?= number_format($sub) ?>đ</td>
    <td><a href="remove_cart.php?id=<?= $id ?>">❌</a></td>
</tr>
<?php } ?>
</table>

<h3>Tổng tiền: <span style="color:red"><?= number_format($total) ?>đ</span></h3>

<a href="checkout.php" class="btn-checkout">Thanh toán</a>

<?php } ?>
</div>
</body>
</html>
