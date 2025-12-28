<?php
include "config.php";

$id = $_GET['id'] ?? 0;

$sql = "SELECT p.*, c.name AS category_name 
        FROM products p
        JOIN categories c ON p.category_id = c.id
        WHERE p.id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$product = mysqli_fetch_assoc($result);

if (!$product) {
    echo "Sản phẩm không tồn tại";
    exit;
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title><?= $product['name'] ?></title>
    <link rel="stylesheet" href="../css/chitietsanpham.css">
</head>
<body>

<header>
    <div class="container nav">
        <a href="./trangchu.php"><h2>SNEAKERZONE</h2></a>
        <div class="menu-right">
            <a href="sanpham.php">Sản phẩm</a>
            <a href="giohang.php">Giỏ hàng</a>
                         <!-- MENU ADMIN (CHỈ ADMIN THẤY) -->
<?php if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin') { ?>
    <a href="../php/qldh.php">Quản lý đơn hàng</a>
    <a href="#">Quản lý khách hàng</a>
    <a href="#">Quản lý sản phẩm</a>
    <a href="../php/thongke.php">Thống kê</a>
<?php } ?>
            </div>
        </div>
    </div>
        </div>
    </div>
</header>

<div class="detail-container">
    <div class="detail-img">
        <img src="../images/<?= $product['image'] ?>">
    </div>

    <div class="detail-info">
        <h1><?= $product['name'] ?></h1>
        <p class="category">Danh mục: <?= $product['category_name'] ?></p>
        <p class="price"><?= number_format($product['price']) ?>đ</p>

        <p class="desc"><?= nl2br($product['description']) ?></p>

        <form method="post" action="add_to_cart.php">
            <input type="hidden" name="id" value="<?= $product['id'] ?>">
            <input type="hidden" name="name" value="<?= $product['name'] ?>">
            <input type="hidden" name="price" value="<?= $product['price'] ?>">

            <label>Số lượng:</label>
            <input type="number" name="quantity" value="1" min="1">

            <button class="btn">Thêm vào giỏ hàng</button>
        </form>
    </div>
</div>

</body>
</html>
