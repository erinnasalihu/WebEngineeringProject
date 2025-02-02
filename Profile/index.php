<?php
session_start();
include '../common/header/header.php';
require_once '../connection/connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../LogIn/index.php");
    exit;
}

try {
    $db = new DatabaseConnection();
    $conn = $db->startConnection();

    $stmt = $conn->prepare("SELECT id, username, email, created_at, profile_photo FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        session_destroy();
        header("Location: ../LogIn/index.php");
        exit;
    }
} catch (PDOException $e) {
    error_log("Error fetching user data: " . $e->getMessage());
    $error = "An error occurred while fetching your profile data.";
}
?>

<link rel="stylesheet" href="/WebEngineeringProject/Profile/profile.css">

<main class="profile-main container">
    <div class="profile-container">
        <div class="profile-header">
            <div class="profile-photo-container">
                <img id="profilePhoto" src="<?php echo $user ? 'get_profile_photo.php' : '/WebEngineeringProject/Profile/default-avatar.png'; ?>" 
                     onerror="this.src='/WebEngineeringProject/Profile/default-avatar.png'" 
                     alt="Profile Photo" class="profile-photo">
            </div>
            <div class="profile-info">
                <h1><?php echo htmlspecialchars($user['username']); ?></h1>
   
