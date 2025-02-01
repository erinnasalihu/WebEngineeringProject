<?php
require_once '../connection/connect.php';

header('Content-Type: application/json; charset=utf-8');

try {
    // Get POST data
    $data = json_decode(file_get_contents('php://input'), true);

    // Validate required fields
    $requiredFields = ['username', 'email', 'password'];
    foreach ($requiredFields as $field) {
        if (!isset($data[$field]) || empty(trim($data[$field]))) {
            throw new Exception('Mungojnë vlerat e kërkuara');
        }
    }

    // Sanitize and validate input
    $username = htmlspecialchars(trim($data['username']), ENT_QUOTES, 'UTF-8');
    $email = filter_var(trim($data['email']), FILTER_VALIDATE_EMAIL);
    $password = $data['password'];

    // Additional validation
    if (!$email) {
        throw new Exception('Formati i emailit është i pavlefshëm');
    }

    if (strlen($password) < 8) {
        throw new Exception('Fjalëkalimi duhet të ketë të paktën 8 karaktere');
    }

    if (strlen($username) < 3 || strlen($username) > 50) {
        throw new Exception('Emri i përdoruesit duhet të jetë midis 3 dhe 50 karaktere');
    }

    // Hash password
    $password = password_hash($password, PASSWORD_DEFAULT, ['cost' => 12]);

    // Initialize database connection with default config
    $db = new DatabaseConnection();
    $conn = $db->startConnection();

    // Check if email already exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->rowCount() > 0) {
        throw new Exception('Emaili ekziston tashmë');
    }

    // Check if username already exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->execute([$username]);
    if ($stmt->rowCount() > 0) {
        throw new Exception('Emri i përdoruesit ekziston tashmë');
    }

    // Insert new user
    $stmt = $conn->prepare("INSERT INTO users (username, email, password, created_at) VALUES (?, ?, ?, NOW())");
    $success = $stmt->execute([$username, $email, $password]);

    if (!$success) {
        throw new Exception('Regjistrimi i përdoruesit dështoi');
    }

    http_response_code(201); // Created
    echo json_encode([
        'success' => true,
        'message' => 'Përdoruesi u regjistrua me sukses'
    ]);
} catch (Exception $e) {
    http_response_code(400); // Bad Request for validation errors
    if ($e instanceof PDOException) {
        error_log("Database Error: " . $e->getMessage());
        http_response_code(500); // Internal Server Error for database issues
        echo json_encode([
            'success' => false,
            'message' => 'Ndodhi një gabim gjatë përpunimit të kërkesës'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => $e->getMessage()
        ]);
    }
} finally {
    if (isset($db)) {
        $db->closeConnection();
    }
}
