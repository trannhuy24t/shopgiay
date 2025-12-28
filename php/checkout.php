<?php
session_start();
if (empty($_SESSION['cart'])) {
    die("Giỏ hàng trống");
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Thanh toán</title>
<link rel="stylesheet" href="../css/checkout.css">
<script>
function onlyNumber(e){
    return e.charCode >= 48 && e.charCode <= 57;
}
</script>
</head>
<body>

<h2>THÔNG TIN THANH TOÁN</h2>

<form action="process_checkout.php" method="post">
    <label>Họ tên</label>
    <input type="text" name="customer_name" required>

    <label>Số điện thoại</label>
    <input type="text" name="phone" required onkeypress="return onlyNumber(event)">

    <label>Địa chỉ</label>
    <textarea name="address" required></textarea>

    <button type="submit">XÁC NHẬN ĐẶT HÀNG</button>
</form>

</body>
</html>
