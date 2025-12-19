<?php
session_start();
header('Content-Type: application/json');

if (isset($_SESSION['user'])) {
    echo json_encode([
        'login' => true,
        'name' => $_SESSION['user']['hoten']
    ]);
} else {
    echo json_encode(['login' => false]);
}
?>