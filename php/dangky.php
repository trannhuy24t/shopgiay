<?php
include "config.php";
header('Content-Type: application/json; charset=utf-8');

$hoten = trim($_POST['hoten'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = trim($_POST['password'] ?? '');
$confirmpassword = trim($_POST['confirmpassword'] ?? '');

if ($hoten === "" || $email === "" || $password === "" || $confirmpassword === "") {
    echo json_encode([
        "status" => "error",
        "message" => "Vui lòng nhập đầy đủ thông tin!"
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

// kiểm tra mật khẩu nhập lại
if ($password !== $confirmpassword) {
    echo json_encode([
        "status" => "error",
        "message" => "Mật khẩu xác nhận không khớp!"
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

// kiểm tra email tồn tại
$checkSql = "SELECT id FROM user WHERE email = ?";
$stmt = mysqli_prepare($conn, $checkSql);
mysqli_stmt_bind_param($stmt, "s", $email);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) > 0) {
    echo json_encode([
        "status" => "error",
        "message" => "Email này đã được sử dụng!"
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

// ===== PHÂN QUYỀN =====
$role = "user"; // mặc định user đăng ký

// mã hóa mật khẩu
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// insert có role
$sql = "INSERT INTO user (hoten, email, password, role)
        VALUES (?, ?, ?, ?)";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "ssss",
    $hoten, $email, $hashedPassword, $role
);

if (mysqli_stmt_execute($stmt)) {
    echo json_encode([
        "status" => "success",
        "message" => "Đăng ký thành công!"
    ], JSON_UNESCAPED_UNICODE);
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Lỗi CSDL không thể đăng ký!"
    ], JSON_UNESCAPED_UNICODE);
}
exit;
?>
