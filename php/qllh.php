<?php
include "config.php"; 
session_start();

$sql = "SELECT * FROM contacts ORDER BY created_at DESC";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý Liên hệ - SneakerZone Admin</title>
    <link rel="stylesheet" href="../css/qllh.css">
</head>
<body>
    <header>
    <div class="container">
        <div class="nav">
            <a href="./trangchu.php"><h2>SNEAKERZONE</h2></a>
            <div class="menu-right">
                <a href="../php/sanpham.php">Sản phẩm</a>
                <a href="../pages/contact.html">Liên hệ</a>
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

    <div class="admin-container">
        <div class="header-area">
            <h1>📥 Danh sách Liên hệ khách hàng</h1>
            <a href="qlsp.php" class="btn-back">Quay lại QLSP</a>
        </div>

        <table class="contact-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Họ tên</th>
                    <th>Email</th>
                    <th>Tiêu đề</th>
                    <th>Nội dung</th>
                    <th>Ngày gửi</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($result) > 0): ?>
                    <?php while($row = mysqli_fetch_assoc($result)): ?>
                        <tr id="contact-row-<?= $row['id'] ?>">
                            <td><?= $row['id'] ?></td>
                            <td><strong><?= htmlspecialchars($row['name']) ?></strong></td>
                            <td><?= htmlspecialchars($row['email']) ?></td>
                            <td><?= htmlspecialchars($row['subject']) ?></td>
                            <td class="message-cell"><?= nl2br(htmlspecialchars($row['message'])) ?></td>
                            <td><?= date('H:i d/m/Y', strtotime($row['created_at'])) ?></td>
                            <td>
                                <button type="button" 
                                        onclick="ajaxDeleteContact(<?= $row['id'] ?>)" 
                                        class="btn-delete" 
                                        style="border:none; cursor:pointer;">Xóa</button>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" style="text-align:center;">Chưa có liên hệ nào.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <script>
    function ajaxDeleteContact(id) {
        if (confirm('Bạn có thực sự muốn xóa liên hệ này không?')) {
            const params = new URLSearchParams();
            params.append('id', id);

            fetch('delete_contact_process.php', {
                method: 'POST',
                body: params
            })
            .then(response => response.text())
            .then(result => {
                if (result.trim() === "success") {
                    // Hiệu ứng xóa dòng mượt mà
                    const row = document.getElementById('contact-row-' + id);
                    row.style.transition = "all 0.4s ease";
                    row.style.backgroundColor = "#ffcdd2";
                    row.style.transform = "translateX(20px)";
                    row.style.opacity = "0";
                    
                    setTimeout(() => {
                        row.remove();
                    }, 400);
                } else {
                    alert("Lỗi server: " + result);
                }
            })
            .catch(error => {
                alert("Lỗi kết nối: " + error);
            });
        }
    }
    </script>
</body>
</html>