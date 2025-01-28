<?php
session_start(); // Nëse është e nevojshme për akses të autentikuar
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cheese Omelette Recipe</title>
</head>
<body>
    <header>
        <div class="navbar">
            <div class="logo-content">
                <img src="logo.png" alt="Olive's Kitchen Logo">
                <h1>Olive's Kitchen</h1>
            </div>
            <nav>
                <a href="/HomePage/Home/home.php">Home</a>
                <a href="/HomePage/About/about.html">About Us</a>
                <a href="/HomePage/Ingredients/ingredients.html">Ingredients</a>
                <a href="/HomePage/Profile/profile.html">Profile</a>
            </nav>
        </div>
    </header>

    <div class="recipe">
        <h2>Cheese Omelette</h2>
        <img src="cheese-omelette.jpg" alt="Cheese Omelette">
   
        <h3>Ingredients:</h3>
        <ul>
            <li>Eggs</li>
            <li>Milk</li>
            <li>Cheese (grated)</li>
            <li>Butter</li>
            <li>Salt and pepper</li>
        </ul>

        <h3>Instructions:</h3>
        <p>Whisk eggs with a splash of milk, salt, and pepper. Melt butter in a pan and pour in the egg mixture. Add grated cheese before folding the omelette in half.</p>

        <form method="POST" action="save_recipe.php">
            <button type="submit" name="save" value="recipes1">Save</button>
        </form>
    </div>

    <footer>
        © 2024 All rights reserved | Contact us: <a href="tel:+1234567890">+123-456-7890</a>
    </footer>
</body>
</html>
