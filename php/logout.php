<?php
session_start();

// 1. Xóa tất cả các biến trong session hiện tại
$_SESSION = array();

// 2. Nếu muốn xóa sạch dấu vết của session trong cookie trình duyệt (Tùy chọn nhưng nên có)
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// 3. Hủy bỏ session trên server
session_destroy();

// 4. Chuyển hướng về trang chủ
header("Location: ../php/trangchu.php");
exit;
?>