<?php
session_start();
include "config.php";

// lấy danh mục
$cat_id = $_GET['cat'] ?? '';

$categories = mysqli_query($conn, "SELECT * FROM categories");

$where = "";
if ($cat_id != '') {
    $where = "WHERE category_id = $cat_id";
}

$sql = "SELECT * FROM products $where ORDER BY id DESC";
$products = mysqli_query($conn, $sql);
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

                <!-- MENU ADMIN -->
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

<!-- DANH MỤC -->
<div class="category">
    <a href="sanpham.php" class="<?= $cat_id==''?'active':'' ?>">Tất cả</a>
    <?php while ($c = mysqli_fetch_assoc($categories)) { ?>
        <a href="sanpham.php?cat=<?= $c['id'] ?>"
           class="<?= $cat_id==$c['id']?'active':'' ?>">
            <?= $c['name'] ?>
        </a>
    <?php } ?>
</div>

<!-- SẢN PHẨM -->
<div class="products">
<?php while ($p = mysqli_fetch_assoc($products)) { ?>
    <div class="product">

        <!-- CLICK VÀO ĐI CHI TIẾT -->
        <a href="chitietsanpham.php?id=<?= $p['id'] ?>" class="product-link">
            <img src="../images/<?= $p['image'] ?>" alt="<?= $p['name'] ?>">
            <h3><?= $p['name'] ?></h3>
        </a>

        <p class="price"><?= number_format($p['price']) ?>đ</p>

        <!-- THÊM GIỎ -->
        <form method="post" action="add_to_card.php">
    <input type="hidden" name="id" value="<?= $p['id'] ?>">
    <input type="hidden" name="name" value="<?= $p['name'] ?>">
    <input type="hidden" name="price" value="<?= $p['price'] ?>">
    <input type="hidden" name="image" value="<?= $p['image'] ?>">
    <button type="submit" class="btn">Thêm giỏ</button>
</form>


    </div>
<?php } ?>
</div>

</body>
</html>
