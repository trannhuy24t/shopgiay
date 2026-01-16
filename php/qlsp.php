<?php
include "../php/config.php";
session_start();

/* ================== THÊM SẢN PHẨM ================== */
if (isset($_POST['action']) && $_POST['action'] == 'add_product') {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $category_id = $_POST['category_id'];
    $image = $_POST['image'];
    $description = $_POST['description'];

    mysqli_query($conn,
        "INSERT INTO products(name, price, category_id, image, description)
         VALUES ('$name','$price','$category_id','$image','$description')"
    );

    header("Location: qlsp.php");
    exit;
}

/* ================== THÊM SIZE + MÀU ================== */
if (isset($_POST['action']) && $_POST['action'] == 'add_variant') {
    mysqli_query($conn,
        "INSERT INTO product_variants(product_id, size, color, quantity)
         VALUES (
            '{$_POST['product_id']}',
            '{$_POST['size']}',
            '{$_POST['color']}',
            '{$_POST['quantity']}'
         )"
    );

    header("Location: qlsp.php");
    exit;
}

/* ================== DỮ LIỆU HIỂN THỊ ================== */
$sp = mysqli_query($conn,
    "SELECT p.*, c.name AS cat_name
     FROM products p
     LEFT JOIN categories c ON p.category_id = c.id
     ORDER BY p.id DESC"
);

$cats = mysqli_query($conn,"SELECT * FROM categories");
?>

<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Quản lý sản phẩm</title>
<link rel="stylesheet" href="../css/qlsp.css">
</head>
<body>

<header>
<div class="container">
<div class="nav">
<a href="./trangchu.php"><h2>SNEAKERZONE</h2></a>
<div class="menu-right">
<a href="../php/sanpham.php">Sản phẩm</a>
<a href="../php/contact.php">Liên hệ</a>
<a href="../php/giohang.php">Giỏ hàng</a>

<?php if(isset($_SESSION['user']) && $_SESSION['user']['role']=='admin'){ ?>
<a href="../php/qldh.php">Quản lý đơn hàng</a>
<a href="../php/qlkh.php">Quản lý khách hàng</a>
<a href="#">Quản lý sản phẩm</a>
<a href="../php/thongke.php">Thống kê</a>
<?php } ?>
</div>
</div>
</div>
</header>

<div class="main-content">
<h1>📦 Quản lý sản phẩm</h1>

<!-- ===== THÊM SẢN PHẨM ===== -->
<form method="post" class="styled-form">
<input type="hidden" name="action" value="add_product">

<div class="form-row">
<input type="text" name="name" placeholder="Tên sản phẩm" required>
<input type="number" name="price" placeholder="Giá tiền" required>
</div>

<div class="form-row">
<select name="category_id" required>
<option value="">-- Chọn phân loại --</option>
<?php while($c=mysqli_fetch_assoc($cats)){ ?>
<option value="<?= $c['id'] ?>"><?= $c['name'] ?></option>
<?php } ?>
</select>
</div>

<input type="text" name="image" placeholder="Ảnh (vd: giay1.jpg | link)">
<textarea name="description" placeholder="Mô tả sản phẩm"></textarea>

<button class="btn-add">➕ Thêm sản phẩm</button>
</form>

<hr>

<!-- ===== THÊM SIZE + MÀU ===== -->
<h2>🎨 Thêm Size & Màu</h2>
<form method="post" class="styled-form">
<input type="hidden" name="action" value="add_variant">

<div class="form-row">
<select name="product_id" required>
<option value="">-- Chọn sản phẩm --</option>
<?php
$p=mysqli_query($conn,"SELECT id,name FROM products");
while($r=mysqli_fetch_assoc($p)){
echo "<option value='{$r['id']}'>{$r['name']}</option>";
}
?>
</select>

<input type="text" name="size" placeholder="Size (40)" required>
<input type="text" name="color" placeholder="Màu (Trắng)" required>
<input type="number" name="quantity" placeholder="Số lượng" required>
</div>

<button class="btn-add">➕ Thêm size & màu</button>
</form>

<!-- ===== BẢNG SẢN PHẨM ===== -->
<table class="styled-table">
<thead>
<tr>
<th>ID</th>
<th>Ảnh</th>
<th>Tên</th>
<th>Giá</th>
<th>Size & Màu (Kho)</th>
<th>Xoá</th>
</tr>
</thead>
<tbody>

<?php while($row=mysqli_fetch_assoc($sp)){ ?>
<tr>
<td><?= $row['id'] ?></td>

<td>
<?php
$img=explode('|',$row['image'])[0];
$src=filter_var($img,FILTER_VALIDATE_URL)?$img:"../images/$img";
?>
<img src="<?= $src ?>" width="60">
</td>

<td>
<b><?= htmlspecialchars($row['name']) ?></b><br>
<i><?= $row['cat_name'] ?></i>
</td>

<td><?= number_format($row['price']) ?> đ</td>

<td>
<?php
$v=mysqli_query($conn,
"SELECT size,color,quantity FROM product_variants WHERE product_id={$row['id']}"
);
if(mysqli_num_rows($v)==0){
echo "<i>Chưa có</i>";
}else{
while($s=mysqli_fetch_assoc($v)){
echo "Size {$s['size']} - {$s['color']} ({$s['quantity']})<br>";
}}
?>
</td>

<td><button class="btn-delete">🗑</button></td>
</tr>
<?php } ?>

</tbody>
</table>
</div>

</body>
</html>
