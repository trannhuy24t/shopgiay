<?php
session_start();
include "config.php";

/* Lấy 8 sản phẩm mới nhất */
$products = mysqli_query($conn, "
    SELECT id, name, price, image
    FROM products
    ORDER BY id DESC
    LIMIT 3
");

// Bổ sung biến kiểm tra đăng nhập để truyền cho file JS
$isLoggedIn = isset($_SESSION['user']) ? 'true' : 'false';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SneakerZone - Trang chủ</title>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="../css/trangchu.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
</head>

<body data-logged-in="<?php echo $isLoggedIn; ?>">

<header>
    <div class="container">
        <div class="nav">
            <a href="./trangchu.php"><h2>SNEAKERZONE</h2></a>
            <div class="menu-right">
                <a href="../php/sanpham.php" class="auth-link">Sản phẩm</a>
                <a href="../php/contact.php" class="auth-link">Liên hệ</a>
                <a href="../php/giohang.php" class="auth-link">Giỏ hàng</a>

                <?php if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin') { ?>
                    <a href="../php/qldh.php">Quản lý đơn hàng</a>
                    <a href="../php/QLKH.php">Quản lý khách hàng</a>
                    <a href="../php/qlsp.php">Quản lý sản phẩm</a>
                    <a href="../php/qllh.php">Quản lý liên hệ</a>
                    <a href="../php/thongke.php">Thống kê</a>
                <?php } ?>

                <?php if (!isset($_SESSION['user'])): ?>
                    <a id="loginbtn" href="../pages/dangnhap.html">Đăng nhập</a>
                <?php else: ?>
                    <div id="usericon" class="user-area">
                        <img src="../images/user.jpg" class="avatar-icon" alt="user">
                        <ul id="usermenu" class="user-menu">
                            <li><a href="../php/profile.php">Thông tin cá nhân</a></li>
                            <li><a href="../php/donhang.php">Đơn hàng của tôi</a></li>
                            <li><a href="../php/logout.php">Đăng xuất</a></li>
                        </ul>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</header>

<div class="slider">
    <div class="slides">
        <img src="https://img.freepik.com/free-photo/pair-trainers_144627-3799.jpg">
        <img src="https://img.freepik.com/free-photo/sneakers_144627-5070.jpg">
    </div>
</div>

<div class="container">
    <h2>Sản phẩm nổi bật</h2>
    <div class="products">
<?php while ($p = mysqli_fetch_assoc($products)):

    $imgs = explode('|', $p['image']);
    $img = $imgs[0] ?? 'default.jpg';
?>
<div class="product">
    <img src="../images/<?= $img ?>">
    <h3><?= htmlspecialchars($p['name']) ?></h3>
    <p class="price"><?= number_format($p['price']) ?>đ</p>

    <button class="btn-add-cart"
        data-id="<?= $p['id'] ?>"
        data-name="<?= htmlspecialchars($p['name']) ?>">
        Thêm vào giỏ
    </button>
</div>
<?php endwhile; ?>
</div>


</div>

<footer>
    <h3>Liên hệ</h3>
    <p>Email: trannhuy543@gmail.com</p>
    <p>Điện thoại: 0397083605</p>
    <p>Facebook: https://www.facebook.com/yunhnart1806</p>
    © 2025 SneakerZone – Bán giày chính hãng.
</footer>

<script src="../js/trangchu.js"></script>
<div id="cartModal" class="modal">
  <div class="modal-box">
    <h3 id="modalProductName"></h3>

    <form method="POST" action="add_to_cart.php">
      <input type="hidden" name="id" id="modalProductId">

      <label>Size</label>
      <select name="size" id="sizeSelect" required>
        <option value="">-- Chọn size --</option>
      </select>

      <label>Màu</label>
      <select name="color" id="colorSelect" required>
        <option value="">-- Chọn màu --</option>
      </select>

      <label>Số lượng</label>
      <input type="number" name="qty" value="1" min="1">

      <div class="modal-actions">
        <button type="submit">Thêm vào giỏ</button>
        <button type="button" onclick="closeModal()">Hủy</button>
      </div>
    </form>
  </div>
</div>

</body>
</html>