<?php
include "config.php";

$id       = $_POST['id'];
$name     = $_POST['name'];
$price    = $_POST['price'];
$quantity = $_POST['quantity'];

if (!empty($_FILES['image']['name'])) {
    $image = $_FILES['image']['name'];
    move_uploaded_file($_FILES['image']['tmp_name'], "../images/".$image);

    $sql = "UPDATE products 
            SET name=?, price=?, quantity=?, image=?
            WHERE id=?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "siisi",
        $name, $price, $quantity, $image, $id
    );
} else {
    $sql = "UPDATE products 
            SET name=?, price=?, quantity=?
            WHERE id=?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "siii",
        $name, $price, $quantity, $id
    );
}

mysqli_stmt_execute($stmt);

header("Location: qlsp.php?msg=updated");
exit;
?>