<?php
include "../php/config.php";

$action = $_POST['action'] ?? $_GET['action'];

if ($action === "add") {
    // Thêm trường description vào câu lệnh INSERT
    $sql = "INSERT INTO products (name, price, quantity, image, category_id, description)
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    
    // Thay đổi "siisi" thành "siisis" (thêm 1 chữ s cuối cho description)
    mysqli_stmt_bind_param($stmt, "siisis",
        $_POST['name'],
        $_POST['price'],
        $_POST['quantity'],
        $_POST['image'],
        $_POST['category_id'],
        $_POST['description'] // Thêm dòng này
    );
    mysqli_stmt_execute($stmt);
}

if ($action === "update") {
    // Thêm description=? vào câu lệnh UPDATE
    $sql = "UPDATE products
            SET name=?, price=?, quantity=?, image=?, category_id=?, description=?
            WHERE id=?";
    $stmt = mysqli_prepare($conn, $sql);
    
    // Thay đổi "siisii" thành "siisisi" (thêm 1 chữ s cho description trước id)
    mysqli_stmt_bind_param($stmt, "siisisi",
        $_POST['name'],
        $_POST['price'],
        $_POST['quantity'],
        $_POST['image'],
        $_POST['category_id'],
        $_POST['description'], // Thêm dòng này
        $_POST['id']
    );
    mysqli_stmt_execute($stmt);
}

if ($action === "delete") {
    mysqli_query($conn,
        "DELETE FROM products WHERE id=".(int)$_GET['id']
    );
}

header("Location: qlsp.php");
exit;
?>