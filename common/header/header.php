<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? $pageTitle . ' - THOK' : 'THOK'; ?></title>
    <link rel="stylesheet" href="../common/global.css">
    <link rel="stylesheet" href="../common/navigation.css">
    <link href="https://fonts.googleapis.com/css2?family=Fredoka+One&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .login-popover {
            display: none;
            position: absolute;
            top: 100%;
            right: 0;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 10px;
            min-width: 120px;
            z-index: 1000;
        }

        .profile-icon:hover .login-popover {
            display: block;
        }

        .login-popover a {
            display: block;
            padding: 8px;
            color: #333;
            text-decoration: none;
        }

        .login-popover a:hover {
            background: #f5f5f5;
            border-radius: 4px;
        }
    </style>
</head>

<body>
    <!-- Decorative Elements -->
    <div class="olive-branch left">
        <img src="../common/images/olive-1.png" alt="Left olive branch decoration">
    </div>
    <div class="olive-branch right">
        <img src="../common/images/olive-2.png" alt="Right olive branch decoration">
    </div>

    <!-- Header Section -->
    <header>
        <div class="header-container">
            <div class="header-left">
                <a href="../Home">
                    <img src="../common/images/THOK-logo.png" alt="THOK Logo" class="logo">
                </a>
                <nav class="navbar">
                    <a href="../Home">Home</a>
                    <a href="../Ingredients">Ingredients</a>
                    <a href="../AboutUs" class="active">About Us</a>
                </nav>
            </div>
            <div class="header-right">
                <div class="search-container">
                    <input type="text" class="search-bar" placeholder="Search...">
                </div>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="../Profile" class="profile-icon">
                        <img src="<?php echo isset($_SESSION['profile_photo']) ? $_SESSION['profile_photo'] : '../common/images/profile-icon.png'; ?>" alt="Profile">
                    </a>
                <?php else: ?>
                    <div class="profile-icon">
                        <img src="../common/images/profile-icon.png" alt="Profile">
                        <div class="login-popover">
                            <a href="../Login">Log In</a>
                            <a href="../Register">Sign Up</a>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </header>