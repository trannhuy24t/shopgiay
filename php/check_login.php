<?php
session_start();
header('Content-Type: application/json; charset=utf-8');

if (isset($_SESSION['user_id'])) {
    echo json_encode([
        'login' => true,
        'hoten' => $_SESSION['hoten'],
        'role'  => $_SESSION['role']
    ], JSON_UNESCAPED_UNICODE);
} else {
    echo json_encode([
        'login' => false
    ]);
}
