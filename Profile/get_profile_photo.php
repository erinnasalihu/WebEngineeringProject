<?php
session_start();
require_once '../connection/connect.php';

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    exit;
}

try {
    $db = new DatabaseConnection();
    $conn = $db->startConnection();

    $stmt = $conn->prepare("SELECT profile_photo, profile_photo_type FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result && $result['profile_photo']) {
        header('Content-Type: ' . $result['profile_photo_type']);
        echo $result['profile_photo'];
    } else {
        http_response_code(404);
    }
} catch (Exception $e) {
    http_response_code(500);
} finally {
    if (isset($db)) {
        $db->closeConnection();
    }
}
