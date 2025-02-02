<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="signup.css">
    <link href="https://fonts.googleapis.com/css2?family=Fredoka+One&display=swap" rel=stylesheet>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
</head>

<body>

    <div class="signup-container">
        <form id="signupForm">
            <h2>Sign up</h2>

            <div class="input-group">
                <input type="text" id="username" placeholder="Username" required>
                <span class="error-message" id="usernameError"></span>
            </div>

            <div class="input-group">
                <input type="email" id="email" placeholder="Email" required>
                <span class="error-message" id="emailError"></span>
            </div>

            <div class="input-group">
                <div class="password-container">
                    <input type="password" id="password" placeholder="Password" required>
                    <button type="button" id="togglePassword">
                        <i id="passwordEyeIcon" class="fa fa-eye"></i>
                    </button>
                </div>
                <span class="error-message" id="passwordError"></span>
            </div>

            <div class="input-group">
                <div class="password-container">
                    <input type="password" id="confirm-password" placeholder="Confirm Password" required>
                    <button type="button" id="toggleConfirmPassword">
                        <i id="confirmPasswordEyeIcon" class="fa fa-eye"></i>
                    </button>
                </div>
                <span class="error-message" id="confirmPasswordError"></span>
            </div>

            <button type="submit" class="signup-button">Sign Up</button>

            <div class="have-acc">
                <p>Have an account? <a href="/WebEngineeringProject/LogIn/index.php">Log in instead</a></p>
            </div>
        </form>
    </div>
    <script src="signup.js"></script>
</body>

</html>