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
                <a href="../php/contact.php">Liên hệ</a>
                <a href="../php/giohang.php">Giỏ hàng</a>

                <?php if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin') { ?>
                    <a href="../php/qldh.php">Quản lý đơn hàng</a>
                    <a href="../php/QLKH.php">Quản lý khách hàng</a>
                    <a href="../php/qlsp.php">Quản lý sản phẩm</a>
                    <a href="../php/thongke.php">Thống kê</a>
                <?php } ?>
            </div>
        </div>
    </div>
</header>

<div class="cart-container">
    <h1>🛒 Giỏ hàng</h1>

    <?php if (empty($cart)) { ?>
        <p>Giỏ hàng trống. <a href="sanpham.php">Tiếp tục mua sắm</a></p>
    <?php } else { ?>

    <table class="cart-table">
        <tr>
            <th>Ảnh</th>
            <th>Tên</th>
            <th>Size</th>
            <th>Màu</th>
            <th>Giá</th>
            <th>Số lượng</th>
            <th>Thành tiền</th>
            <th>Xóa</th>
        </tr>

        <?php foreach ($cart as $id => $item) {

            $name  = $item['name'] ?? 'Sản phẩm không tên';
            $price = $item['price'] ?? 0;
            $qty   = $item['quantity'] ?? 0;
            $size  = $item['size'] ?? '—';
            $color = $item['color'] ?? '—';
            $img_raw = $item['image'] ?? 'default.jpg';

            $sub   = $price * $qty;
            $total += $sub;

            if (filter_var($img_raw, FILTER_VALIDATE_URL)) {
                $img_src = $img_raw;
            } else {
                $img_src = "../images/" . $img_raw;
            }
        ?>
        <tr>
            <td><img src="<?= $img_src ?>" width="80" style="object-fit:cover"></td>
            <td><?= htmlspecialchars($name) ?></td>
            <td><?= htmlspecialchars($size) ?></td>
            <td><?= htmlspecialchars($color) ?></td>
            <td><?= number_format($price, 0, ',', '.') ?>đ</td>

            <td>
                <a href="update_cart.php?id=<?= $id ?>&type=minus">➖</a>
                <span style="margin:0 10px"><?= $qty ?></span>
                <a href="update_cart.php?id=<?= $id ?>&type=plus">➕</a>
            </td>

            <td><?= number_format($sub, 0, ',', '.') ?>đ</td>
            <td>
                <a href="remove_cart.php?id=<?= $id ?>"
                   onclick="return confirm('Xóa sản phẩm này?')">❌</a>
            </td>
        </tr>
        <?php } ?>
    </table>

    <div style="text-align:right; margin-top:20px">
        <h3>
            Tổng tiền:
            <span style="color:red">
                <?= number_format($total, 0, ',', '.') ?>đ
            </span>
        </h3>
        <br>
        <a href="checkout.php"
           style="background:#28a745;color:#fff;padding:10px 20px;
                  text-decoration:none;border-radius:5px">
            Thanh toán ngay
        </a>
    </div>

    <?php } ?>
</div>
</body>
</html>
