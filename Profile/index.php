<?php
session_start();
include '../common/header/header.php';
require_once '../connection/connect.php';

// Get user data
$db = new DatabaseConnection();
$conn = $db->startConnection();

try {
    $stmt = $conn->prepare("SELECT id, username, email, created_at FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        session_destroy();
        header("Location: /LogIn/index.php");
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
                <img id="profilePhoto" src="get_profile_photo.php" onerror="this.src='/WebEngineeringProject/Profile/default-avatar.png'" alt="Profile Photo" class="profile-photo">
                <div class="upload-photo">
                    <label for="photoUpload" class="upload-btn">
                        <i class="fas fa-camera"></i>
                        Change Photo
                    </label>
                    <input type="file" id="photoUpload" name="photo" accept="image/*" hidden>
                </div>
            </div>
            <div class="profile-info">
                <div class="username-container">
                    <h1><?php echo htmlspecialchars($user['username']); ?></h1>
                    <a href="logout.php" class="btn btn-logout">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </div>
                <p class="email"><?php echo htmlspecialchars($user['email']); ?></p>
                <p class="member-since">Member since: <?php echo date('F j, Y', strtotime($user['created_at'])); ?></p>
            </div>
        </div>

        <div class="profile-content">
            <div class="profile-section">
                <div class="section-header">
                    <h2>My Recipes</h2>
                    <button id="addRecipeBtn" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Add Recipe
                    </button>
                </div>
                <div class="table-responsive">
                    <table id="userRecipes" class="recipes-table">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Category</th>
                                <th>Cooking Time</th>
                                <th>Ingredients</th>
                                <th>Created</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- User recipes will be loaded here via JavaScript -->
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="profile-section">
                <h2>Account Settings</h2>
                <form id="updateProfileForm" class="settings-form">
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>">
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>">
                    </div>
                    <div class="form-group">
                        <label for="newPassword">New Password</label>
                        <input type="password" id="newPassword" name="newPassword" placeholder="Enter new password">
                    </div>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </form>
            </div>
        </div>
    </div>
</main>


<script src="profile.js"></script>

<!-- Add Recipe Modal -->
<div id="addRecipeModal" class="modal">
    <div class="modal-content">
        <span class="close-modal" data-modal="addRecipeModal">&times;</span>
        <h2>Add New Recipe</h2>
        <form id="addRecipeForm" onsubmit="event.preventDefault(); saveNewRecipe();">
            <div class="form-group">
                <label for="newTitle">Title</label>
                <input type="text" id="newTitle" required>
            </div>
            <div class="form-group">
                <label for="newIngredients">Ingredients</label>
                <textarea id="newIngredients" required placeholder="Enter ingredients, one per line"></textarea>
            </div>
            <div class="form-group">
                <label for="newCookingTime">Cooking Time (minutes)</label>
                <input type="number" id="newCookingTime" required min="1">
            </div>
            <div class="form-group">
                <label for="newCategory">Category</label>
                <select id="newCategory" required>
                    <option value="sweet">Sweet</option>
                    <option value="savory">Savory</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Create Recipe</button>
        </form>
    </div>
</div>

<!-- Edit Recipe Modal -->
<div id="editRecipeModal" class="modal">
    <div class="modal-content">
        <span class="close-modal">&times;</span>
        <h2>Edit Recipe</h2>
        <form id="editRecipeForm" onsubmit="event.preventDefault(); saveRecipeEdit();">
            <div class="form-group">
                <label for="editTitle">Title</label>
                <input type="text" id="editTitle" required>
            </div>
            <div class="form-group">
                <label for="editIngredients">Ingredients</label>
                <textarea id="editIngredients" required></textarea>
            </div>
            <div class="form-group">
                <label for="editCookingTime">Cooking Time (minutes)</label>
                <input type="number" id="editCookingTime" required min="1">
            </div>
            <div class="form-group">
                <label for="editCategory">Category</label>
                <select id="editCategory" required>
                    <option value="sweet">Sweet</option>
                    <option value="savory">Savory</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Save Changes</button>
        </form>
    </div>
</div>

<?php
include '../common/footer/footer.php';
?>