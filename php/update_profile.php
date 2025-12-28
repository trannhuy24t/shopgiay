<?php
session_start();
include "config.php";

if (!isset($_SESSION['user'])) {
    header("Location: ../pages/dangnhap.html");
    exit;
}

$user_id = $_SESSION['user']['id'];
$hoten   = trim($_POST['hoten']);
$password = $_POST['password'] ?? '';
$confirm  = $_POST['confirmpassword'] ?? '';

// ✅ Nếu có nhập mật khẩu mới
if (!empty($password)) {

    // Kiểm tra xác nhận mật khẩu
    if ($password !== $confirm) {
        echo "<script>alert('Mật khẩu xác nhận không khớp');history.back();</script>";
        exit;
    }

    // Hash mật khẩu
    $hashed = password_hash($password, PASSWORD_DEFAULT);

    $sql = "UPDATE user SET hoten = ?, password = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ssi", $hoten, $hashed, $user_id);

} else {
    // ❌ Không đổi mật khẩu → chỉ update họ tên
    $sql = "UPDATE user SET hoten = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "si", $hoten, $user_id);
}

if (mysqli_stmt_execute($stmt)) {
    // Cập nhật lại session
    $_SESSION['user']['hoten'] = $hoten;

    echo "<script>alert('Cập nhật thành công');location.href='profile.php';</script>";
} else {
    echo "<script>alert('Cập nhật thất bại');history.back();</script>";
}
?>
