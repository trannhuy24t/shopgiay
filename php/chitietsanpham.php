<?php
include "config.php";

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Sản phẩm không tồn tại");
}

$id = (int)$_GET['id'];

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

<header class="headerrr">
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
                    <a href="../php/qlsp.php">Quản lý sản phẩm</a>
                    <a href="../php/thongke.php">Thống kê</a>
                <?php } ?>
            </div>
        </div>
    </div>
</header>

<div class="detail-container">
    <div class="detail-left">
       <div class="slideshow-container">
    <?php 
    // Tách chuỗi và dùng array_filter để loại bỏ các phần tử rỗng
    $images = array_filter(explode('|', $p['image'])); 

    foreach ($images as $index => $img) {
        $img = trim($img); // Loại bỏ dấu cách thừa ở 2 đầu tên file
        if ($img == '') continue;

        // Kiểm tra xem là link URL hay tên file trong thư mục images
        $src = (filter_var($img, FILTER_VALIDATE_URL)) ? $img : "../images/" . $img;
    ?>
        <div class="mySlides fade">
            <img src="<?= $src ?>" style="width:100%" onerror="this.src='../images/default.jpg'; console.log('Không tìm thấy: <?= $src ?>');">
        </div>
    <?php } ?>

    <a class="prev" onclick="plusSlides(-1)">&#10094;</a>
    <a class="next" onclick="plusSlides(1)">&#10095;</a>
</div>

        <div class="thumbnail-row">
            <?php foreach ($images as $index => $img) {
                $img = trim($img);
                if ($img == '') continue;
                $src = (filter_var($img, FILTER_VALIDATE_URL)) ? $img : "../images/" . $img;
            ?>
                <img class="demo-thumb cursor" src="<?= $src ?>" onclick="currentSlide(<?= $index + 1 ?>)">
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
<script src="../js/chitietsanpham.js"></script>

</body>
</html>