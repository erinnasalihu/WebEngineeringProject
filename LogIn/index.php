<?php
require '../connection/connect.php';

// If user is already logged in, redirect to home page
if (isset($_SESSION['user_id'])) {
    header("Location: /Home/index.php");
    exit;
}

$db = new DatabaseConnection();
$conn = $db->startConnection();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        echo "Të gjitha fushat janë të detyrueshme!";
        exit;
    }

    try {
        $stmt = $conn->prepare("SELECT * FROM user WHERE email = :email");
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            header("Location: home.php");
            exit;
        } else {
            echo "Email ose fjalëkalimi i gabuar!";
        }
    } catch (PDOException $e) {
        error_log("Gabim në SQL: " . $e->getMessage());
        echo "Ndodhi një gabim. Ju lutem provoni përsëri.";
    }
}
?>

<?php
include '../common/header/header.php';
?>

<link rel="stylesheet" href="LogIn/login.css" preload>

<div class="login-container">
    <form id="loginForm">
        <h2>Log In</h2>
        <input type="email" id="identifier" name="email" placeholder="Email" required>

        <div class="password-container">
            <input type="password" id="password" name="password" placeholder="Password" required>
            <button type="button" id="togglePassword" aria-label="Toggle password visibility">
                <i id="passwordEyeIcon" class="fa fa-eye"></i>
            </button>
        </div>

        <button type="submit" class="login-button">Log in</button>
        <br><br>
    </form>
    <p>Don't have an account? <a href="/SignUp/signup.php">Sign up instead</a></p>


</div>
<script src="LogIn/login.js"></script>

<?php
include '../common/footer/footer.php';
?>