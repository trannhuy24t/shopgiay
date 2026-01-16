<?php
session_start();
include "config.php";

$cat_id = $_GET['cat'] ?? '';

/* ===== LẤY DANH MỤC ===== */
$categories = mysqli_query($conn, "SELECT * FROM categories");

/* ===== ĐIỀU KIỆN ===== */
$where = "";
if ($cat_id != '') {
    $where = "WHERE p.category_id = $cat_id";
}

/* ===== SẢN PHẨM + TỔNG KHO TỪ VARIANT ===== */
$products = mysqli_query($conn, "
    SELECT 
        p.*,
        IFNULL(SUM(v.quantity), 0) AS total_qty
    FROM products p
    LEFT JOIN product_variants v ON p.id = v.product_id
    $where
    GROUP BY p.id
    ORDER BY p.id DESC
");
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

                <?php if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin') { ?>
                    <a href="../php/qldh.php">Quản lý đơn hàng</a>
                    <a href="../php/qlkh.php">Quản lý khách hàng</a>
                    <a href="../php/qlsp.php">Quản lý sản phẩm</a>
                    <a href="../php/thongke.php">Thống kê</a>
                <?php } ?>
            </div>
        </div>
    </div>
</header>

<!-- DANH SÁCH SẢN PHẨM -->
<div class="products">

<?php while ($p = mysqli_fetch_assoc($products)):

    /* ẢNH ĐẦU TIÊN */
    $imgs = explode('|', $p['image']);
    $first = trim($imgs[0] ?? 'default.jpg');

    if (filter_var($first, FILTER_VALIDATE_URL)) {
        $src = $first;
    } else {
        $src = "../images/" . $first;
    }
?>

    <div class="product">
        <a href="chitietsanpham.php?id=<?= $p['id'] ?>">
            <img src="<?= $src ?>" alt="<?= htmlspecialchars($p['name']) ?>">
            <h3><?= htmlspecialchars($p['name']) ?></h3>
        </a>

        <p class="price"><?= number_format($p['price']) ?>đ</p>

        <!-- KHO -->
        <p class="qty">
            <?= $p['total_qty'] > 0 ? "Còn: {$p['total_qty']}" : "Hết hàng" ?>
        </p>

        <!-- NÚT -->
        <?php if ($p['total_qty'] > 0) { ?>
            <a href="chitietsanpham.php?id=<?= $p['id'] ?>" class="btn-add">
                Thêm giỏ
            </a>
        <?php } else { ?>
            <button disabled>Hết hàng</button>
        <?php } ?>
    </div>

<?php endwhile; ?>

</div>

</body>
</html>
