<?php
include "config.php";

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Sản phẩm không tồn tại");
}

$id = (int)$_GET['id'];

// SQL lấy thông tin sản phẩm và tên danh mục
$sql = "SELECT p.*, c.name as cat_name 
        FROM products p 
        LEFT JOIN categories c ON p.category_id = c.id 
        WHERE p.id = $id";

$result = mysqli_query($conn, $sql);
if (mysqli_num_rows($result) == 0) {
    die("Sản phẩm không tồn tại");
}
$p = mysqli_fetch_assoc($result);
$images = explode('|', $p['image']);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($p['name']) ?> - SneakerZone</title>
    <link rel="stylesheet" href="../css/chitietsanpham.css">
</head>
<body>

<header>
    </header>

<div class="detail-container">
    <div class="detail-left">
        <div class="image-gallery">
            <?php foreach ($images as $img) {
                $img = trim($img);
                if ($img == '') continue;
                $src = (filter_var($img, FILTER_VALIDATE_URL)) ? $img : "../images/" . $img;
            ?>
                <img src="<?= $src ?>" alt="<?= htmlspecialchars($p['name']) ?>">
            <?php } ?>
        </div>
    </div>

    <div class="detail-right">
        <span class="category-tag">🏷️ <?= $p['cat_name'] ?? 'Chưa phân loại' ?></span>
        
        <h1><?= htmlspecialchars($p['name']) ?></h1>
        
        <div class="price-large"><?= number_format($p['price']) ?>đ</div>
        
        <div class="inventory-status">
            👟 Tình trạng: 
            <strong>
                <?= $p['quantity'] > 0 ? "Còn hàng (".$p['quantity'].")" : "<span style='color:red'>Hết hàng</span>" ?>
            </strong>
        </div>

        <div class="description-box">
            <h3>📖 Giới thiệu sản phẩm</h3>
            <div class="description-content">
                <?= !empty($p['description']) ? htmlspecialchars($p['description']) : "Nội dung đang được cập nhật..." ?>
            </div>
        </div>

        <div class="action-area">
            <?php if ($p['quantity'] > 0) { ?>
                <form method="post" action="add_to_card.php">
                    <input type="hidden" name="id" value="<?= $p['id'] ?>">
                    <button type="submit" class="btn-buy">Thêm vào giỏ hàng</button>
                </form>
            <?php } ?>
            <a href="sanpham.php" class="back-link">← Quay lại danh sách sản phẩm</a>
        </div>
    </div>
</div>

</body>
</html>