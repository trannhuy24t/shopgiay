<?php
session_start();
include "config.php";

// Chỉ admin mới được sửa
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    echo json_encode(["status" => "error", "message" => "Không có quyền"]);
    exit;
}

$order_id = $_POST['order_id'] ?? '';
$status   = $_POST['status'] ?? '';

if ($order_id === '' || $status === '') {
    echo json_encode(["status" => "error", "message" => "Thiếu dữ liệu"]);
    exit;
}

$sql = "UPDATE orders SET status = ? WHERE id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "si", $status, $order_id);

if (mysqli_stmt_execute($stmt)) {
    echo json_encode(["status" => "success"]);
} else {
    echo json_encode(["status" => "error", "message" => "Update thất bại"]);
}
?>
