<?php
session_start();
include "config.php";

header('Content-Type: application/json; charset=utf-8');

$email = trim($_POST['email'] ?? '');
$password = trim($_POST['password'] ?? '');

if ($email === "" || $password === "") {
    echo json_encode([
        "status" => "error",
        "message" => "Vui lòng nhập đầy đủ email và mật khẩu!"
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

$sql = "SELECT * FROM user WHERE email = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "s", $email);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) === 1) {
    $row = mysqli_fetch_assoc($result);

    if (password_verify($password, $row['password'])) {

        
        $_SESSION['user'] = [
    'id'    => $row['id'],
    'hoten' => $row['hoten'],
    'email' => $row['email'],
    'role'  => $row['role']  
];


        echo json_encode([
            "status" => "success",
            "message" => "Đăng nhập thành công!",
            "redirect" => "../php/trangchu.php"
        ], JSON_UNESCAPED_UNICODE);
        exit;
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "Sai mật khẩu!"
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }
}

echo json_encode([
    "status" => "error",
    "message" => "Tài khoản không tồn tại!"
], JSON_UNESCAPED_UNICODE);
exit;
?>