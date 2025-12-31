<?php
include "config.php"; // File kết nối DB của bạn

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