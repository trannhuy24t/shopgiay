<?php
include "config.php"; // File kết nối DB của bạn
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Lấy dữ liệu và bảo mật
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $subject = mysqli_real_escape_string($conn, $_POST['subject']);
    $message = mysqli_real_escape_string($conn, $_POST['message']);

    // Câu lệnh INSERT vào bảng contacts
    $sql = "INSERT INTO contacts (name, email, subject, message) 
            VALUES ('$name', '$email', '$subject', '$message')";

    if (mysqli_query($conn, $sql)) {
        echo "<script>
            alert('Gửi liên hệ thành công!');
            window.location.href = 'lienhe.php';
        </script>";
    } else {
        echo "Lỗi: " . mysqli_error($conn);
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Liên hệ - SneakerZone</title>
    <link rel="stylesheet" href="../css/contact.css">
</head>
<body>
    <header class="headerrr">
    <div class="container">
        <div class="nav">
            <a href="../php/trangchu.php"><h2>SNEAKERZONE</h2></a>
            <div class="menu-right">
                <a href="../php/sanpham.php">Sản phẩm</a>
                <a href="#">Liên hệ</a>
                <a href="../php/giohang.php">Giỏ hàng</a>

                <?php if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin') { ?>
                    <a href="../php/qldh.php">Quản lý đơn hàng</a>
                    <a href="#">Quản lý khách hàng</a>
                    <a href="../php/qlsp.php">Quản lý sản phẩm</a>
                    <a href="../php/thongke.php">Thống kê</a>
                <?php } ?>
            </div>
        </div>
    </div>
</header>
    <div class="contact-container">
        <div class="contact-form">
            <h2>GỬI LIÊN HỆ</h2>
            <form id="contactForm" action="../php/lienhe_process.php" method="POST">
                <div class="input-group">
                    <input type="text" name="name" id="name" placeholder="Họ và tên">
                    <span class="error-msg" id="nameError"></span>
                </div>
                
                <div class="input-group">
                    <input type="email" name="email" id="email" placeholder="Email">
                    <span class="error-msg" id="emailError"></span>
                </div>

                <div class="input-group">
                    <input type="text" name="subject" id="subject" placeholder="Tiêu đề">
                    <span class="error-msg" id="subjectError"></span>
                </div>

                <div class="input-group">
                    <textarea name="message" id="message" rows="5" placeholder="Nội dung tin nhắn..."></textarea>
                    <span class="error-msg" id="messageError"></span>
                </div>

                <button type="submit" class="btn-send">GỬI NGAY</button>
            </form>
        </div>
    </div>
    <script src="../js/contact.js"></script>
</body>
</html>