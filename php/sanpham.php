<?php
session_start();
include "config.php";

$cat_id = $_GET['cat'] ?? '';

$categories = mysqli_query($conn, "SELECT * FROM categories");

$where = "";
if ($cat_id != '') {
    $where = "WHERE category_id = $cat_id";
}

$products = mysqli_query($conn,
    "SELECT * FROM products $where ORDER BY id DESC"
);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Sản phẩm</title>
    <link rel="stylesheet" href="../css/sanpham.css">
</head>
<body>
    <!-- HEADER -->
 <header>
    <div class="container">
        <div class="nav">
            <a href="./trangchu.php"><h2>SNEAKERZONE</h2></a>
            <div class="menu-right">
                <a href="../php/sanpham.php">Sản phẩm</a>
                <a href="#">Liên hệ</a>
                <a href="../php/giohang.php">Giỏ hàng</a>

               <!-- MENU ADMIN (CHỈ ADMIN THẤY) -->
<?php if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin') { ?>
    <a href="../php/qldh.php">Quản lý đơn hàng</a>
    <a href="../php/qlkh.php">Quản lý khách hàng</a>
    <a href="#">Quản lý sản phẩm</a>
    <a href="../php/thongke.php">Thống kê</a>
<?php } ?>
</header>

<div class="products">
<?php while ($p = mysqli_fetch_assoc($products)) {

    // lấy ảnh đầu tiên
    $imgs = explode('|', $p['image']);
    $first = $imgs[0];

    if (filter_var($first, FILTER_VALIDATE_URL)) {
        $src = $first;
    } else {
        $src = "../images/" . $first;
    }
?>
    <div class="product">
        <a href="chitietsanpham.php?id=<?= $p['id'] ?>">
            <img src="<?= $src ?>" alt="<?= $p['name'] ?>">
            <h3><?= $p['name'] ?></h3>
        </a>

        <p class="price"><?= number_format($p['price']) ?>đ</p>
        <p class="qty">Còn: <?= $p['quantity'] ?></p>

        <?php if ($p['quantity'] > 0) { ?>
        <form method="post" action="add_to_card.php">
            <input type="hidden" name="id" value="<?= $p['id'] ?>">
            <button type="submit">Thêm giỏ</button>
        </form>
        <?php } else { ?>
            <button disabled>Hết hàng</button>
        <?php } ?>
    </div>
<?php } ?>
</div>

</body>
</html>
