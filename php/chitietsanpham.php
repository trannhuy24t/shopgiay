<?php
session_start();
include "config.php";

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Sản phẩm không tồn tại");
}

$id = (int)$_GET['id'];

/* LẤY SẢN PHẨM */
$sql = "SELECT p.*, c.name as cat_name
        FROM products p
        LEFT JOIN categories c ON p.category_id = c.id
        WHERE p.id = $id";
$res = mysqli_query($conn, $sql);
if (mysqli_num_rows($res) == 0) die("Sản phẩm không tồn tại");
$p = mysqli_fetch_assoc($res);

/* LẤY BIẾN THỂ SIZE + MÀU */
$variants = mysqli_query(
    $conn,
    "SELECT * FROM product_variants 
     WHERE product_id = $id AND quantity > 0
     ORDER BY size, color"
);

/* ẢNH */
$images = array_filter(explode('|', $p['image']));
?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title><?= htmlspecialchars($p['name']) ?></title>
<link rel="stylesheet" href="../css/chitietsanpham.css">
</head>
<body>

<header class="headerrr">
    <div class="container">
        <div class="nav">
            <a href="./trangchu.php"><h2>SNEAKERZONE</h2></a>
            <div class="menu-right">
                <a href="sanpham.php">Sản phẩm</a>
                <a href="giohang.php">Giỏ hàng</a>
            </div>
        </div>
    </div>
</header>

<div class="detail-container">
<!-- ========== TRÁI: ẢNH ========== -->
<div class="detail-left">
<?php foreach ($images as $i => $img):
    $src = filter_var($img, FILTER_VALIDATE_URL) ? $img : "../images/$img"; ?>
    <img src="<?= $src ?>" class="detail-img">
<?php endforeach; ?>
</div>

<!-- ========== PHẢI: THÔNG TIN ========== -->
<div class="detail-right">
<span class="category-tag">🏷 <?= $p['cat_name'] ?></span>
<h1><?= htmlspecialchars($p['name']) ?></h1>
<div class="price-large"><?= number_format($p['price']) ?>đ</div>

<div class="description-box">
<?= nl2br(htmlspecialchars($p['description'] ?? 'Đang cập nhật')) ?>
</div>

<?php if (mysqli_num_rows($variants) > 0) { ?>
<form method="post" action="../php/add_to_card.php" class="variant-form">
    <input type="hidden" name="id" value="<?= $id ?>">

    <label>Size</label>
    <select name="size" required>
        <option value="">-- Chọn size --</option>
        <?php
        mysqli_data_seek($variants, 0);
        $sizes = [];
        while ($v = mysqli_fetch_assoc($variants)) {
            if (!in_array($v['size'], $sizes)) {
                $sizes[] = $v['size'];
                echo "<option value='{$v['size']}'>{$v['size']}</option>";
            }
        }
        ?>
    </select>

    <label>Màu sắc</label>
    <select name="color" required>
        <option value="">-- Chọn màu --</option>
        <?php
        mysqli_data_seek($variants, 0);
        $colors = [];
        while ($v = mysqli_fetch_assoc($variants)) {
            if (!in_array($v['color'], $colors)) {
                $colors[] = $v['color'];
                echo "<option value='{$v['color']}'>{$v['color']}</option>";
            }
        }
        ?>
    </select>

    <label>Số lượng</label>
    <input type="number" name="qty" value="1" min="1">

    <button type="submit" class="btn-buy">
        🛒 Thêm vào giỏ hàng
    </button>
</form>

<?php } else { ?>
<p style="color:red;font-weight:bold">Hết hàng</p>
<?php } ?>

<a href="sanpham.php" class="back-link">← Quay lại</a>
</div>
</div>

</body>
</html>
?>