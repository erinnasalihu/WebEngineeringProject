<?php
session_start();
require_once '../connection/connect.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit;
}

try {
    $db = new DatabaseConnection();
    $conn = $db->startConnection();

    // Get user's recipes with all details from the database
    $stmt = $conn->prepare("
        SELECT id, title, ingredients, cooking_time, category, image_url, created_at 
        FROM recipes 
        WHERE user_id = ? 
        ORDER BY created_at DESC
    ");
    $stmt->execute([$_SESSION['user_id']]);
    $recipes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'success' => true,
        'recipes' => $recipes
    ]);
} catch (PDOException $e) {
    error_log("Error fetching user recipes: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => 'Failed to fetch recipes'
    ]);
} finally {
    if (isset($db)) {
        $db->closeConnection();
    }
}
