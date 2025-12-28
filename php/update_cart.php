<?php
session_start();
$id = $_GET['id'];
$type = $_GET['type'];

if (isset($_SESSION['cart'][$id])) {
    if ($type == 'plus') {
       $_SESSION['cart'][$id]['quantity']++;

    } else {
        $_SESSION['cart'][$id]['quantity']--;

        if ($_SESSION['cart'][$id]['quantity'] <= 0) {
            unset($_SESSION['cart'][$id]);
        }
    }
}
header("Location: ../php/giohang.php");
exit;
?>