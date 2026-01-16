<?php
include "config.php";

$id = (int)($_GET['id'] ?? 0);

$rs = mysqli_query($conn, "
    SELECT DISTINCT size, color
    FROM product_variants
    WHERE product_id = $id
    AND stock > 0
");

$sizes = [];
$colors = [];

while ($r = mysqli_fetch_assoc($rs)) {
    $sizes[]  = $r['size'];
    $colors[] = $r['color'];
}

echo json_encode([
    'sizes'  => array_unique($sizes),
    'colors' => array_unique($colors)
]);
