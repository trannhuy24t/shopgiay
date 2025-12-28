<?php
session_start();
include "config.php";

// Kiểm tra đăng nhập
if (!isset($_SESSION['user'])) {
    header("Location: ../pages/dangnhap.html");
    exit;
}

// Lấy thông tin user từ DB
$user_id = $_SESSION['user']['id'];
$sql = "SELECT id, hoten, email FROM user WHERE id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thông tin cá nhân</title>
    <link rel="stylesheet" href="../css/profile.css">
</head>
<body>
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
    <a href="#">Quản lý sản phẩm</a>
    <a href="../php/thongke.php">Thống kê</a>
<?php } ?>
</header>

    <h1>Thông tin cá nhân</h1>

    <form action="update_profile.php" method="post" class="profile">
        <label>Họ tên:</label>
        <input type="text" name="hoten" value="<?= htmlspecialchars($user['hoten']) ?>" required>

        <label>Email:</label>
        <input type="email" value="<?= htmlspecialchars($user['email']) ?>" disabled>

        <label>Mật khẩu mới:</label>
        <input type="password" name="password" placeholder="Để trống nếu không muốn đổi">

        <label>Xác nhận mật khẩu mới:</label>
        <input type="password" name="confirmpassword" placeholder="Nhập lại mật khẩu mới">

        <button type="submit">Cập nhật</button>
    </form>
</body>
</html>
