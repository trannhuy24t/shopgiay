<?php
session_start();
include "config.php";
include "check_admin.php";

if (!isset($_GET['id'])) {
    header("Location: qldh.php");
    exit;
}

$id = (int)$_GET['id'];

/* XÓA CHI TIẾT ĐƠN HÀNG TRƯỚC */
mysqli_query($conn, "DELETE FROM order_items WHERE order_id = $id");

/* XÓA ĐƠN HÀNG */
mysqli_query($conn, "DELETE FROM orders WHERE id = $id");

header("Location: qldh.php");
exit;
?>