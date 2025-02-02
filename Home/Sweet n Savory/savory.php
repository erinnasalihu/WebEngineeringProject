
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link rel="stylesheet" href="savory.css">
    

    <title>Savory</title>
</head>
<body>
    <header>
        <div class="olive-branch left">
            <img src="../../common/images/olive-1.png" alt="Left Olive Branch">
        </div>
        <div class="olive-branch right">
            <img src="../../common/images/olive-2.png" alt="Right Olive Branch">
        </div>
        
        <div class="header-container">
            <div class="header-left">
                <img src="../../common/images/THOK-logo.png" alt="Logo" class="logo">
                <nav class="navbar">
                    <a href="../index.php">Home</a>
                    <a href="../../AboutUs/index.php">AboutUs</a>
                    <a href="../../Ingredients/index.php">Ingredients</a>
                </nav>
            </div>
            <div class="header-right">
                <button class="navigation-menu" onclick="toggleNavbar()">&#9776;</button>
                <div class="search-container">
                    <input type="text" placeholder="Search" class="search-bar">
                </div>
            
                <div class="header-actions">
                    <div class="profile-icon" onclick="toggleProfileMenu()">
                        <img src="../../common/images/profile-icon.png" alt="profile-icon">
                    </div>
                    <div class="profile-menu">
                        <a href="../../LogIn/login.php">Log In</a>
                        <a href="../../SignUp/signup.php">Sign Up</a>
                    </div>
                </div>
            </div>
        </div>
    </header>
        
             <div class="slider">

                    <div class="list">
                        <div class="item" >
                            <img src="./1.png">
                        </div>
                        <div class="item active">
                            <img src="./2.png">
                        </div>
                        <div class="item">
                            <img src="./3.png">
                        </div>
                        <div class="item">
                            <img src="./4.png">
                        </div>
                        <div class="item">
                            <img src="./5.png">
                        </div>
                    </div>
                    
                    <div class="content">
                       
            
                    </div>
                    <div class="arow">
                        <button id="prev"><</button>
                        <button id="next">></button>
                    </div>
                </div>
            
            
                <script src="savory.js"></script>
                <footer>
                    © 2024 All rights reserved | Contact us: <a href="tel:">+123-456-7890</a>
                </footer>
</body>
</html>