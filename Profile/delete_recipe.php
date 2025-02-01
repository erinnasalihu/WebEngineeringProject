<?php
session_start();
require_once '../connection/connect.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit;
}

// Decode the JSON data from the request
$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['id'])) {
    echo json_encode(['success' => false, 'message' => 'Recipe ID is required']);
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

    // Delete the recipe
    $stmt = $conn->prepare("DELETE FROM recipes WHERE id = ? AND user_id = ?");
    $success = $stmt->execute([$data['id'], $_SESSION['user_id']]);

    echo json_encode([
        'success' => $success,
        'message' => $success ? 'Recipe deleted successfully' : 'Failed to delete recipe'
    ]);
} catch (PDOException $e) {
    error_log("Error deleting recipe: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => 'Failed to delete recipe'
    ]);
} finally {
    if (isset($db)) {
        $db->closeConnection();
    }
}
