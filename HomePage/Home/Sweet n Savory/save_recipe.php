<?php
session_start();

if (isset($_POST['save'])) {
    if (isset($_SESSION['user_id'])) {
        $recipe = $_POST['save'];
        echo "Recipe '$recipe' has been saved for user.";
   
    } else {
        echo "Please log in to save recipes.";
    }
}
?>
