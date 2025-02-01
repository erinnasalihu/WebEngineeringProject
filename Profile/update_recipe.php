<?php
session_start();
require_once '../connection/connect.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit;
}

// Decode the JSON data from the request
$data = json_decode(file_get_contents('php://input'), true);

if (
    !isset($data['id']) || !isset($data['title']) || !isset($data['ingredients']) ||
    !isset($data['cooking_time']) || !isset($data['category'])
) {
    echo json_encode(['success' => false, 'message' => 'Missing required fields']);
    exit;
}

try {
    $db = new DatabaseConnection();
    $conn = $db->startConnection();

    // First verify that this recipe belongs to the user
    $checkStmt = $conn->prepare("SELECT user_id FROM recipes WHERE id = ?");
    $checkStmt->execute([$data['id']]);
    $recipe = $checkStmt->fetch(PDO::FETCH_ASSOC);

    if (!$recipe || $recipe['user_id'] !== $_SESSION['user_id']) {
        echo json_encode(['success' => false, 'message' => 'Unauthorized']);
        exit;
    }

    // Update the recipe
    $stmt = $conn->prepare("
        UPDATE recipes 
        SET title = ?, 
            ingredients = ?, 
            cooking_time = ?, 
            category = ?
        WHERE id = ? AND user_id = ?
    ");

    $success = $stmt->execute([
        $data['title'],
        $data['ingredients'],
        $data['cooking_time'],
        $data['category'],
        $data['id'],
        $_SESSION['user_id']
    ]);

    echo json_encode([
        'success' => $success,
        'message' => $success ? 'Recipe updated successfully' : 'Failed to update recipe'
    ]);
} catch (PDOException $e) {
    error_log("Error updating recipe: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => 'Failed to update recipe'
    ]);
} finally {
    if (isset($db)) {
        $db->closeConnection();
    }
}
