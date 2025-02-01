<?php
session_start();
require_once '../connection/connect.php';

header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Not authenticated']);
    exit;
}

try {
    // Get POST data
    $data = json_decode(file_get_contents('php://input'), true);

    // Validate input
    $username = trim($data['username'] ?? '');
    $email = filter_var(trim($data['email'] ?? ''), FILTER_VALIDATE_EMAIL);
    $newPassword = $data['newPassword'] ?? '';

    if (empty($username) || empty($email)) {
        throw new Exception('Username and email are required');
    }

    // Initialize database connection
    $db = new DatabaseConnection();
    $conn = $db->startConnection();

    // Start transaction
    $conn->beginTransaction();

    // Check if email is already taken by another user
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
    $stmt->execute([$email, $_SESSION['user_id']]);
    if ($stmt->rowCount() > 0) {
        throw new Exception('Email is already taken');
    }

    // Check if username is already taken by another user
    $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? AND id != ?");
    $stmt->execute([$username, $_SESSION['user_id']]);
    if ($stmt->rowCount() > 0) {
        throw new Exception('Username is already taken');
    }

    // Update user data
    if (!empty($newPassword)) {
        // If password is being updated
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE users SET username = ?, email = ?, password = ? WHERE id = ?");
        $stmt->execute([$username, $email, $hashedPassword, $_SESSION['user_id']]);
    } else {
        // If password is not being updated
        $stmt = $conn->prepare("UPDATE users SET username = ?, email = ? WHERE id = ?");
        $stmt->execute([$username, $email, $_SESSION['user_id']]);
    }

    $conn->commit();

    echo json_encode([
        'success' => true,
        'message' => 'Profile updated successfully',
        'username' => $username,
        'email' => $email
    ]);
} catch (Exception $e) {
    if (isset($conn)) {
        $conn->rollBack();
    }
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
} finally {
    if (isset($db)) {
        $db->closeConnection();
    }
}
