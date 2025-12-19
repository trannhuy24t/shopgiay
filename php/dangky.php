<?php
include "config.php";
header('Content-Type: application/json; charset=utf-8');

$hoten = trim($_POST['hoten'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = trim($_POST['password'] ?? '');
$confirmpassword = trim($_POST['confirmpassword'] ?? '');

if ($hoten === "" || $email === "" || $password === ""|| $confirmpassword === "") {
    echo json_encode([
        "status" => "error",
        "message" => "Vui lòng nhập đầy đủ thông tin!"
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

// Kiểm tra email đã tồn tại chưa
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

// Mã hóa mật khẩu và lưu vào DB
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);
$sql = "INSERT INTO user (hoten, email, password) VALUES (?, ?, ?)";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "sss", $hoten, $email, $hashedPassword);

if (mysqli_stmt_execute($stmt)) {
    echo json_encode(["status" => "success"]);
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Lỗi CSDL không thể đăng ký!"
    ], JSON_UNESCAPED_UNICODE);
}
exit;
?>