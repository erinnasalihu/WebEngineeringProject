<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Not authenticated']);
    exit;
}

require_once '../connection/connect.php';

// Get and validate input data
$input = json_decode(file_get_contents('php://input'), true);

if (!isset($input['title']) || !isset($input['ingredients']) || !isset($input['cooking_time']) || !isset($input['category'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Missing required fields']);
    exit;
}

// Sanitize and validate input
$title = trim($input['title']);
$ingredients = trim($input['ingredients']);
$cooking_time = intval($input['cooking_time']);
$category = strtolower(trim($input['category']));

// Basic validation
if (empty($title) || empty($ingredients) || $cooking_time < 1 || !in_array($category, ['sweet', 'savory'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid input data']);
    exit;
}

try {
    $db = new DatabaseConnection();
    $conn = $db->startConnection();

    $stmt = $conn->prepare("INSERT INTO recipes (user_id, title, ingredients, cooking_time, category) VALUES (?, ?, ?, ?, ?)");
    $result = $stmt->execute([
        $_SESSION['user_id'],
        $title,
        $ingredients,
        $cooking_time,
        $category
    ]);

    if ($result) {
        echo json_encode([
            'success' => true,
            'message' => 'Recipe created successfully'
        ]);
    } else {
        throw new Exception('Failed to create recipe');
    }
} catch (Exception $e) {
    error_log("Error creating recipe: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'An error occurred while creating the recipe'
    ]);
}
