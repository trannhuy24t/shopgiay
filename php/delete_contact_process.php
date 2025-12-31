<?php
include "config.php";
if (isset($_POST['id'])) {
    $id = (int)$_POST['id'];
    $sql = "DELETE FROM contacts WHERE id = $id";
    if (mysqli_query($conn, $sql)) {
        echo "success";
    } else {
        echo "error";
    }
}
?>