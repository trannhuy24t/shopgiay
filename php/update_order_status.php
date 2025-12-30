<?php
session_start();
include "config.php";

// chỉ admin
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    echo json_encode(["status" => "error", "msg" => "no_permission"]);
    exit;
}

$order_id = $_POST['order_id'] ?? '';
$status   = $_POST['status'] ?? '';

if ($order_id == '' || $status == '') {
    echo json_encode(["status" => "error", "msg" => "missing_data"]);
    exit;
}

$sql = "UPDATE orders SET status = ? WHERE id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "si", $status, $order_id);

if (mysqli_stmt_execute($stmt)) {
    echo json_encode(["status" => "success"]);
} else {
    echo json_encode(["status" => "error"]);
}
?>