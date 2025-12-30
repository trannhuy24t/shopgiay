<?php
session_start();
include "config.php";

if (empty($_SESSION['cart'])) {
    die("Giỏ hàng trống");
}

if (!isset($_SESSION['user'])) {
    header("Location: ../pages/dangnhap.html");
    exit;
}

$user_id = $_SESSION['user']['id'];

// Lấy thông tin user
$sql = "SELECT hoten, phone, address FROM user WHERE id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$user = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Thanh toán</title>
<link rel="stylesheet" href="../css/checkout.css">

<script>
function validateForm() {
    const phone = document.getElementById("phone").value;

    if (!/^[0-9]{9,11}$/.test(phone)) {
        alert("❌ Số điện thoại không hợp lệ!");
        return false;
    }
    return true;
}
</script>
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

                <?php if ($_SESSION['user']['role'] === 'admin') { ?>
                    <a href="../php/qldh.php">Quản lý đơn hàng</a>
                    <a href="#">Quản lý khách hàng</a>
                    <a href="#">Quản lý sản phẩm</a>
                    <a href="../php/thongke.php">Thống kê</a>
                <?php } ?>
            </div>
        </div>
    </div>
</header>

<h2>🧾 THÔNG TIN THANH TOÁN</h2>

<form action="process_checkout.php" method="post" onsubmit="return validatePhone()">

    <label>Họ tên</label>
    <input type="text" name="customer_name"
           value="<?= htmlspecialchars($user['hoten'] ?? '') ?>" required>

    <label>Số điện thoại</label>
<input type="text" name="phone" id="phone" required>

<small id="phone-error" style="color:red; display:none;">
    ⚠ Số điện thoại phải có 10–11 chữ số
</small>


    <label>Địa chỉ</label>
    <textarea name="address" required><?= htmlspecialchars($user['address'] ?? '') ?></textarea>

    <label>
        <input type="checkbox" name="save_info" checked>
        Lưu thông tin cho lần sau
    </label>

    <button type="submit">✅ XÁC NHẬN ĐẶT HÀNG</button>
    <p><a href="../php/sanpham.php">Quay Lai</a></p>
</form>
<script src="../js/checkout.js"></script>
</body>
</html>
