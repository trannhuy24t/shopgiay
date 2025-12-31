<?php
include "../php/config.php";
session_start();

// 1. Lấy danh sách sản phẩm (kèm tên phân loại)
$sp = mysqli_query($conn, "SELECT p.*, c.name as cat_name 
                           FROM products p 
                           LEFT JOIN categories c ON p.category_id = c.id 
                           ORDER BY p.id DESC");

// 2. Lấy danh sách phân loại cho thẻ <select>
$cats = mysqli_query($conn, "SELECT * FROM categories");
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý sản phẩm</title>
    <link rel="stylesheet" href="../css/qlsp.css">
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

<div class="main-content">
    <h1>📦 Quản lý sản phẩm</h1>

    <form id="productForm" action="qlsp_process.php" method="post" class="styled-form">
        <input type="hidden" name="action" value="add">
        <input type="hidden" name="id">

        <div class="form-row">
            <input type="text" name="name" placeholder="Tên sản phẩm" required>
            <input type="number" name="price" placeholder="Giá tiền" required>
        </div>

        <div class="form-row">
            <input type="number" name="quantity" placeholder="Số lượng" required>
            <select name="category_id" required>
                <option value="">-- Chọn phân loại --</option>
                <?php while($c = mysqli_fetch_assoc($cats)) { ?>
                    <option value="<?= $c['id'] ?>"><?= $c['name'] ?></option>
                <?php } ?>
            </select>
        </div>

        <input type="text" name="image" placeholder="Tên file ảnh (ví dụ: giay1.jpg) hoặc Link URL">
        
        <textarea name="description" placeholder="Nhập giới thiệu chi tiết về sản phẩm..."></textarea>

        <button type="submit" class="btn-add">➕ Thêm sản phẩm mới</button>
    </form>

    <table class="styled-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Ảnh</th>
                <th>Tên & Phân loại</th>
                <th>Giá</th>
                <th>SL</th>
                <th>Xoá</th>
            </tr>
        </thead>
      <tbody>
<?php while ($row = mysqli_fetch_assoc($sp)) { ?>
    <tr class="row-product" 
        data-id="<?= $row['id'] ?>" 
        data-name="<?= htmlspecialchars($row['name']) ?>" 
        data-price="<?= $row['price'] ?>" 
        data-quantity="<?= $row['quantity'] ?>" 
        data-image="<?= $row['image'] ?>"
        data-category="<?= $row['category_id'] ?>"
        data-description="<?= htmlspecialchars($row['description'] ?? '') ?>"> <td><?= $row['id'] ?></td>
       <td>
    <?php
    // Tách chuỗi ảnh và chỉ lấy tấm đầu tiên để hiện ở bảng quản lý
    $all_imgs = explode('|', $row['image']);
    $first_img = trim($all_imgs[0]);
    $src = (filter_var($first_img, FILTER_VALIDATE_URL)) ? $first_img : "../images/" . $first_img;
    ?>
    <img src="<?= $src ?>" class="img-preview" width="60">
</td>
        <td class="text-left">
            <div class="prod-name"><?= htmlspecialchars($row['name']) ?></div>
            <span class="prod-cat"><?= $row['cat_name'] ?? 'Chưa phân loại' ?></span>
        </td>
        <td class="prod-price"><?= number_format($row['price']) ?> đ</td>
        <td><?= $row['quantity'] ?></td>
        <td>
            <button class="btn-delete" data-id="<?= $row['id'] ?>">🗑</button>
        </td>
    </tr>
<?php } ?>
</tbody>
    </table>
</div>

<script src="../js/qlsp.js"></script>
</body>
</html>