<?php
require_once '../connection/connect.php';

header('Content-Type: application/json; charset=utf-8');

try {
    // Get POST data
    $data = json_decode(file_get_contents('php://input'), true);

    // Validate required fields
    if (
        !isset($data['email']) || !isset($data['password']) ||
        empty(trim($data['email'])) || empty(trim($data['password']))
    ) {
        throw new Exception('Email and password are required');
    }

    // Sanitize input
    $email = filter_var(trim($data['email']), FILTER_VALIDATE_EMAIL);
    $password = $data['password'];

    if (!$email) {
        throw new Exception('Invalid email format');
    }

    // Initialize database connection
    $db = new DatabaseConnection();
    $conn = $db->startConnection();

    // Check user credentials
    $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user || !password_verify($password, $user['password'])) {
        throw new Exception('Invalid email or password');
    }

    // Start session and set user data
    session_start();
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $user['username'];

    echo json_encode([
        'success' => true,
        'message' => 'Login successful',
        'redirect' => '/Home/index.php'
    ]);
} catch (Exception $e) {
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
