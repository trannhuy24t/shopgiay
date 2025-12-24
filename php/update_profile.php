<?php
session_start();
include "config.php";

// Kiểm tra đăng nhập
if (!isset($_SESSION['user'])) {
    echo json_encode(["status"=>"error","message"=>"Chưa đăng nhập"]);
    exit;
}

$user_id = $_SESSION['user']['id'];
$hoten = trim($_POST['hoten'] ?? '');
$password = trim($_POST['password'] ?? '');
$confirm_password = trim($_POST['confirm_password'] ?? '');

if ($hoten === '') {
    echo json_encode(["status"=>"error","message"=>"Họ tên không được để trống"]);
    exit;
}

// Nếu người dùng nhập mật khẩu mới, kiểm tra khớp
$update_pass = false;
if ($password !== '') {
    if ($password !== $confirm_password) {
        echo json_encode(["status"=>"error","message"=>"Mật khẩu mới không khớp"]);
        exit;
    }
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $update_pass = true;
}

// Cập nhật DB
if ($update_pass) {
    $sql = "UPDATE user SET hoten = ?, password = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ssi", $hoten, $hashed_password, $user_id);
} else {
    $sql = "UPDATE user SET hoten = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "si", $hoten, $user_id);
}

if (mysqli_stmt_execute($stmt)) {
    $_SESSION['user']['hoten'] = $hoten;
    echo json_encode(["status"=>"success","message"=>"Cập nhật thành công"]);
} else {
    echo json_encode(["status"=>"error","message"=>"Cập nhật thất bại"]);
}
?>
