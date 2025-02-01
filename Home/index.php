<?php
include '../common/header/header.php';
?>

<link rel="stylesheet" href="/Home/home.css" preload>
<div class="content">
    <h1 class="wave-text">What are we making today, Chef?</h1>
    <div class="categories">
        <div class="category">
            <a href="/Home/Sweet n Savory/sweet.html">
                <img src="/Home/Sweet n Savory/Sweet.jpg" alt="Sweet">
                <span class="category-title">Sweet</span>
            </a>
        </div>
        <div class="or-divider">or</div>
        <div class="category">
            <a href="/Home/Sweet n Savory/savory.html">
                <img src="/Home/Sweet n Savory/Savory.jpg" alt="Savory">
                <span class="category-title">Savory</span>
            </a>
        </div>
    </div>
</div>
<script src="home.js"></script>


<?php
include '../common/footer/footer.php';
?>