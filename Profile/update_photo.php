<?php
session_start();
require_once '../connection/connect.php';

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    error_log("User not authenticated");
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Not authenticated']);
    exit;
}

try {
    // Debug uploaded files
    error_log("FILES array: " . print_r($_FILES, true));

    // Check if file was uploaded
    if (!isset($_FILES['photo'])) {
        throw new Exception('No file uploaded');
    }

    if ($_FILES['photo']['error'] !== UPLOAD_ERR_OK) {
        $message = match ($_FILES['photo']['error']) {
            UPLOAD_ERR_INI_SIZE => 'The uploaded file exceeds the maximum file size limit (usually set to ' . ini_get('upload_max_filesize') . ')',
            UPLOAD_ERR_FORM_SIZE => 'The uploaded file exceeds the MAX_FILE_SIZE directive in the HTML form',
            UPLOAD_ERR_PARTIAL => 'The file was only partially uploaded',
            UPLOAD_ERR_NO_FILE => 'No file was uploaded',
            UPLOAD_ERR_NO_TMP_DIR => 'Missing a temporary folder',
            UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk',
            UPLOAD_ERR_EXTENSION => 'A PHP extension stopped the file upload',
            default => 'Unknown upload error'
        };
        throw new Exception($message);
    }

    $file = $_FILES['photo'];

    // Validate file type
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
    if (!in_array($file['type'], $allowedTypes)) {
        throw new Exception('Invalid file type: ' . $file['type'] . '. Only JPG, PNG and GIF are allowed');
    }

    // Validate file size (max 5MB)
    if ($file['size'] > 5 * 1024 * 1024) {
        throw new Exception('File too large. Maximum size is 5MB');
    }

    // Create image from uploaded file
    $sourceImage = null;
    switch ($file['type']) {
        case 'image/jpeg':
            $sourceImage = imagecreatefromjpeg($file['tmp_name']);
            break;
        case 'image/png':
            $sourceImage = imagecreatefrompng($file['tmp_name']);
            break;
        case 'image/gif':
            $sourceImage = imagecreatefromgif($file['tmp_name']);
            break;
    }

    if (!$sourceImage) {
        throw new Exception('Failed to process image');
    }

    // Get original dimensions
    $width = imagesx($sourceImage);
    $height = imagesy($sourceImage);

    // Calculate new dimensions (max 800px width)
    $maxWidth = 800;
    $ratio = $width / $height;
    if ($width > $maxWidth) {
        $width = $maxWidth;
        $height = $maxWidth / $ratio;
    }

    // Create new image with new dimensions
    $newImage = imagecreatetruecolor($width, $height);

    // Preserve transparency for PNG images
    if ($file['type'] === 'image/png') {
        imagealphablending($newImage, false);
        imagesavealpha($newImage, true);
    }

    // Resize image
    imagecopyresampled(
        $newImage,
        $sourceImage,
        0,
        0,
        0,
        0,
        $width,
        $height,
        imagesx($sourceImage),
        imagesy($sourceImage)
    );

    // Start output buffering
    ob_start();

    // Save the compressed image to the output buffer
    switch ($file['type']) {
        case 'image/jpeg':
            imagejpeg($newImage, null, 75); // 75% quality
            break;
        case 'image/png':
            imagepng($newImage, null, 6); // Compression level 6 (0-9)
            break;
        case 'image/gif':
            imagegif($newImage);
            break;
    }

    // Get the compressed image data
    $imageData = ob_get_clean();

    // Free up memory
    imagedestroy($sourceImage);
    imagedestroy($newImage);

    // Update database with new photo
    $db = new DatabaseConnection();
    $conn = $db->startConnection();

    $stmt = $conn->prepare("UPDATE users SET profile_photo = ?, profile_photo_type = ? WHERE id = ?");
    $stmt->execute([$imageData, $file['type'], $_SESSION['user_id']]);

    // After database update, add logging
    error_log("Profile photo updated successfully for user: " . $_SESSION['user_id']);

    echo json_encode([
        'success' => true,
        'message' => 'Profile photo updated successfully',
        'photo_url' => 'get_profile_photo.php'
    ]);
} catch (Exception $e) {
    error_log("Profile photo upload error: " . $e->getMessage());
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
