<?php
session_start();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SneakerZone - Trang chủ</title>

    <!-- CSS -->
    <link rel="stylesheet" href="../css/trangchu.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
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
    <a href="#">Quản lý khách hàng</a>
    <a href="../php/qlsp.php">Quản lý sản phẩm</a>
    <a href="../php/thongke.php">Thống kê</a>
<?php } ?>


                <!-- LOGIN / USER -->
                <?php if (!isset($_SESSION['user'])): ?>
                    <!-- CHƯA ĐĂNG NHẬP -->
                    <a id="loginbtn" href="../pages/dangnhap.html">Đăng nhập</a>
                <?php else: ?>
                    <!-- ĐÃ ĐĂNG NHẬP -->
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

<!-- BANNER -->
<div class="slider">
    <div class="slides">
        <img src="https://img.freepik.com/free-photo/pair-trainers_144627-3799.jpg">
        <img src="https://img.freepik.com/free-photo/sneakers_144627-5070.jpg">
    </div>
</div>

<!-- PRODUCTS -->
<div class="container">
    <h2>Sản phẩm nổi bật</h2>
    <div class="products">
        <div class="product">
            <img src="../images/nike-air-max.png" alt="Nike Air Max">
            <h3>Nike Air Max</h3>
            <p class="price">2,500,000đ</p>
            <button class="btn">Thêm vào giỏ</button>
        </div>
        <div class="product">
            <img src="../images/nike-air-max.png" alt="Nike Air Max">
            <h3>Nike Air Max</h3>
            <p class="price">2,500,000đ</p>
            <button class="btn">Thêm vào giỏ</button>
        </div>
        <div class="product">
            <img src="../images/nike-air-max.png" alt="Nike Air Max">
            <h3>Nike Air Max</h3>
            <p class="price">2,500,000đ</p>
            <button class="btn">Thêm vào giỏ</button>
        </div>
        <div class="product">
            <img src="../images/nike-air-max.png" alt="Nike Air Max">
            <h3>Nike Air Max</h3>
            <p class="price">2,500,000đ</p>
            <button class="btn">Thêm vào giỏ</button>
        </div>
    </div>
</div>

<!-- FOOTER -->
<footer>
    <h3>Liên hệ</h3>
    <p>Email: trannhuy543@gmail.com</p>
    <p>Điện thoại: 0397083605</p>
    <p>Facebook: https://www.facebook.com/yunhnart1806</p>
    © 2025 SneakerZone – Bán giày chính hãng.
</footer>

<script src="../js/trangchu.js"></script>

</body>
</html>
