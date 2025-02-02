<?php
require_once '../common/header/header.php';
require_once '../connection/connect.php';


$dbConnection = new DatabaseConnection();
$pdo = $dbConnection->startConnection();


$category = $_GET['category'] ?? '';
$keywords = $_GET['keywords'] ?? '';

$sql = "SELECT * FROM recipes WHERE 1=1";
if ($category) {
    $sql .= " AND category = ?";
}
if ($keywords) {
    $keywords = trim($keywords);
    $searchTerms = preg_split('/[\s,]+/', $keywords, -1, PREG_SPLIT_NO_EMPTY);

    $searchConditions = [];
    $params = [];

    foreach ($searchTerms as $term) {
        $searchConditions[] = "(title LIKE ? OR ingredients LIKE ?)";
        $params[] = "%$term%";
        $params[] = "%$term%";
    }

    if (!empty($searchConditions)) {
        $sql .= " AND (" . implode(" AND ", $searchConditions) . ")";
    }
}

try {
    $stmt = $pdo->prepare($sql);

    $paramsList = [];
    if ($category) {
        $paramsList[] = $category;
    }
    if (!empty($params)) {
        $paramsList = array_merge($paramsList, $params);
    }

    $stmt->execute($paramsList);
    $recipes = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = "Database error: " . $e->getMessage();
} finally {
    // Close the connection when done
    $dbConnection->closeConnection();
}
?>

<main>
    <h1>Search Recipes</h1>

    <form method="GET" action="" class="search-form">
        <div class="form-group">
            <label for="category">Category:</label>
            <select name="category" id="category">
                <option value="">All Categories</option>
                <option value="sweet" <?php echo $category === 'sweet' ? 'selected' : ''; ?>>Sweet</option>
                <option value="savory" <?php echo $category === 'savory' ? 'selected' : ''; ?>>Savory</option>
            </select>
        </div>

        <div class="form-group">
            <label for="keywords">Search Keywords:</label>
            <input type="text" name="keywords" id="keywords"
                value="<?php echo htmlspecialchars($keywords); ?>"
                placeholder="Enter ingredients or recipe name">
        </div>

        <button type="submit">Search</button>
    </form>

    <?php if (isset($recipes)): ?>
        <div class="results">
            <?php if (empty($recipes)): ?>
                <p>No recipes found.</p>
            <?php else: ?>
                <ul class="recipe-list">
                    <?php foreach ($recipes as $recipe): ?>
                        <li>
                            <h3><?php echo htmlspecialchars($recipe['title']); ?></h3>
                            <p>Category: <?php echo htmlspecialchars($recipe['category']); ?></p>
                            <p>Cooking Time: <?php echo htmlspecialchars($recipe['cooking_time']); ?> minutes</p>
                            <p>Ingredients: <?php echo htmlspecialchars($recipe['ingredients']); ?></p>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</main>

<style>
    .search-form {
        max-width: 600px;
        margin: 20px auto;
        padding: 20px;
        background:rgb(210, 223, 205);
        border-radius: 8px;
    }

    .form-group {
        margin-bottom: 15px;
    }

    .form-group label {
        display: block;
        margin-bottom: 5px;
    }

    .form-group input,
    .form-group select {
        width: 100%;
        padding: 8px;
        border: 1px solid #ddd;
        border-radius: 4px;
    }

    button {
        background: #007bff;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }

    .recipe-list {
        list-style: none;
        padding: 0;
    }

    .recipe-list li {
        background: white;
        margin: 10px 0;
        padding: 15px;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }
</style>

<?php require_once '../common/footer/footer.php'; ?>